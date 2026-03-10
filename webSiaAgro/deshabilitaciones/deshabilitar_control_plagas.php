<?php
session_start();
include_once("../conexion/conexion.php");

// Establecer conexión con PDO para PostgreSQL
$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error al conectar a la base de datos.");
}

$id = $_GET['id'];
$usuario = $_GET['session_acceso'];
$id_usuario = $_GET['session_id'];

try {
    // Consulta para deshabilitar el registro en lugar de eliminarlo
    $sql = "UPDATE control_plagas SET estado = 'inactivo' WHERE \"Id_plagas\" = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $tabla = "Control Plagas";
        $numero_registro = $id;

        // Incluir el archivo bitacora.php
        include("./../bitacora.php");

        // Crear una instancia de la clase Bitacora
        $bitacora = new Bitacora($conn);

        // Llamar al método para registrar la acción en la bitácora
        $bitacora->deletebitacora($tabla, $usuario, $id_usuario, $numero_registro);

        $_SESSION['mensaje'] = "El registro se eliminino con éxito.";
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar deshabilitar el registro.";
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
}

// Redirigir al usuario
header("Location: ./../control_plagas.php");
?>
