<?php
session_start();
include("../conexion/conexion.php"); // Archivo que contiene la conexión a PostgreSQL
$conn = cconexion::ConexionBD(); // Asegúrate de que esta función devuelva una conexión PDO válida

ob_start();

if (isset($_POST['submit'])) {
    // Obtener el nombre del usuario desde la sesión
    $usuario = $_SESSION['usuario'] ?? null;
    $respuesta1 = $_POST['respuesta1'] ?? null;
    $respuesta2 = $_POST['respuesta2'] ?? null;
    $respuesta3 = $_POST['respuesta3'] ?? null;

    if (!$usuario || !$respuesta1 || !$respuesta2 || !$respuesta3) {
        echo "Error: Todos los campos son obligatorios.";
        exit();
    }

    try {
        // Verificar si las respuestas son correctas
        $sql = "SELECT * FROM usuarios
                WHERE \"Respuesta_1\" = :respuesta1 
                AND \"Respuesta_2\" = :respuesta2 
                AND \"Respuesta_3\" = :respuesta3 
                AND \"Nombre\" = :nombre";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':respuesta1', $respuesta1, PDO::PARAM_STR);
        $stmt->bindParam(':respuesta2', $respuesta2, PDO::PARAM_STR);
        $stmt->bindParam(':respuesta3', $respuesta3, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $usuario, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            header("Location: verificar.php");
            $_SESSION['usuario'] = $usuario;
            exit();
        } else {
            $_SESSION["ERORO_PASSW_US"] = "Las Preguntas de verificación no han sido Respondidas Correctamente.";
            header("location: verificar1.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error al realizar la consulta: " . $e->getMessage();
        exit();
    }
}

ob_end_flush();
?>