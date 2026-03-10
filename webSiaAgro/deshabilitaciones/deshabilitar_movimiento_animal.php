<?php 
session_start(); 
include_once("../conexion/conexion.php");

$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error de conexión: No se pudo conectar a la base de datos.");
}
if(isset($_GET['id'])) {
    $id_actividad = $_GET['id'];

$sql = "DELETE FROM actividad_animal WHERE \"Id_Actividad\" = :id_actividad";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_actividad', $id_actividad, PDO::PARAM_INT);

if ($stmt->execute()) {
    $tabla = "Actividad Animal";
    $numero_registro = $id_actividad;
    $usuario = $_GET['session_acceso'];
    $id_usuario = $_GET['session_id'];
    include("./../bitacora.php");
    $bitacora = new Bitacora($conn);
    $bitacora->deletebitacora($tabla, $usuario, $id_usuario, $numero_registro);

    $_SESSION['mensaje'] = "El registro se eliminó con éxito.";
} else {
    $_SESSION['mensaje'] = "Ocurrió un error al intentar eliminar el registro: " . $stmt->errorInfo()[2];
}
} else {
$_SESSION['mensaje'] = "Error: No se proporcionó el ID del animal para eliminar.";
}
header("Location: ./../movimiento_animal.php");
exit();
$conn = null;
?>