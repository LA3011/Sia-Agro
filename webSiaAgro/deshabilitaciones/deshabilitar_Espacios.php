<?php
session_start();
include("../conexion/conexion.php");
$conn = cconexion::ConexionBD(); 

header('Content-Type: application/json'); 

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID de espacio no válido.']);
        exit;
    }
    $id_espacio = intval($_GET['id']);

    // Verificar si hay cultivos activos en el espacio
    $consultaCultivosActivos = "SELECT COUNT(*) AS total FROM cultivos WHERE id_espacio = :id_espacio AND estado = 'true'";
    $stmtCultivosActivos = $conn->prepare($consultaCultivosActivos);
    $stmtCultivosActivos->bindParam(':id_espacio', $id_espacio, PDO::PARAM_INT);
    $stmtCultivosActivos->execute();
    $resultadoCultivosActivos = $stmtCultivosActivos->fetch(PDO::FETCH_ASSOC);

    if ($resultadoCultivosActivos['total'] > 0) {
        echo json_encode(['success' => false, 'message' => 'El espacio no se puede deshabilitar porque tiene cultivos activos.']);
        exit;
    }

    // Verificar si hay cultivos inactivos en el espacio
    $consultaCultivosInactivos = "SELECT COUNT(*) AS total FROM cultivos WHERE id_espacio = :id_espacio AND estado = 'false'";
    $stmtCultivosInactivos = $conn->prepare($consultaCultivosInactivos);
    $stmtCultivosInactivos->bindParam(':id_espacio', $id_espacio, PDO::PARAM_INT);
    $stmtCultivosInactivos->execute();
    $resultadoCultivosInactivos = $stmtCultivosInactivos->fetch(PDO::FETCH_ASSOC);

    if ($resultadoCultivosInactivos['total'] > 0) {
        // Verificar si los cultivos inactivos ya fueron cosechados
        $consultaCosechados = "SELECT COUNT(*) AS total FROM cultivos WHERE id_espacio = :id_espacio AND estado = 'false' AND cosechado = 'true'";
        $stmtCosechados = $conn->prepare($consultaCosechados);
        $stmtCosechados->bindParam(':id_espacio', $id_espacio, PDO::PARAM_INT);
        $stmtCosechados->execute();
        $resultadoCosechados = $stmtCosechados->fetch(PDO::FETCH_ASSOC);

        if ($resultadoCosechados['total'] == $resultadoCultivosInactivos['total']) {
            // Todos los cultivos inactivos ya fueron cosechados, se puede deshabilitar
            $actualizarEspacio = "UPDATE espacios SET deshabilitar = true WHERE \"Id_espacios\" = :id_espacio";
            $stmtActualizarEspacio = $conn->prepare($actualizarEspacio);
            $stmtActualizarEspacio->bindParam(':id_espacio', $id_espacio, PDO::PARAM_INT);

            if ($stmtActualizarEspacio->execute()) {
                if ($stmtActualizarEspacio->rowCount() > 0) {
                    echo json_encode(['success' => true, 'message' => 'El espacio se deshabilitó correctamente porque todos los cultivos inactivos ya fueron cosechados.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'No se encontró ningún registro para actualizar en la tabla espacios.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta SQL para actualizar el campo deshabilitar.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'El espacio no se puede deshabilitar porque tiene cultivos inactivos que no han sido cosechados.']);
        }
        exit;
    }

    // Si no hay registros en cultivos, se puede deshabilitar
    $actualizarEspacio = "UPDATE espacios SET deshabilitar = true WHERE \"Id_espacios\" = :id_espacio";
    $stmtActualizarEspacio = $conn->prepare($actualizarEspacio);
    $stmtActualizarEspacio->bindParam(':id_espacio', $id_espacio, PDO::PARAM_INT);

    if ($stmtActualizarEspacio->execute()) {
        if ($stmtActualizarEspacio->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'El espacio se deshabilitó correctamente porque no tiene registros en cultivos.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró ningún registro para actualizar en la tabla espacios.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta SQL para actualizar el campo deshabilitar.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

// No redirigir a ninguna página
$conn = null;
?>