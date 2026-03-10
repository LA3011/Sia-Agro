<?php
session_start();
include("../conexion/conexion.php"); // Archivo que contiene la conexión a PostgreSQL
$conn = cconexion::ConexionBD(); // Asegúrate de que esta función devuelva una conexión PDO válida

if (isset($_POST['submit'])) {
    $clave = $_POST['clave'];
    $clave2 = $_POST['clave2'];
    $usuario = $_SESSION['usuario'] ?? null;

    if (!$usuario) {
        $_SESSION["password_repet"] = "El nombre de usuario no está definido.";
        header("location: verificar.php");
        exit();
    }

    try {
        // Verificar si el usuario existe
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE \"Usuario\" = :usuario");
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Verificar si las contraseñas coinciden
            if ($clave === $clave2) {
                // Encriptar la nueva contraseña
                $clavencrip = password_hash($clave, PASSWORD_DEFAULT);

                // Actualizar la contraseña en la base de datos
                $updateStmt = $conn->prepare("UPDATE \"usuarios\" SET \"Clave\" = :clave WHERE \"Usuario\" = :usuario");
                $updateStmt->bindParam(':clave', $clavencrip, PDO::PARAM_STR);
                $updateStmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
                $updateStmt->execute();

                // Redirigir a la página de nueva clave
                header("location: nueva_clave.php");
                exit();
            } else {
                // Si las contraseñas no coinciden
                $_SESSION["password_repet"] = "Las Contraseñas no Coinciden";
                header("location: verificar.php");
                exit();
            }
        } else {
            // Si el usuario no existe
            $_SESSION["password_repet"] = "El nombre de usuario no existe";
            header("location: verificar.php");
            exit();
        }
    } catch (PDOException $e) {
        // Manejo de errores de la base de datos
        echo "Error al realizar la consulta: " . $e->getMessage();
        exit();
    }
}
?>