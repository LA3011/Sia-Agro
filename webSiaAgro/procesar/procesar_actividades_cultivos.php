<?php
session_start();
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}

$nombre_actividad = $_POST["nombre_actividad"];
$detalle_actividad = $_POST["detalle_actividad"];
$fecha_inicio = $_POST["fecha_inicio"];
$fecha_final = $_POST["fecha_final"];
$espacio_usado = $_POST["espacio_usado"];
$tipo_cultivo = $_POST["tipo_cultivo"];
$responsable = $_POST["responsable"];
$cantidad_trabajadores = $_POST["cantidad_trabajadores"];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];
 
$sql=" INSERT INTO actividades (nombre_actividad, detalle_actividad,Fechainicio, Fechafinal,espacio_usado,tipo_cultivo, elaborada,cantidad_trabajadores)
VALUES 
('$nombre_actividad','$detalle_actividad','$fecha_inicio','$fecha_final','$espacio_usado ','$tipo_cultivo','$responsable','$cantidad_trabajadores')";

if ($conn->query($sql) === TRUE) {

  include("../bitacora.php");
  $tabla = "Actividades de Cultivos";
$numero_registro = $conn->insert_id; // Obtiene el ID del último registro
$bitacora = new Bitacora($conn);
$bitacora->insertbitacora($tabla, $usuario, $id_usuario, $numero_registro);

  $_SESSION['mensaje'] = "El registro se guardo con exito.";
} else {
  $_SESSION['mensaje'] = "El registro se guardo con exito.";
}
header("Location: ../actividades_cultivos.php");
?>