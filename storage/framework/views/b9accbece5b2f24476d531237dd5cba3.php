<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GPS Mapping</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #0b0f14;
            --panel: #121821;
            --panel-2: #171f2a;
            --border: #232c39;
            --text: #e8edf5;
            --muted: #8b98ab;
            --accent: #37e08a;
            --accent-2: #4fa3ff;
            --danger: #ff5c72;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--bg);
            font-family: "Inter", system-ui, sans-serif;
            color: var(--text);
            padding: 24px;
        }

        .wrap {
            max-width: 1100px;
            margin: 0 auto;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        header h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pin {
            color: var(--accent-2);
        }

        .status-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--panel);
            border: 1px solid var(--border);
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 13px;
            color: var(--muted);
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 0 0 0 rgba(55, 224, 138, 0.6);
            animation: pulse 1.8s infinite;
        }

        .dot.offline {
            background: var(--danger);
            animation: none;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(55, 224, 138, 0.55); }
            70% { box-shadow: 0 0 0 8px rgba(55, 224, 138, 0); }
            100% { box-shadow: 0 0 0 0 rgba(55, 224, 138, 0); }
        }

        .layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 18px;
        }

        .card,
        .info-card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 8px;
        }

        .card {
            overflow: hidden;
        }

        #map {
            height: 560px;
            width: 100%;
            filter: saturate(0.9) contrast(1.02);
        }

        .side {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .info-card {
            padding: 18px;
        }

        .info-card h3 {
            margin: 0 0 14px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            font-weight: 600;
        }

        .stat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 11px 0;
            border-bottom: 1px solid var(--border);
        }

        .stat:last-child {
            border-bottom: none;
        }

        .label {
            font-size: 13px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .value {
            font-family: "JetBrains Mono", monospace;
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            text-align: right;
            overflow-wrap: anywhere;
        }

        .accent-value {
            color: var(--accent-2);
        }

        .footer-note {
            font-size: 12px;
            color: var(--muted);
            text-align: center;
            padding-top: 4px;
        }

        .clear-button {
            margin-top: 12px;
            width: 100%;
            min-height: 40px;
            padding: 10px;
            background: var(--panel-2);
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 8px;
            font-family: inherit;
            font-size: 13px;
            cursor: pointer;
        }

        .clear-button:hover {
            border-color: var(--accent-2);
        }

        .leaflet-control-attribution {
            background: rgba(10, 14, 20, 0.75) !important;
            color: var(--muted) !important;
        }

        .leaflet-control-attribution a {
            color: var(--accent-2) !important;
        }

        .leaflet-control-zoom a {
            background: var(--panel-2) !important;
            color: var(--text) !important;
            border-color: var(--border) !important;
        }

        .pulse-marker {
            width: 20px;
            height: 20px;
            position: relative;
        }

        .pulse-marker .core {
            width: 14px;
            height: 14px;
            background: var(--accent-2);
            border: 2px solid #ffffff;
            border-radius: 50%;
            position: absolute;
            top: 3px;
            left: 3px;
            box-shadow: 0 0 8px rgba(79, 163, 255, 0.8);
        }

        .pulse-marker .ring {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: rgba(79, 163, 255, 0.4);
            position: absolute;
            top: 0;
            left: 0;
            animation: ring 1.8s infinite;
        }

        @keyframes ring {
            0% { transform: scale(0.4); opacity: 1; }
            100% { transform: scale(2.2); opacity: 0; }
        }

        @media (max-width: 800px) {
            body {
                padding: 16px;
            }

            .layout {
                grid-template-columns: 1fr;
            }

            #map {
                height: 440px;
            }
        }
    </style>
</head>

