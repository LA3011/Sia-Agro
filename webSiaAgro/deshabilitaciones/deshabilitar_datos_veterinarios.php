<?php
session_start();
include_once("../conexion/conexion.php");

$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error de conexión: No se pudo conectar a la base de datos.");
}

$id = $_GET['id'];
$usuario = $_GET['session_acceso'];
$id_usuario = $_GET['session_id'];

$sql = "SELECT \"Precio\", \"Fecha_hora_registro\" FROM datos_veterinarios WHERE \"Id_Veterinario\" = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $precio = $row['precio'];
    $fecha = $row['fecha_hora_registro'];
    $fecha = date('Y-m-d', strtotime($fecha));
    $sql = "UPDATE inversion SET veterinario = veterinario - :precio WHERE fecha = :fecha";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':fecha', $fecha);
    if ($stmt->execute()) {
    } else {
        echo "Error en la consulta de actualización: " . $stmt->errorInfo();
    }
}

$sql = "DELETE FROM datos_veterinarios WHERE \"Id_Veterinario\" = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
if ($stmt->execute()) {
    $tabla = "Datos veterinarios";
    $numero_registro = $id;

    include("./../bitacora.php");
    $bitacora = new Bitacora($conn);
    $bitacora->deletebitacora($tabla, $usuario, $id_usuario, $numero_registro);

    $_SESSION['mensaje'] = "El registro se eliminó con éxito.";
} else {
    $_SESSION['mensaje'] = "Ocurrió un error al intentar eliminar el registro.";
}

header("Location: ./../datos_veterinarios.php");
?>
