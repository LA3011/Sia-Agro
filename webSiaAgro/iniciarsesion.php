<?php
session_start(); // Iniciar sesión PHP

include_once("conexion/conexion.php");

// Llamada al método estático
$conn = cconexion::ConexionBD();

if (isset($_POST['Usuario']) && isset($_POST['Clave'])) {
    $Usuario = $_POST['Usuario'];
    $Clave = $_POST['Clave'];

    if (!$conn) {
        $error_message = "No se pudo conectar a la base de datos";
        header("Location: index.html?error=" . urlencode($error_message));
        exit();
    }

    $query = "SELECT * FROM usuarios WHERE \"Usuario\" ILIKE :usuario AND \"Habilitado\" = '1'";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':usuario', $Usuario, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll();

    if (count($result) === 1) {
        $row = $result[0];

        $Clavencrip = $row["Clave"];

        if (password_verify($Clave, $Clavencrip)) {
            $_SESSION['Id_Perfilp'] = $row['Id_Perfilp'];
            $_SESSION['Tipo'] = $row['tipo_usuario'];
            $_SESSION['Usuario'] = $row['Usuario'];
            $_SESSION['Nombre'] = $row['Nombre'];
            $_SESSION['Id_Usuario'] = $row['Id_Usuario'];
            $_SESSION['Aceso'] = "Valido";

            header("Location: inicio.php");
            exit();
        } else {
            $error_message = "El usuario o la clave son incorrectos";
            header("Location: index.html?error=" . urlencode($error_message));
            exit();
        }
    } else {
        $error_message = "El usuario o la clave son incorrectos";
        header("Location: index.html?error=" . urlencode($error_message));
        exit();
    }
} else {
    header("Location: index.html");
    exit();
}