<title>VENTAS REALIZADAS</title>
<?php 
include("../conexion/conexion.php");
$conn = cconexion::ConexionBD(); 

$fi = $_POST['fechaInicio'];
$ff = $_POST['fechaFinal'];
$Ingreso_generado = $_POST['ingreso_generado']; 
$ganancia = $_POST['ganancia'];
$promedio = $_POST['promedio'];
$iterador = 1;

$fi2 = date("d/m/Y", strtotime($_POST['fechaInicio']));
$ff2 = date("d/m/Y", strtotime($_POST['fechaFinal']));


$string1 = "LOS TUCUPIDOS C.A.<br>"; 
$string2 = "<strong>Rif: J-0000000-00 </strong><br>"; 
$string3 = "<br>";
?>
<body style="margin-left:10%; margin-right:8%; margin-bottom:8%; margin-top:2%; padding-bottom:2%;">
  <p style="position: absolute; margin-left:10%; top:-10px;">
    <strong style="font-size:30px;">LOS TUCUPIDOS C.A.</strong><br>
    <?php echo $string2 . $string3; ?>
  </p>
  <div>
    <img src="logo222.jpg" style="height:80px; display:inline-block;">
  </div>
  <br>
  <div style="position: absolute; right:0px; top:8%;">
    FECHA: <?php echo $fi2 . " - " . $ff2; ?>
  </div>
  <div style="background-color: #A59F9F; text-align: center; border: 1px solid black;">
    <h1 style="margin: 0;">GANANCIAS OPTENIDAS</h1>
  </div>
  <br><br>
  <table class="table table-bordered" style="border:1.5px solid #333; margin-left:130px; position: relative; top: -25px;">
    <thead style="border-bottom:1px solid #333; background-color:#6AAD3E;">
      <tr>
        <th>Nro</th>
        <th>Fecha</th>
        <th>Ingresos</th>
        <th>Ganancias</th>
      </tr>
    </thead>
    <tbody>
<?php
$queryBD = "SELECT * FROM factura WHERE fecha BETWEEN :fechaInicio AND :fechaFinal";
$stmt = $conn->prepare($queryBD);
$stmt->bindParam(':fechaInicio', $fi, PDO::PARAM_STR);
$stmt->bindParam(':fechaFinal', $ff, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
foreach ($result as $row) { ?>
      <tr>
        <td><?php echo "  " . $iterador; ?> </td>
        <td><?php echo date("d/m/Y", strtotime($row['fecha'])); ?></td>
        <td><?php echo number_format($row['precio'], 2, ",", "."); ?></td>
        <td><?php echo number_format($row['ganancia'], 2, ",", "."); ?></td>
      </tr>
<?php 
  $iterador++;
}
?>
    </tbody>
  </table>
  <div style="border-left:1.5px solid #333; border-right:1.5px solid #333; border-bottom:1.5px solid #333; margin-left:130px; position: relative; top: -25px; width: 249px;">
    Ingreso Generado: <?php echo $Ingreso_generado . "BS"; ?><br>
    Ganancia Obtenida: <?php echo number_format($ganancia, 2, ",", ".") . "BS"; ?><br>
    Promedio: <?php echo number_format($promedio, 2, ",", ".") . "BS"; ?>
  </div>
  </div>
<div style="border-top: 1px solid black; position: absolute; bottom:-100px; width: 100%; text-align: center;">
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

$dompdf = new Dompdf();
$opcion = $dompdf->getOptions();
$opcion->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($opcion);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Ganancias.pdf", array("Attachment" => false));
?>