<body>
<div class="wrap">
    <header>
        <h2><span class="pin">GPS</span> Pemetaan GPS</h2>
        <div class="status-pill">
            <span class="dot offline" id="status-dot"></span>
            <span id="status-text">Menghubungkan...</span>
        </div>
    </header>

    <div class="layout">
        <div class="card">
            <div id="map"></div>
        </div>

        <div class="side">
            <div class="info-card">
                <h3>Informasi Perangkat</h3>
                <div class="stat">
                    <span class="label">Device ID</span>
                    <span class="value" id="device_id">-</span>
                </div>
                <div class="stat">
                    <span class="label">Latitude</span>
                    <span class="value accent-value" id="latitude">-</span>
                </div>
                <div class="stat">
                    <span class="label">Longitude</span>
                    <span class="value accent-value" id="longitude">-</span>
                </div>
                <div class="stat">
                    <span class="label">Update Terakhir</span>
                    <span class="value" id="timestamp">-</span>
                </div>
            </div>

            <div class="info-card">
                <h3>Jejak Perjalanan</h3>
                <div class="stat">
                    <span class="label">Titik Terekam</span>
                    <span class="value" id="track-count">0</span>
                </div>
                <button class="clear-button" id="clear-track" type="button">Hapus Jejak</button>
            </div>

            <p class="footer-note">Diperbarui otomatis setiap 5 detik</p>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const defaultPosition = [-8.1723000, 113.7001000];
    const map = L.map('map', { zoomControl: true }).setView(defaultPosition, 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    const pulseIcon = L.divIcon({
        className: '',
        html: '<div class="pulse-marker"><div class="ring"></div><div class="core"></div></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    let marker = null;
    let lastPosition = null;
    let trackPoints = [];
    const polyline = L.polyline([], {
        color: '#4fa3ff',
        weight: 3,
        opacity: 0.7,
        dashArray: '1, 8',
        lineCap: 'round'
    }).addTo(map);

    const statusDot = document.getElementById('status-dot');
    const statusText = document.getElementById('status-text');
    const deviceId = document.getElementById('device_id');
    const latitudeText = document.getElementById('latitude');
    const longitudeText = document.getElementById('longitude');
    const timestampText = document.getElementById('timestamp');
    const trackCount = document.getElementById('track-count');

    function setStatus(text, isOnline) {
        statusDot.classList.toggle('offline', !isOnline);
        statusText.textContent = text;
    }

    function updatePanel(location) {
        deviceId.textContent = location.device_id || 'GPS001';
        latitudeText.textContent = Number(location.latitude).toFixed(7);
        longitudeText.textContent = Number(location.longitude).toFixed(7);
        timestampText.textContent = location.timestamp || '-';
    }

    function updateMap(location) {
        const position = [
            Number(location.latitude),
            Number(location.longitude)
        ];

        if (!marker) {
            marker = L.marker(position, { icon: pulseIcon }).addTo(map);
        } else {
            marker.setLatLng(position);
        }

        if (!lastPosition || lastPosition[0] !== position[0] || lastPosition[1] !== position[1]) {
            trackPoints.push(position);
            polyline.setLatLngs(trackPoints);
            trackCount.textContent = trackPoints.length;
            map.setView(position, 16);
            lastPosition = position;
        }
    }

    async function loadLocation() {
        try {
            const response = await fetch('/api/location/latest', {
                headers: {
                    Accept: 'application/json'
                }
            });

            if (response.status === 404) {
                setStatus('Data GPS belum tersedia', false);
                return;
            }

            if (!response.ok) {
                throw new Error('Gagal mengambil data GPS');
            }

            const result = await response.json();

            if (!result.success || !result.data) {
                setStatus('Data GPS belum tersedia', false);
                return;
            }

            updatePanel(result.data);
            updateMap(result.data);
            setStatus('Live', true);
        } catch (error) {
            setStatus('Gagal mengambil data GPS', false);
            console.error(error);
        }
    }

    document.getElementById('clear-track').addEventListener('click', () => {
        trackPoints = [];
        lastPosition = marker ? [
            marker.getLatLng().lat,
            marker.getLatLng().lng
        ] : null;
        polyline.setLatLngs([]);
        trackCount.textContent = 0;
    });

    loadLocation();
    setInterval(loadLocation, 5000);
</script>
</body>
</html>
<?php /**PATH C:\project\iot_mapping\resources\views/map.blade.php ENDPATH**/ ?>