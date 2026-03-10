<?php 
session_start(); 
include_once("../conexion/conexion.php");

try {
    // Llamar al método para establecer la conexión a la base de datos
    $conn = cconexion::ConexionBD();

    // Verificar si se proporcionó el ID del usuario
    if (isset($_GET['id'])) {
        $idUsuario = intval($_GET['id']); // Convertir a entero para mayor seguridad

        // Consulta preparada para deshabilitar al usuario
        $sql = "UPDATE usuarios SET \"Habilitado\" = :habilitado WHERE \"Id_Usuario\" = :id_usuario";
        $stmt = $conn->prepare($sql);

        // Asignar valores y ejecutar
        $stmt->bindValue(':habilitado', 0, PDO::PARAM_INT);
        $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Usuario inhabilitado correctamente.";
            $_SESSION['acceso'] = "Válido";
        } else {
            throw new Exception("Error al intentar inhabilitar al usuario.");
        }
    } else {
        throw new Exception("Error: No se proporcionó un ID de usuario válido.");
    }
} catch (Exception $e) {
    // Manejo de errores y establecimiento de mensajes de sesión
    $_SESSION['mensaje'] = $e->getMessage();
    $_SESSION['acceso'] = "Inválido";
} finally {
    // Redirigir de vuelta a la página usuarios.php
    header("Location: ../usuario.php");
    exit();
}
?>

<script>
    // Ocultar el mensaje de confirmación después de 3 segundos
    setTimeout(function() {
        const mensaje = document.getElementById("mensaje");
        if (mensaje) {
            mensaje.style.display = "none";
        }
    }, 3000);
</script>
