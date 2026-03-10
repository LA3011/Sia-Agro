<?php
session_start();
include_once("../conexion/conexion.php");

// Obtén la conexión PDO
$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}

$usuario_session = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];
$id = $_SESSION['Id_Usuario'];
$tipo = $_POST["perfilx"]; // Perfil del usuario
$usuario = $_POST["Usuario"];
$nombre = $_POST["Nombre"];
$clave = $_POST["clave"];
$clavencrip = password_hash($clave, PASSWORD_DEFAULT);
$apellido = $_POST["apellido"];
$pelicula = $_POST["pelicula"];
$comida = $_POST["comida"];
$mascota = $_POST["mascota"];
$habilitado = 1;

try {
    // Verificar si el usuario ya existe en la tabla de usuarios
    $sql_verificar = "SELECT COUNT(*) FROM usuarios WHERE \"Usuario\" = :usuario OR \"Nombre\" = :nombre";
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->execute([':usuario' => $usuario, ':nombre' => $nombre]);
    $existe_usuario = $stmt_verificar->fetchColumn();

    if ($existe_usuario > 0) {
        $_SESSION['mensaje_user_existent'] = "El Usuario ya existe";
        header("Location: editar_usuarios.php");
        exit;
    }

    // Obtener el ID del perfil correspondiente al tipo de usuario
    $sql_perfil = "SELECT \"Id_Perfil\" FROM perfil WHERE \"nombre_perfil\" = :tipo";
    $stmt_perfil = $conn->prepare($sql_perfil);
    $stmt_perfil->execute([':tipo' => $tipo]);
    $id_perfil = $stmt_perfil->fetchColumn();

    if (!$id_perfil) {
        echo "Error: El perfil especificado no existe.";
        exit;
    }

    // Insertar el nuevo usuario con el ID del perfil
    $sql_insertar = "INSERT INTO usuarios (\"Usuario\", \"Id_Perfilp\", \"Nombre\", \"Apellido\", \"tipo_usuario\", \"Clave\", \"Respuesta_1\", \"Respuesta_2\", \"Respuesta_3\", \"Habilitado\")
                     VALUES (:usuario, :id_perfil, :nombre, :apellido, :tipo, :clave, :pelicula, :comida, :mascota, :habilitado)";
    $stmt_insertar = $conn->prepare($sql_insertar);
    $stmt_insertar->execute([
        ':usuario' => $usuario,
        ':id_perfil' => $id_perfil,
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':tipo' => $tipo,
        ':clave' => $clavencrip,
        ':pelicula' => $pelicula,
        ':comida' => $comida,
        ':mascota' => $mascota,
        ':habilitado' => $habilitado,
    ]);

    // Registro en bitácora
    $numero_registro = $stmt_insertar->rowCount();
    $tabla = "Registro de Usuario";
    include("../bitacora.php");
    $bitacora = new Bitacora($conn);
    $bitacora->insertbitacora($tabla, $usuario, $id_usuario, $numero_registro);

    $_SESSION['mensaje'] = "El registro se guardó con éxito.";
    $conn = null;

    header("Location: ../usuario.php");
    exit;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
