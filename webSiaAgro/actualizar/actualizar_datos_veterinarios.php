<?php
session_start();
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}

$Id_Veterinario = $_POST['Id_Veterinario'];
$Animal = $_POST['Animal'];
$Tipo_Tratamiento = $_POST['Tipo_Tratamiento'];
$Nombre_tratamiento = $_POST['Nombre_tratamiento'];
$Veterinario = $_POST['Veterinario'];
$Diagnostico = $_POST['Diagnostico'];
$Dias_tratamiento = $_POST['Dias_tratamiento'];
$Precio = $_POST['Precio'];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

$sql = "UPDATE datos_veterinarios SET id_animal=?, \"Tipo_Tratamiento\"=?, \"Nombre_tratamiento\"=?, \"Veterinario\"=?, \"Diagnostico\"=?, \"Dias_tratamiento\"=?, \"Precio\"=? WHERE \"Id_Veterinario\"=?";

$stmt = $conn->prepare($sql);

if ($stmt->execute([$Animal, $Tipo_Tratamiento, $Nombre_tratamiento, $Veterinario, $Diagnostico, $Dias_tratamiento, $Precio, $Id_Veterinario])) {

    $tabla = "Datos Veterinarios";
    $numero_registro = $Id_Veterinario;
    include("../bitacora.php");
    $bitacora = new Bitacora($conn);
    $bitacora->updatebitacora($tabla, $usuario, $id_usuario, $numero_registro);

    $_SESSION['mensaje'] = "El registro se actualizó con éxito.";
} else {
    $_SESSION['mensaje'] = "Ocurrió un error al intentar actualizar un registro.";
}
header("Location: ../datos_veterinarios.php");
?>
