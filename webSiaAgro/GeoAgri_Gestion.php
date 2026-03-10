<?php session_start(); ?>
<?php if (!isset($_SESSION['Aceso'])) { header("location: index.html"); } ?>
<?php include_once("header.php") ?>
<?php include_once("Sidebar.php") ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Polígonos</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="css_personalizado/estilo_Gis.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.8.0/proj4.js"></script>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <style>
        body {
            margin-top: 50px;
            font-family: 'Poppins', sans-serif;
            background-color: #f0f8ff;
            color: #333;
        }
        #map {
            height: 500px;
            width: 80%;
            margin: 20px auto;
            margin-left: 18%;
            border: 2px solid #007bff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        .map-title {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
            font-family: 'Poppins', sans-serif;
        }
        #map-legend {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            z-index: 1000;
        }
        #map-legend h4 { margin: 0 0 10px; font-size: 16px; color: #333; }
        #map-legend ul { list-style: none; padding: 0; margin: 0; }
        #map-legend li { display: flex; align-items: center; margin-bottom: 5px; }
        #map-legend li span {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border: 1px solid #ccc;
        }
        .poligono-label {
            background-color: rgba(255, 255, 255, 0.9);
            border: 1px solid #007bff;
            border-radius: 5px;
            padding: 5px;
            font-size: 12px;
            color: #333;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            font-family: Arial, sans-serif;
            white-space: nowrap;
            text-align: center;
        }
        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 30px;
            font-size: 36px;
            font-family: 'Georgia', serif;
            font-weight: 700;
            text-transform: uppercase;
        }
        .table-container {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f8ff;
            color: #333;
            padding: 20px;
            margin-left: 450px;
        }
        .action-icons .btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.3s, box-shadow 0.3s;
            color: #fff;
        }
        .btn-success:hover {
            background-color: #47a847;
            border-color: #47a847;
            box-shadow: 0 4px 8px rgba(71, 168, 71, 0.4);
        }
        .btn-info:hover {
            background-color: #0099c5;
            border-color: #0089b5;
            box-shadow: 0 4px 8px rgba(0, 153, 197, 0.4);
        }
        .table {
            width: 83%;
            margin-top: 20px;
            font-size: 15px;
            border-radius: 8px;
            overflow: hidden;
        }
        .table th, .table td {
            padding: 14px;
            text-align: center;
            vertical-align: middle;
        }
        .table th {
            background-color: #007bff;
            color: #fff;
            font-weight: 700;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }
        .table-hover tbody tr:hover {
            background-color: #dcdcdc;
        }
    </style>
</head>
<body>
<div class="map-container">
    <div id="map">
        <div id="map-legend">
            <h4>Leyenda</h4>
            <ul>
                <li><span style="background-color:rgb(33, 46, 221);"></span>Espacio Disponible</li>
                <li><span style="background-color: #55ff33;"></span> Espacios asignados a siembra</li>
                <li><span style="background-color: red;"></span> Espacios ocupados</li>
                <li><span style="background-color: brown;"></span> Espacios asignados a ganadería</li>
            </ul>
        </div>
    </div>
