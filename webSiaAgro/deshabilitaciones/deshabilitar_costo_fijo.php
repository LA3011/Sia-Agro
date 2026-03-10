<?php
session_start();
include("../conexion/conexion.php");
$conn = cconexion::ConexionBD();

$Id_fijo = $_GET['id'];
$usuario = $_GET['session_acceso'];
$id_usuario = $_GET['session_id'];

try {
    $conn->beginTransaction();
    $sql = "DELETE FROM costo_fijo WHERE \"Id_fijo\" = :Id_fijo";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':Id_fijo', $Id_fijo, PDO::PARAM_INT);
    $stmt->execute();

    $tabla = "costo_fijo";
    $numero_registro = $Id_fijo;

    include("./../bitacora.php");
    $bitacora = new Bitacora($conn);

    $bitacora->deletebitacora($tabla, $usuario, $id_usuario, $numero_registro);
    $conn->commit();
    $_SESSION['mensaje'] = "El registro se eliminó con éxito.";
} catch (Exception $e) {

    $conn->rollBack();

    $_SESSION['mensaje'] = "Ocurrió un error al intentar eliminar el registro: " . $e->getMessage();
}

header("Location: ./../costo_fijo.php");
exit();
$conn = null;
?>
