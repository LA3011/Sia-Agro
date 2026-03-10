<?php
session_start();
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actualizar"])) {
$Id_potreros = $_POST['Id_potreros'];
$Nombre = $_POST['Nombre'];
$Tipo_suelo = $_POST['Tipo_suelo'];
$Cantidad_dias_secos = $_POST['Cantidad_dias_secos'];
$Cantidad_dias_verdes = $_POST['Cantidad_dias_verdes'];
$Tipo_pasto = $_POST['Tipo_pasto'];
$area_expresada= $_POST['area_expresada'];
$area = $_POST['area'];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

$sql = "UPDATE potreros SET \"Nombre\" =:Nombre, \"Tipo_suelo\" = :Tipo_suelo, \"Cantidad_dias_secos\" = :Cantidad_dias_secos, \"Cantidad_dias_verdes\" = :Cantidad_dias_verdes ,\"Tipo_pasto\" = :Tipo_pasto, area_expresada= :area_expresada, area= :area  WHERE \"Id_potreros\" = :Id_potreros";

$stmt = $conn->prepare($sql);

  $stmt->bindParam(":Nombre", $Nombre);
  $stmt->bindParam(":Tipo_suelo", $Tipo_suelo);
  $stmt->bindParam(":Cantidad_dias_secos", $Cantidad_dias_secos);
  $stmt->bindParam(":Cantidad_dias_verdes", $Cantidad_dias_verdes);
  $stmt->bindParam(":Tipo_pasto", $Tipo_pasto);
  $stmt->bindParam(":area_expresada", $area_expresada);
  $stmt->bindParam(":area", $area);
  $stmt->bindParam(":Id_potreros", $Id_potreros);

  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    $tabla = "Potreros";
    $numero_registro =$Id_potreros;
    include_once("../bitacora.php");
    $bitacora = new Bitacora($conn);
    $bitacora->updatebitacora($tabla, $usuario, $id_usuario,$numero_registro,);
    $_SESSION['mensaje'] = "El registro se Actualizó con exito.";
  header("Location: ../calculo_potreros.php");
  exit();
  } else {
      echo "No se encontraron cambios para actualizar";
  }
}