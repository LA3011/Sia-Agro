<?php
session_start();
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actualizar"])) {
$Id_Actividad = $_POST['Id_Actividad'];
$Fecha = $_POST['Fecha'];
$Cantidad_personal = $_POST['Cantidad_personal'];
$Encargado = $_POST['Encargado'];
$Detalle = $_POST['Detalle'];
$Establo = $_POST['Establo'];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

$sql = "UPDATE actividad_animal SET \"Fecha\"= :Fecha, \"Cantidad_personal\"= :Cantidad_personal, \"Encargado\"= :Encargado, \"Detalle\"= :Detalle, \"Establo\"= :Establo WHERE \"Id_Actividad\"= :Id_Actividad";

$stmt = $conn->prepare($sql);

$stmt->bindParam(":Fecha", $Fecha);
$stmt->bindParam(":Cantidad_personal", $Cantidad_personal);
$stmt->bindParam(":Encargado", $Encargado);
$stmt->bindParam(":Detalle", $Detalle);
$stmt->bindParam(":Establo", $Establo);
$stmt->bindParam(":Id_Actividad", $Id_Actividad);

$stmt->execute();

if ($stmt->rowCount() > 0) {
  $tabla = "Actividad Animal";
  $numero_registro =$Id_Actividad;
  include_once("../bitacora.php");
  $bitacora = new Bitacora($conn);
  $bitacora->updatebitacora($tabla, $usuario, $id_usuario,$numero_registro,);
  $_SESSION['mensaje'] = "El registro se Actualizó con exito.";
header("Location: ../movimiento_animal.php");
exit();
} else {
    echo "No se encontraron cambios para actualizar";
}
}
