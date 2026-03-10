<?php
session_start();
include("../conexion/conexion.php");

$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error al conectar a la base de datos.");
}

// Habilitar el modo de errores de PDO
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_GET['id'] ?? null;
$usuario = $_GET['session_acceso'] ?? null;
$id_usuario = $_GET['session_id'] ?? null;

if (!$id) {
    die("Error: No se proporcionó un ID válido.");
}

try {
    // Depuración: Verificar el ID recibido
    echo "ID recibido: $id<br>";

    // Consultar el campo id_espacios de la tabla cultivos
    $consultaEspacios = "SELECT id_espacio FROM cultivos WHERE \"ID\" = :id";
    $stmtEspacios = $conn->prepare($consultaEspacios);
    $stmtEspacios->bindParam(':id', $id, PDO::PARAM_INT);
    $stmtEspacios->execute();
    $resultadoEspacios = $stmtEspacios->fetch(PDO::FETCH_ASSOC);

    if (!$resultadoEspacios) {
        die("Error: No se encontró un registro con el ID proporcionado.");
    }

    echo "id_espacio encontrado: " . $resultadoEspacios['id_espacio'] . "<br>";

    // Actualizar el campo estado de la tabla espacios a TRUE
    $id_espacio = $resultadoEspacios['id_espacio'];
    $actualizarEstado = "UPDATE espacios SET estado = TRUE WHERE \"Id_espacios\" = :id_espacio";
    $stmtActualizar = $conn->prepare($actualizarEstado);
    $stmtActualizar->bindParam(':id_espacio', $id_espacio, PDO::PARAM_INT);

    if ($stmtActualizar->execute()) {
        echo "Estado actualizado correctamente para id_espacio: $id_espacio<br>";
    } else {
        die("Error: No se pudo actualizar el estado del espacio.");
    }

    // Eliminar el registro de la tabla cultivos
    $sql = "DELETE FROM cultivos WHERE \"ID\" = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Registro eliminado correctamente para ID: $id<br>";

        // Registrar en la bitácora
        $tabla = "Cultivos";
        $numero_registro = $id;

        include("./../bitacora.php");
        $bitacora = new Bitacora($conn);

        try {
            $bitacora->deletebitacora($tabla, $usuario, $id_usuario, $numero_registro);
        } catch (PDOException $e) {
            echo "Error al insertar registro en la bitácora: " . $e->getMessage();
            exit();
        }

        $_SESSION['mensaje'] = "El registro se ha eliminado con éxito y el estado del espacio se actualizó a 'TRUE'.";
    } else {
        die("Error: No se pudo eliminar el registro de la tabla cultivos.");
    }
} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}

// Redirigir al usuario
header("Location: ./../cultivos.php");
exit;
?>catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}

// Redirigir al usuario
header("Location: ./../cultivos.php");
exit;
?>