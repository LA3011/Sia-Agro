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
    // Iniciar una transacción
    $conn->beginTransaction();

    // Obtener el precio y fecha de registro
    $sql = "SELECT total_costo, \"Fecha_hora_registro\" FROM comida_animal WHERE id_comida = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $precio = $row['total_costo'];
        $fecha = date('Y-m-d', strtotime($row['Fecha_hora_registro']));

        // Actualizar la tabla inversion
        $sql = "UPDATE inversion SET comida = comida - :precio WHERE fecha = :fecha";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->execute();
    }

    // Eliminar registro de comida_animal
    $sql = "DELETE FROM comida_animal WHERE id_comida = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        include("./../bitacora.php");
        $bitacora = new Bitacora($conn);
        $bitacora->deletebitacora("Comida Animal", $usuario, $id_usuario, $id);
        $_SESSION['mensaje'] = "El registro se eliminó con éxito.";
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar eliminar el registro.";
    }

    // Confirmar la transacción
    $conn->commit();

    // Redirigir después de la operación
    header("Location: ./../comida_animal.php");
    exit();

} catch (PDOException $e) {
    // Revertir la transacción en caso de error
    $conn->rollBack();
    error_log("Error al ejecutar la consulta: " . $e->getMessage(), 3, "/var/log/php_errors.log");
    $_SESSION['mensaje'] = "Ocurrió un error: " . $e->getMessage();
    header("Location: ./../comida_animal.php");
    exit();
}

// Cerrar la conexión a la base de datos
$conn = null;
?>
