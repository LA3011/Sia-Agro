
<?php session_start();            ?>
<?php if(!isset($_SESSION['Aceso'])){
  header("location: index.html");
}?>
<!-- ---- ↓↓ CODIGO A COPIAR ↓↓ ---- -->
<?php
include("conexion/conexion.php");
$conn = cconexion::ConexionBD();

$id_perfil_actual = $_SESSION['Id_Perfilp'];

$query = "SELECT * FROM privilegios WHERE id_perfil = :id_perfil_actual";
$statement = $conn->prepare($query);
$statement->bindValue(':id_perfil_actual', $id_perfil_actual, PDO::PARAM_INT);
$statement->execute();

while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    $ver = $row['ver'];
    $eliminar = $row['eliminar'];
    $editar = $row['editar'];
    $imprimir = $row['imprimir'];
}
?>
<!-- ---- ↑↑ CODIGO A COPIAR ↑↑ ---- -->


<?php include_once("header.php") ?>



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
    font-family: 'Poppins', sans-serif;
    background-color: #f9f9f9; /* Fondo suave */
    color: #333; /* Color del texto */
    margin: 100px;
    padding: 0;
}

h1 {
    text-align: center;
    margin-top: 20px;
    color: #007bff; /* Azul profesional */
}

.form-section {
    margin-bottom: 20px;
    padding: 15px;
    background-color: #fff; /* Fondo blanco */
    border: 1px solid #ddd; /* Borde gris claro */
    border-radius: 10px; /* Bordes redondeados */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra */
}

.form-section h2 {
    margin-top: 0;
    border-bottom: 2px solid #007bff; /* Línea azul */
    padding-bottom: 5px;
    color: #007bff;
    text-align: center; /* Centrar el título */
    font-weight: 700; /* Letra más gruesa */
    font-size: 28px; /* Tamaño más grande */
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
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Sombra */
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #555; /* Color del texto */
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group select,
.form-group textarea,
.form-group input[type="date"] {
    width: calc(100% - 12px);
    padding: 10px; /* Espaciado interno */
    border: 1px solid #ddd;
    border-radius: 5px; /* Bordes redondeados */
    box-sizing: border-box;
    font-size: 14px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #007bff; /* Azul al enfocar */
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Sombra azul */
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
    background-color: #007bff; /* Fondo azul */
    color: white; /* Texto blanco */
}

#vertices-table input[type="number"],
#vertices-table select {
    width: 90%;
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 3px;
    box-sizing: border-box;
}

.button-container {
    margin-top: 20px;
    text-align: center; /* Centrar botones */
}

.button-container button {
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    background-color: #007bff;
    color: white;
    cursor: pointer;
    margin-right: 10px;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.button-container button:hover {
    background-color: #0056b3; /* Azul más oscuro al pasar el cursor */
}

.hidden {
    display: none;
}
/* Contenedor de los fieldsets */
.fieldset-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* Ajusta el número de columnas automáticamente */
    gap: 20px; /* Espaciado entre los fieldsets */
    margin-top: 20px; /* Espaciado superior */
}

/* Estilo de los fieldsets */
fieldset {
    border: 1px solid #ddd;
    padding: 20px;
    border-radius: 10px;
    background-color: #f9fafc; /* Fondo más suave */
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Sombra */
}

fieldset:hover {
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2); /* Sombra más intensa al pasar el cursor */
    border-color: #007bff;
    background-color: #eef6ff; /* Fondo más claro al hacer hover */
}

legend {
    font-weight: bold;
    font-size: 18px;
    color: #007bff;
    padding: 0 10px;
    margin-bottom: 10px;
    text-align: center;
}

/* Inputs y etiquetas */
label {
    font-size: 14px;
    color: #555;
}

input[type="number"], input[type="text"], textarea, select {
    width: 100%;
    padding: 12px;
    margin-top: 5px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 8px; /* Bordes redondeados */
    transition: border 0.3s ease;
}

input[type="checkbox"] {
    margin-right: 10px;
}

input:focus, textarea:focus, select:focus {
    border-color: #007bff;
    outline: none;
}

