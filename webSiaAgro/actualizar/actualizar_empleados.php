<?php
session_start();
include_once("../conexion/conexion.php");

$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}

// Obtener los datos del formulario
$id_empleados = $_POST["id_empleados"];
$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$direccion_vivienda = $_POST["direccion_vivienda"];
$correo_electronico = $_POST["correo"];
$numero_telefonico = $_POST["numero"];
$rif = $_POST["rif"];
$fecha_nacimiento = $_POST["fecha_nacimiento"];
$fecha_ingreso = $_POST["fecha_ingreso"];
$cargo = $_POST["cargo"];
$sueldo = $_POST["sueldo"];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

try {
    // Preparar la consulta SQL
    $sql = "UPDATE empleados SET nombre = :nombre, apellido = :apellido, 
            direccion_vivienda = :direccion_vivienda, correo = :correo, 
            numero_telefonico = :numero_telefonico, rif = :rif, 
            \"Fecha_nacimiento\" = :fecha_nacimiento, \"Fecha_ingreso\" = :fecha_ingreso, 
            cargo = :cargo, sueldo = :sueldo 
            WHERE \"Id_empleados\" = :id_empleados";

    $stmt = $conn->prepare($sql);

    // Vincular los parámetros
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':direccion_vivienda', $direccion_vivienda);
    $stmt->bindParam(':correo', $correo_electronico);
    $stmt->bindParam(':numero_telefonico', $numero_telefonico);
    $stmt->bindParam(':rif', $rif);
    $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
    $stmt->bindParam(':fecha_ingreso', $fecha_ingreso);
    $stmt->bindParam(':cargo', $cargo);
    $stmt->bindParam(':sueldo', $sueldo);
    $stmt->bindParam(':id_empleados', $id_empleados);

    // Ejecutar la consulta
    $stmt->execute();

    // Comprobar si la actualización fue exitosa
    if ($stmt->rowCount() > 0) {
        $tabla = "Empleados";
        $numero_registro = $id_empleados;

        // Incluir el archivo bitacora.php
        include("../bitacora.php");

        // Crear una instancia de la clase Bitacora
        $bitacora = new Bitacora($conn);

        // Llamar al método updatebitacora() con los argumentos correctos
        $bitacora->updatebitacora($tabla, $usuario, $id_usuario, $numero_registro);

        $_SESSION['mensaje'] = "El registro se actualizó con éxito.";
    } else {
        $_SESSION['mensaje'] = "No se realizaron cambios en el registro.";
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Ocurrió un error al intentar actualizar un registro: " . $e->getMessage();
}

header("Location: ../empleados.php");
?>
