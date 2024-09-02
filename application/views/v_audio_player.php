<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio Streaming</title>
</head>

<body>
    <script>
        let audioContext = new(window.AudioContext || window.webkitAudioContext)({
            sampleRate: 10000
        });
        let gainNode = audioContext.createGain();
        let scriptNode;
        let channels = 1;
        let sampleRate = 10000;
        let targetLatency = 50;

        gainNode.connect(audioContext.destination);

        const ws = new WebSocket('wss://hypercube.my.id:9103');

        // Circular buffer
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

        let circularBuffer;
        let bufferSize;
        let lastTimestamp = Date.now();
        let totalBytesReceived = 0;

        function initAudio() {
            if (scriptNode) {
                scriptNode.disconnect();
            }
            bufferSize = Math.pow(2, Math.ceil(Math.log2(sampleRate * targetLatency / 1000)));
            circularBuffer = new CircularBuffer(bufferSize * channels * 4); // 4x buffer for safety
            scriptNode = audioContext.createScriptProcessor(bufferSize, channels, channels);
            scriptNode.onaudioprocess = processAudio;
            scriptNode.connect(gainNode);
            gainNode.connect(audioContext.destination);
        }

        initAudio();

        ws.onmessage = async (event) => {
            const arrayBuffer = await event.data.arrayBuffer();
            const int16Array = new Int16Array(arrayBuffer);

            const floatArray = new Float32Array(int16Array.length);
            for (let i = 0; i < int16Array.length; i++) {
                floatArray[i] = int16Array[i] / 32768.0;
            }

            circularBuffer.write(floatArray);
        };

        function processAudio(audioProcessingEvent) {
            const outputBuffer = audioProcessingEvent.outputBuffer;
            const channelData = [];
            for (let channel = 0; channel < outputBuffer.numberOfChannels; channel++) {
                channelData.push(outputBuffer.getChannelData(channel));
            }

            const availableSamples = circularBuffer.available() / channels;
            if (availableSamples >= outputBuffer.length) {
                const data = circularBuffer.read(outputBuffer.length * channels);
                for (let channel = 0; channel < outputBuffer.numberOfChannels; channel++) {
                    for (let sample = 0; sample < outputBuffer.length; sample++) {
                        channelData[channel][sample] = data[sample * channels + channel];
                    }
                }
            } else {
                for (let channel = 0; channel < outputBuffer.numberOfChannels; channel++) {
                    for (let sample = 0; sample < outputBuffer.length; sample++) {
                        channelData[channel][sample] = channelData[channel][sample] * (1 - (sample / outputBuffer.length));
                    }
                }
                console.warn('Buffer underrun');
            }
        }

        ws.onopen = () => {
            console.log('WebSocket connected');
        };

        ws.onclose = () => {
            console.log('WebSocket disconnected');
        };

        ws.onerror = (error) => {
            console.error('WebSocket error:', error);
        };
    </script>
</body>

</html>