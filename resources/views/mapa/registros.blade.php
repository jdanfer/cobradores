<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mapa de Ubicaciones</title>

    {{-- CSS Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
        #map {
            height: 600px;
        }
    </style>
</head>

<body>

    <div class="container mt-3">
        <h3>Ubicaciones encontradas</h3>
        <div id="map"></div>
    </div>

    {{-- JS Leaflet --}}
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        // Inicializar mapa
        var map = L.map('map').setView([-34.52476, -56.27932], 12); // Uruguay (Montevideo como centro)

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        // Direcciones que vienen desde Laravel
        var direcciones = @json($direcciones);

        // Geocodificación simple (Nominatim)
        direcciones.forEach(function(direccion) {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(direccion)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        let lat = data[0].lat;
                        let lon = data[0].lon;

                        // Agregar marcador
                        L.marker([lat, lon])
                            .addTo(map)
                            .bindPopup(direccion);
                    }

                });
        });
    </script>

</body>

</html>
