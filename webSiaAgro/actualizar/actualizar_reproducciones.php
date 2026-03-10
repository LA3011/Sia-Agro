<?php
session_start();
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actualizar"])) {
  $id_reproduccion = $_POST["id_reproduccion"];
  $nombre_hembra = $_POST["Nombre_hembra"];
  $nombre_macho = $_POST["Nombre_macho"];
  $tipo_reproduccion = $_POST["Tipo_reproducción"];
  $fecha_revision = $_POST["Fecha_revision"];
  $fecha_parto = $_POST["Fecha_parto"];
  $tipo_fertilizacion = $_POST["Tipo_fertilizacion"];
  $encargado = $_POST["Encargado"];
  $usuario = $_POST['session_acceso'];
  $id_usuario = $_POST['session_id'];

  $sql = "UPDATE reproduccion SET \"Nombre_hembra\" = :nombre_hembra, \"Nombre_macho\" = :nombre_macho, \"tipo_reproducción\" = :tipo_reproduccion, \"Fecha_revision\" = :fecha_revision, \"Fecha_parto\" = :fecha_parto, \"Tipo_fertilizacion\" = :tipo_fertilizacion, \"encargado\" = :encargado WHERE \"Id_reproduccion\" = :id_reproduccion";
  
  $stmt = $conn->prepare($sql);

  $stmt->bindParam(":nombre_hembra", $nombre_hembra);
  $stmt->bindParam(":nombre_macho", $nombre_macho);
  $stmt->bindParam(":tipo_reproduccion", $tipo_reproduccion);
  $stmt->bindParam(":fecha_revision", $fecha_revision);
  $stmt->bindParam(":fecha_parto", $fecha_parto);
  $stmt->bindParam(":tipo_fertilizacion", $tipo_fertilizacion);
  $stmt->bindParam(":encargado", $encargado);
  $stmt->bindParam(":id_reproduccion", $id_reproduccion);

  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    $tabla = "Reproduccion";
    $numero_registro =$id_reproduccion;
    include_once("../bitacora.php");
    $bitacora = new Bitacora($conn);
    $bitacora->updatebitacora($tabla, $usuario, $id_usuario,$numero_registro,);
    $_SESSION['mensaje'] = "El registro se Actualizó con exito.";
  header("Location: ../reproducciones.php");
  exit();
  } else {
      echo "No se encontraron cambios para actualizar";
  }
}
 
?>