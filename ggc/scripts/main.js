document.addEventListener('DOMContentLoaded', function() {
    // Control panel toggle
    $(document).ready(function() {
        setTimeout(function() {
            $(".control-wrapper").animate({
                height: "toggle"
            });
        }, 1000);
        
        $("#slide-toggle").click(function() {
            $(".control-wrapper").animate({
                height: "toggle"
            });
            
            // Check if the setting-box is currently visible
            if ($('.more-box').is(':visible')) {
                $('#input_show_more').trigger('click');
            }
        });

        $("#fullscreen-toggle").click(function() {
            document.body.requestFullscreen();
        });
    });

    slideToggle.addEventListener('click', function() {
        controlWrapper.style.display = controlWrapper.style.display === 'none' ? 'block' : 'none';
        const moreBox = document.querySelector('.more-box');
        if (moreBox && moreBox.style.display !== 'none') {
            document.getElementById('input_show_more').click();
        }
    });

    // Fullscreen toggle
    const fullscreenToggle = document.getElementById('fullscreen-toggle');
    fullscreenToggle.addEventListener('click', function() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    });
});

// Mobile browser detection
function isMobileBrowser() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

if (isMobileBrowser()) {
    $('.DraggableDiv').draggableTouch();
}

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
    // Select all elements with the class 'device-view'
    const deviceViewElements = document.querySelectorAll('.device-view');
    deviceViewElements.forEach(element => {
        element.remove();
        // console.log("Device view element removed from the DOM.");
    });
}

function stream_quality() {
    var e = document.getElementById("stream_quality").value;
    if (e == "1") {
        bitrate = "1524288"
        document.getElementById("in_bitrate").value = "1524288";
        document.getElementById("in_fps").value = "40";
        document.getElementById("in_max_w").value = "1080";
        document.getElementById("in_max_h").value = "1080";
    } else if (e == "2") {
        bitrate = "3524288"
        document.getElementById("in_bitrate").value = "2524288";
        document.getElementById("in_fps").value = "40";
        document.getElementById("in_max_w").value = "1080";
        document.getElementById("in_max_h").value = "1080";
    } else if (e == "3") {
        bitrate = "10524288"
        document.getElementById("in_bitrate").value = "5024288";
        document.getElementById("in_fps").value = "40";
        document.getElementById("in_max_w").value = "1080";
        document.getElementById("in_max_h").value = "1080";
    }
}

// Define CircularBuffer class first
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

// Define AudioStream class
class AudioStream {
    constructor(wsUrl, sampleRate = 10000, targetLatency = 100) {
        this.wsUrl = wsUrl;
        this.sampleRate = sampleRate;
        this.targetLatency = targetLatency;
        this.audioContext = new (window.AudioContext || window.webkitAudioContext)({ sampleRate });
        this.gainNode = this.audioContext.createGain();
        this.channels = 1;
        this.bufferSize = Math.pow(2, Math.ceil(Math.log2(this.sampleRate * this.targetLatency / 1000)));
        this.circularBuffer = new CircularBuffer(this.bufferSize * this.channels * 4); // 4x buffer for safety
        this.lastSampleData = new Float32Array(this.bufferSize * this.channels);
        this.isMuted = false; // Track if audio is muted
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
            if (this.isMuted) return; // Skip processing if muted

            const arrayBuffer = event.data;
            const int16Array = new Int16Array(arrayBuffer);
            const floatArray = new Float32Array(int16Array.length);
            for (let i = 0; i < int16Array.length; i++) {
                floatArray[i] = int16Array[i] / 32768.0;
            }
            this.circularBuffer.write(floatArray);
        };

        this.ws.onopen = () => console.log('WebSocket connected');
        this.ws.onclose = () => {
            console.log('WebSocket disconnected');
            this.muteAudio(true); // Mute audio on WebSocket close
        };
        this.ws.onerror = (error) => console.error('WebSocket error:', error);
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
                    channelData[channel][sample] = this.lastSampleData[sample * this.channels + channel];
                }
            }
        }
    }

    muteAudio(mute) {
        this.isMuted = mute;
        this.gainNode.gain.value = mute ? 0 : 1; // Set gain to 0 to mute, 1 to unmute
    }

    closeWebSocket() {
        if (this.ws) {
            this.ws.close();
        }
    }

    reconnectWebSocket() {
        if (!this.ws || this.ws.readyState === WebSocket.CLOSED || this.ws.readyState === WebSocket.CLOSING) {
            this.setupWebSocket();
        }
    }
}

// Initialize the AudioStream after both classes are defined
let stream1 = new AudioStream('wss://hypercube.my.id:' + audio_port);

// Visibility change handlers
let bitrate;
let blurStartTime = null; // To keep track of the time when the page was blurred
const blurThreshold = 60000; // 1 minute in milliseconds

ifvisible.on("blur", function() {
    setStream("524288");

    // Record the time when the page is blurred
    blurStartTime = Date.now();

    // Close the WebSocket connection and mute audio when the page is blurred
    if (stream1) {
        stream1.closeWebSocket();
        stream1.muteAudio(true); // Mute audio on blur
    }
});

ifvisible.on("wakeup", function() {
    if (blurStartTime) {
        const blurDuration = Date.now() - blurStartTime;

        // Check if the blur duration was more than 1 minute
        if (blurDuration >= blurThreshold) {
            // Remove elements with the class name 'device-view'
            removeDeviceViewElements();

            // Refresh the page
            location.reload();
        } else {
            // Reconnect the WebSocket and unmute audio if needed
            if (stream1) {
                stream1.reconnectWebSocket();
                stream1.muteAudio(false); // Unmute audio on wakeup
            }

            if (bitrate) {
                setStream(bitrate);
            } else {
                setStream("2524288");
            }
        }

        // Reset blurStartTime after processing wakeup event
        blurStartTime = null;
    }
});
