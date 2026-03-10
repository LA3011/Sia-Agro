<?php
require_once '../librerias/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

include("../conexion/conexion.php");

$conn = cconexion::ConexionBD();

// Consulta para obtener todos los campos de la tabla cultivos excepto el ID
$sqlCultivos = "SELECT \"nombre\", \"tipo\", \"espacio\", \"cosecha_estimada\", \"fecha_aspercion\", 
                       \"nombre_producto\", \"dosis\", \"tipo_aspercion\", \"tipo_fertilizante\", 
                       \"cantidad_fertilizante\", \"observaciones\", \"fecha_siembra\", \"fecha_cosecha\", 
                       \"tipo_riego\", \"fecha_registro\", \"fecha_fertilizacion\", \"id_espacio\", \"estado\"
                FROM \"cultivos\"
                ORDER BY \"fecha_registro\" DESC";
$resultCultivos = $conn->query($sqlCultivos);

// Consulta para obtener todos los detalles de cosecha
$sqlDetalles = "SELECT \"id_cosecha\", \"fecha_cosecha\", \"cantidad_cosechada\", \"observaciones\"
                FROM \"detalle_cosecha\"
                ORDER BY \"id_cosecha\"";
$resultDetalles = $conn->query($sqlDetalles);

// Iniciar el contenido HTML para el PDF
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
 <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background-image: url("cultivos_imagen.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        h1, h2 {
            text-align: center;
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0; /* Eliminar márgenes entre tablas */
            background-color: rgba(255, 255, 255, 0.9);
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        .section {
            margin: 10; /* Eliminar márgenes entre secciones */
            padding: 0;
        }
        .paragraph {
            text-align: justify;
            margin: 20px 0;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 5px;
        }
        .signature {
            margin-top: 50px;
            text-align: center;
        }
        .line {
            border-top: 1px solid #000;
            margin: 20px 0;
        }
    </style>
</head>
<body>
<br>
  <p style="position: absolute; margin: 50px; margin-left:30%; ">
    <strong style="font-size:30px;"></strong><br><?php echo $string2 . $string3; ?>
  </p>
<br><br><br><br><br><br>
 <br>
  <br>
    <!-- Tabla General -->
    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Espacio</th>
                    <th>Cosecha Estimada</th>
                    <th>Fecha Siembra</th>
                    <th>Fecha Estimada Cosecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>';

// Agregar los datos generales de la tabla cultivos
while ($cultivo = $resultCultivos->fetch(PDO::FETCH_ASSOC)) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($cultivo['nombre']) . '</td>';
    $html .= '<td>' . htmlspecialchars($cultivo['tipo']) . '</td>';
    $html .= '<td>' . htmlspecialchars($cultivo['espacio']) . '</td>';
    $html .= '<td>' . htmlspecialchars($cultivo['cosecha_estimada']) . '</td>';
    $html .= '<td>' . (!empty($cultivo['fecha_siembra']) ? date("d/m/Y", strtotime($cultivo['fecha_siembra'])) : 'Sin registrar') . '</td>';
    $html .= '<td>' . (!empty($cultivo['fecha_cosecha']) ? date("d/m/Y", strtotime($cultivo['fecha_cosecha'])) : 'Sin registrar') . '</td>';
    $html .= '<td>' . ($cultivo['estado'] ? 'Activo' : 'Inactivo') . '</td>';
    $html .= '</tr>';
}

$html .= '
            </tbody>
        </table>
    </div>

    <!-- Tabla Adicional -->
    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>Fecha Asperción</th>
                    <th>Nombre Producto</th>
                    <th>Dosis</th>
                    <th>Tipo Asperción</th>
                    <th>Tipo Fertilizante</th>
                    <th>Cantidad Fertilizante</th>
                    <th>Observaciones</th>
                    <th>Fecha Registro</th>
                    <th>Fecha Fertilización</th>
               
                </tr>
            </thead>
            <tbody>';