</div>
<script>
    var map = L.map('map', {
        center: [10.254106165236507, -67.56081876448688],
        zoom: 18,
        maxZoom: 22,
        minZoom: 16,
        scrollWheelZoom: false,
        maxBounds: [[10.245, -67.565], [10.260, -67.535]],
        maxBoundsViscosity: 1.0
    });

    L.tileLayer('https://api.maptiler.com/maps/hybrid/256/{z}/{x}/{y}.jpg?key=hkoMgOIljQEwWNNpAFWc', {
        maxZoom: 22,
        tileSize: 512,
        zoomOffset: -1,
        detectRetina: true
    }).addTo(map);

    function loadTerrainData() {
        fetch('data_poligono.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(terreno => {

                    var puntos = terreno.puntos.map(p => [p.latitud, p.longitud]);

                    // ============================
                    // 🎨 LÓGICA DE COLORES ADAPTADA
                    // ============================
                    let polygonColor = "blue"; // libre

                    const tieneEspacio   = terreno.espacio_id !== null;
                    const tieneCultivo   = terreno.cultivo_id !== null;
                    const tienePotreros  = terreno.potreros_count > 0;

                    if (tienePotreros) {
                        polygonColor = "#800000"; // vinotinto
                    }
                    else if (tieneEspacio && !tieneCultivo) {
                        polygonColor = "#55ff33"; // verde
                    }
                    else if (tieneEspacio && tieneCultivo) {
                        polygonColor = "red"; // rojo
                    }
                    // ============================

                    var polygon = L.polygon(puntos, {
                        color: polygonColor,
                        weight: 3,
                        opacity: 0.9,
                        fillColor: polygonColor,
                        fillOpacity: 0.35,
                        dashArray: '6, 4'
                    }).addTo(map);

                    polygon.on('mouseover', function () {
                        this.setStyle({
                            weight: 5,
                            color: '#FFD700',
                            fillOpacity: 0.5
                        });
                    });

                    polygon.on('mouseout', function () {
                        this.setStyle({
                            weight: 3,
                            color: polygonColor,
                            fillOpacity: 0.35
                        });
                    });

                    polygon.poligono_id = terreno.poligono_id;

                    map.fitBounds(polygon.getBounds());

                    let labelContent = `<strong>${terreno.nombre}</strong>`;

                    if (tieneEspacio) {
                        labelContent += `<br><strong>Estatus:</strong> ${terreno.espacio_estatus}`;
                        labelContent += `<br><strong>Espacio:</strong> Registrado`;
                    }

                    if (tieneCultivo) {
                        labelContent += `<br><strong>Cultivo:</strong> Sí`;
                    }

                    if (tienePotreros) {
                        labelContent += `<br><strong>Potreros:</strong> ${terreno.potreros_count}`;
                    }

                    polygon.bindPopup(labelContent, { closeButton: false, offset: L.point(0, -10) });

                    var labelIcon = L.divIcon({
                        className: 'poligono-label',
                        html: `<div style="font-size: 12px; padding: 5px; background-color: white; border: 1px solid black; border-radius: 5px;">
                                    ${labelContent}
                               </div>`,
                        iconSize: 'auto'
                    });

                    var centro = polygon.getBounds().getCenter();
                    L.marker(centro, { icon: labelIcon }).addTo(map);

                    polygon.on('click', async function() {
                        var coordinates = polygon.getLatLngs()[0];
                        var area = L.GeometryUtil.geodesicArea(coordinates) / 10000;
                        var bounds = polygon.getBounds().toBBoxString();
                        var geo = polygon.getBounds().getCenter();
                        var boundaryDescriptions = await getBoundaryDescriptions(coordinates, bounds);

                        var url = 'poligono.php?' +
                            'id=' + encodeURIComponent(polygon.poligono_id) +
                            '&coordinates=' + encodeURIComponent(JSON.stringify(coordinates)) +
                            '&area=' + encodeURIComponent(area) +
                            '&bounds=' + encodeURIComponent(bounds) +
                            '&geographicLocation=' + encodeURIComponent(JSON.stringify(geo)) +
                            '&descriptions=' + encodeURIComponent(JSON.stringify(boundaryDescriptions));

                        window.location.href = url;
                    });

                });
            })
            .catch(error => console.error('Error al cargar los datos del terreno:', error));
    }

    async function getBoundaryDescriptions(coordinates, bounds) {
        const simulatedDescriptions = {
            noreste: "al noreste con la Avenida Bolívar",
            sureste: "al sureste con el Río San Juan",
            noroeste: "al noroeste con la Calle Principal",
            suroeste: "al suroeste con el Parque Nacional",
            norte: "al norte con la Calle Miranda",
            sur: "al sur con la Avenida Libertador",
            este: "al este con la Autopista Regional",
            oeste: "al oeste con la Finca Los Robles"
        };

        async function getLocationInfo(latlng, key) {
            return {
                lat: latlng.lat,
                lng: latlng.lng,
                desc: simulatedDescriptions[key] || "Descripción no disponible"
            };
        }

        const b = bounds.split(',').map(Number);

        return {
            noreste: await getLocationInfo(coordinates[0], 'noreste'),
            sureste: await getLocationInfo(coordinates[1], 'sureste'),
            noroeste: await getLocationInfo(coordinates[2], 'noroeste'),
            suroeste: await getLocationInfo(coordinates[3], 'suroeste'),
            norte: await getLocationInfo({ lat: b[3], lng: (b[0] + b[2]) / 2 }, 'norte'),
            sur: await getLocationInfo({ lat: b[1], lng: (b[0] + b[2]) / 2 }, 'sur'),
            este: await getLocationInfo({ lat: (b[1] + b[3]) / 2, lng: b[2] }, 'este'),
            oeste: await getLocationInfo({ lat: (b[1] + b[3]) / 2, lng: b[0] }, 'oeste')
        };
    }

    loadTerrainData();
