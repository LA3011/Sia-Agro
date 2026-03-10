<?php
session_start();
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}
$Fecha = $_POST['Fecha'];
$Cantidad_personal = $_POST['Cantidad_personal'];
$Encargado = $_POST['Encargado'];
$Detalle = $_POST['Detalle'];
$Establo = $_POST['Establo'];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

$sql = "INSERT INTO actividad_animal (\"Fecha\", \"Cantidad_personal\", \"Encargado\", \"Detalle\", \"Establo\")
 VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$Fecha,$Cantidad_personal, $Encargado,$Detalle,$Establo]);
if ($stmt) {
      $numero_registro = $stmt->rowCount();
      $tabla = "Actividad animal";
      include("../bitacora.php");
      $bitacora = new Bitacora($conn);
      $bitacora->insertbitacora($tabla, $usuario, $id_usuario, $numero_registro);
      $_SESSION['mensaje'] = "El registro se guardó con éxito.";
  } else {
      echo "Error al insertar en la base de datos: " . $stmt->errorInfo()[2];
  }
  $conn = null;
  header("Location: ../movimiento_animal.php");
  exit;
?>