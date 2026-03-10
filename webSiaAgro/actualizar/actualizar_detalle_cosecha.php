<?php
session_start();
include("../conexion/conexion.php");

$conn = cconexion::ConexionBD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idDetalle = intval($_POST['id_detalle']);
    $idCultivo = intval($_POST['id_cultivo']);
    $cantidadCosechada = $_POST['cantidad_cosechada'];
    $fechaCosecha = $_POST['fecha_cosecha'];
    $observaciones = $_POST['observaciones'];

    try {
        // Actualizar el detalle de la cosecha en la base de datos
        $sql = "UPDATE detalle_cosecha 
                SET cantidad_cosechada = :cantidad_cosechada, 
                    fecha_cosecha = :fecha_cosecha, 
                    observaciones = :observaciones 
                WHERE id = :id_detalle";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':cantidad_cosechada', $cantidadCosechada, PDO::PARAM_STR);
        $stmt->bindParam(':fecha_cosecha', $fechaCosecha, PDO::PARAM_STR);
        $stmt->bindParam(':observaciones', $observaciones, PDO::PARAM_STR);
        $stmt->bindParam(':id_detalle', $idDetalle, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "El detalle de la cosecha se actualizó con éxito.";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            $_SESSION['mensaje'] = "No se pudo actualizar el detalle de la cosecha.";
            $_SESSION['tipo_mensaje'] = "error";
        }
    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "Error: " . $e->getMessage();
        $_SESSION['tipo_mensaje'] = "error";
    }

    // Redirigir a la página principal
    header("Location: ../cultivos.php");
    exit();
} else {
    // Si no se recibe una solicitud POST válida
    $_SESSION['mensaje'] = "Solicitud inválida.";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: ../cultivos.php");
    exit();
}
?>