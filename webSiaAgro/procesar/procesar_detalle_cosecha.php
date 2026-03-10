<?php
session_start();
include("../conexion/conexion.php");

$conn = cconexion::ConexionBD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCultivo = intval($_POST['id_cultivo']);
    $cantidadCosechada = $_POST['cantidad_cosechada'];
    $fechaCosecha = $_POST['fecha_cosecha'];
    $observaciones = $_POST['observaciones'];

    try {
        // Insertar el detalle de la cosecha en la base de datos
        $sql = "INSERT INTO detalle_cosecha (id_cosecha, cantidad_cosechada, fecha_cosecha, observaciones) 
                VALUES (:id_cosecha, :cantidad_cosechada, :fecha_cosecha, :observaciones)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_cosecha', $idCultivo, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad_cosechada', $cantidadCosechada, PDO::PARAM_STR);
        $stmt->bindParam(':fecha_cosecha', $fechaCosecha, PDO::PARAM_STR);
        $stmt->bindParam(':observaciones', $observaciones, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Guardar mensaje de éxito en la sesión
            $_SESSION['mensaje'] = "El detalle de la cosecha se registró con éxito.";
            $_SESSION['tipo_mensaje'] = "success"; // Tipo de mensaje (éxito)
        } else {
            // Guardar mensaje de error en la sesión
            $_SESSION['mensaje'] = "No se pudo registrar el detalle de la cosecha.";
            $_SESSION['tipo_mensaje'] = "error"; // Tipo de mensaje (error)
        }
    } catch (PDOException $e) {
        // Guardar mensaje de error en la sesión
        $_SESSION['mensaje'] = "Error: " . $e->getMessage();
        $_SESSION['tipo_mensaje'] = "error"; // Tipo de mensaje (error)
    }

    // Redirigir a la página principal
    header("Location: ../cultivos.php");
    exit();
} else {
    // Si no se recibe una solicitud POST válida
    $_SESSION['mensaje'] = "Solicitud inválida.";
    $_SESSION['tipo_mensaje'] = "error"; // Tipo de mensaje (error)
    header("Location: ../cultivos.php");
    exit();
}
?>