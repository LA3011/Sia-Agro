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

try {
    $sql = "SELECT \"Precio\", \"Fecha_hora_registro\" FROM dieta_animal WHERE \"Id_Dieta\" = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $precio = $row['precio'];
        $fecha = $row['fecha_hora_registro'];

        $fecha = date('Y-m-d', strtotime($fecha));
        $sql = "UPDATE inversion SET dieta = dieta - :precio WHERE fecha = :fecha";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
    }

    $sql = "DELETE FROM dieta_animal WHERE \"Id_Dieta\" = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $tabla = "Dieta Animal";
    $numero_registro = $id;
    include("./../bitacora.php");
    $bitacora = new Bitacora($conn);
    $bitacora->deletebitacora($tabla, $usuario, $id_usuario, $numero_registro);

    $_SESSION['mensaje'] = "El registro se eliminó con éxito.";
} catch(PDOException $e) {
    $_SESSION['mensaje'] = "Ocurrió un error al intentar eliminar el registro: " . $e->getMessage();
}

header("Location: ./../dieta_animal.php");
exit;
?>
