<?php
session_start();
include_once("../conexion/conexion.php");

$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error de conexión: No se pudo conectar a la base de datos.");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['mensaje'] = "ID inválido o no proporcionado.";
    header("Location: ./../empleados.php");
    exit;
}

$id = $_GET['id'];
$usuario = $_GET['session_acceso'];
$id_usuario = $_GET['session_id'];

try {
    // Preparar la consulta SQL para eliminar el registro
    $sql = "DELETE FROM empleados WHERE \"Id_empleados\" = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Ejecutar la consulta
    $stmt->execute();

    // Comprobar si la eliminación fue exitosa
    if ($stmt->rowCount() > 0) {
        $tabla = "Empleados";
        $numero_registro = $id;

        // Incluir el archivo bitacora.php
        include("../bitacora.php");

        // Crear una instancia de la clase Bitacora
        $bitacora = new Bitacora($conn);

        // Llamar al método deletebitacora() con los argumentos correctos
        $bitacora->deletebitacora($tabla, $usuario, $id_usuario, $numero_registro);

        $_SESSION['mensaje'] = "El registro se eliminó con éxito.";
    } else {
        $_SESSION['mensaje'] = "No se encontró el registro a eliminar.";
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Ocurrió un error al intentar eliminar el registro: " . $e->getMessage();
}

header("Location: ./../empleados.php");
exit;
?>
