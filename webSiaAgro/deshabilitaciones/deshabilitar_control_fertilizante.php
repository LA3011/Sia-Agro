<?php
session_start();
include_once("../conexion/conexion.php");

// Establecer conexión con la base de datos
$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error al conectar a la base de datos.");
}

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Recibir datos del GET
$id = $_GET['id'];
$usuario = $_GET['session_acceso'];
$id_usuario = $_GET['session_id'];

try {
    // Preparar la consulta SQL para eliminar el registro
    $sql = "DELETE FROM control_fertilizante WHERE \"Id_fertilizante\" = :id";
    $stmt = $conn->prepare($sql);

    // Vincular el parámetro
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $tabla = "Control Fertilizante";
        $numero_registro = $id;

        // Incluir el archivo bitacora.php
        include("./../bitacora.php");

        // Crear una instancia de la clase Bitacora
        $bitacora = new Bitacora($conn);

        // Llamar al método deletebitacora() con los argumentos correctos
        $bitacora->deletebitacora($tabla, $usuario, $id_usuario, $numero_registro);

        // Guardar mensaje en la sesión
        $_SESSION['mensaje'] = "El registro se eliminó con éxito.";
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar eliminar el registro.";
    }
} catch (PDOException $e) {
    // Manejo de errores
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
}

// Redirigir al usuario
header("Location: ./../control_fertilizante.php");
?>
