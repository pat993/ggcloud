// Dual Toggle Component
class SyncToggleControls {
    constructor() {
        this.pageId = this.getPageIdentifier();
        this.loadStoredState();
        this.isVisible = false;
        this.createToggleContainer();
        
        // Delay initialization to ensure all components are ready
        setTimeout(() => {
            this.initializeSyncState();
        }, 500);
    }

    getPageIdentifier() {
        const path = window.location.pathname;
        const matches = path.match(/\/player\/go\/([A-Z0-9]+)/);
        return matches ? matches[1] : 'default';
    }

    getStorageKeys() {
        return {
            source: `syncToggle_source_${this.pageId}`,
            client: `syncToggle_client_${this.pageId}`
        };
    }

    loadStoredState() {
        const keys = this.getStorageKeys();
        this.isSourceEnabled = localStorage.getItem(keys.source) === 'true';
        this.isClientEnabled = localStorage.getItem(keys.client) === 'true';
    }

    // Initialize sync state with forced reinitialization
    initializeSyncState() {
        if (this.isSourceEnabled) {
            // Force off then on to ensure proper initialization
            window.dispatchEvent(new CustomEvent('mouseSyncSourceToggle', {
                detail: { enabled: false }
            }));
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('mouseSyncSourceToggle', {
                    detail: { enabled: true }
                }));
            }, 100);
        }
        
        if (this.isClientEnabled) {
            // Force off then on to ensure proper initialization
            window.dispatchEvent(new CustomEvent('mouseSyncClientToggle', {
                detail: { enabled: false }
            }));
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('mouseSyncClientToggle', {
                    detail: { enabled: true }
                }));
            }, 100);
        }
    }

    saveState() {
        const keys = this.getStorageKeys();
        localStorage.setItem(keys.source, this.isSourceEnabled);
        localStorage.setItem(keys.client, this.isClientEnabled);
    }

    createToggleContainer() {
        const container = document.createElement('div');
        container.style.cssText = `
            position: fixed;
            left: 20px;
            bottom: 20px;
            z-index: 9999;
            display: none;
            flex-direction: column;
            gap: 8px;
        `;

        this.container = container;

        this.sourceToggle = this.createToggleButton('Source');
        this.clientToggle = this.createToggleButton('Client');

        container.appendChild(this.sourceToggle.element);
        container.appendChild(this.clientToggle.element);
        document.body.appendChild(container);

        // Initialize visual toggle states
        if (this.isSourceEnabled) {
            this.updateSourceVisuals(true);
        }
        if (this.isClientEnabled) {
            this.updateClientVisuals(true);
        }

        this.sourceToggle.element.addEventListener('click', () => {
            if (this.isClientEnabled) {
                this.toggleClient(false);
            }
            this.toggleSource(!this.isSourceEnabled);
        });

        this.clientToggle.element.addEventListener('click', () => {
            if (this.isSourceEnabled) {
                this.toggleSource(false);
            }
            this.toggleClient(!this.isClientEnabled);
        });

        const syncToggleBtn = document.getElementById('sync-toggle');
        if (syncToggleBtn) {
            syncToggleBtn.addEventListener('click', () => this.toggleVisibility());
        }
    }

    toggleVisibility() {
        this.isVisible = !this.isVisible;
        this.container.style.display = this.isVisible ? 'flex' : 'none';
    }

    createToggleButton(type) {
        const toggle = document.createElement('div');
        toggle.style.cssText = `
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(0, 0, 0, 0.8);
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            user-select: none;
            transition: background-color 0.3s;
        `;

        const indicator = document.createElement('div');
        indicator.style.cssText = `
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ff4444;
            transition: background-color 0.3s;
        `;

        const label = document.createElement('span');
        label.style.cssText = `
            color: white;
            font-family: Arial, sans-serif;
            font-size: 9px;
        `;
        label.textContent = `Sync ${type}: OFF`;

        toggle.appendChild(indicator);
        toggle.appendChild(label);

        return {
            element: toggle,
            indicator: indicator,
            label: label
        };
    }

    updateSourceVisuals(enable) {
        this.sourceToggle.indicator.style.background = enable ? '#44ff44' : '#ff4444';
        this.sourceToggle.label.textContent = `Sync Source: ${enable ? 'ON' : 'OFF'}`;
    }

    updateClientVisuals(enable) {
        this.clientToggle.indicator.style.background = enable ? '#44ff44' : '#ff4444';
        this.clientToggle.label.textContent = `Sync Client: ${enable ? 'ON' : 'OFF'}`;
    }

    toggleSource(enable) {
        this.isSourceEnabled = enable;
        this.updateSourceVisuals(enable);
        window.dispatchEvent(new CustomEvent('mouseSyncSourceToggle', {
            detail: { enabled: enable }
        }));
        this.saveState();
    }

    toggleClient(enable) {
        this.isClientEnabled = enable;
        this.updateClientVisuals(enable);
        window.dispatchEvent(new CustomEvent('mouseSyncClientToggle', {
            detail: { enabled: enable }
        }));
        this.saveState();
    }
}

