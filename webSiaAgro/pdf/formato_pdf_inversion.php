<!DOCTYPE html>
<html lang="es">
<head>
  <title>INVERSION CULTIVOS</title>
</head>
<?php 
include("../conexion/conexion.php"); // Archivo de conexión PDO para PostgreSQL
$conn = cconexion::ConexionBD(); // Obtener la conexión PDO

// Variables obtenidas de POST
$fi = $_POST['fechaInicio'];
$ff = $_POST['fechaFinal'];

$gan = $_POST['gan'];
$inv = $_POST['inv']; 
$Ingreso_generado = $_POST['ingreso_poc']; 
$ganancia = $_POST['total_inv'];
$promedio = $_POST['promedio'];
$iterador = 1;

// Convertir las fechas al formato deseado
$fi2 = date("d/m/Y", strtotime($_POST['fechaInicio']));
$ff2 = date("d/m/Y", strtotime($_POST['fechaFinal']));

// Encabezado de la tabla
$string1 = "LOS TUCUPIDOS C.A.<br>"; 
$string2 = "<strong>Rif: J-0000000-00 </strong><br>"; 
$string3 = "<br>";
?> 
<body style="margin-left:10%; margin-right:8%; margin-bottom:8%; margin-top:2%; padding-bottom:2%;">
  <p style="position: absolute; margin-left:10%; top:-10px;">
    <strong style="font-size:30px;">LOS TUCUPIDOS C.A.</strong><br><?php echo $string2.$string3; ?>
  </p>
  <div><img src="logo222.jpg" style="height:80px; display:inline-block;"></div>
  <div style="position: absolute; right:0px; top:8%;">
    FECHA:  <?php echo $fi2 . " - " . $ff2; ?>
  </div>
  <div style="background-color: #A59F9F; text-align: center; border: 1px solid black;">
    <h1 style="margin: 0;">Inversión Cultivos</h1>
  </div>
  <br><br><br>
  <table class="table table-bordered" style="border:1.5px solid #333; margin-left:37px; position: relative; top: -35px;">
    <thead style="border-bottom:1px solid #333; background-color:#6AAD3E;">
      <tr>
        <th> Nro     </th>
        <th>    Fecha    </th>
        <th>Inver. Veterinaria    </th>
        <th>Inver. Dieta    </th>
        <th>Inver. Comida    </th> 
      </tr>
    </thead>
    <tbody>
      <?php
      try {
        // Preparar la consulta con parámetros
        $queryBD = "SELECT * FROM inversion WHERE fecha BETWEEN :fi AND :ff";
        $stmt = $conn->prepare($queryBD);
        $stmt->bindParam(':fi', $fi);
        $stmt->bindParam(':ff', $ff);
        $stmt->execute();

        // Recorrer los resultados
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      ?>
      <tr>
        <td><?php echo $iterador; ?></td>
        <td><?php echo date("d/m/Y", strtotime($row['fecha'])); ?></td>
        <td><?php echo "          " . number_format($row['veterinario'], 2, ",", "."); ?></td>
        <td><?php echo "          " . number_format($row['dieta'], 2, ",", "."); ?></td>
        <td><?php echo "          " . number_format($row['comida'], 2, ",", "."); ?></td>
      </tr>
      <?php
          $iterador++;
        }
      } catch (PDOException $e) {
        echo "<tr><td colspan='5'>Error: " . $e->getMessage() . "</td></tr>";
      }
      ?>
    </tbody>
  </table>
  <div style="border-left:1.5px solid #333; border-right:1.5px solid #333; border-bottom:1.5px solid #333; margin-left:37px; position: relative; top: -35px; width: 503px;">
    Ganancia Generada: <?php echo $gan; ?><br>
    Inversión Realizada: <?php echo $inv; ?><br>
    Ingreso (%): <?php echo $Ingreso_generado; ?><br>
    Promedio: <?php echo number_format($promedio, 2, ",", ".") . "Bs"; ?><br>
    Total Inversión: <?php echo number_format($ganancia, 2, ",", ".") . "Bs"; ?>
  </div>
  <div style="border-top: 1px solid black; position: absolute; bottom:-100px; width: 100%; text-align: center;">
    <strong>
      Dirección: C/ Miranda, Edif. Los Andes <br>
      Tlf: 0412-000 00 00 / 0416-000 00 00<br>
    </strong>
    Software para la Gestión Agrícola de la Hacienda los Tucupidos<br>©2023 Derechos Reservados. Sistema para U.P.T. Estado Aragua
  </div>
</body>
</html>

<?php
//! DOMPDF
$html = ob_get_clean();
require_once('../librerias/dompdf/autoload.inc.php');
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Inversion_Animal.pdf", array("Attachment" => false));
?>
