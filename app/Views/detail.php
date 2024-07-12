<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Peta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

</head>

<body>

    <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3">
        <div class="container-fluid ">
            <a class="navbar-brand fw-bold" href="#">Detail Lokasi <?=' ' . $nama_peta;?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active bg-primary p-2 btn" aria-current="page" href="/">Kembali</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container rounded  mt-4">
        <div style="width: 100%;height: 600px" id="map"></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
    var map = L.map('map').setView([-3.629525, 114.719896], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var geojson = <?php echo $geoJson ?>;


    var greenIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',

        iconSize: [38, 95], // size of the icon
        shadowSize: [50, 64], // size of the shadow
        iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
        shadowAnchor: [4, 62], // the same for the shadow
        popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
    });

    L.marker([-3.629525, 114.719896], {
        icon: greenIcon
    }).addTo(map);

    // menambah data ke map
    var geojsonLayer = L.geoJSON(geojson, {
        style: function(feature) {
            return { // sesuaikan dengan preferensi Anda
                color: 'blue',
                weight: 1,
                opacity: 0.5
            };
        },
        onEachFeature: function(feature, layer) {
            layer.bindPopup('<h3>' + feature.properties.name + '</h3>');
        }
    }).addTo(map);

    // Menyesuaikan tampilan peta dengan bounds dari GeoJSON
    map.fitBounds(geojsonLayer.getBounds());
    </script>
</body>

</html>