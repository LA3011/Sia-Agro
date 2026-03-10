<?php
session_start();
include_once("../conexion/conexion.php");

// Llamada al método estático para establecer la conexión
$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error de conexión: " . pg_last_error());
}

// Asegurar que la conexión esté usando UTF-8
$conn->exec("SET NAMES 'UTF8'");

try {

    $espacio = $_POST['espacio'];
    list($poligono_id, $nombre_poligono) = explode('-', $espacio);

    $estatus = $_POST['estatus'];
    $recursos_hidricos = $_POST['recursos_hidricos'];
    $historial_uso = $_POST['historial_uso'];
    $observaciones = $_POST['observaciones'];
    $tipo_riego = $_POST['tipo_riego'];
    $sql = "INSERT INTO espacios (poligono_id, nombre_espacio, estatus, recursos_hidricos, historial_uso, observaciones, tipo_riego)
            VALUES (:poligono_id, :nombre_poligono, :estatus, :recursos_hidricos, :historial_uso, :observaciones, :tipo_riego)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':poligono_id', $poligono_id);
    $stmt->bindParam(':nombre_poligono', $nombre_poligono);
    $stmt->bindParam(':estatus', $estatus);
    $stmt->bindParam(':recursos_hidricos', $recursos_hidricos);
    $stmt->bindParam(':historial_uso', $historial_uso);
    $stmt->bindParam(':observaciones', $observaciones);
    $stmt->bindParam(':tipo_riego', $tipo_riego);
    $stmt->execute();
    $_SESSION['mensaje'] = "El registro se guardó con éxito.";
   
    $updateSql = "UPDATE poligono SET estado = 'inctivo' WHERE id = :poligono_id";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':poligono_id', $poligono_id);
    $updateStmt->execute();

    // Verificar si la actualización afectó alguna fila
    $_SESSION['mensaje'] = "El registro se guardó con éxito.";

    header("Location: ../Espacios.php");

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
