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
    // Obtener cantidad_adquirida, precio_unitario y Fecha
    $sql = "SELECT cantidad_adquirida, precio_unitario, fecha 
            FROM semillas 
            WHERE id_semillas = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $cantidad_adquirida = $row['cantidad_adquirida'];
        $precio_unitario = $row['precio_unitario'];
        $fecha = $row['fecha'];

        // Realizar cálculo
        $resultado = $cantidad_adquirida * $precio_unitario;

        // Extraer solo la parte de la fecha
        $fecha = date('Y-m-d', strtotime($fecha));

        // Actualizar tabla inversiones
        $sql = "UPDATE inversion_cultivos 
                SET semillas = semillas - :resultado 
                WHERE fecha = :fecha";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':resultado', $resultado, PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->execute();
    }

    // Deshabilitar registro en lugar de eliminar
    $sql = "UPDATE semillas SET activo = FALSE WHERE id_semillas = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $tabla = "Semillas";
        $numero_registro = $id;

        // Incluir el archivo bitacora.php
        include("./../bitacora.php");

        // Crear una instancia de la clase Bitacora
        $bitacora = new Bitacora($conn);

        // Llamar al método deletebitacora() con los argumentos correctos
        $bitacora->deletebitacora($tabla, $usuario, $id_usuario, $numero_registro);

        $_SESSION['mensaje'] = "El registro se elimino con éxito.";
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar deshabilitar el registro.";
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error de base de datos: " . $e->getMessage();
}

header("Location: ./../semillas.php");

// Cerrar la conexión
$conn = null;
?>
