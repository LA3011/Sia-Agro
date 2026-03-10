<?php
 include("../conexion/conexion.php");
 $conn = cconexion::ConexionBD();

// Iniciar el almacenamiento en búfer
ob_start();

// Obtener y validar el ID del potrero desde la URL
$potrero_id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($potrero_id <= 0) {
    echo "ID de potrero inválido.";
    exit();
}

// Definir variables estáticas
$tlf_tucupidos = "Telefonos: 0000-0000000 / 0000-0000000 ";  // Teléfonos de la empresa
$rif_tucupidos = "J-0000000-0";
date_default_timezone_set("America/Caracas");               // Definir zona horaria
$fecha_ejec = date("d-m-Y h.i.s a");                        // Almacenar fecha
$fecha = date("d-m-Y");

// Variables para encabezado
$string1 = "LOS TUCUPIDOS C.A.<br>"; 
$string2 = "<strong>Rif: J-0000000-00 </strong><br>"; 
$string3 = "<br>";

// Consulta para obtener los datos del potrero específico
$sql_potrero = "SELECT \"Nombre\", \"Cantidad_dias_verdes\", \"Cantidad_dias_secos\",\"Fecha_hora_registro\" FROM potreros WHERE \"Id_potreros\" = :potrero_id";
$stmt_potrero = $conn->prepare($sql_potrero);
$stmt_potrero->bindParam(':potrero_id', $potrero_id, PDO::PARAM_INT);

try {
    $stmt_potrero->execute();
    $row_potrero = $stmt_potrero->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error en la consulta del potrero: " . $e->getMessage();
    exit();
}

if ($row_potrero) {
    $nombre_potrero = $row_potrero['Nombre'];
    $cantidad_dias_verdes = $row_potrero['Cantidad_dias_verdes'];
    $cantidad_dias_secos = $row_potrero['Cantidad_dias_secos'];
    $fecha_registro = $row_potrero['Fecha_hora_registro'];
} else {
    echo "No se encontró información del potrero con ID: $potrero_id";
    exit();
}

// Calcula las fechas en las que los lotes de animales pueden estar en el potrero
$fechas_disponibles = array();
$fecha_inicio = date('Y-m-d', strtotime("$fecha_registro +1 day"));
$fecha_final = date('Y-m-d', strtotime("$fecha_inicio +$cantidad_dias_verdes days"));
$fechas_disponibles[] = array(
    'inicio' => $fecha_inicio,
    'final' => $fecha_final
);

// Genera 30 fechas adicionales
for ($i = 0; $i < 30; $i++) { 
    $fecha_inicio = date('Y-m-d', strtotime("$fecha_final +$cantidad_dias_secos days"));
    $fecha_final = date('Y-m-d', strtotime("$fecha_inicio +$cantidad_dias_verdes days"));
    $fechas_disponibles[] = array(
        'inicio' => $fecha_inicio,
        'final' => $fecha_final
    );
}

// Obtener todos los lotes disponibles
$sql_lotes = "SELECT nombre, numero FROM lotes";
$stmt_lotes = $conn->prepare($sql_lotes);

try {
    $stmt_lotes->execute();
    $result_lotes = $stmt_lotes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error en la consulta de lotes: " . $e->getMessage();
    exit();
}

if ($result_lotes && count($result_lotes) > 0) {
    $lotes = $result_lotes;
} else {
    $lotes = array();
}

// Comienza la generación del HTML
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PDF</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            position: relative;
            margin: 0;
            padding: 0;
        }
        .header {
            margin-bottom: 0;
        }
        .header p {
            position: absolute;
            margin-left:10%;
            top:8px;
            font-size: 30px;
            font-weight: bold;
        }
        .header img {
            height:80px;
            display:inline-block;
        }
        .fecha {
            position: absolute;
            right:0px;
            top:89px;
            font-size: 14px;
        }
        .titulo {
            background-color: #A59F9F;
            text-align: center;
            border: 1px solid black;
            padding: 10px 0;
            margin-top: 100px; /* Ajusta según sea necesario */
        }
        .contenido {
          
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table thead tr {
            background-color: #6AAD3E;
            border-bottom:1px solid #333;
        }
        table thead th {
            border:1px solid #333;
            padding: 10px;
            text-align: left;
            font-size: 16px;
        }
        table tbody td {
            border:1px solid #333;
            padding: 8px;
            font-size: 14px;
        }
        .footer {
            border-top: 1px solid black;
            position: absolute;
            bottom:-10px;
            width: 100%;
            text-align: center;
            font-size: 12px;
            padding: 10px 0;
        }
    </style>
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
            <h1 style="margin: 0;">Pastoreo</h1>
        </div>
    <div class="contenido">
        <table class="table table-bordered" style=" text-align: center; ">
            <thead>
                <tr>
                    <th>Lotes</th>
                    <th>N° Lote</th>
                    <th>Fecha-inicio</th>
                    <th>Fecha-final</th>
                    <th>Recorrido</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($lotes) > 0) {
                    foreach ($fechas_disponibles as $fecha_disponible) {
                        // Seleccionar un lote aleatorio para cada fecha
                        $lote_aleatorio = $lotes[array_rand($lotes)];
                        $lote = $lote_aleatorio['nombre'];
                        $numero = $lote_aleatorio['numero'];

                        // Verifica si la fecha ya ha pasado
                        $fecha_actual = date('Y-m-d');
                        $fecha_final_lote = $fecha_disponible['final'];
                        $fecha_pasada = ($fecha_actual > $fecha_final_lote);

                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($lote) . '</td>';
                        echo '<td>' . htmlspecialchars($numero) . '</td>';
                        echo '<td>' . htmlspecialchars(date("d/m/Y", strtotime($fecha_disponible['inicio']))) . '</td>';
                        echo '<td>' . htmlspecialchars(date("d/m/Y", strtotime($fecha_disponible['final']))) . '</td>';

                        // Agrega el punto verde o rojo según corresponda si se cumplió el pastoreo
                        if ($fecha_pasada) {
                            echo '<td>&#10004;</td>'; // Usar símbolo de check
                        } else {
                            echo '<td>&#10060;</td>'; // Usar símbolo de X
                        }
                        echo '</tr>';
                    }
                } else {
                    echo '<tr>';
                    echo '<td colspan="5">No hay lotes disponibles</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="footer">
        <strong>
            Dirección: C/ Miranda, Edif. Los Andes<br>
            Tlf: 0412-000 00 00 / 0416-000 00 00<br>
        </strong>
        Software para la Gestión Agrícola de la Hacienda Los Tucupidos<br>
        ©2023 Derechos Reservados. Sistema para U.P.T. Estado Aragua
    </div>
</body>
</html>

<?php
// Capturar el contenido HTML generado
$html = ob_get_clean();

// Incluir la biblioteca DOMPDF
require_once('../librerias/dompdf/autoload.inc.php');
use Dompdf\Dompdf;
use Dompdf\Options;

// Instanciar y configurar DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true); // Permitir cargar imágenes remotas
$options->set('defaultFont', 'DejaVu Sans'); // Establecer DejaVu Sans como fuente predeterminada
$dompdf = new Dompdf($options);

// Cargar el HTML y renderizar el PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Enviar el PDF al navegador
$dompdf->stream("Pastoreo_[" . $fecha_ejec . "].pdf", ["Attachment" => false]);
?>
