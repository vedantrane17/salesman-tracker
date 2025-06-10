@extends('layouts.admin')

@section('title', 'Salesman Tracking Info')

@section('content')
    <div class="bg-white rounded shadow p-6 space-y-6">
        <h2 class="text-2xl font-semibold mb-4">Tracking Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 text-sm">
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded shadow">
                <div class="text-sm">Start Time</div>
                <div class="text-lg font-bold" id="startTime">--</div>
            </div>
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow">
                <div class="text-sm">End Time</div>
                <div class="text-lg font-bold" id="endTime">--</div>
            </div>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow">
                <div class="text-sm">Total Duration</div>
                <div class="text-lg font-bold" id="duration">--</div>
            </div>
            <div class="bg-purple-100 border-l-4 border-purple-500 text-purple-700 p-4 rounded shadow">
                <div class="text-sm">Total Distance</div>
                <div class="text-lg font-bold"><span id="distance">--</span> km</div>
            </div>
        </div>

        <div id="map" class="rounded shadow border border-gray-300" style="height: 600px; width: 100%;"></div>
    </div>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        const sessionId = {{ $sessionId }};
        const map = L.map('map').setView([0, 0], 15);
        let polyline;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        async function loadSessionData() {
            const res = await fetch(`/admin/api/session-data/${sessionId}`);
            const data = await res.json();

            document.getElementById('startTime').textContent = data.start_time || '--';
            document.getElementById('endTime').textContent = data.end_time ?? 'Still Tracking';
            document.getElementById('duration').textContent = data.duration || '--';
            document.getElementById('distance').textContent = data.distance_km || '--';

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
@endsection
