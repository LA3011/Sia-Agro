var map = L.map('map').setView([10.2451, -67.5911], 10);

L.tileLayer('https://api.maptiler.com/maps/hybrid/256/{z}/{x}/{y}.jpg?key=hkoMgOIljQEwWNNpAFWc', {
    maxZoom: 22,
    tileSize: 512,
    zoomOffset: -1,
    detectRetina: true
}).addTo(map);

var drawnItems = new L.FeatureGroup();
map.addLayer(drawnItems);

var drawControl = new L.Control.Draw({
    draw: {
        polygon: true,
        polyline: true,
        circle: false,
        rectangle: false,
        circlemarker: false,
        marker: false
    },
    edit: {
        featureGroup: drawnItems,
        remove: true
    }
});
map.addControl(drawControl);

function updateLegend() {
    var polygons = drawnItems.getLayers().filter(layer => layer instanceof L.Polygon).length;
    var lines = drawnItems.getLayers().filter(layer => layer instanceof L.Polyline).length;
    var points = drawnItems.getLayers().filter(layer => layer instanceof L.CircleMarker).length;
    document.getElementById('legend-polygon').innerText = polygons;
    document.getElementById('legend-line').innerText = lines;
    document.getElementById('legend-point').innerText = points;
}

function saveDrawnItems() {
    var data = drawnItems.toGeoJSON();
    localStorage.setItem('drawnItems', JSON.stringify(data));
}

function loadDrawnItems() {
    var data = localStorage.getItem('drawnItems');
    if (data) {
        var geoJsonData = JSON.parse(data);
        L.geoJSON(geoJsonData, {
            onEachFeature: function (feature, layer) {
                drawnItems.addLayer(layer);
            }
        }).addTo(map); // Asegurarse de agregarlo al mapa
        updateLegend();
    }
}

loadDrawnItems();

map.on(L.Draw.Event.CREATED, function (event) {
    var layer = event.layer;
    drawnItems.addLayer(layer);
    saveDrawnItems();
    updateLegend();

    // Obtener coordenadas, linderos, área y ubicación geográfica
    var coordinates = layer.getLatLngs()[0];
    var bounds = layer.getBounds().toBBoxString();
    var area = L.GeometryUtil.geodesicArea(coordinates);
    var geographicLocation = layer.getBounds().getCenter();

    // Enviar datos al servidor
    fetch('/save-polygon', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            coordinates,
            bounds,
            area,
            geographicLocation
        })
    })
    .then(response => {
        if (response.ok) {
            // Redirigir a poligono.html
            window.location.href = 'poligono.html';
        } else {
            // Manejar error de envío de datos
            console.error('Error al enviar los datos del polígono');
        }
    })
    .catch(error => {
        console.error('Error al enviar los datos del polígono:', error);
    });
});

map.on(L.Draw.Event.DELETED, function (event) {
    saveDrawnItems();
    updateLegend();
});

map.on(L.Draw.Event.EDITED, function (event) {
    saveDrawnItems();
    updateLegend();
});

document.getElementById('btnPolygon').addEventListener('click', function () {
    map.removeControl(drawControl);
    drawControl = new L.Control.Draw({
        draw: {
            polygon: true,
            polyline: false,
            circle: false,
            rectangle: false,
            circlemarker: false,
            marker: false
        },
        edit: {
            featureGroup: drawnItems,
            remove: true
        }
    });
    map.addControl(drawControl);
});

document.getElementById('btnLine').addEventListener('click', function () {
    map.removeControl(drawControl);
    drawControl = new L.Control.Draw({
        draw: {
            polygon: false,
            polyline: true,
            circle: false,
            rectangle: false,
            circlemarker: false,
            marker: false
        },
        edit: {
            featureGroup: drawnItems,
            remove: true
        }
    });
    map.addControl(drawControl);
});

document.getElementById('btnPoint').addEventListener('click', function () {
    map.removeControl(drawControl);
    drawControl = new L.Control.Draw({
        draw: {
            polygon: false,
            polyline: false,
            circle: false,
            rectangle: false,
            circlemarker: true,
            marker: false
        },
        edit: {
            featureGroup: drawnItems,
            remove: true
        }
    });
    map.addControl(drawControl);
});

document.getElementById('btnSelect').addEventListener('click', function () {
    map.removeControl(drawControl);
    drawControl = new L.Control.Draw({
        draw: false,
        edit: {
            featureGroup: drawnItems,
            remove: true
        }
    });
    map.addControl(drawControl);
});

document.getElementById('btnDelete').addEventListener('click', function () {
    drawnItems.clearLayers();
    saveDrawnItems();
    updateLegend();
});