<?php 

include("../conexion/conexion.php");
$conn = cconexion::ConexionBD(); 
require_once '../librerias/dompdf/autoload.inc.php';

// Captura de datos del formulario
$fi = $_POST['fechaInicio'];
$ff = $_POST['fechaFinal'];
$usuario = $_POST['usuario']; // Usuario que realiza la consulta

$fi2 = date("d/m/Y", strtotime($fi));
$ff2 = date("d/m/Y", strtotime($ff));

// Datos de encabezado
$string1 = "LOS TUCUPIDOS C.A.<br>"; 
$string2 = "<strong>Rif: J-0000000-00 </strong><br>"; 
$string3 = "<br>";

// Iniciar la captura del contenido
ob_start();
?>

<div style="margin-bottom: 0;">
  <p style="position: absolute; margin-left:10%; top:8px;">
    <strong style="font-size:30px;">LOS TUCUPIDOS C.A.</strong><br><?php echo $string2 . $string3; ?>
  </p>
  <div><img src="logo222.jpg" style="height:80px; display:inline-block;"></div><br>
  <div style="position: absolute; right:0px; top:9%;"> FECHA: 
    <?php echo $fi2 . " - " . $ff2; ?>
  </div>
  <div style="background-color: #A59F9F; text-align: center; border: 1px solid black;">
    <h1 style="margin: 0;">Bitácora</h1>
  </div>
  <br>
  <table style="border:1.5px solid #333; margin-left:120px;">
    <thead style="border-bottom: 1px solid black; background-color:#6AAD3E;">
      <tr>
        <th>Nro</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Acción</th>
        <th>Registro</th>
        <th>Tabla Modificada</th>
      </tr>
    </thead>
    <tbody>
<?php
// Consulta a PostgreSQL usando PDO
$queryBD = "SELECT * FROM bitacora 
            WHERE \"Usuario\" = :usuario 
            AND \"Fecha\" BETWEEN :fechaInicio AND :fechaFinal";
$stmt = $conn->prepare($queryBD);
$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
$stmt->bindParam(':fechaInicio', $fi, PDO::PARAM_STR);
$stmt->bindParam(':fechaFinal', $ff, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verificar si hay datos antes de mostrar la tabla
if (count($result) > 0) {
    $iterador = 1;
    foreach ($result as $row) { ?>
        <tr>
            <td><?php echo $iterador; ?></td>
            <td><?php echo date("d/m/Y", strtotime($row['Fecha'])); ?></td>
            <td><?php echo htmlspecialchars($row['Hora']); ?></td>
            <td><?php echo htmlspecialchars($row['Accion']); ?></td>
            <td><?php echo htmlspecialchars($row['Numero_Registro']); ?></td>
            <td><?php echo htmlspecialchars($row['Tabla_Modificada']); ?></td>
        </tr>
    <?php 
        $iterador++;
    } 
} else {
    echo "<tr><td colspan='6'>No hay registros disponibles en este rango de fechas.</td></tr>";
}
?>
    </tbody>
  </table>
</div>

<div style="border-top: 1px solid black; position: absolute; bottom:-10px; width: 100%; text-align: center;">
  <strong>
    <?php echo "Direccion: C/ Miranda, Edif. Los Andes <br>"; ?>
    <?php echo "Tlf: 0412-000 00 00 / 0416-000 00 00"; ?><br>
  </strong>
  Software para la Gestión Agrícola de la Hacienda Los Tucupidos<br>©2023 Derechos Reservados. Sistema para U.P.T. Estado Aragua
  <br>
</div>

</body>
</html>

<?php
// Generar el PDF
$html = ob_get_clean();
require_once('../librerias/dompdf/autoload.inc.php');
use Dompdf\Dompdf;

// Configuración y generación del PDF
$dompdf = new Dompdf();
$opcion = $dompdf->getOptions();
$opcion->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($opcion);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Bitacora_Acciones.pdf", array("Attachment" => false));
?>
