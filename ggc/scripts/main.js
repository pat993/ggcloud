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
        document.getElementById("in_fps").value = "23";
        document.getElementById("in_max_w").value = "1920";
        document.getElementById("in_max_h").value = "1920";
    } else if (e == "2") {
        bitrate = "2524288";
        document.getElementById("in_bitrate").value = "2524288";
        document.getElementById("in_fps").value = "23";
        document.getElementById("in_max_w").value = "1920";
        document.getElementById("in_max_h").value = "1920";
    } else if (e == "3") {
        bitrate = "5524288";
        document.getElementById("in_bitrate").value = "5524288";
        document.getElementById("in_fps").value = "23";
        document.getElementById("in_max_w").value = "1920";
        document.getElementById("in_max_h").value = "1920";
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
    constructor(wsUrl, sampleRate = 24000, targetLatency = 50) {
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
        
        // Properties for average ping calculation
        this.pingHistory = [];
        this.maxPingHistory = 3; // Changed to 3 pings
        this.originalBitrate = null;
        this.currentBitrate = null;
        
        // Thresholds for bitrate adjustment
        this.highPingThreshold = 400; // Exactly 180ms as requested
        this.lowPingThreshold = 150; // Exactly 130ms as requested
        
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
            } else {
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
            this.startPing();
        };

        this.ws.onclose = () => {
            console.log('WebSocket disconnected');
            this.stopPing();
            this.updateLatencyDisplay('Disconnected');
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

    disconnect() {
        if (this.ws) {
            this.ws.close();
            this.stopPing();
        }
    }

    reconnect() {
        this.setupWebSocket();
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
                    // Update ping history
                    this.pingHistory.push(this.lastPingTime);
                    if (this.pingHistory.length > this.maxPingHistory) {
                        this.pingHistory.shift();
                    }

                    // Display current ping
                    latencyDisplay.innerHTML = `<i class='fas fa-signal'></i> ${this.lastPingTime} ms`;

                    // Only proceed with bitrate adjustment if we have enough ping history
                    if (this.pingHistory.length === this.maxPingHistory) {
                        // Calculate average of last 3 pings
                        const avgPing = this.pingHistory.reduce((a, b) => a + b, 0) / this.maxPingHistory;

                        // Store original bitrate if not already stored
                        if (!this.originalBitrate) {
                            this.originalBitrate = parseInt(document.getElementById("in_bitrate").value);
                            this.currentBitrate = this.originalBitrate;
                        }

                        // Adjust bitrate based on average ping
                        if (avgPing >= this.highPingThreshold && this.currentBitrate > 524288) {
                            this.currentBitrate = Math.max(524288, this.currentBitrate - 1048576);
                            setStream(this.currentBitrate.toString());
                            console.log(`High average ping (${Math.round(avgPing)}ms), reducing bitrate to ${this.currentBitrate}`);
                        } else if (avgPing <= this.lowPingThreshold && this.currentBitrate < this.originalBitrate) {
                            this.currentBitrate = Math.min(this.originalBitrate, this.currentBitrate + 524288);
                            setStream(this.currentBitrate.toString());
                            console.log(`Low average ping (${Math.round(avgPing)}ms), increasing bitrate to ${this.currentBitrate}`);
                        }
                    }
                }
            } else {
                setTimeout(updateDisplay, 100);
            }
        };

        updateDisplay();
    }
}

// Initialize the AudioStream
let stream1 = new AudioStream('wss://hypercube.my.id:' + audio_port);

// Bitrate and visibility handling
let bitrate;
let blurStartTime = null;
const blurThreshold = 60000;

ifvisible.on("blur", function() {
    visible_status = false;
    setStream("524288");
    blurStartTime = Date.now();

    if (stream1) {
        stream1.disconnect();
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
                stream1.reconnect();
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

// Utility functions
function removeDeviceViewElements() {
    const deviceViewElements = document.querySelectorAll('.device-view');
    deviceViewElements.forEach(element => {
        element.remove();
    });
}

function setStream(br) {
    document.getElementById("in_bitrate").value = br;
    document.getElementById("in_fps").value = "23";
    document.getElementById("in_max_w").value = "1920";
    document.getElementById("in_max_h").value = "1920";

    setTimeout(function() {
        const changeVideoBtn = document.getElementById("btn_change_video");
        if (changeVideoBtn) {
            changeVideoBtn.click();
        }
    }, 1000);
}

// DOM ready handlers
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
        const isMuted = !stream1.isMuted;
        stream1.muteAudio(isMuted);

        const icon = $(this).find('i');
        if (isMuted) {
            icon.removeClass('fa-volume-up').addClass('fa-volume-mute');
        } else {
            icon.removeClass('fa-volume-mute').addClass('fa-volume-up');
        }
    });
});