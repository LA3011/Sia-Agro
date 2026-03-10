<?php
session_start();
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}

$Nombre = $_POST["Nombre"];
$apellido = $_POST["apellido"];
$direccion_vivienda = $_POST["direccion_vivienda"];
$correo_electronico = $_POST["correo_electronico"];
$numero_telefonico = $_POST["numero_telefonico"];
$rif = $_POST["rif"];
$fecha_nacimiento = $_POST["fecha_nacimiento"];
$fecha_ingreso = $_POST["fecha_ingreso"];
$cargo = $_POST["cargo"];
$sueldo = $_POST["sueldo"];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

try {

    $sql = "INSERT INTO empleados (nombre, apellido, direccion_vivienda, correo, numero_telefonico, rif, \"Fecha_nacimiento\", \"Fecha_ingreso\", cargo, sueldo) 
            VALUES (:nombre, :apellido, :direccion_vivienda, :correo, :numero_telefonico, :rif, :fecha_nacimiento, :fecha_ingreso, :cargo, :sueldo)";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':nombre', $Nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':direccion_vivienda', $direccion_vivienda);
    $stmt->bindParam(':correo', $correo_electronico);
    $stmt->bindParam(':numero_telefonico', $numero_telefonico);
    $stmt->bindParam(':rif', $rif);
    $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
    $stmt->bindParam(':fecha_ingreso', $fecha_ingreso);
    $stmt->bindParam(':cargo', $cargo);
    $stmt->bindParam(':sueldo', $sueldo);

    $stmt->execute();
    $tabla = "Empleados";
    $numero_registro = $conn->lastInsertId();

    include("../bitacora.php");
    $bitacora = new Bitacora($conn);
    $bitacora->insertbitacora($tabla, $usuario, $id_usuario, $numero_registro);

    $_SESSION['mensaje'] = "El registro se guardó con éxito.";
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Ocurrió un error al intentar agregar un registro: " . $e->getMessage();
}

header("Location: ../empleados.php");
?>
