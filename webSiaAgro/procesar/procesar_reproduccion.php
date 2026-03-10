<?php
session_start();
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}
$Nombre_hembra = $_POST['Nombre_hembra'];
$Nombre_macho = $_POST['Nombre_macho'];
$Tipo_reproducción = $_POST['Tipo_reproducción'];
$Tipo_fertilizacion = $_POST['Tipo_fertilizacion'];
$Fecha_revision = $_POST['Fecha_revision'];
$Fecha_parto = $_POST['Fecha_parto'];
$Encargado = $_POST['Encargado'];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

$sql = "INSERT INTO reproduccion (\"Nombre_hembra\", \"Nombre_macho\", \"tipo_reproducción\", \"Tipo_fertilizacion\", \"Fecha_revision\", \"Fecha_parto\", encargado)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->execute([$Nombre_hembra, $Nombre_macho, $Tipo_reproducción, $Tipo_fertilizacion, $Fecha_revision, $Fecha_parto, $Encargado]);
if ($stmt) {
$numero_registro = $stmt->rowCount();
    $tabla = "Reproducciones";
    include("../bitacora.php");
    $bitacora = new Bitacora($conn);
    $bitacora->insertbitacora($tabla, $usuario, $id_usuario, $numero_registro);
    $_SESSION['mensaje'] = "El registro se guardó con éxito.";
} else {
    echo "Error al insertar en la base de datos: " . $stmt->errorInfo()[2];
}
$conn = null;
header("Location: ../reproducciones.php");
exit;