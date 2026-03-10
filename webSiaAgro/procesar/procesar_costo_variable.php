<?php
session_start();
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();

$Nombre = $_POST['Nombre'];
$Monto = $_POST['Monto'];
$Observaciones = $_POST['Observaciones'];
$Prioridad = $_POST['Prioridad'];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

try {
    $conn->beginTransaction();
    $sql = "INSERT INTO costo_variable (\"Nombre\", \"Monto\", \"Observaciones\", \"Prioridad\") VALUES (:Nombre, :Monto, :Observaciones, :Prioridad)";
    $stmt = $conn->prepare($sql);
    
    $stmt->bindParam(':Nombre', $Nombre);
    $stmt->bindParam(':Monto', $Monto);
    $stmt->bindParam(':Observaciones', $Observaciones);
    $stmt->bindParam(':Prioridad', $Prioridad);
    $stmt->execute();

    $numero_registro = $conn->lastInsertId();

    $tabla = "costo variable";

    include("../bitacora.php");

    $bitacora = new Bitacora($conn);
    $bitacora->insertbitacora($tabla, $usuario, $id_usuario, $numero_registro);
    $conn->commit();
    $_SESSION['mensaje'] = "El registro se guardó con éxito.";
} catch (Exception $e) {

    $conn->rollBack();
    $_SESSION['mensaje'] = "Ocurrió un error al intentar agregar un registro: " . $e->getMessage();
}
header("Location: ../costo_variable.php");
$conn = null;
?>