// Base MouseSync Class with improved position calculations
class MouseSyncBase {
    constructor(canvasSelector) {
        this.canvasSelector = canvasSelector;
        this.waitForElement();
        this.originalAspectRatio = null;
        this.activeTouchId = null;
        this.lastEventTime = 0;
        this.eventBuffer = [];
        this.THROTTLE_MS = 8; // ~60fps
        this.BUFFER_SIZE = 5;  // Store recent positions for interpolation
    }
    waitForElement() {
        const element = document.querySelector(this.canvasSelector);
        if (element) {
            this.initialize(element);
            return;
        }

        const observer = new MutationObserver(() => {
            const element = document.querySelector(this.canvasSelector);
            if (element) {
                observer.disconnect();
                this.initialize(element);
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    setOriginalAspectRatio() {
        if (!this.originalAspectRatio) {
            this.originalAspectRatio = this.canvas.width / this.canvas.height;
        }
    }

    calculateScalingFactors() {
        const rect = this.canvas.getBoundingClientRect();
        const currentAspectRatio = rect.width / rect.height;
        
        let scaleX = this.canvas.width / rect.width;
        let scaleY = this.canvas.height / rect.height;
        
        // Calculate letterboxing or pillarboxing adjustments
        let offsetX = 0;
        let offsetY = 0;
        
        if (currentAspectRatio > this.originalAspectRatio) {
            // Letterboxing (horizontal bars)
            const scaledWidth = rect.height * this.originalAspectRatio;
            offsetX = (rect.width - scaledWidth) / 2;
            scaleX = this.canvas.width / scaledWidth;
        } else if (currentAspectRatio < this.originalAspectRatio) {
            // Pillarboxing (vertical bars)
            const scaledHeight = rect.width / this.originalAspectRatio;
            offsetY = (rect.height - scaledHeight) / 2;
            scaleY = this.canvas.height / scaledHeight;
        }

        return {
            scaleX,
            scaleY,
            offsetX,
            offsetY,
            rect
        };
    }

    getRelativePosition(clientX, clientY) {
        this.setOriginalAspectRatio();
        const { scaleX, scaleY, offsetX, offsetY, rect } = this.calculateScalingFactors();
        
        // Adjust for letterboxing/pillarboxing
        const adjustedX = clientX - rect.left - offsetX;
        const adjustedY = clientY - rect.top - offsetY;
        
        // Calculate normalized positions (0 to 1)
        let normalizedX = (adjustedX * scaleX) / this.canvas.width;
        let normalizedY = (adjustedY * scaleY) / this.canvas.height;
        
        // Clamp values between 0 and 1
        normalizedX = Math.max(0, Math.min(1, normalizedX));
        normalizedY = Math.max(0, Math.min(1, normalizedY));
        
        return {
            x: normalizedX,
            y: normalizedY
        };
    }

    getRelativePositionFromTouch(touch) {
        return this.getRelativePosition(touch.clientX, touch.clientY);
    }

    isPointInCanvas(clientX, clientY) {
        this.setOriginalAspectRatio();
        const { offsetX, offsetY, rect } = this.calculateScalingFactors();
        
        // Calculate effective display area accounting for letterboxing/pillarboxing
        const effectiveLeft = rect.left + offsetX;
        const effectiveRight = rect.right - offsetX;
        const effectiveTop = rect.top + offsetY;
        const effectiveBottom = rect.bottom - offsetY;
        
        return (
            clientX >= effectiveLeft && 
            clientX <= effectiveRight &&
            clientY >= effectiveTop && 
            clientY <= effectiveBottom
        );
    }
}

// Source (Broadcaster) Class remains largely the same
class MouseSyncSource extends MouseSyncBase {
    initialize(canvas) {
        this.canvas = canvas;
        this.mouseBroadcast = new BroadcastChannel('mouse-sync-canvas');
        this.isMouseDown = false;
        this.isTouchActive = false;
        this.lastPos = { x: 0, y: 0 };
        this.isEnabled = false;
        this.activeTouchId = null;
        
        window.addEventListener('mouseSyncSourceToggle', (e) => {
            this.isEnabled = e.detail.enabled;
            console.log(`🎯 Source Mode ${this.isEnabled ? 'enabled' : 'disabled'}`);
        });
        
        this.initializeEvents();
        console.log('🎯 Mouse Sync Source initialized with touch support');
    }
    
    broadcastEvent(type, relativeX, relativeY) {
        if (!this.isEnabled) return;
        
        const message = {
            type,
            x: relativeX,
            y: relativeY,
            timestamp: Date.now(),
            source: true
        };
        this.mouseBroadcast.postMessage(message);
    }

    initializeEvents() {
        // Mouse Events
        this.canvas.addEventListener('mousedown', (e) => {
            if (!this.isEnabled || !this.isPointInCanvas(e.clientX, e.clientY) || this.isTouchActive) return;
            
            this.isMouseDown = true;
            this.lastPos = this.getRelativePosition(e.clientX, e.clientY);
            this.broadcastEvent('mousedown', this.lastPos.x, this.lastPos.y);
            e.preventDefault();
        }, { capture: true });

        this.canvas.addEventListener('mousemove', (e) => {
            if (!this.isEnabled || !this.isMouseDown || !this.isPointInCanvas(e.clientX, e.clientY) || this.isTouchActive) return;

            this.lastPos = this.getRelativePosition(e.clientX, e.clientY);
            this.broadcastEvent('mousemove', this.lastPos.x, this.lastPos.y);
            e.preventDefault();
        }, { capture: true });

        this.canvas.addEventListener('mouseup', (e) => {
            if (!this.isEnabled || !this.isMouseDown || this.isTouchActive) return;
            
            if (this.isPointInCanvas(e.clientX, e.clientY)) {
                this.lastPos = this.getRelativePosition(e.clientX, e.clientY);
                this.broadcastEvent('mouseup', this.lastPos.x, this.lastPos.y);
            }
            
            this.isMouseDown = false;
            e.preventDefault();
        }, { capture: true });

        // Touch Events
        this.canvas.addEventListener('touchstart', (e) => {
            if (!this.isEnabled) return;
            
            const touch = e.touches[0];
            if (!this.isPointInCanvas(touch.clientX, touch.clientY)) return;
            
            this.isTouchActive = true;
            this.activeTouchId = touch.identifier;
            this.lastPos = this.getRelativePositionFromTouch(touch);
            this.broadcastEvent('mousedown', this.lastPos.x, this.lastPos.y);
            e.preventDefault();
        }, { capture: true, passive: false });

        this.canvas.addEventListener('touchmove', (e) => {
            if (!this.isEnabled || !this.isTouchActive) return;
            
            const touch = Array.from(e.touches).find(t => t.identifier === this.activeTouchId);
            if (!touch || !this.isPointInCanvas(touch.clientX, touch.clientY)) return;
            
            this.lastPos = this.getRelativePositionFromTouch(touch);
            this.broadcastEvent('mousemove', this.lastPos.x, this.lastPos.y);
            e.preventDefault();
        }, { capture: true, passive: false });

        this.canvas.addEventListener('touchend', (e) => {
            if (!this.isEnabled || !this.isTouchActive) return;
            
            const touch = Array.from(e.changedTouches).find(t => t.identifier === this.activeTouchId);
            if (!touch) return;
            
            if (this.isPointInCanvas(touch.clientX, touch.clientY)) {
                this.lastPos = this.getRelativePositionFromTouch(touch);
                this.broadcastEvent('mouseup', this.lastPos.x, this.lastPos.y);
            }
            
            this.isTouchActive = false;
            this.activeTouchId = null;
            e.preventDefault();
        }, { capture: true, passive: false });

        // Handle touch cancel the same as touch end
        this.canvas.addEventListener('touchcancel', (e) => {
            if (!this.isEnabled || !this.isTouchActive) return;
            
            const touch = Array.from(e.changedTouches).find(t => t.identifier === this.activeTouchId);
            if (!touch) return;
            
            this.broadcastEvent('mouseup', this.lastPos.x, this.lastPos.y);
            this.isTouchActive = false;
            this.activeTouchId = null;
            e.preventDefault();
        }, { capture: true, passive: false });

        // Window blur
        window.addEventListener('blur', () => {
            if (this.isEnabled && (this.isMouseDown || this.isTouchActive)) {
                this.isMouseDown = false;
                this.isTouchActive = false;
                this.activeTouchId = null;
                this.broadcastEvent('mouseup', this.lastPos.x, this.lastPos.y);
            }
        });

        // Prevent default behaviors
        this.canvas.addEventListener('dragstart', e => e.preventDefault());
        this.canvas.addEventListener('drop', e => e.preventDefault());
        this.canvas.addEventListener('dragover', e => e.preventDefault());
        this.canvas.addEventListener('contextmenu', e => e.preventDefault());

        // Cleanup
        window.addEventListener('beforeunload', () => {
            this.mouseBroadcast.close();
        });
    }
}

// Client (Receiver) Class with updated position handling
class MouseSyncClient extends MouseSyncBase {
    initialize(canvas) {
        this.canvas = canvas;
        this.mouseBroadcast = new BroadcastChannel('mouse-sync-canvas');
        this.isReceivingEvent = false;
        this.isEnabled = false;
        
        window.addEventListener('mouseSyncClientToggle', (e) => {
            this.isEnabled = e.detail.enabled;
            console.log(`🎯 Client Mode ${this.isEnabled ? 'enabled' : 'disabled'}`);
        });
        
        this.initializeEvents();
        console.log('🎯 Mouse Sync Client initialized');
    }

    simulateMouseEvent(type, normalizedX, normalizedY) {
        if (!this.isEnabled) return;
        
        this.isReceivingEvent = true;
        this.setOriginalAspectRatio();
        const { scaleX, scaleY, offsetX, offsetY, rect } = this.calculateScalingFactors();
        
        // Convert normalized coordinates back to client coordinates
        const clientX = rect.left + offsetX + ((normalizedX * this.canvas.width) / scaleX);
        const clientY = rect.top + offsetY + ((normalizedY * this.canvas.height) / scaleY);

        const eventInit = {
            view: window,
            bubbles: true,
            cancelable: true,
            clientX,
            clientY,
            screenX: clientX,
            screenY: clientY,
            button: 0,
            buttons: type === 'mouseup' ? 0 : 1,
            detail: 1,
            pageX: clientX + window.pageXOffset,
            pageY: clientY + window.pageYOffset,
        };

        // Dispatch mouse event
        this.canvas.dispatchEvent(new MouseEvent(type, eventInit));

        // Dispatch pointer event
        const pointerEventType = type.replace('mouse', 'pointer');
        this.canvas.dispatchEvent(new PointerEvent(pointerEventType, {
            ...eventInit,
            pointerType: 'mouse',
            isPrimary: true,
            pressure: type === 'mouseup' ? 0 : 1.0,
            width: 1,
            height: 1,
            pointerId: 1
        }));

        // Dispatch touch event for mousedown
        if (type === 'mousedown') {
            const touch = new Touch({
                identifier: 0,
                target: this.canvas,
                clientX,
                clientY,
                screenX: clientX,
                screenY: clientY,
                pageX: clientX + window.pageXOffset,
                pageY: clientY + window.pageYOffset,
                radiusX: 1,
                radiusY: 1,
                rotationAngle: 0,
                force: 1
            });

            this.canvas.dispatchEvent(new TouchEvent('touchstart', {
                bubbles: true,
                cancelable: true,
                view: window,
                touches: [touch],
                targetTouches: [touch],
                changedTouches: [touch]
            }));
        }

        setTimeout(() => {
            this.isReceivingEvent = false;
        }, 0);
    }

    initializeEvents() {
        this.mouseBroadcast.onmessage = (event) => {
            if (!this.isEnabled || this.isReceivingEvent) return;
            
            const mouseEvent = event.data;
            if (Date.now() - mouseEvent.timestamp > 100) return;
            
            // Only process events from source
            if (!mouseEvent.source) return;
            
            this.simulateMouseEvent(mouseEvent.type, mouseEvent.x, mouseEvent.y);
        };

        window.addEventListener('beforeunload', () => {
            this.mouseBroadcast.close();
        });
    }
}

// Initialize the system
const initializeMouseSync = (canvasSelector) => {
    const toggleControls = new SyncToggleControls();
    const mouseSync = {
        source: new MouseSyncSource(canvasSelector),
        client: new MouseSyncClient(canvasSelector)
    };
    return mouseSync;
};