/* Botones */


/* Interactividad y animación */
fieldset:hover {
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); /* Sombra suave al hacer hover */
    border-color: #007bff;
    background-color: #eef6ff; /* Efecto de color suave al pasar sobre la sección */
}

.action-icons button {
    background-color: #28a745;
    font-size: 14px;
    padding: 10px 20px;
}

.action-icons button:hover {
    background-color: #218838;
}

/* Estilos para el canvas */



    </style>
</head>
<body>


      <form id="pointsForm" method="POST" action="procesar/procesar_ficha.php">
    <div class="form-section">
        <h2>Información General del Polígono</h2>
        <div class="form-container">
            <div class="form-fields">
                <div class="form-group">
                    <label for="nombre">Nombre del Polígono:</label>
                    <input type="text" id="nombre" name="nombre">
                </div>

                <div class="form-group">
                    <label for="fecha_registro">Fecha de Registro:</label>
                    <input type="date" id="fecha_registro" name="fecha_registro" readonly>
                </div>

                <div class="form-group">
                    <label for="area">Área del Terreno (m²):</label>
                    <input type="number" id="area" name="area" placeholder="Ingrese el área en metros cuadrados">
                </div>

                <div class="form-group">
                    <label for="ubicacion_norte">Ubicación - Norte:</label>
                    <input type="number" id="ubicacion_norte" name="ubicacion_norte" placeholder="Ingrese la coordenada Norte">
                </div>

                <div class="form-group">
                    <label for="ubicacion_este">Ubicación - Este:</label>
                    <input type="number" id="ubicacion_este" name="ubicacion_este" placeholder="Ingrese la coordenada Este">
                </div>
            </div>
             <div id="map"></div>
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        </div>
        </div>
    </div>
                <div class="form-section">
        <h2>Coordenadas de los Vértices del Polígono (UTM)</h2>
        <p>Por favor, ingrese las coordenadas UTM de cada vértice del polígono en el orden en que se conectan para formar el límite. Asegúrese de indicar la Zona UTM y el Hemisferio correctos.</p>
        <table id="vertices-table">
            <thead>
                <tr>
                    <th>Número de Vértice</th>
                    <th>Este</th>
                    <th>Norte</th>
                    <th>Zona UTM</th>
                    <th>Hemisferio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="vertices-body">
                <tr id="vertex-row-1">
                    <td>1</td>
                    <td><input type="number" name="vertices[0][este]" class="easting"></td>
                    <td><input type="number" name="vertices[0][norte]" class="northing"></td>
                    <td>
                        <select name="vertices[0][zona]" class="utm-zone">
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
                        <select name="vertices[0][hemisferio]" class="hemisferio">
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
            <input type="checkbox" id="cerrar_poligono" name="cerrar_poligono">
            <label for="cerrar_poligono">Cerrar Polígono Automáticamente (El primer y último vértice serán el mismo)</label>
        </div>
    </div>

    <div class="form-section">
        <h2>Información Adicional</h2>
        <div class="fieldset-container">
            <!-- Sección: Topografía -->
            <fieldset>
                <legend>12. Topografía</legend>
                <div class="grid">
                    <label><input type="checkbox" name="plano"> Plano</label>
                    <label><input type="checkbox" name="sobre_nivel"> Sobre Nivel</label>
                    <label><input type="checkbox" name="bajo_nivel"> Bajo Nivel</label>
                    <label><input type="checkbox" name="corte"> Corte</label>
                    <label><input type="checkbox" name="relleno"> Relleno</label>
                    <label><input type="checkbox" name="inclinado"> Inclinado</label>
                    <label><input type="checkbox" name="irregular"> Irregular</label>
                </div>
            </fieldset>

            <!-- Sección: Forma -->
            <fieldset>
                <legend>13. Forma</legend>
                <div class="grid">
                    <label><input type="checkbox" name="regular"> Regular</label>
                    <label><input type="checkbox" name="irregular"> Irregular</label>
                    <label><input type="checkbox" name="muy_irregular"> Muy Irregular</label>
                </div>
            </fieldset>

            <!-- Sección: Ubicación -->
            <fieldset>
                <legend>14. Ubicación</legend>
                <div class="grid">
                    <label><input type="checkbox" name="convencional"> Convencional</label>
                    <label><input type="checkbox" name="esquina"> Esquina</label>
                    <label><input type="checkbox" name="interior_manzana"> Interior de Manzana</label>
                </div>
            </fieldset>

            <!-- Sección: Entorno Físico -->
            <fieldset>
                <legend>15. Entorno Físico</legend>
                <div class="grid">
                    <label><input type="checkbox" name="zona_urbanizada"> Zona Urbanizada</label>
                    <label><input type="checkbox" name="zona_no_urbanizada"> Zona No Urbanizada</label>
                    <label><input type="checkbox" name="rio_quebrada"> Río/Quebrada</label>
                    <label><input type="checkbox" name="barranco_talud"> Barranco/Talud</label>
                    <label><input type="checkbox" name="otro_entorno"> Otro</label>
                </div>
            </fieldset>

            <!-- Sección: Mejoras al Terreno -->
            <fieldset>
                <legend>16. Mejoras al Terreno</legend>
                <div class="grid">
                    <label><input type="checkbox" name="muro_contencion"> Muro de Contención</label>
                    <label><input type="checkbox" name="nivelacion"> Nivelación</label>
                    <label><input type="checkbox" name="cercado"> Cercado</label>
                    <label><input type="checkbox" name="pozo_septico"> Pozo Séptico</label>
                    <label><input type="checkbox" name="lagunas_artificiales"> Lagunas Artificiales</label>
                    <label><input type="checkbox" name="otro_mejoras"> Otro</label>
                </div>
            </fieldset>
        </div>
    </div>

    <div class="form-group">
        <label for="observaciones">Observaciones Adicionales:</label>
        <textarea id="observaciones" name="observaciones"></textarea>
    </div>

    <div class="button-container">
        <button type="submit">Enviar</button>
        <button type="button" id="limpiar-btn">Limpiar Formulario</button>
    </div>
