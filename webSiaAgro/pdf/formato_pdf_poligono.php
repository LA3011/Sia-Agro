<?php
// Conexión a la base de datos PostgreSQL usando PDO
include("../conexion/conexion.php");
$conn = cconexion::ConexionBD();

// Importar la clase Dompdf
require_once('../librerias/dompdf/autoload.inc.php');
use Dompdf\Dompdf;

// Verificar si la conexión fue exitosa
if (!$conn) {
    die(json_encode(['error' => 'No se pudo establecer la conexión con la base de datos.']));
}

// Variables de encabezado
$espaciado = "  ";
$string1 = "LOS TUCUPIDOS C.A.<br>"; 
$string2 = "<strong>Rif: J-0000000-00 </strong><br>"; 
$string3 = "<br>";

try {
    // Realizar la consulta y obtener el resultado
    $queryBD = "SELECT * FROM poligono";
    $stmt = $conn->query($queryBD); // Ejecuta la consulta y guarda el resultado en $stmt

    // Construir el contenido HTML para el PDF
    $html = '
    <body style="margin-left:10%; margin-right:8%; margin-bottom:8%; margin-top:2%; padding-bottom:2%;">
      <p style="position: absolute; margin-left:10%; top:-10px;">
        <strong style="font-size:30px;">LOS TUCUPIDOS C.A.</strong><br>' . $string2 . $string3 . '
      </p>
      <div><img src="logo222.jpg" style="height:80px; display:inline-block;"></div><br>
      <div style="position: absolute; right:0px; top:8%;">FECHA: ' . date("d/m/Y") . '</div>
      <div style="background-color: #A59F9F; text-align: center; padding: 10px; border: 1px solid black; margin-bottom: 20px;">
        <h2 style="margin: 0;">REPORTE DE POLÍGONOS</h2>
      </div>

      <!-- Tabla de Datos -->
      <div style="margin: 0 auto; width: 90%;">
        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
          <thead>
            <tr style="background-color: #f2f2f2;">
              <th style="border: 1px solid black; padding: 8px; text-align: left;">Nombre</th>
              <th style="border: 1px solid black; padding: 8px; text-align: left;">Ficha Técnica ID</th>
              <th style="border: 1px solid black; padding: 8px; text-align: left;">Fecha y Hora</th>
            </tr>
          </thead>
          <tbody>';

    // Cargar datos en la tabla
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $html .= '
            <tr>
              <td style="border: 1px solid black; padding: 8px;">' . htmlspecialchars($espaciado . $row['nombre']) . '</td>
              <td style="border: 1px solid black; padding: 8px;">' . htmlspecialchars($espaciado . $row['ficha_tecnica_id']) . '</td>
              <td style="border: 1px solid black; padding: 8px;">' . htmlspecialchars(date("d/m/Y H:i:s", strtotime($row['fecha_hora']))) . '</td>
            </tr>';
    }

    $html .= '
          </tbody>
        </table>
      </div>
      <div style="border-top: 1px solid black; position: absolute; bottom: -100px; width: 100%; text-align: center;">
        <strong>
          Dirección: Av Casanova Godoy/Municipio Santiago Mariño<br> 
          Tlf: 0412-000 00 00 / 0416-000 00 00<br>
        </strong>
        Software para la Gestión Agrícola de la Hacienda los Tucupidos<br>
        ©2023 Derechos Reservados. Sistema para U.P.T. Estado Aragua<br>
      </div>
    </body>';

    // Generar el PDF usando Dompdf
    $dompdf = new Dompdf();
    $opcion = $dompdf->getOptions();
    $opcion->set(array('isRemoteEnabled' => true));
    $dompdf->setOptions($opcion);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("Poligono.pdf", array("Attachment" => false));

} catch (PDOException $e) {
    // Manejo de errores en la consulta
    echo json_encode(['error' => 'Error al realizar la consulta: ' . $e->getMessage()]);
    exit;
}
?>