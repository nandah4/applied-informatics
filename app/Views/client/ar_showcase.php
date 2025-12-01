<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AR Showcase - Applied Informatics Laboratory</title>

    <!-- AR.js with A-Frame -->
    <script src="https://aframe.io/releases/1.4.2/aframe.min.js"></script>
    <script src="https://raw.githack.com/AR-js-org/AR.js/master/aframe/build/aframe-ar.js"></script>

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- AR Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/ar/ar_showcase.css') ?>">

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>

<body>
    <!-- Header Navigation -->
    <header class="ar-header">
        <div class="ar-header-content">
            <a href="<?= base_url() ?>" class="ar-back-btn">
                <i data-feather="arrow-left"></i>
                <span>Kembali</span>
            </a>
            <h1 class="ar-header-title">AR Showcase</h1>
            <button class="ar-info-btn" id="arInfoBtn">
                <i data-feather="info"></i>
            </button>
        </div>
    </header>

    <!-- AR Info Panel -->
    <div class="ar-info-panel" id="arInfoPanel">
        <div class="ar-info-content">
            <div class="ar-info-header">
                <h3>Cara Menggunakan AR</h3>
                <button class="ar-info-close" id="arInfoClose">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="ar-info-body">
                <ol class="ar-instructions">
                    <li>
                        <strong>Izinkan Akses Kamera</strong>
                        <p>Aplikasi memerlukan akses kamera untuk mendeteksi marker AR</p>
                    </li>
                    <li>
                        <strong>Download Marker AR</strong>
                        <p>Klik tombol "Download Marker" untuk mendapatkan marker Hiro</p>
                        <a href="https://raw.githubusercontent.com/AR-js-org/AR.js/master/data/images/hiro.png"
                            download="hiro-marker.png"
                            class="btn-download-marker">
                            <i data-feather="download"></i>
                            Download Marker
                        </a>
                    </li>
                    <li>
                        <strong>Print atau Tampilkan Marker</strong>
                        <p>Print marker atau tampilkan di layar lain</p>
                    </li>
                    <li>
                        <strong>Arahkan Kamera ke Marker</strong>
                        <p>Objek 3D akan muncul di atas marker yang terdeteksi</p>
                    </li>
                </ol>

                <div class="ar-tips">
                    <h4>Tips:</h4>
                    <ul>
                        <li>Pastikan pencahayaan cukup</li>
                        <li>Jaga jarak kamera 20-50cm dari marker</li>
                        <li>Marker harus terlihat jelas dan tidak terpotong</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- AR Scene -->
    <a-scene
        embedded
        arjs="sourceType: webcam; debugUIEnabled: false; detectionMode: mono_and_matrix; matrixCodeType: 3x3;"
        vr-mode-ui="enabled: false"
        id="arScene">

        <a-entity camera></a-entity>

        <a-marker preset="hiro">

            <a-entity position="0 0.5 0" animation="property: rotation; to: 0 360 0; loop: true; dur: 10000; easing: linear">

                <a-box
                    position="-0.6 -0.25 0"
                    width="0.25" depth="0.25" height="0.5"
                    color="#00b5b9" metalness="0.3">
                </a-box>

                <a-box
                    position="-0.32 -0.12 0"
                    width="0.25" depth="0.25" height="0.75"
                    color="#00b5b9" metalness="0.3">
                </a-box>

                <a-box
                    position="-0.04 0 0"
                    width="0.25" depth="0.25" height="1"
                    color="#00b5b9" metalness="0.3">
                </a-box>

                <a-box
                    position="0.35 0.1 0"
                    width="0.3" depth="0.3" height="1.2"
                    color="#003466" metalness="0.4">
                </a-box>

                <a-sphere
                    position="0.35 0.95 0"
                    radius="0.2"
                    color="#fd7e14" metalness="0.4"
                    animation="property: position; to: 0.35 1.05 0; dir: alternate; dur: 1000; loop: true; easing: easeInOutQuad">
                </a-sphere>

            </a-entity>
            <a-text
                value="LABORATORY OF"
                align="center"
                color="#fd7e14"
                width="2"
                position="0 0 1"
                rotation="-90 0 0"
                font="roboto">
            </a-text>

            <a-text
                value="APPLIED\nINFORMATICS"
                align="center"
                color="#003466"
                width="3.5"
                position="0 0 1.3"
                rotation="-90 0 0"
                font="roboto"
                line-height="60">
            </a-text>

            <a-cylinder
                position="0 0 0"
                radius="1.2"
                height="0.05"
                color="#ffffff"
                opacity="0.8">
            </a-cylinder>

            <a-torus
                position="0 0 0"
                rotation="90 0 0"
                radius="1.1"
                radius-tubular="0.02"
                color="#00b5b9"
                opacity="0.5">
            </a-torus>

        </a-marker>
    </a-scene>

    <!-- Loading Overlay -->
    <div class="ar-loading" id="arLoading">
        <div class="ar-loading-content">
            <div class="ar-spinner"></div>
            <p>Memuat AR Scene...</p>
            <small>Mohon izinkan akses kamera</small>
        </div>
    </div>

    <!-- AR Controls -->
    <div class="ar-controls">
        <div class="ar-status" id="arStatus">
            <div class="status-indicator"></div>
            <span id="statusText">Mencari marker...</span>
        </div>
    </div>

    <script>
        // Initialize Feather Icons
        feather.replace();

        // DOM Elements
        const arInfoBtn = document.getElementById('arInfoBtn');
        const arInfoPanel = document.getElementById('arInfoPanel');
        const arInfoClose = document.getElementById('arInfoClose');
        const arLoading = document.getElementById('arLoading');
        const arScene = document.getElementById('arScene');
        const arStatus = document.getElementById('arStatus');
        const statusText = document.getElementById('statusText');

        // Info Panel Toggle
        arInfoBtn.addEventListener('click', () => {
            arInfoPanel.classList.add('show');
            document.body.style.overflow = 'hidden';
        });

        arInfoClose.addEventListener('click', () => {
            arInfoPanel.classList.remove('show');
            document.body.style.overflow = '';
        });

        arInfoPanel.addEventListener('click', (e) => {
            if (e.target === arInfoPanel) {
                arInfoPanel.classList.remove('show');
                document.body.style.overflow = '';
            }
        });

        // AR Scene Loading
        arScene.addEventListener('loaded', () => {
            console.log('AR Scene loaded');
            setTimeout(() => {
                arLoading.style.opacity = '0';
                setTimeout(() => {
                    arLoading.style.display = 'none';
                }, 300);
            }, 1000);
        });

        // Marker Detection
        const marker = document.querySelector('a-marker');
        let markerFound = false;

        marker.addEventListener('markerFound', () => {
            markerFound = true;
            arStatus.classList.add('marker-found');
            statusText.textContent = 'Marker terdeteksi!';
            console.log('Marker found');
        });

        marker.addEventListener('markerLost', () => {
            markerFound = false;
            arStatus.classList.remove('marker-found');
            statusText.textContent = 'Mencari marker...';
            console.log('Marker lost');
        });

        // Auto show info on first visit
        setTimeout(() => {
            const hasVisited = localStorage.getItem('ar_visited');
            if (!hasVisited) {
                arInfoPanel.classList.add('show');
                document.body.style.overflow = 'hidden';
                localStorage.setItem('ar_visited', 'true');
            }
        }, 1500);

        // Update feather icons after download button
        setTimeout(() => {
            feather.replace();
        }, 100);
    </script>
</body>

</html>