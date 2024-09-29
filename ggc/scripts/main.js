// Mobile browser detection
function isMobileBrowser() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

if (isMobileBrowser()) {
    $('.DraggableDiv').draggableTouch();
}

function stream_quality() {
    var e = document.getElementById("stream_quality").value;
    if (e == "1") {
        bitrate = "1524288";
        document.getElementById("in_bitrate").value = "1524288";
        document.getElementById("in_fps").value = "40";
        document.getElementById("in_max_w").value = "1080";
        document.getElementById("in_max_h").value = "1080";
    } else if (e == "2") {
        bitrate = "3524288";
        document.getElementById("in_bitrate").value = "2524288";
        document.getElementById("in_fps").value = "40";
        document.getElementById("in_max_w").value = "1080";
        document.getElementById("in_max_h").value = "1080";
    } else if (e == "3") {
        bitrate = "8524288";
        document.getElementById("in_bitrate").value = "8524288";
        document.getElementById("in_fps").value = "40";
        document.getElementById("in_max_w").value = "1080";
        document.getElementById("in_max_h").value = "1080";
    }
}

// Function to read a cookie by name
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) {
        return parts.pop().split(';').shift();
    }
    return null;
}

// Get the audio port value from the 'audio_port' cookie
const audio_port = getCookie('venus') || 'error'; // Replace 'default_port_value' with your default port if cookie is not set

let visible_status = true;

class CircularBuffer {
    constructor(size) {
        this.buffer = new Float32Array(size);
        this.size = size;
        this.writePointer = 0;
        this.readPointer = 0;
    }

    write(data) {
        for (let i = 0; i < data.length; i++) {
            this.buffer[this.writePointer] = data[i];
            this.writePointer = (this.writePointer + 1) % this.size;
        }
    }

    read(size) {
        const output = new Float32Array(size);
        for (let i = 0; i < size; i++) {
            output[i] = this.buffer[this.readPointer];
            this.readPointer = (this.readPointer + 1) % this.size;
        }
        return output;
    }

    available() {
        return (this.writePointer - this.readPointer + this.size) % this.size;
    }
}

class AudioStream {
    constructor(wsUrl, sampleRate = 32000, targetLatency = 100) {
        this.wsUrl = wsUrl;
        this.sampleRate = sampleRate;
        this.targetLatency = targetLatency;
        this.audioContext = new (window.AudioContext || window.webkitAudioContext)({ sampleRate });
        this.gainNode = this.audioContext.createGain();
        this.channels = 1;
        this.bufferSize = Math.pow(2, Math.ceil(Math.log2(this.sampleRate * this.targetLatency / 1000)));
        this.circularBuffer = new CircularBuffer(this.bufferSize * this.channels * 4);
        this.lastSampleData = new Float32Array(this.bufferSize * this.channels);
        this.isMuted = false;
        this.pingInterval = null;
        this.pingStartTime = null;
        this.latencyDisplay = document.getElementById('latency-display');
        this.lastPingTime = null;
        this.highPingCount = 0; // Initialize high ping counter
        this.normalPingCount = 0; // Initialize normal ping counter
        this.originalBitrate = null; // Original bitrate chosen by the user
        this.currentBitrate = null; // Current bitrate being used
        this.initAudio();
        this.setupWebSocket();
    }

    initAudio() {
        this.scriptNode = this.audioContext.createScriptProcessor(this.bufferSize, this.channels, this.channels);
        this.scriptNode.onaudioprocess = this.processAudio.bind(this);
        this.scriptNode.connect(this.gainNode);
        this.gainNode.connect(this.audioContext.destination);
    }

    setupWebSocket() {
        this.ws = new WebSocket(this.wsUrl);
        this.ws.binaryType = 'arraybuffer';

        this.ws.onmessage = async (event) => {
            if (event.data === 'pong') {
                const pingTime = Date.now() - this.pingStartTime;
                this.lastPingTime = pingTime;
                this.updateLatencyDisplay();
            } else if (!this.isMuted) { // hanya mute audio, tapi tetap terima data
                const arrayBuffer = event.data;
                const int16Array = new Int16Array(arrayBuffer);
                const floatArray = new Float32Array(int16Array.length);
                for (let i = 0; i < int16Array.length; i++) {
                    floatArray[i] = int16Array[i] / 32768.0;
                }
                this.circularBuffer.write(floatArray);
            }
        };

        this.ws.onopen = () => {
            console.log('WebSocket connected');
            this.muteAudio(false); 
            this.startPing(); 
        };

        this.ws.onclose = () => {
            console.log('WebSocket disconnected');
            this.stopPing(); 
            this.updateLatencyDisplay('Disconnected'); 
            if (visible_status == true) { 
                setTimeout(() => this.reconnectWebSocket(), 1000); 
            }
        };

        this.ws.onerror = (error) => {
            console.error('WebSocket error:', error);
        };
    }

    processAudio(audioProcessingEvent) {
        const outputBuffer = audioProcessingEvent.outputBuffer;
        const channelData = [];
        for (let channel = 0; channel < outputBuffer.numberOfChannels; channel++) {
            channelData.push(outputBuffer.getChannelData(channel));
        }

        const availableSamples = this.circularBuffer.available() / this.channels;

        if (availableSamples >= outputBuffer.length) {
            const data = this.circularBuffer.read(outputBuffer.length * this.channels);
            for (let channel = 0; channel < outputBuffer.numberOfChannels; channel++) {
                for (let sample = 0; sample < outputBuffer.length; sample++) {
                    channelData[channel][sample] = data[sample * this.channels + channel];
                    this.lastSampleData[sample * this.channels + channel] = channelData[channel][sample];
                }
            }
        } else {
            console.warn('Buffer underrun');
            
            for (let channel = 0; channel < outputBuffer.numberOfChannels; channel++) {
                for (let sample = 0; sample < outputBuffer.length; sample++) {
                    let previousSample = this.lastSampleData[(sample - 1 + this.channels * outputBuffer.length) % (this.channels * outputBuffer.length)];
                    let nextSample = 0.0;
                    
                    if (availableSamples > 0) {
                        nextSample = this.circularBuffer.buffer[this.circularBuffer.readPointer];
                    }

                    let interpolatedSample = previousSample + ((nextSample - previousSample) * (sample / outputBuffer.length));
                    channelData[channel][sample] = interpolatedSample;
                    
                    this.lastSampleData[sample * this.channels + channel] = channelData[channel][sample];
                }
            }
        }
    }
    
