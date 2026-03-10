<?php

include("../conexion/conexion.php");
$conn = cconexion::ConexionBD();

// Obtener y validar el ID de la factura desde la URL
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($id <= 0) {
    echo "ID de factura inválido.";
    exit();
}

// Definir variables estáticas
$dir_tucupidos = "Calle Miranda C/C Comercio, Edificio Profesional Los Andes, Oficina 7-B Cagua Edo. Aragua"; // Ubicación
$tlf_tucupidos = "Telefonos: 0000-0000000 / 0000-0000000 ";  // Teléfonos de la empresa
$rif_cliente = "J-00000000-0";                               // RIF del cliente
$ubc_destino = "ZONA INDUSTRIAL CORINZA, TURMERO EDO. ARAGUA"; // DESTINO
$rif_tucupidos = "J-0000000-00";
date_default_timezone_set("America/Caracas");               // Definir zona horaria
$fecha_ejec = date("d-m-Y h.i.s a");                        // Almacenar fecha

// Utilizar sentencias preparadas para mayor seguridad
$queryBD = "SELECT * FROM factura WHERE id = :id";
$stmt = $conn->prepare($queryBD);
$stmt->bindParam(':id', $id, PDO::PARAM_INT); // Asumiendo que 'id' es de tipo entero

try {
    $stmt->execute();
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
    exit();
}

if ($fila) {
    $nro_orden    = $fila["numero"];              // Número de orden
    $fecha        = $fila["fecha"];               // Fecha de la orden
    $solicitud    = $fila["cliente"];             // Solicitud del cliente
    $serie        = $fila["serie"];               // Serie
    $depachador   = $fila["despachador"];         // Despachador
    $tip          = $fila["tipopublico"];         // Tipo/Público
    $cantidad     = $fila["cantidad_animales"];
    $razax        = $fila['descripcion'];         // Razas
} else {
    echo "No se encontró la factura con el ID proporcionado.";
    exit();
}

// Procesar las razas
$razax = explode(',', $razax);             // Convertir string a array
$tam = count($razax);                      // Contar elementos del array
$razax[$tam] = "";                         // Agregar elemento vacío al final
$arrayUnico = [];                          // Array para elementos únicos
$arrayRep = [];                            // Array para repeticiones
$contadorArray = 1;                        // Contador

for ($i = 0; $i < $tam; $i++) {
    if (isset($razax[$i + 1]) && ($razax[$i + 1] == $razax[$i])) {
        $contadorArray++;
    } else {
        $arrayUnico[] = $razax[$i];
        $arrayRep[] = $contadorArray;
        $contadorArray = 1;
    }
}

$limit = count($arrayUnico);
$string1 = "LOS TUCUPIDOS C.A.<br>"; 
$string2 = "<strong>Rif: J-0000000-00 </strong><br>"; 
$string3 = "<br>";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ORDEN DE SALIDA</title>
</head>
<body>
    <div style="margin-bottom: 0;">
        <p style="position: absolute; margin-left:10%; top:8px;">
            <strong style="font-size:30px;">LOS TUCUPIDOS C.A.</strong><br>
            <?php echo $string2 . $string3; ?>
        </p>
        <div>
            <img src="logo222.jpg" style="height:80px; display:inline-block;" alt="Logo">
        </div>
        <p style="display: inline-block; position: absolute; right:0px; top:89px;"> FECHA:
            <?php 
            $fecha_formateada = date('d-m-Y', strtotime($fecha)); 
            echo htmlspecialchars($fecha_formateada); 
            ?>
        </p>
        <div style="background-color: #A59F9F; text-align: center; border: 1px solid black;">
            <h1 style="margin: 0;">Orden de Salida</h1>
        </div>
        <br>
        <p style="margin: 0;">NOMBRE/RAZÓN SOCIAL: <?php echo htmlspecialchars($solicitud); ?></p>
        <p style="margin: 0;">RIF: <?php echo htmlspecialchars($rif_cliente); ?></p>
        <p style="margin: 0;">Dirección: <?php echo htmlspecialchars($ubc_destino); ?></p>
        <p style="margin: 0;">Despachado por: <?php echo htmlspecialchars($depachador); ?></p>
        <p style="margin-bottom:20px;">CONTROL DE SALIDA Nro. <?php echo htmlspecialchars($serie) . " - " . htmlspecialchars($nro_orden); ?></p>
        <div class="card-body">
        <table class="table table-bordered" style="border:1.5px solid #333; width: 80%; margin-left:auto; margin-right:auto;">

                <thead style="border-bottom:1px solid #333; background-color:#6AAD3E;">
                    <tr>
                        <th style="border-right:1px solid #333;">Descripción</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < $limit; $i++) { ?>
                        <tr>
                            <td style="border-right:0.5px solid #333; border-bottom:1px solid #333;">
                                <?php echo htmlspecialchars($arrayUnico[$i]); ?>
                            </td>
                            <td style="border-bottom:.5px solid #333;">
                                <?php echo htmlspecialchars($arrayRep[$i]); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div style="border-top: 1px solid black; position: absolute; bottom:-10px; width: 100%; text-align: center;">
            <strong>
                <?php 
                echo "Dirección: C/ Miranda, Edif. Los Andes<br>"; 
                echo "Tlf: 0412-000 00 00 / 0416-000 00 00"; 
                ?><br>
            </strong>
            Software para la Gestión Agrícola de la Hacienda Los Tucupidos<br>
            ©2023 Derechos Reservados. Sistema para U.P.T. Estado Aragua
            <br>
        </div>
    </div>
</body>
</html>

<?php
// Capturar el contenido HTML generado
$html = ob_get_clean();

// Incluir la biblioteca DOMPDF
require_once('../librerias/dompdf/autoload.inc.php');
use Dompdf\Dompdf;

// Instanciar y configurar DOMPDF
$dompdf = new Dompdf();
$options = $dompdf->getOptions();
$options->set(['isRemoteEnabled' => true]); // Permitir cargar imágenes remotas
$dompdf->setOptions($options);

// Cargar el HTML y renderizar el PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Enviar el PDF al navegador
$dompdf->stream("Orden_de_Salida_[" . $fecha_ejec . "].pdf", ["Attachment" => false]);
?>
