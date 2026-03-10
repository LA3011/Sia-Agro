<?php 
session_start(); 
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();

try {
    $id = $_GET['id'];

    $sql = "DELETE FROM factura WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['Aceso'] = "Valido";
        $_SESSION['mensaje'] = "El registro se eliminó con éxito.";
        header("Location: ./../tabla_orden_salida.php");
        exit();
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar eliminar el registro.";
        header("Location: ./../tabla_orden_salida.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error de conexión: " . $e->getMessage();
    header("Location: ./../tabla_orden_salida.php");
    exit();
}

// Cerrar conexión
$conn = null;
?>