    muteAudio(mute) {
        this.isMuted = mute;
        this.gainNode.gain.value = mute ? 0 : 1;
    }

    closeWebSocket() {
        if (this.ws) {
            this.ws.close();
        }
    }

    reconnectWebSocket() {
        this.updateLatencyDisplay('Reconnecting'); 
        if (!this.ws || this.ws.readyState === WebSocket.CLOSED || this.ws.readyState === WebSocket.CLOSING) {
            this.setupWebSocket(); 
        }
    }

    startPing() {
        this.pingInterval = setInterval(() => {
            if (this.ws && this.ws.readyState === WebSocket.OPEN) {
                this.pingStartTime = Date.now();
                this.ws.send('ping');
            }
        }, 3000);
    }

    stopPing() {
        if (this.pingInterval) {
            clearInterval(this.pingInterval);
            this.pingInterval = null;
        }
    }

    updateLatencyDisplay(status = null) {
        const updateDisplay = () => {
            const latencyDisplay = document.getElementById('latency-display');
            if (latencyDisplay) {
                if (status) {
                    latencyDisplay.innerHTML = status;
                } else if (this.lastPingTime !== null) {
                    latencyDisplay.innerHTML = `<i class='fas fa-signal'></i> ${this.lastPingTime} ms`;

                    // Check ping conditions and adjust bitrate
                    if (this.lastPingTime > 200) {
                        this.highPingCount++;
                        this.normalPingCount = 0;
                    } else if (this.lastPingTime < 150) {
                        this.normalPingCount++;
                        this.highPingCount = 0;
                    } else {
                        this.highPingCount = 0;
                        this.normalPingCount = 0;
                    }

                    // Adjust bitrate if ping is consistently high
                    if (this.highPingCount >= 3) {
                        if (!this.originalBitrate) {
                            this.originalBitrate = document.getElementById("in_bitrate").value;
                        }
                        this.currentBitrate = this.currentBitrate ? this.currentBitrate : this.originalBitrate;
                        
                        if (this.currentBitrate > 524288) {
                            this.currentBitrate -= 524288; // Reduce bitrate
                            setStream(this.currentBitrate.toString());
                        }
                        this.highPingCount = 0; // Reset counter after adjusting bitrate
                    }

                    // Adjust bitrate back to original if ping is consistently low
                    if (this.normalPingCount >= 3) {
                        if (this.originalBitrate && this.currentBitrate < this.originalBitrate) {
                            this.currentBitrate += 524288; // Increase bitrate
                            if (this.currentBitrate > this.originalBitrate) {
                                this.currentBitrate = this.originalBitrate; // Ensure we do not exceed the original bitrate
                            }
                            setStream(this.currentBitrate.toString());
                        }
                        this.normalPingCount = 0; // Reset counter after restoring bitrate
                    }
                }
            } else {
                setTimeout(updateDisplay, 100);
            }
        };

        updateDisplay();
    } 
    
}

// Initialize the AudioStream after both classes are defined
let stream1 = new AudioStream('wss://hypercube.my.id:' + audio_port);

// Stream quality control
function setStream(br) {
    document.getElementById("in_bitrate").value = br;
    setTimeout(function() {
        const changeVideoBtn = document.getElementById("btn_change_video");
        if (changeVideoBtn) {
            changeVideoBtn.click();
        }
    }, 1000);
}

function removeDeviceViewElements() {
    const deviceViewElements = document.querySelectorAll('.device-view');
    deviceViewElements.forEach(element => {
        element.remove();
    });
}

let isMuted = false; 

let bitrate;
let blurStartTime = null;
const blurThreshold = 60000;

ifvisible.on("blur", function() {
    visible_status = false;
    setStream("524288");
    blurStartTime = Date.now();

    if (stream1) {
        stream1.muteAudio(true);
    }
});

ifvisible.on("wakeup", function() {
    visible_status = true;
    if (blurStartTime) {
        const blurDuration = Date.now() - blurStartTime;

        if (blurDuration >= blurThreshold) {
            removeDeviceViewElements();
            location.reload();
        } else {
            if (stream1) {
                stream1.reconnectWebSocket();
                stream1.muteAudio(false);
            }

            if (bitrate) {
                setStream(bitrate);
            } else {
                setStream("2524288");
            }
        }

        blurStartTime = null;
    }
});

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        $(".control-wrapper").animate({ height: "toggle" });
    }, 1000);
    
    $("#slide-toggle").click(function() {
        $(".control-wrapper").animate({ height: "toggle" });
        
        if ($('.more-box').is(':visible')) {
            $('#input_show_more').trigger('click');
        }
    });

    $("#fullscreen-toggle").click(function() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    });

    $("#audio-toggle").click(function() {
        isMuted = !isMuted;
        stream1.muteAudio(isMuted);

        const icon = $(this).find('i');
        if (isMuted) {
            icon.removeClass('fa-volume-up').addClass('fa-volume-mute');
        } else {
            icon.removeClass('fa-volume-mute').addClass('fa-volume-up');
        }
    });
});

