const WebSocket = require('ws');
const { spawn } = require('child_process');
const { Readable } = require('stream');
const net = require('net');
const fs = require('fs');
const readline = require('readline');
const os = require('os');

const FFMPEG_BINARY = 'ffmpeg';
const RECONNECT_INTERVAL = 1000;
const PORT_SCAN_RETRY_INTERVAL = 5000;
const WS_BUFFER_SIZE = 128 * 1024;
const PING_INTERVAL = 5000;
const PING_TIMEOUT = 3000;
const INPUT_PORT_RANGE = [8080, 8081, 8082, 8083, 8084, 8085];
const DEFAULT_OUTPUT_PORT = 9000;
const STATUS_UPDATE_INTERVAL = 3000;

const sourceStatuses = new Map();
const sourceBandwidth = new Map();

function updateSourceStatus(ip, status) {
  sourceStatuses.set(ip, status);
}

function updateSourceBandwidth(ip, bytes) {
  const now = Date.now();
  const prevData = sourceBandwidth.get(ip) || { lastUpdate: now, bytes: 0 };
  const timeDiff = (now - prevData.lastUpdate) / 1000; // in seconds
  
  if (timeDiff > 0) {
    const kbps = ((bytes - prevData.bytes) / 1024 / timeDiff).toFixed(2);
    sourceBandwidth.set(ip, { lastUpdate: now, bytes, kbps });
  } else {
    sourceBandwidth.set(ip, { ...prevData, bytes });
  }
}

function getStatusString() {
  let statusString = '\n--- Source Statuses ---\n';
  for (const [ip, status] of sourceStatuses) {
    const bw = sourceBandwidth.get(ip);
    const bandwidthInfo = bw ? `${bw.kbps} KB/s` : 'N/A';
    statusString += `${ip}: ${status} | Bandwidth to Client: ${bandwidthInfo}\n`;
  }
  return statusString;
}

function getResourceUsage() {
  const cpuUsage = os.loadavg()[0].toFixed(2);
  const totalMem = os.totalmem();
  const freeMem = os.freemem();
  const usedMem = ((totalMem - freeMem) / totalMem * 100).toFixed(2);
  
  return `\n--- Resource Usage ---\nCPU Load: ${cpuUsage}%\nMemory Usage: ${usedMem}%`;
}

function clearConsoleAndPrintStatus() {
  console.clear();
  console.log(getStatusString());
  console.log(getResourceUsage());
}

setInterval(clearConsoleAndPrintStatus, STATUS_UPDATE_INTERVAL);

async function readAudioSources() {
  const sources = [];
  const fileStream = fs.createReadStream('audio_sources.txt');
  const rl = readline.createInterface({
    input: fileStream,
    crlfDelay: Infinity
  });

  let lineNumber = 0;
  for await (const line of rl) {
    lineNumber++;
    if (line.includes('|')) {
      const [ip, outputPortStr] = line.split('|').map(s => s.trim());
      if (ip) {
        const outputPort = parseInt(outputPortStr);
        if (isNaN(outputPort) || outputPort < 1 || outputPort > 65535) {
          updateSourceStatus(ip, `Invalid output port "${outputPortStr}". Using default port ${DEFAULT_OUTPUT_PORT}.`);
          sources.push({ ip, outputPort: DEFAULT_OUTPUT_PORT });
        } else {
          sources.push({ ip, outputPort });
        }
        updateSourceStatus(ip, 'Initializing...');
        sourceBandwidth.set(ip, { lastUpdate: Date.now(), bytes: 0, kbps: 0 });
      } else {
        updateSourceStatus(`Line ${lineNumber}`, 'Invalid IP address. Skipping this line.');
      }
    }
  }

  return sources;
}

async function scanForOpenPort(host, ports) {
  for (const port of ports) {
    try {
      await new Promise((resolve, reject) => {
        const socket = new net.Socket();
        socket.setTimeout(1000);
        socket.on('connect', () => {
          socket.destroy();
          resolve();
        });
        socket.on('timeout', () => {
          socket.destroy();
          reject(new Error('Connection timeout'));
        });
        socket.on('error', (err) => {
          reject(err);
        });
        socket.connect(port, host);
      });
      updateSourceStatus(host, `Connected on port ${port}`);
      return port;
    } catch (error) {
      updateSourceStatus(host, `Port ${port} is closed or unreachable`);
    }
  }
  throw new Error('All ports failed');
}

