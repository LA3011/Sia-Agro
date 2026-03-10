<title>VENTAS REALIZADAS</title>
<?php 

include("../conexion/conexion.php");
$conn = cconexion::ConexionBD(); // Asegúrate de que esta función configure correctamente la conexión PDO
require_once '../librerias/dompdf/autoload.inc.php';

// Captura de datos del formulario
$fi = $_POST['fechaInicio'];
$ff = $_POST['fechaFinal'];
$Ingreso_generado = $_POST['ingreso_generado']; 
$ganancia = $_POST['ganancia'];
$iterador = 1;

$nombreImagen = "imgPrint.jpg";

$fi2 = date("d/m/Y", strtotime($_POST['fechaInicio']));
$ff2 = date("d/m/Y", strtotime($_POST['fechaFinal']));

// Datos de encabezado
$string1 = "LOS TUCUPIDOS C.A.<br>"; 
$string2 = "<strong>Rif: J-0000000-00 </strong><br>"; 
$string3 = "<br>";
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
    <h1 style="margin: 0;">Ventas Realizadas</h1>
  </div>
  <br>
  <table style="border:1.5px solid #333; margin-left:120px;">
    <thead style="border-bottom: 1px solid black; background-color:#6AAD3E;">
      <tr>
        <th>Nro</th>
        <th>Fecha</th>
        <th>Tipo/usuario</th>
        <th>Cant/Anim</th>
        <th>Ingresos</th>
        <th>Ganancias</th>
      </tr>
    </thead>
    <tbody>
<?php
// Consulta a PostgreSQL usando PDO
$queryBD = "SELECT * FROM factura WHERE fecha BETWEEN :fechaInicio AND :fechaFinal";
$stmt = $conn->prepare($queryBD);
$stmt->bindParam(':fechaInicio', $fi, PDO::PARAM_STR);
$stmt->bindParam(':fechaFinal', $ff, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Iterar sobre los resultados
foreach ($result as $row) { ?>
      <tr>
        <td><?php echo $iterador; ?></td>
        <td><?php echo date("d/m/Y", strtotime($row['fecha'])); ?></td>
        <td><?php echo $row['tipopublico']; ?></td>
        <td><?php echo $row['cantidad_animales']; ?></td>
        <td><?php echo number_format($row['precio'], 2, ",", "."); ?></td>
        <td><?php echo number_format($row['ganancia'], 2, ",", "."); ?></td>
      </tr>
<?php 
  $iterador++;
} 
?>
    </tbody>
  </table>
  <div style="border-left:1.5px solid #333; border-right:1.5px solid #333; border-bottom:1.5px solid #333; margin-left:120px; padding-top:5px; width: 441.5px;">
    Ingreso Generado: <?php echo number_format($Ingreso_generado, 2, ",", ".") . " $"; ?> <br>
    Ganancia Obtenida: <?php echo number_format($ganancia, 2, ",", ".") . "Bs"; ?>
  </div>
</div>
<div style="border-top: 1px solid black; position: absolute; bottom:-10px; width: 100%; text-align: center;">
  <strong>
    <?php echo "Direccion: C/ Miranda, Edif. Los Andes <br>"; ?>
    <?php echo "Tlf: 0412-000 00 00 / 0416-000 00 00"; ?><br>
  </strong>
  Software para la Gestion Agricola de la Hacienda los Tucupidos<br>©2023 Derechos Reservados. Sistema para U.P.T. Estado Aragua
  <br>
</div>
</body>
</html>
<?php
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
$dompdf->stream("Ventas realizadas.pdf", array("Attachment" => false));
?>