// Agregar los datos adicionales de la tabla cultivos
$resultCultivos->execute(); // Reejecutar la consulta para reutilizar los datos
while ($cultivo = $resultCultivos->fetch(PDO::FETCH_ASSOC)) {
    $html .= '<tr>';
    $html .= '<td>' . (!empty($cultivo['fecha_aspercion']) ? date("d/m/Y", strtotime($cultivo['fecha_aspercion'])) : 'Sin registrar') . '</td>';
    $html .= '<td>' . htmlspecialchars($cultivo['nombre_producto']) . '</td>';
    $html .= '<td>' . htmlspecialchars($cultivo['dosis']) . '</td>';
    $html .= '<td>' . htmlspecialchars($cultivo['tipo_aspercion']) . '</td>';
    $html .= '<td>' . htmlspecialchars($cultivo['tipo_fertilizante']) . '</td>';
    $html .= '<td>' . htmlspecialchars($cultivo['cantidad_fertilizante']) . '</td>';
    $html .= '<td>' . htmlspecialchars($cultivo['observaciones'] ?? 'Sin observaciones') . '</td>';
    $html .= '<td>' . (!empty($cultivo['fecha_registro']) ? date("d/m/Y", strtotime($cultivo['fecha_registro'])) : 'Sin registrar') . '</td>';
    $html .= '<td>' . (!empty($cultivo['fecha_fertilizacion']) ? date("d/m/Y", strtotime($cultivo['fecha_fertilizacion'])) : 'Sin registrar') . '</td>';

    $html .= '</tr>';
}

$html .= '
            </tbody>
        </table>
    </div>

    <!-- Tabla de Detalles de Cosecha -->
    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>Fecha Cosecha</th>
                    <th>Cantidad Cosechada (Kg)</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>';

// Agregar los datos de la tabla detalle_cosecha
while ($detalle = $resultDetalles->fetch(PDO::FETCH_ASSOC)) {
    $html .= '<tr>';
    $html .= '<td>' . (!empty($detalle['fecha_cosecha']) ? date("d/m/Y", strtotime($detalle['fecha_cosecha'])) : 'Sin registrar') . '</td>';
    $html .= '<td>' . htmlspecialchars($detalle['cantidad_cosechada']) . '</td>';
    $html .= '<td>' . htmlspecialchars($detalle['observaciones'] ?? 'Sin observaciones') . '</td>';
    $html .= '</tr>';
}

$html .= '
            </tbody>
        </table>
    </div>
     <!-- Párrafo Extenso -->
    <div class="paragraph">
        Este reporte ha sido elaborado conforme a las normativas establecidas por la Ley de Fedeagro, 
        que regula y avala las actividades agrícolas en el territorio nacional. La información contenida 
        en este documento refleja los datos registrados en el sistema de gestión agrícola, incluyendo 
        detalles sobre los cultivos, las prácticas de fertilización, riego y cosecha, así como las 
        observaciones relevantes para garantizar la trazabilidad y cumplimiento de los estándares de calidad. 
        Este reporte es un instrumento clave para la toma de decisiones estratégicas en el sector agrícola, 
        promoviendo la sostenibilidad, la eficiencia y el desarrollo rural. Fedeagro, como organismo rector, 
        respalda la veracidad de los datos aquí presentados, los cuales han sido recopilados y procesados 
        bajo estrictos controles de calidad y transparencia.
    </div>

    <!-- Línea Horizontal -->
    <div class="line"></div>

    <!-- Espacio para la Firma -->
    <div class="signature">
     <br> <br> <br> <br>
        <p>______________________________</p>
        <p>Firma del Responsable</p>
    </div>
</body>
</html>';

// Configuración y generación del PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); // Habilitar imágenes remotas
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape'); // Configurar tamaño y orientación del papel
$dompdf->render();

// Enviar el PDF al navegador
$dompdf->stream("Reporte_General.pdf", array("Attachment" => false));
?>