function createPCMWebSocket(url, onOpen, onMessage, onClose, onError) {
  const socket = new WebSocket(url, {
    perMessageDeflate: false,
    maxPayload: WS_BUFFER_SIZE
  });

  let pingTimeout;

  socket.on('open', () => {
    socket._socket.setNoDelay(true);
    onOpen();

    const interval = setInterval(() => {
      if (socket.readyState === WebSocket.OPEN) {
        socket.ping();
      }
    }, PING_INTERVAL);

    socket.on('close', () => {
      clearInterval(interval);
      clearTimeout(pingTimeout);
      onClose();
    });
  });

  socket.on('pong', () => {
    clearTimeout(pingTimeout);
  });

  socket.on('message', onMessage);
  socket.on('close', onClose);
  socket.on('error', onError);

  return socket;
}

async function setupAudioProcessor(source) {
  const wss = new WebSocket.Server({ port: source.outputPort });
  let ffmpeg;
  let aacStream;
  let clients = new Set();

  function setupFFmpeg() {
    ffmpeg = spawn(FFMPEG_BINARY, [
      '-fflags', 'nobuffer',
      '-f', 's16le',
      '-ar', '44100',
      '-ac', '1',
      '-i', '-',
      '-c:a', 'aac',
      '-b:a', '64k',
      '-af', 'aresample=async=1',
      '-fflags', 'nobuffer',
      '-f', 'adts',
      '-'
    ]);

    ffmpeg.stderr.on('data', (data) => {
      updateSourceStatus(source.ip, `FFmpeg: ${data.toString().trim()}`);
    });

    aacStream = new Readable({
      read(size) { },
      highWaterMark: 64 * 1024
    });

    ffmpeg.stdout.on('data', (aacData) => {
      if (clients.size > 0) {
        updateSourceStatus(source.ip, `Encoding: ${aacData.length} bytes processed`);
        for (let client of clients) {
          if (client.readyState === WebSocket.OPEN) {
            client.send(aacData, (error) => {
              if (!error) {
                updateSourceBandwidth(source.ip, aacData.length);
              }
            });
          }
        }
      } else {
        // If no clients are connected, we don't process the data
        updateSourceStatus(source.ip, `No clients connected. Skipping ${aacData.length} bytes.`);
      }
    });
  }

  function onOpen() {
    updateSourceStatus(source.ip, 'Connected to PCM WebSocket');
    if (clients.size > 0 && !ffmpeg) {
      setupFFmpeg();
    }
  }

  function onMessage(pcmData) {
    updateSourceStatus(source.ip, `Received: ${pcmData.length} bytes`);
    if (clients.size > 0 && ffmpeg && ffmpeg.stdin.writable) {
      ffmpeg.stdin.write(pcmData);
    }
  }

  function onClose() {
    if (ffmpeg) {
      ffmpeg.kill();
      ffmpeg = null;
    }
    updateSourceStatus(source.ip, 'Disconnected from PCM WebSocket');
    reconnect();
  }

  function onError(error) {
    updateSourceStatus(source.ip, `Error: ${error.message}`);
  }

  async function reconnect() {
    setTimeout(async () => {
      updateSourceStatus(source.ip, 'Attempting to reconnect...');
      try {
        const port = await scanForOpenPort(source.ip, INPUT_PORT_RANGE);
        const newUrl = `ws://${source.ip}:${port}`;
        createPCMWebSocket(newUrl, onOpen, onMessage, onClose, onError);
      } catch (error) {
        updateSourceStatus(source.ip, `Failed to reconnect: ${error.message}`);
        reconnect();
      }
    }, RECONNECT_INTERVAL);
  }

  try {
    const port = await scanForOpenPort(source.ip, INPUT_PORT_RANGE);
    const url = `ws://${source.ip}:${port}`;
    createPCMWebSocket(url, onOpen, onMessage, onClose, onError);
  } catch (error) {
    updateSourceStatus(source.ip, `Failed to connect: ${error.message}`);
    reconnect();
  }

  wss.on('connection', (ws) => {
    updateSourceStatus(source.ip, `Client connected to output port ${source.outputPort}`);
    clients.add(ws);
    
    if (clients.size === 1 && !ffmpeg) {
      setupFFmpeg();
    }

    ws.on('close', () => {
      updateSourceStatus(source.ip, `Client disconnected from output port ${source.outputPort}`);
      clients.delete(ws);
      
      if (clients.size === 0 && ffmpeg) {
        ffmpeg.kill();
        ffmpeg = null;
        updateSourceStatus(source.ip, 'No clients connected. Stopped FFmpeg processing.');
      }
    });
  });

  updateSourceStatus(source.ip, `Listening on output port ${source.outputPort}`);
}

async function main() {
  try {
    const sources = await readAudioSources();
    for (const source of sources) {
      setupAudioProcessor(source);
    }
  } catch (error) {
    console.error(`Error: ${error.message}`);
    process.exit(1);
  }
}

main();