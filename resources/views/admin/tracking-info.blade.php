@extends('layouts.admin')

@section('title', 'Salesman Tracking Info')

@section('content')
    <div class="bg-white rounded shadow p-6 space-y-6">
        <h2 class="text-2xl font-semibold mb-4">Tracking Details</h2>

        <!-- Summary Boxes -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6 text-sm">
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded shadow">
                <div class="text-xs">Start Time</div>
                <div class="text-lg font-bold" id="startTime">--</div>
            </div>
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow">
                <div class="text-xs">End Time</div>
                <div class="text-lg font-bold" id="endTime">--</div>
            </div>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow">
                <div class="text-xs">Total Duration</div>
                <div class="text-lg font-bold" id="duration">--</div>
            </div>
            <div class="bg-purple-100 border-l-4 border-purple-500 text-purple-700 p-4 rounded shadow">
                <div class="text-xs">Total Distance</div>
                <div class="text-lg font-bold"><span id="distance">--</span> km</div>
            </div>
            <div id="statusBox" class="bg-gray-100 border-l-4 text-gray-800 p-4 rounded shadow">
                <div class="text-xs">Status</div>
                <div class="text-lg font-bold" id="status">--</div>
            </div>
        </div>

        <!-- Map -->
        <div id="map" class="rounded shadow border border-gray-300" style="height: 600px; width: 100%;"></div>

        <!-- Legend -->
        <div class="mt-4 p-2 text-sm bg-gray-100 rounded border border-gray-300 inline-block shadow">
            <div><span class="inline-block w-3 h-3 rounded-full bg-blue-500 mr-2"></span> Path</div>
            <div><span class="inline-block w-3 h-3 rounded-full bg-green-600 mr-2"></span> Start</div>
            <div><span class="inline-block w-3 h-3 rounded-full bg-red-600 mr-2"></span> End</div>
        </div>
    </div>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        const sessionId = {{ $sessionId }};
        const map = L.map('map').setView([0, 0], 15);
        let polyline;
        let startMarker, endMarker;

        const greenIcon = L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.9.3/dist/images/marker-icon.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowUrl: 'https://unpkg.com/leaflet@1.9.3/dist/images/marker-shadow.png',
        });

        const redIcon = L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.9.3/dist/images/marker-icon-red.png', // You can use a custom red marker icon
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowUrl: 'https://unpkg.com/leaflet@1.9.3/dist/images/marker-shadow.png',
        });

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

            // Status Display
            const statusEl = document.getElementById('status');
            statusEl.textContent = data.status || '--';
            statusEl.className = "text-lg font-bold";
            if (data.status === 'active') {
                statusEl.classList.add('text-green-600');
            } else {
                statusEl.classList.add('text-red-500');
            }

            const coordinates = data.locations.map(loc => [loc.latitude, loc.longitude]);

            if (coordinates.length > 0) {
                map.setView(coordinates[coordinates.length - 1], 16);

                if (polyline) polyline.remove();
                polyline = L.polyline(coordinates, { color: 'blue' }).addTo(map);

                if (startMarker) startMarker.remove();
                if (endMarker) endMarker.remove();

                startMarker = L.marker(coordinates[0], { icon: greenIcon }).addTo(map).bindPopup('Start').openPopup();
                endMarker = L.marker(coordinates[coordinates.length - 1], { icon: redIcon }).addTo(map).bindPopup('End');
            }
        }

        loadSessionData();
        setInterval(loadSessionData, 10000); // Auto-refresh every 10 seconds
    </script>
@endsection
