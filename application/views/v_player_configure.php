<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <title>Connecting to Server ...</title>
    <link rel="stylesheet" href="/ggc/main.css">
    <link rel="stylesheet" href="/ggc/styles/bootstrap.css">
    <link rel="stylesheet" href="/ggc/styles/style.css">
    <link rel="stylesheet" href="/ggc/draggable.css">
    <link rel="stylesheet" href="/ggc/styles/loader.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i|Source+Sans+Pro:300,300i,400,400i,600,600i,700,700i,900,900i&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.6.0/css/all.min.css" integrity="sha512-ykRBEJhyZ+B/BIJcBuOyUoIxh0OfdICfHPnPfBy7eIiyJv536ojTCsgX8aqrLQ9VJZHGz4tvYyzOM0lkgmQZGw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="icon" type="image/x-icon" href="/images/ggc_play.png">
    <link rel="stylesheet" href="/ggc/styles/custom1.css">
</head>

<body style="background-color: black; position: relative" class="theme-dark" data-highlight="highlight-red" data-gradient="body-default">
    <div class="battery">
        <div class="straight"></div>
        <div class="curve"></div>
        <div class="center"></div>
        <div class="inner"></div>
    </div>

    <div class="DraggableDiv">
        <button class="btn btn-dark rounded-xl" id="fullscreen-toggle"><i class="fas fa-expand" style="font-size: 10px;"></i></button>
        <button class="btn btn-dark rounded-xl mt-1" id="slide-toggle"><i class="fas fa-ellipsis-h" style="font-size: 10px;"></i></button>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="/ggc/bundle.js"></script>
    <script src="/ggc/scripts/bootstrap.min.js"></script>
    <script src="/ggc/scripts/custom.js"></script>
    <script src="/ggc/draggable.js"></script>
    <script src="/ggc/scripts/ifvisible.js"></script>
    <script src="/ggc/scripts/main.js"></script>

    <script>
        let audioContext = new(window.AudioContext || window.webkitAudioContext)({
            sampleRate: 16000
        });
        let gainNode = audioContext.createGain();
        let scriptNode;
        let channels = 2;
        let sampleRate = 16000;
        let targetLatency = 50;

        gainNode.connect(audioContext.destination);

        const ws = new WebSocket('wss://hypercube.my.id:<?= $audio_port; ?>');

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

    <a style="position:absolute; bottom: 10px; right: 10px; z-index: 99" class="btn btn-primary" href="<?= base_url() . 'device_manager/done_configure/' . $dev_id; ?>"><button>Done Configure</button></a>
</body>

</html>