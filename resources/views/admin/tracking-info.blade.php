<!DOCTYPE html>
<html>
<head>
    <title>Salesman Tracking Info</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 600px; width: 100%; }
    </style>
</head>
<body>
    <h2>Tracking Details</h2>
    <div id="info">
        <p><strong>Start Time:</strong> <span id="startTime"></span></p>
        <p><strong>End Time:</strong> <span id="endTime"></span></p>
        <p><strong>Total Duration:</strong> <span id="duration"></span></p>
        <p><strong>Total Distance:</strong> <span id="distance"></span> km</p>
    </div>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        const userId = {{ $userId }};
        const map = L.map('map').setView([0, 0], 15);
        let polyline;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        async function loadSessionData() {
            const res = await fetch(`/admin/api/session-data/${userId}`);
            const data = await res.json();

            document.getElementById('startTime').textContent = data.start_time;
            document.getElementById('endTime').textContent = data.end_time ?? 'Still Tracking';
            document.getElementById('duration').textContent = data.duration;
            document.getElementById('distance').textContent = data.distance_km;


            const coordinates = data.locations.map(loc => [loc.latitude, loc.longitude]);

            if (coordinates.length > 0) {
                map.setView(coordinates[coordinates.length - 1], 16);
                if (polyline) polyline.remove();
                polyline = L.polyline(coordinates, { color: 'blue' }).addTo(map);

                L.marker(coordinates[0]).addTo(map).bindPopup('Start').openPopup();
                L.marker(coordinates[coordinates.length - 1]).addTo(map).bindPopup('End');
            }
        }

        loadSessionData();
    </script>
</body>
</html>