</script>


<div class="container table-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-center flex-grow-1">TERRENOS Y FICHAS TECNICAS</h2>
        <div class="action-icons">
            <a href="agregar_ficha.php" class="btn btn-success btn-sm me-2" title="Agregar">
                <i class="fas fa-plus"></i>
            </a>
            <a href="pdf/formato_pdf_poligono.php" class="btn btn-info btn-sm" title="Reporte">
                <i class="fas fa-file-alt"></i>
            </a>
        </div>
    </div>
    <table id="dataTable" class="table table-striped table-hover table-bordered align-middle">
        <thead class="table-primary">
            <tr>
                <th>Nombre</th>
                <th>Nº Ficha Técnica</th>
                <th>Fecha y hora</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Los datos se llenarán automáticamente con DataTables -->
        </tbody>
    </table>
</div>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/chart.js/chart.umd.js"></script>
<script src="assets/vendor/echarts/echarts.min.js"></script>
<script src="assets/vendor/quill/quill.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/js/main.js"></script>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "ajax": "ficha.php",
        "columns": [
            { "data": "nombre" },
            { "data": "ficha_tecnica_id" },
            { "data": "fecha_hora" },
            {
                "data": null,
                "render": function(data, type, row) {
                    return `
                      <button class='btn btn-outline-primary btn-sm' onclick="window.location.href='pdf/formato_pdf_ficha_tecnica.php?id=${row.id}'" title="Descargar">
                        <i class='fas fa-download'></i>
                      </button>
                      <button class='btn btn-outline-danger btn-sm' onclick="deletePoligono(${row.id})" title="Eliminar">
                        <i class='fas fa-trash-alt'></i>
                      </button>
                    `;
                },
                "orderable": false,
                "searchable": false
            }
        ],
        "pageLength": 5,
        "language": {
            "sEmptyTable": "No hay datos disponibles en la tabla",
            "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
            "sInfoEmpty": "Mostrando 0 a 0 de 0 entradas",
            "sInfoFiltered": "(filtrado de _MAX_ entradas en total)",
            "sLengthMenu": "Mostrar _MENU_ entradas",
            "sLoadingRecords": "Cargando...",
            "sProcessing": "Procesando...",
            "sSearch": "Buscar:",
            "sZeroRecords": "No se encontraron resultados",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": activar para ordenar la columna de manera descendente"
            }
        }
    });
});

function deletePoligono(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este polígono?')) {
        $.ajax({
            url: 'eliminar_poligono.php',
            type: 'POST',
            data: { poligono_id: id },
            success: function(response) {
                console.log('Respuesta del servidor:', response);
                if (response.success) {
                    alert(response.message);
                    $('#dataTable').DataTable().ajax.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', error);
                alert('Error al intentar eliminar el polígono.');
            }
        });
    }
}
</script>
</body>
</html>