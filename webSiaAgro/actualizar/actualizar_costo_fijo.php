<?php
session_start();
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();

$Id_variable = $_POST["Id_fijo"];
$Nombre = $_POST['Nombre'];
$Monto = $_POST['Monto'];
$Observaciones = $_POST['Observaciones'];
$Prioridad = $_POST['Prioridad'];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

try {

    $conn->beginTransaction();
    $sql = "UPDATE costo_fijo SET \"Nombre\" = :Nombre, \"Monto\" = :Monto, \"Observaciones\" = :Observaciones, \"Prioridad\" = :Prioridad WHERE \"Id_fijo\" = :Id_fijo";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':Nombre', $Nombre);
    $stmt->bindParam(':Monto', $Monto);
    $stmt->bindParam(':Observaciones', $Observaciones);
    $stmt->bindParam(':Prioridad', $Prioridad);
    $stmt->bindParam(':Id_fijo', $Id_variable);
    $stmt->execute();

    $tabla = "costo_Variable";
    $numero_registro =$Id_variable;
    include("../bitacora.php");
    $bitacora = new Bitacora($conn);
    $bitacora->updatebitacora($tabla, $usuario, $id_usuario, $numero_registro);
    $conn->commit();
    $_SESSION['mensaje'] = "El registro se actualizó con éxito.";
} catch (Exception $e) {

    $conn->rollBack();
    $_SESSION['mensaje'] = "Ocurrió un error al intentar actualizar un registro: " . $e->getMessage();
}

header("Location: ../costo_fijo.php");
$conn = null;
?>
