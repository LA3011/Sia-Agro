<?php 
session_start(); 
include_once("../conexion/conexion.php");

$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error de conexión: No se pudo conectar a la base de datos.");
}
if(isset($_GET['id'])) {
    $id_reproduccion = $_GET['id'];

$sql = "DELETE FROM reproduccion WHERE \"Id_reproduccion\" = :id_reproduccion";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_reproduccion', $id_reproduccion, PDO::PARAM_INT);

if ($stmt->execute()) {
    $tabla = "Reproducion Animal";
    $numero_registro = $id_reproduccion;
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

header("Location: ./../reproducciones.php");
exit();
$conn = null;
?>