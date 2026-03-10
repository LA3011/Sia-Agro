<?php 
include("../conexion/conexion.php"); // Archivo de conexión PDO para PostgreSQL
$conn = cconexion::ConexionBD(); // Obtener la conexión PDO

// Variables obtenidas de POST
$fi = $_POST['fechaInicio'];
$ff = $_POST['fechaFinal']; 
$inv = $_POST['inv'];
$Ingreso_generado = $_POST['ingreso_poc']; 
$ganancia = $_POST['total_inv'];
$promedio = $_POST['promedio'];
$iterador = 1;

$fi2 = date("d/m/Y", strtotime($_POST['fechaInicio']));
$ff2 = date("d/m/Y", strtotime($_POST['fechaFinal']));

// $string1 = "Direccion: C/ Miranda, Edif. Los Andes <br>"; 
// $string2 = "Rif: J-0000000-00 <br>"; 
// $string3 = "Tlf: 0412-000 00 00 <br>"; 

$string1 = "LOS TUCUPIDOS C.A.<br>"; 
$string2 = "<strong>Rif: J-0000000-00 </strong><br>"; 
$string3 = "<br>";

?> 
<body style="margin-left:10%; margin-right:8%; margin-bottom:8%; margin-top:2%; padding-bottom:2%;">
  <p style="position: absolute; margin-left:10%; top:-10px;"><strong style="font-size:30px;">LOS TUCUPIDOS C.A.</strong><br><?php echo $string2.$string3; ?></p><div><img src="logo222.jpg" style="height:80px; display:inline-block;"></div><br><div style="position: absolute; right:0px; top:9%;"> FECHA: <?php echo  $fi2 . " - ";?><?php echo $ff2; ?></div><div style="background-color: #A59F9F; text-align: center; border: 1px solid black; margin-top: 5px;"><h1 style="margin: 0;">
        Inversión Cultivos</h1></div><br><br><table class="table table-bordered" style="border:1.5px solid #333; margin-left:0px; position: relative; top: -25px;"><thead style="border-bottom:1px solid #333; background-color:#6AAD3E ;">
        <tr>
          <th> Nro     </th>
          <th>  Fecha    </th>
          <th>Inv. Fertilizante   </th>
          <th>Inv. Funguisida   </th>
          <th>Inv. Semillas   </th>
          <th>Inv. Equipos</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Usando PDO para la consulta a la base de datos PostgreSQL
        $queryBD = "SELECT * FROM inversion_cultivos WHERE fecha BETWEEN :fi AND :ff";
        $stmt = $conn->prepare($queryBD);
        $stmt->bindParam(':fi', $fi);
        $stmt->bindParam(':ff', $ff);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          ?>
          <tr>
            <td>
              <?php echo "  " . $iterador; ?>
            </td>
            <td>
              <?php echo date("d/m/Y", strtotime($row['fecha'])); ?>
            </td>

            <td>
              <?php echo "     " . number_format($row['fertilizante'], 2, ",", "."); ?>
            </td>

            <td>
              <?php echo "     " . number_format($row['semillas'], 2, ",", "."); ?>
            </td>

            <td>
              <?php echo "     " . number_format($row['funguisida'], 2, ",", "."); ?>
            </td>

            <td>
              <?php echo "     " . number_format($row['equipos'], 2, ",", "."); ?>
            </td>

          </tr>
          <?php $iterador++; } ?>
        </tbody>
      </table>
    <div style="border-left:1.5px solid #333; border-right:1.5px solid #333; border-bottom:1.5px solid #333; margin-left:0px; position: relative; top: -25px; width:;">
         Inversión Realizada:  <?php echo number_format($inv, 2, ",", ".") . "Bs"; ?>  <br>  Ingreso (%):  <?php echo number_format($Ingreso_generado, 2, ",", ".") . "Bs"; ?> <br>  Total Inversión: <?php echo number_format($ganancia, 2, ",", ".") . "Bs"; ?> <br>  Promedio:          <?php echo number_format($promedio, 2, ",", ".") . "$"; ?>
    </div>
  </div>
<div style="border-top: 1px solid black; position: absolute; bottom:-100px; width: 100%; text-align: center;">
  <strong>
    <?php echo "Direccion: C/ Miranda, Edif. Los Andes <br> "; ?>
    <?php echo "Tlf: 0412-000 00 00 / 0416-000 00 00"; ?><br>
  </strong>
  Software para la Gestión Agrícola de la Hacienda los Tucupidos<br>©2023 Derechos Reservados. Sistema para U.P.T. Estado Aragua
<br>
</div>
  </body>
  </html>

  <?php
//! DOMPDF
  $html = ob_get_clean();
require_once('../librerias/dompdf/autoload.inc.php');        //incluimos el archivo DOM PDF [permitiendo crear un obj. con funcionalidades de reconversión]
use Dompdf\Dompdf;                                          //haciendo uso de opción
$dompdf = new Dompdf();                                     //instanciando clase
// configurarlo para mostrar imágenes, que sería la tabla
$options = $dompdf->getOptions();                            //recuperando la opción
$options->set(array('isRemoteEnabled' => true));               //activando [mostrar imágenes]
$dompdf->setOptions($options);                               //pasar nuevamente mostrándolo
$dompdf->loadHtml($html);                                   //cargar el HTML
$dompdf->setPaper('A4', 'portrait');                         //formato 
$dompdf->render();                              
$dompdf->stream("Inversion_Cultivo.pdf", array("Attachment" => false)); //modo de DESCARGA 
?>
