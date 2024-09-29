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
const audio_port = getCookie('venus') || 'error';

let visible_status = true;

class AudioStream {
    constructor(wsUrl, sampleRate = 32000, targetLatency = 100) {
        this.wsUrl = wsUrl;
        this.sampleRate = sampleRate;
        this.targetLatency = targetLatency;
        this.audioContext = new (window.AudioContext || window.webkitAudioContext)({ 
            latencyHint: 'interactive',
            sampleRate: this.sampleRate 
        });
        this.mediaSource = new MediaSource();
        this.sourceBuffer = null;
        this.audioQueue = [];
        this.bufferSize = 0.7;
        this.audioPlayer = document.createElement('audio');
        this.audioPlayer.style.display = 'none';
        document.body.appendChild(this.audioPlayer);
        this.audioPlayer.src = URL.createObjectURL(this.mediaSource);
        this.isMuted = false;
        this.pingInterval = null;
        this.pingStartTime = null;
        this.latencyDisplay = document.getElementById('latency-display');
        this.lastPingTime = null;
        this.highPingCount = 0;
        this.normalPingCount = 0;
        this.originalBitrate = null;
        this.currentBitrate = null;
        this.totalBytesReceived = 0;
        this.lastBandwidthCheck = Date.now();
        this.lastBufferUpdate = Date.now();
        this.initAudio();
        this.setupWebSocket();
    }

    initAudio() {
        this.mediaSource.addEventListener('sourceopen', () => {
            const mimeType = 'audio/aac';
            this.sourceBuffer = this.mediaSource.addSourceBuffer(mimeType);
            this.sourceBuffer.mode = 'sequence';
            this.sourceBuffer.addEventListener('updateend', this.appendNextSegment.bind(this));
        });
    }

    setupWebSocket() {
        this.ws = new WebSocket(this.wsUrl);
        this.ws.binaryType = 'arraybuffer';

        this.ws.onopen = () => {
            console.log('WebSocket connected');
            this.muteAudio(false);
            this.startPing();
        };

        this.ws.onmessage = (event) => {
            if (event.data === 'pong') {
                const pingTime = Date.now() - this.pingStartTime;
                this.lastPingTime = pingTime;
                this.updateLatencyDisplay();
            } else if (!this.isMuted) {
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
            if (visible_status == true) {
                setTimeout(() => this.reconnectWebSocket(), 1000);
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
        this.updateBufferInfo();

        if (this.audioPlayer.paused) {
            this.audioPlayer.play().catch(e => console.error('Playback failed:', e));
        }
    }

    updateBufferInfo() {
        const buffered = this.audioPlayer.buffered;
        let bufferLength = 0;

        if (buffered.length > 0) {
            bufferLength = buffered.end(buffered.length - 1) - this.audioPlayer.currentTime;
        }

        if (bufferLength < this.bufferSize * 0.5) {
            this.audioPlayer.playbackRate = 0.98;
        } else if (bufferLength > this.bufferSize) {
            this.audioPlayer.playbackRate = 2;
        } else {
            this.audioPlayer.playbackRate = 1.0;
        }

        const timeSinceLastUpdate = Date.now() - this.lastBufferUpdate;
        if (timeSinceLastUpdate > 10000) {
            this.updateLatencyDisplay('Buffer freeze detected. Reconnecting...');
            this.ws.close();
        }

        // Calculate and display bandwidth
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
        this.audioPlayer.muted = mute;
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

// Initialize the AudioStream after the class is defined
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

// Start periodic buffer info update
setInterval(() => {
    if (stream1) {
        stream1.updateBufferInfo();
    }
}, 1000);