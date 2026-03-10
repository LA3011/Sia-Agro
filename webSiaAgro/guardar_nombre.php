<?php
// Conexión a la base de datos (suponiendo que tu clase de conexión se llama `cconexion` y está definida en `conexion/conexion.php`)
include("conexion/conexion.php");
$conn = cconexion::ConexionBD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $poligono_id = $_POST['id'];
    $nuevo_nombre = $_POST['nombre'];

    $query = "UPDATE poligonos SET nombre = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $nuevo_nombre, $poligono_id);
    
    if ($stmt->execute()) {
        echo 'Nombre actualizado correctamente';
    } else {
        echo 'Error al actualizar el nombre';
    }
}
?>
