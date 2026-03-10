<?php
session_start();
include_once("../conexion/conexion.php");

// Establecer conexión con la base de datos
$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error de conexión: No se pudo conectar a la base de datos.");
}

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Recibir datos de la solicitud
$id = $_GET['id'];
$usuario = $_GET['session_acceso'];
$id_usuario = $_GET['session_id'];

try {
    // Actualizar el estado del registro a inactivo (deshabilitar)
    $sql = "UPDATE actividades SET \"activo\" = FALSE WHERE \"Id_actividades\" = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $tabla = "Actividades de Cultivos";
        $numero_registro = $id;

        // Incluir el archivo bitacora.php
        include("./../bitacora.php");

        // Crear una instancia de la clase Bitacora
        $bitacora = new Bitacora($conn);

        // Llamar al método deletebitacora() con los argumentos correctos
        $bitacora->deletebitacora($tabla, $usuario, $id_usuario, $numero_registro);

        // Guardar mensaje en la sesión
        $_SESSION['mensaje'] = "El registro se deshabilitó con éxito.";
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar deshabilitar el registro.";
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
}

// Redirigir al usuario
header("Location: ./../actividades_cultivos.php");

// Cerrar conexión
$conn = null;
?>
