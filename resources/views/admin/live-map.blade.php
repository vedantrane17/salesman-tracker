<!-- resources/views/admin/live-map.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Live Location Map</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 600px;
        }
    </style>
</head>
<body>
    <h2>Live Location & Path of User</h2>
    <div id="map"></div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        const userId = {{ $userId }};
        const map = L.map('map').setView([0, 0], 15);
        const marker = L.marker([0, 0]).addTo(map);
        const pathCoordinates = [];
        const pathLine = L.polyline(pathCoordinates, { color: 'blue' }).addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        async function fetchLocation() {
            try {
                const response = await fetch(`/admin/latest-location/${userId}`);
                const data = await response.json();

                const { latitude, longitude } = data;

                if (latitude && longitude) {
                    const newLatLng = [latitude, longitude];
                    
                    // Add to path and update map
                    pathCoordinates.push(newLatLng);
                    pathLine.setLatLngs(pathCoordinates);
                    
                    marker.setLatLng(newLatLng);
                    map.setView(newLatLng, 16);
                }
            } catch (error) {
                console.error("Error fetching location:", error);
            }
        }

        fetchLocation();
        setInterval(fetchLocation, 5000);
    </script>
</body>
</html>
