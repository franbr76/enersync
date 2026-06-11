<!DOCTYPE html>
<html>

<head>

<link rel="stylesheet"
href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<link rel="stylesheet"
href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css"/>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

<style>
#map{
    height:100vh;
}
</style>

</head>

<body>

<div id="map"></div>

<script>

var map = L.map('map').setView([-25.429, -49.271], 7);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
}).addTo(map);

L.Routing.control({
    waypoints: [
        L.latLng(-25.429, -49.271),
        L.latLng(-23.550, -46.633)
    ],
    routeWhileDragging: true
}).addTo(map);

</script>

</body>
</html>