</form>
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
    const form = document.getElementById('pointsForm');
    let vertexCounter = 2;

    // Configuración de la proyección UTM para la zona 19N (WGS84)
    const utm19N = "+proj=utm +zone=19 +datum=WGS84 +units=m +no_defs";
    const wgs84 = "+proj=longlat +datum=WGS84 +no_defs";

    // Establecer la fecha actual en el campo de fecha
    const fechaRegistroInput = document.getElementById('fecha_registro');
    const today = new Date();
    const formattedDate = today.toISOString().split('T')[0]; // Formato YYYY-MM-DD
    fechaRegistroInput.value = formattedDate;

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

                    // Ajustar la proyección UTM según la zona seleccionada
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
        <td><input type="number" name="vertices[${vertexCounter - 1}][este]" class="easting" placeholder="Este"></td>
        <td><input type="number" name="vertices[${vertexCounter - 1}][norte]" class="northing" placeholder="Norte"></td>
        <td>
            <select name="vertices[${vertexCounter - 1}][zona]" class="utm-zone">
                <option value="17N">17N</option>
                <option value="18N">18N</option>
                <option value="19N" selected>19N</option>
                <option value="17S">17S</option>
                <option value="18S">18S</option>
                <option value="19S">19S</option>
            </select>
        </td>
        <td>
            <select name="vertices[${vertexCounter - 1}][hemisferio]" class="hemisferio">
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

    
    // Escuchar cambios en los campos de coordenadas para actualizar el polígono
    verticesTableBody.addEventListener('input', updatePolygon);
    cerrarPoligonoCheckbox.addEventListener('change', updatePolygon);

    // Inicializar el mapa y (opcionalmente) un primer vértice
    updatePolygon();
    updateVertexNumbers();
});
</script>
<?php
if (isset($_SESSION['mensaje'])) {
    echo "<script>alert('" . $_SESSION['mensaje'] . "');</script>";
    unset($_SESSION['mensaje']);
}
?>
</body>
</html>

