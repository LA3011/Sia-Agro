<?php
session_start();
include_once("../conexion/conexion.php");

try {
    $conn = cconexion::ConexionBD();

    if (isset($_GET['id'])) {
        $id_animal = intval($_GET['id']);

        // Iniciar una transacción
        $conn->beginTransaction();

        // Eliminar registros relacionados en otras tablas
        $sql_reproduccion = "DELETE FROM reproduccion WHERE \"Id_reproduccion\" = :id_animal";
        $stmt_reproduccion = $conn->prepare($sql_reproduccion);
        $stmt_reproduccion->bindParam(':id_animal', $id_animal, PDO::PARAM_INT);
        $stmt_reproduccion->execute();

        $sql_dieta = "DELETE FROM datos_veterinarios WHERE \"Id_Veterinario\" = :id_animal";
        $stmt_dieta = $conn->prepare($sql_dieta);
        $stmt_dieta->bindParam(':id_animal', $id_animal, PDO::PARAM_INT);
        $stmt_dieta->execute();

        $sql_dieta = "DELETE FROM dieta_animal WHERE \"Id_Dieta\" = :id_animal";
        $stmt_dieta = $conn->prepare($sql_dieta);
        $stmt_dieta->bindParam(':id_animal', $id_animal, PDO::PARAM_INT);
        $stmt_dieta->execute();

        // Eliminar el registro de la tabla animales
        $sql_animal = "DELETE FROM animales WHERE \"Id_animal\" = :id_animal";
        $stmt_animal = $conn->prepare($sql_animal);
        $stmt_animal->bindParam(':id_animal', $id_animal, PDO::PARAM_INT);
        $stmt_animal->execute();

        // Confirmar la transacción
        $conn->commit();

        $_SESSION['mensaje'] = "El registro y sus relaciones se eliminaron con éxito.";
    } else {
        throw new Exception("Error: No se proporcionó el ID del animal.");
    }
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    $_SESSION['mensaje'] = "Error al eliminar el registro: " . $e->getMessage();
} finally {
    header("Location: ./../animales.php");
    exit();
}
?>
