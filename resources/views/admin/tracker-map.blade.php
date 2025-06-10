@extends('layouts.admin')

@section('title', 'Live Tracker')

@section('content')
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-2xl font-semibold mb-4">Live Location</h2>

        <div id="map" class="rounded shadow border border-gray-300" style="height: 600px; width: 100%;"></div>
    </div>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Leaflet + jQuery -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        const userId = {{ $userId }};

        const map = L.map('map').setView([0, 0], 15);
        let pathPolyline = L.polyline([], { color: 'blue' }).addTo(map);
        let riderMarker = null;
        let animationIndex = 0;
        let coordinates = [];

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        function animateMarker() {
            if (animationIndex >= coordinates.length) return;

            const [lat, lng] = coordinates[animationIndex];
            if (riderMarker) {
                riderMarker.setLatLng([lat, lng]);
            } else {
                riderMarker = L.marker([lat, lng]).addTo(map);
            }

            animationIndex++;
            setTimeout(animateMarker, 1000); // Animate 1 sec per point
        }

        async function fetchPath() {
            try {
                const res = await fetch(`/admin/live-path/${userId}`);
                const data = await res.json();

                if (data.locations && data.locations.length) {
                    coordinates = data.locations.map(loc => [loc.latitude, loc.longitude]);
                    pathPolyline.setLatLngs(coordinates);

                    const lastPoint = coordinates[coordinates.length - 1];
                    map.setView(lastPoint, 16);
                    
                    animationIndex = 0;
                    animateMarker();
                }
            } catch (error) {
                console.error('Error loading path:', error);
            }
        }

        fetchPath();
        setInterval(fetchPath, 10000); // Refresh every 10 seconds
    </script>
@endsection
