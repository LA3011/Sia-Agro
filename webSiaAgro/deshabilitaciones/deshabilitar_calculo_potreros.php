<?php 
session_start(); 
include_once("../conexion/conexion.php");

// Llamada al método estático para establecer la conexión
$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error de conexión: No se pudo conectar a la base de datos.");
}

if(isset($_GET['id'])) {
$Id_potreros = $_GET['id'];
$sql = "DELETE FROM potreros WHERE \"Id_potreros\" = :Id_potreros";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':Id_potreros', $Id_potreros, PDO::PARAM_INT);

if ($stmt->execute()) {
    $tabla = "Potreros";
    $numero_registro = $Id_potreros;
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
header("Location: ./../calculo_potreros.php");
exit();
$conn = null;
?>