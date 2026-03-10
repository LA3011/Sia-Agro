<script>
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
                    addActionButtons(layer);
                }
            }).addTo(map);
            updateLegend();
        }
    }

    function addActionButtons(layer) {
        var container = L.DomUtil.create('div', 'action-buttons');

        container.style.position = 'absolute';
        container.style.zIndex = '1000';

        var viewButton = container.querySelector('.view-button');
        var editButton = container.querySelector('.edit-button');

        map.getContainer().appendChild(container);
        layer._actionButtons = container;

        layer.on('mouseover', function (e) {
            var point = map.latLngToContainerPoint(e.latlng);
            container.style.top = (point.y + 10) + 'px';
            container.style.left = (point.x + 10) + 'px';
            container.style.display = 'block';
        });

        layer.on('mouseout', function () {
            container.style.display = 'none';
        });

        layer.on('click', async function () {
            var coordinates = layer.getLatLngs()[0];
            var area = L.GeometryUtil.geodesicArea(coordinates) / 10000;
            var bounds = layer.getBounds().toBBoxString();
            var geographicLocation = layer.getBounds().getCenter();

            var boundaryDescriptions = await getBoundaryDescriptions(coordinates, bounds);

            var url = 'poligono.php?' +
                'coordinates=' + JSON.stringify(coordinates) +
                '&area=' + area +
                '&bounds=' + bounds +
                '&geographicLocation=' + JSON.stringify(geographicLocation) +
                '&descriptions=' + JSON.stringify(boundaryDescriptions);

            window.location.href = url;
        });
    }

    async function getBoundaryDescriptions(coordinates, bounds) {
        const boundsArray = bounds.split(',').map(Number);
        const descriptions = {
            noreste: await getLocationName(coordinates[0]),
            sureste: await getLocationName(coordinates[1]),
            noroeste: await getLocationName(coordinates[2]),
            suroeste: await getLocationName(coordinates[3]),
            norte: await getLocationName({lat: boundsArray[3], lng: (boundsArray[0] + boundsArray[2]) / 2}),
            sur: await getLocationName({lat: boundsArray[1], lng: (boundsArray[0] + boundsArray[2]) / 2}),
            este: await getLocationName({lat: (boundsArray[1] + boundsArray[3]) / 2, lng: boundsArray[2]}),
            oeste: await getLocationName({lat: (boundsArray[1] + boundsArray[3]) / 2, lng: boundsArray[0]})
        };
        return descriptions;
    }

    async function getLocationName(latlng) {
        const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latlng.lat}&lon=${latlng.lng}`;
        return fetch(url)
            .then(response => response.json())
            .then(data => data.display_name || 'Ubicación desconocida')
            .catch(error => 'Ubicación desconocida');
    }

    loadDrawnItems();

    map.on(L.Draw.Event.CREATED, function (event) {
        var layer = event.layer;
        drawnItems.addLayer(layer);
        saveDrawnItems();
        updateLegend();
        addActionButtons(layer);
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
</script>
