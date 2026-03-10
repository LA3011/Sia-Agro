<?php
session_start();
include_once("../conexion/conexion.php");

// Obtén la conexión PDO
$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}

// Inicializando rectificador
$actualizado = 0;

// Verifica si se envió el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_session = $_POST['session_acceso'];
    $id_usuario_session = $_POST['session_id'];

    // Obtiene los datos del formulario
    $id_usuario = $_POST["id_usuario"];
    $usuario = $_POST["usuario"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["Apellido"];
    $mix = $_POST["Id_Perfilp"]; // Recibe el parámetro

    $inf = explode(",", $mix); // Separar los valores por coma
    $id_perfilp = (int)$inf[0]; // Solo el ID del perfil, convertido a entero
    $tipo_usuario = $inf[1] ?? ''; // Tipo de usuario (cadena)

    // Validación de seguridad
    if (($id_usuario == 1) || ($id_usuario == $_SESSION['Id_Usuario'])) {
        $_SESSION['validacion_permisos'] = "VALIDACION #0003: <br> PERFIL NO ADMITIDO... <br> 
        <p>Este perfil no puede ser 'editado' por Seguridad</p>";
        header("Location: editar_usuarios.php");
        exit();
    }

    // Preparar consulta SQL
    $sql = "UPDATE usuarios 
    SET \"Usuario\" = :usuario, 
        \"Nombre\" = :nombre, 
        \"Apellido\" = :apellido, 
        \"Id_Perfilp\" = :id_perfilp, 
        \"tipo_usuario\" = :tipo_usuario 
    WHERE \"Id_Usuario\" = :id_usuario";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':usuario' => $usuario,
    ':nombre' => $nombre,
    ':apellido' => $apellido,
    ':id_perfilp' => $id_perfilp, // Asegúrate de enviar solo el ID
    ':tipo_usuario' => $tipo_usuario,
    ':id_usuario' => $id_usuario
]);

    // Registrar en bitácora
    $tabla = "Usuarios";
    $numero_registro = $id_usuario;
    include("../bitacora.php");

    $bitacora = new Bitacora($conn);
    $bitacora->updatebitacora($tabla, $usuario_session, $id_usuario_session, $numero_registro);

    $_SESSION['mensaje'] = "El registro se actualizó con éxito.";
}

header("Location: ../usuario.php");
?>
