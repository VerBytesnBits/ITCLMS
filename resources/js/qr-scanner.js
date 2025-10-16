import { Html5Qrcode } from "html5-qrcode";

export default function qrScanner() {
    return {
        html5QrCode: null,
        placeholderHTML: null,

        async init() {
            const reader = document.getElementById('reader');
            const cameraSelect = document.getElementById('cameraSelect');
            this.placeholderHTML = reader.innerHTML;
            this.html5QrCode = new Html5Qrcode("reader");

            await this.loadCameras(cameraSelect);
        },

        async loadCameras(cameraSelect) {
            try {
                const cameras = await Html5Qrcode.getCameras();
                cameraSelect.innerHTML = "";
                cameras.forEach(cam => {
                    const option = document.createElement('option');
                    option.value = cam.id;
                    option.text = cam.label || `Camera ${cameraSelect.length + 1}`;
                    cameraSelect.appendChild(option);
                });
            } catch (error) {
                console.error("Camera access error:", error);
                alert("Unable to access camera. Please allow permissions and refresh.");
            }
        },

        async startScanner() {
            const reader = document.getElementById('reader');
            const cameraSelect = document.getElementById('cameraSelect');
            const cameraId = cameraSelect.value;

            if (!cameraId) {
                alert("Please select a camera first.");
                return;
            }
                
            // Get container dimensions (square)
            const size = reader.offsetWidth; // width = height because of aspect-square

            try {
                await this.html5QrCode.start(
                    cameraId,
                    {
                        fps: 10,
                        qrbox: { width: size, height: size }, // full area scanning
                        aspectRatio: 1.0, // keep square
                    },
                    (decodedText) => {
                        console.log("✅ QR Scanned:", decodedText);
                        if (window.Livewire) Livewire.dispatch('qrScanned', { code: decodedText });
                        this.stopScanner();
                    },
                    (error) => { /* ignore scan errors */ }
                );
            } catch (error) {
                console.error("Error starting scanner:", error);
            }
        },

        async stopScanner() {
            try {
                await this.html5QrCode.stop();
                document.getElementById('reader').innerHTML = this.placeholderHTML;
            } catch (err) {
                console.warn("Scanner not running:", err);
            }
        },
    };
}
