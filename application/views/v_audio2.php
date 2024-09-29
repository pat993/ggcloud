<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-time Audio Player</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }

        #status {
            margin-bottom: 20px;
            font-weight: bold;
        }

        #audioPlayer {
            width: 100%;
            max-width: 500px;
        }

        #bufferInfo,
        #bandwidthInfo {
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>

<body>
    <h1>Real-time Audio Stream</h1>
    <div id="status">Connecting to audio stream...</div>
    <audio id="audioPlayer" controls></audio>
    <div id="bufferInfo"></div>
    <div id="bandwidthInfo"></div>

    <script>
        const audioContext = new(window.AudioContext || window.webkitAudioContext)({
            latencyHint: 'interactive',
            sampleRate: 44100
        });
        let mediaSource = new MediaSource();
        let sourceBuffer = null;
        let audioQueue = [];
        const bufferSize = 0.3;
        const audioPlayer = document.getElementById('audioPlayer');
        audioPlayer.src = URL.createObjectURL(mediaSource);

        let ws = null;
        let reconnectTimeout = 5000;
        let lastBufferUpdate = Date.now();

        // Bandwidth calculation variables
        let totalBytesReceived = 0;
        let lastBandwidthCheck = Date.now();

        function connectWebSocket() {
            ws = new WebSocket('wss://hypercube.my.id:9101');
            ws.binaryType = 'arraybuffer';

            ws.onopen = () => {
                document.getElementById('status').textContent = 'Connected to audio stream';
                totalBytesReceived = 0;
                lastBandwidthCheck = Date.now();
            };

            ws.onmessage = (event) => {
                if (event.data instanceof ArrayBuffer) {
                    const audioData = new Uint8Array(event.data);
                    audioQueue.push(audioData);
                    lastBufferUpdate = Date.now();
                    totalBytesReceived += audioData.length;
                    if (sourceBuffer && !sourceBuffer.updating && audioQueue.length > 0) {
                        appendNextSegment();
                    }
                }
            };

            ws.onerror = (error) => {
                document.getElementById('status').textContent = 'Error in audio stream: ' + error;
                reconnectWebSocket();
            };

            ws.onclose = () => {
                document.getElementById('status').textContent = 'Disconnected from audio stream';
                reconnectWebSocket();
            };
        }

        function reconnectWebSocket() {
            setTimeout(() => {
                document.getElementById('status').textContent = 'Reconnecting to audio stream...';
                connectWebSocket();
            }, reconnectTimeout);
        }

        mediaSource.addEventListener('sourceopen', () => {
            const mimeType = 'audio/aac';
            sourceBuffer = mediaSource.addSourceBuffer(mimeType);
            sourceBuffer.mode = 'sequence';
            sourceBuffer.addEventListener('updateend', appendNextSegment);
        });

        function appendNextSegment() {
            if (sourceBuffer.updating || audioQueue.length === 0) return;

            const segment = audioQueue.shift();
            sourceBuffer.appendBuffer(segment);
            updateBufferInfo();

            if (audioPlayer.paused) {
                audioPlayer.play().catch(e => console.error('Playback failed:', e));
            }
        }

        function updateBufferInfo() {
            const buffered = audioPlayer.buffered;
            let bufferLength = 0;

            if (buffered.length > 0) {
                bufferLength = buffered.end(buffered.length - 1) - audioPlayer.currentTime;
            }

            document.getElementById('bufferInfo').textContent = `Buffer length: ${bufferLength.toFixed(2)}s`;

            if (bufferLength < bufferSize * 0.5) {
                audioPlayer.playbackRate = 0.98;
            } else if (bufferLength > bufferSize) {
                audioPlayer.playbackRate = 2;
            } else {
                audioPlayer.playbackRate = 1.0;
            }

            const timeSinceLastUpdate = Date.now() - lastBufferUpdate;
            if (timeSinceLastUpdate > 10000) {
                document.getElementById('status').textContent = 'Buffer freeze detected. Reconnecting...';
                ws.close();
            }

            // Calculate and display bandwidth
            const now = Date.now();
            const timeDiff = (now - lastBandwidthCheck) / 1000; // Convert to seconds
            if (timeDiff >= 1) { // Update bandwidth every second
                const bandwidthKBps = (totalBytesReceived / timeDiff / 1024).toFixed(2);
                document.getElementById('bandwidthInfo').textContent = `Bandwidth: ${bandwidthKBps} KB/s`;
                totalBytesReceived = 0;
                lastBandwidthCheck = now;
            }
        }

        setInterval(updateBufferInfo, 1000);

        connectWebSocket();
    </script>
</body>

</html>