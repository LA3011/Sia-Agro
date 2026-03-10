<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Polígono (Coordenadas UTM)</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.9.0/proj4.min.js"></script>
    <title>Leaflet Draw Example</title>
       <style>
       body {
            font-family: sans-serif;
            margin: 400px;
        }

        .form-section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-section h2 {
            margin-top: 0;
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
        }

        .form-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .form-fields {
            width: 60%;
        }

        #map {
            width: 35%;
            height: 300px; /* Tamaño del mapa */
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea,
        .form-group input[type="date"] {
            width: calc(100% - 12px);
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            box-sizing: border-box;
        }

        #vertices-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        #vertices-table th,
        #vertices-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        #vertices-table th {
            background-color: #f0f0f0;
        }

        #vertices-table input[type="number"],
        #vertices-table select {
            width: 90%;
            padding: 5px;
            border: 1px solid #eee;
            border-radius: 3px;
            box-sizing: border-box;
        }

        .button-container {
            margin-top: 20px;
        }

        .button-container button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            margin-right: 10px;
        }

        .button-container button:hover {
            background-color: #0056b3;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>

    <h1>Registro de Polígono (Coordenadas UTM)</h1>

    <div class="form-section">
        <h2>Información General del Polígono</h2>
        <div class="form-container">
            <!-- Campos del formulario -->
            <div class="form-fields">
                <div class="form-group">
                    <label for="nombre">Nombre del Polígono:</label>
                    <input type="text" id="nombre">
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción (Opcional):</label>
                    <textarea id="descripcion"></textarea>
                </div>
                <div class="form-group">
                    <label for="fecha_registro">Fecha de Registro:</label>
                    <input type="date" id="fecha_registro" readonly>
                </div>
                <div class="form-group">
                    <label for="responsable">Responsable del Registro:</label>
                    <input type="text" id="responsable">
                </div>
                <div class="form-group">
                    <label for="fuente_datos">Fuente de los Datos (Opcional):</label>
                    <input type="text" id="fuente_datos">
                </div>
            </div>

            <!-- Mapa -->
            <div id="map"></div>
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        </div>
    </div>
    <div class="form-section">
        <h2>Coordenadas de los Vértices del Polígono (UTM)</h2>
        <p>Por favor, ingrese las coordenadas UTM de cada vértice del polígono en el orden en que se conectan para formar el límite. Asegúrese de indicar la Zona UTM y el Hemisferio correctos.</p>
        <table id="vertices-table">
            <thead>
                <tr>
                    <th>Número de Vértice</th>
                    <th>Este (Easting)</th>
                    <th>Norte (Northing)</th>
                    <th>Zona UTM</th>
                    <th>Hemisferio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="vertices-body">
                <tr id="vertex-row-1">
                    <td>1</td>
                    <td><input type="number" class="easting"></td>
                    <td><input type="number" class="northing"></td>
                    <td>
                        <select class="utm-zone">
                            <option value="">Seleccionar Zona</option>
                            <option value="17N">17N</option>
                            <option value="18N">18N</option>
                            <option value="19N">19N</option>
                            <option value="17S">17S</option>
                            <option value="18S">18S</option>
                            <option value="19S">19S</option>
                            </select>
                    </td>
                    <td>
                        <select class="hemisferio">
                            <option value="">Seleccionar</option>
                            <option value="Norte">Norte</option>
                            <option value="Sur">Sur</option>
                        </select>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <button type="button" id="add-vertex-btn">Agregar Vértice</button>
        <div class="form-group">
            <input type="checkbox" id="cerrar_poligono">
            <label for="cerrar_poligono">Cerrar Polígono Automáticamente (El primer y último vértice serán el mismo)</label>
        </div>
    </div>

    <div class="form-section">
        <h2>Información Adicional (Opcional)</h2>
        <div class="form-group">
            <label for="datum">Sistema de Referencia Geodésico (Datum):</label>
            <select id="datum">
                <option value="">Seleccionar Datum</option>
                <option value="WGS84">WGS84</option>
                <option value="SIRGAS">SIRGAS</option>
                </select>
        </div>
        <div class="form-group">
            <label for="precision">Precisión de las Coordenadas (Opcional):</label>
            <input type="text" id="precision">
        </div>
        <div class="form-group">
            <label for="documentos">Documentos Adjuntos (Opcional):</label>
            <input type="file" id="documentos" multiple>
        </div>
        <div class="form-group">
            <label for="observaciones">Observaciones Adicionales:</label>
            <textarea id="observaciones"></textarea>
        </div>
    </div>

    <div class="button-container">
        <button type="button" id="guardar-btn">Guardar Polígono</button>
        <button type="button" id="limpiar-btn">Limpiar Formulario</button>
    </div>

   <script>
   document.addEventListener('DOMContentLoaded', function () {
    const map = L.map('map').setView([10.23, -67.76], 10); // Inicializar mapa centrado en Aragua
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    const polygonLayer = L.polygon([], { color: 'blue' }).addTo(map);
    const verticesData = []; // Array para almacenar los datos de los vértices (incluyendo UTM)

    const addVertexBtn = document.getElementById('add-vertex-btn');
    const verticesTableBody = document.getElementById('vertices-body');
    const cerrarPoligonoCheckbox = document.getElementById('cerrar_poligono');
    let vertexCounter = 2;

    // Configuración de la proyección UTM para la zona 19N (WGS84)
    const utm19N = "+proj=utm +zone=19 +datum=WGS84 +units=m +no_defs";
    const wgs84 = "+proj=longlat +datum=WGS84 +no_defs";

    // Función para actualizar el polígono en el mapa
    function updatePolygon() {
        verticesData.length = 0; // Limpiar datos de vértices
        const leafletVertices = [];
        const rows = verticesTableBody.querySelectorAll('tr');

        rows.forEach(row => {
            const eastingInput = row.querySelector('.easting');
            const northingInput = row.querySelector('.northing');
            const utmZoneSelect = row.querySelector('.utm-zone');
            const hemisphereSelect = row.querySelector('.hemisferio');

            if (eastingInput && northingInput && utmZoneSelect && hemisphereSelect) {
                const easting = parseFloat(eastingInput.value);
                const northing = parseFloat(northingInput.value);
                const utmZone = utmZoneSelect.value;
                const hemisphere = hemisphereSelect.value;

                if (!isNaN(easting) && !isNaN(northing) && utmZone && hemisphere) {
                    let targetProj = utm19N; // Por defecto asumimos 19N

                    // Ajustar la proyección UTM según la zona seleccionada (esto es básico)
                    const zoneNumber = utmZone.slice(0, -1);
                    targetProj = `+proj=utm +zone=${zoneNumber} +datum=WGS84 +units=m +no_defs`;

                    const [lng, lat] = proj4(targetProj, wgs84, [easting, northing]);
                    leafletVertices.push([lat, lng]);
                    verticesData.push({ easting, northing, utmZone, hemisphere });
                }
            }
        });

        // Cerrar el polígono si está activado y hay suficientes vértices
        const finalVertices = [...leafletVertices];
        if (cerrarPoligonoCheckbox.checked && leafletVertices.length > 2) {
            finalVertices.push(leafletVertices[0]);
        }

        polygonLayer.setLatLngs(finalVertices);

        if (finalVertices.length > 0) {
            map.fitBounds(polygonLayer.getBounds(), { padding: [50, 50] });
        } else {
            map.setView([10.23, -67.76], 10); // Volver a la vista inicial si no hay vértices
        }
    }

    // Agregar un nuevo vértice
    addVertexBtn.addEventListener('click', function () {
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>${vertexCounter}</td>
            <td><input type="number" class="easting" placeholder="Easting"></td>
            <td><input type="number" class="northing" placeholder="Northing"></td>
            <td>
                <select class="utm-zone">
                    <option value="17N">17N</option>
                    <option value="18N">18N</option>
                    <option value="19N" selected>19N</option>
                    <option value="17S">17S</option>
                    <option value="18S">18S</option>
                    <option value="19S">19S</option>
                </select>
            </td>
            <td>
                <select class="hemisferio">
                    <option value="Norte" selected>Norte</option>
                    <option value="Sur">Sur</option>
                </select>
            </td>
            <td><button type="button" class="remove-vertex-btn">Eliminar</button></td>
        `;
        verticesTableBody.appendChild(newRow);
        vertexCounter++;

        // Asignar evento de eliminación
        newRow.querySelector('.remove-vertex-btn').addEventListener('click', function () {
            newRow.remove();
            updatePolygon();
            updateVertexNumbers();
        });
        updateVertexNumbers();
    });

    // Actualizar los números de los vértices
    function updateVertexNumbers() {
        const rows = verticesTableBody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            row.querySelector('td:first-child').textContent = index + 1;
        });
        vertexCounter = rows.length + 1;
    }

    // Limpiar el formulario (sin recargar el mapa)
    const limpiarBtn = document.getElementById('limpiar-btn');
    limpiarBtn.addEventListener('click', function () {
        document.getElementById('nombre').value = '';
        document.getElementById('descripcion').value = '';
        document.getElementById('responsable').value = '';
        document.getElementById('fuente_datos').value = '';
        document.getElementById('datum').value = '';
        document.getElementById('precision').value = '';
        document.getElementById('documentos').value = '';
        document.getElementById('observaciones').value = '';

        const rowsToRemove = verticesTableBody.querySelectorAll('tr:not(:first-child)');
        rowsToRemove.forEach(row => row.remove());

        const firstRow = document.getElementById('vertex-row-1');
        if (firstRow) {
            firstRow.querySelector('.easting').value = '';
            firstRow.querySelector('.northing').value = '';
            firstRow.querySelector('.utm-zone').value = '19N';
            firstRow.querySelector('.hemisferio').value = 'Norte';
        }

        vertexCounter = 2;
        updatePolygon(); // Limpiar el polígono del mapa
    });

    // Guardar los datos del formulario
    const guardarBtn = document.getElementById('guardar-btn');
    guardarBtn.addEventListener('click', function () {
        const nombre = document.getElementById('nombre').value;
        const cerrarPoligono = document.getElementById('cerrar_poligono').checked;
        const formData = {
            nombre,
            descripcion: document.getElementById('descripcion').value,
            fechaRegistro: document.getElementById('fecha_registro').value,
            responsable: document.getElementById('responsable').value,
            fuenteDatos: document.getElementById('fuente_datos').value,
            vertices: verticesData,
            datum: document.getElementById('datum').value,
            precision: document.getElementById('precision').value,
            observaciones: document.getElementById('observaciones').value,
            cerrarPoligono
        };

        console.log('Datos del formulario a guardar:', formData);
        alert('Datos del polígono listos para ser guardados (ver consola del navegador).');
    });

    // Escuchar cambios en los campos de coordenadas para actualizar el polígono
    verticesTableBody.addEventListener('input', updatePolygon);
    cerrarPoligonoCheckbox.addEventListener('change', updatePolygon);

    // Inicializar el mapa y (opcionalmente) un primer vértice
    updatePolygon();
    updateVertexNumbers();
});

</script>
</body>
</html>