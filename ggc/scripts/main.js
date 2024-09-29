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

// New AudioPlayer class
class AudioPlayer {
    constructor(wsUrl) {
        this.wsUrl = wsUrl;
        this.audioContext = new (window.AudioContext || window.webkitAudioContext)({
            latencyHint: 'interactive',
            sampleRate: 32000
        });
        this.mediaSource = new MediaSource();
        this.sourceBuffer = null;
        this.audioQueue = [];
        this.bufferSize = 0.5;
        this.audioElement = document.createElement('audio');
        this.audioElement.style.display = 'none';
        document.body.appendChild(this.audioElement);
        this.audioElement.src = URL.createObjectURL(this.mediaSource);
        this.lastBufferUpdate = Date.now();
        this.totalBytesReceived = 0;
        this.lastBandwidthCheck = Date.now();
        this.isMuted = false;
        this.pingInterval = null;
        this.pingStartTime = null;
        this.latencyDisplay = document.getElementById('latency-display');
        this.lastPingTime = null;
        this.highPingCount = 0;
        this.normalPingCount = 0;
        this.originalBitrate = null;
        this.currentBitrate = null;
        this.setupMediaSource();
        this.setupWebSocket();
        this.updateInterval = setInterval(() => this.updateBufferInfo(), 1000);
    }

    setupMediaSource() {
        this.mediaSource.addEventListener('sourceopen', () => {
            const mimeType = 'audio/aac';
            this.sourceBuffer = this.mediaSource.addSourceBuffer(mimeType);
            this.sourceBuffer.mode = 'sequence';
            this.sourceBuffer.addEventListener('updateend', () => this.appendNextSegment());
        });
    }

    setupWebSocket() {
        this.ws = new WebSocket(this.wsUrl);
        this.ws.binaryType = 'arraybuffer';

        this.ws.onopen = () => {
            console.log('WebSocket connected');
            this.totalBytesReceived = 0;
            this.lastBandwidthCheck = Date.now();
            this.startPing();
        };

        this.ws.onmessage = (event) => {
            if (event.data === 'pong') {
                const pingTime = Date.now() - this.pingStartTime;
                this.lastPingTime = pingTime;
                this.updateLatencyDisplay();
            } else if (event.data instanceof ArrayBuffer) {
                const audioData = new Uint8Array(event.data);
                this.audioQueue.push(audioData);
                this.lastBufferUpdate = Date.now();
                this.totalBytesReceived += audioData.length;
                if (this.sourceBuffer && !this.sourceBuffer.updating && this.audioQueue.length > 0) {
                    this.appendNextSegment();
                }
            }
        };

        this.ws.onclose = () => {
            console.log('WebSocket disconnected');
            this.stopPing();
            this.updateLatencyDisplay('Disconnected');
            if (visible_status) {
                setTimeout(() => this.setupWebSocket(), 1000);
            }
        };

        this.ws.onerror = (error) => {
            console.error('WebSocket error:', error);
        };
    }

    appendNextSegment() {
        if (this.sourceBuffer.updating || this.audioQueue.length === 0) return;

        const segment = this.audioQueue.shift();
        this.sourceBuffer.appendBuffer(segment);

        if (this.audioElement.paused && !this.isMuted) {
            this.audioElement.play().catch(e => console.error('Playback failed:', e));
        }
    }

    updateBufferInfo() {
        const buffered = this.audioElement.buffered;
        let bufferLength = 0;

        if (buffered.length > 0) {
            bufferLength = buffered.end(buffered.length - 1) - this.audioElement.currentTime;
        }

        if (bufferLength < this.bufferSize * 0.5) {
            this.audioElement.playbackRate = 0.98;
        } else if (bufferLength > this.bufferSize) {
            this.audioElement.playbackRate = 2;
        } else {
            this.audioElement.playbackRate = 1.0;
        }

        const timeSinceLastUpdate = Date.now() - this.lastBufferUpdate;
        if (timeSinceLastUpdate > 10000) {
            console.log('Buffer freeze detected. Reconnecting...');
            this.ws.close();
        }

        // Calculate bandwidth
        const now = Date.now();
        const timeDiff = (now - this.lastBandwidthCheck) / 1000; // Convert to seconds
        if (timeDiff >= 1) { // Update bandwidth every second
            const bandwidthKBps = (this.totalBytesReceived / timeDiff / 1024).toFixed(2);
            console.log(`Bandwidth: ${bandwidthKBps} KB/s`);
            this.totalBytesReceived = 0;
            this.lastBandwidthCheck = now;
        }
    }

    muteAudio(mute) {
        this.isMuted = mute;
        this.audioElement.muted = mute;
    }

    closeWebSocket() {
        if (this.ws) {
            this.ws.close();
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
            if (this.latencyDisplay) {
                if (status) {
                    this.latencyDisplay.innerHTML = status;
                } else if (this.lastPingTime !== null) {
                    this.latencyDisplay.innerHTML = `<i class='fas fa-signal'></i> ${this.lastPingTime} ms`;

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

// Initialize the AudioPlayer
let audioPlayer = new AudioPlayer('wss://hypercube.my.id:' + audio_port);

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

    if (audioPlayer) {
        audioPlayer.muteAudio(true);
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
            if (audioPlayer) {
                audioPlayer.setupWebSocket();
                audioPlayer.muteAudio(false);
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
        audioPlayer.muteAudio(isMuted);

        const icon = $(this).find('i');
        if (isMuted) {
            icon.removeClass('fa-volume-up').addClass('fa-volume-mute');
        } else {
            icon.removeClass('fa-volume-mute').addClass('fa-volume-up');
        }
    });
});