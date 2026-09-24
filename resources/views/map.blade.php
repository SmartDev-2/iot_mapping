<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IoT GPS Mapping</title>
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIINfQBO8GztF3HYn5I5rYbclnHzFzmtvF4="
        crossorigin=""
    >
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            color: #1f2937;
            background: #f3f4f6;
        }

        .page {
            width: min(960px, calc(100% - 32px));
            margin: 0 auto;
            padding: 32px 0;
        }

        h1 {
            margin: 0 0 24px;
            text-align: center;
            font-size: 28px;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        .info {
            margin-bottom: 16px;
            padding: 16px;
            border: 1px solid #d1d5db;
            background: #ffffff;
        }

        .row {
            display: flex;
            gap: 12px;
            margin: 6px 0;
            font-size: 16px;
        }

        .label {
            width: 90px;
            font-weight: 700;
        }

        #message {
            margin-top: 12px;
            color: #b45309;
        }

        #map {
            width: 100%;
            height: 520px;
            border: 1px solid #d1d5db;
            background: #e5e7eb;
        }

        @media (max-width: 640px) {
            .page {
                width: min(100% - 24px, 960px);
                padding: 20px 0;
            }

            h1 {
                font-size: 22px;
            }

            #map {
                height: 420px;
            }
        }
    </style>
</head>
<body>
    <main class="page">
        <h1>IoT GPS Mapping</h1>

        <section class="info" aria-live="polite">
            <div class="row">
                <span class="label">Latitude</span>
                <span id="latitude">-</span>
            </div>
            <div class="row">
                <span class="label">Longitude</span>
                <span id="longitude">-</span>
            </div>
            <div id="message">Data GPS belum tersedia</div>
        </section>

        <div id="map"></div>
    </main>

    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""
    ></script>
    <script>
        const defaultPosition = [-8.1723000, 113.7001000];
        const map = L.map('map').setView(defaultPosition, 13);
        let marker = null;
        let lastPosition = null;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        function setMessage(message) {
            document.getElementById('message').textContent = message;
        }

        function updateInfo(latitude, longitude) {
            document.getElementById('latitude').textContent = Number(latitude).toFixed(7);
            document.getElementById('longitude').textContent = Number(longitude).toFixed(7);
        }

        function updateMap(latitude, longitude) {
            const position = [Number(latitude), Number(longitude)];

            if (!marker) {
                marker = L.marker(position).addTo(map);
            } else {
                marker.setLatLng(position);
            }

            if (!lastPosition || lastPosition[0] !== position[0] || lastPosition[1] !== position[1]) {
                map.setView(position, 16);
                lastPosition = position;
            }
        }

        async function loadLatestLocation() {
            try {
                const response = await fetch('/api/location/latest', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (response.status === 404) {
                    setMessage('Data GPS belum tersedia');
                    return;
                }

                if (!response.ok) {
                    throw new Error('API error');
                }

                const result = await response.json();

                if (!result.success || !result.data) {
                    setMessage('Data GPS belum tersedia');
                    return;
                }

                updateInfo(result.data.latitude, result.data.longitude);
                updateMap(result.data.latitude, result.data.longitude);
                setMessage('');
            } catch (error) {
                setMessage('Gagal mengambil data GPS');
            }
        }

        loadLatestLocation();
        setInterval(loadLatestLocation, 5000);
    </script>
</body>
</html>
