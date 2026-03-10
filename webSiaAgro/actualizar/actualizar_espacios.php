<?php
session_start();

// Configuración de conexión con la base de datos PostgreSQL
include("../conexion/conexion.php");
$conn = cconexion::ConexionBD(); // Asegúrate de que esta función devuelva una conexión PDO válida


try {
    // Establecer la conexión con la base de datos usando PDO

    // Recopilar los datos del formulario
    $id_espacio = $_POST['id_espacio'];
    $nombre_espacio = $_POST['nombre_espacio'];
    $estatus = $_POST['estatus'];
    $recursos_hidricos = $_POST['recursos_hidricos'];
    $historial_uso = $_POST['historial_uso'];
    $observaciones = $_POST['observaciones'];
    $tipo_riego = $_POST['tipo_riego'];


    // Preparar la consulta SQL de actualización
    $sql = "UPDATE espacios 
            SET nombre_espacio = :nombre_espacio, 
                estatus = :estatus, 
                recursos_hidricos = :recursos_hidricos, 
                historial_uso = :historial_uso, 
                observaciones = :observaciones, 
                tipo_riego = :tipo_riego
                
            WHERE \"Id_espacios\" = :id_espacio";  // Sin comillas alrededor de id_espacios

    // Preparar la sentencia con PDO
    $stmt = $conn->prepare($sql);

    // Asignar los valores de las variables a los parámetros de la consulta
    $stmt->bindParam(':id_espacio', $id_espacio);
    $stmt->bindParam(':nombre_espacio', $nombre_espacio);
    $stmt->bindParam(':estatus', $estatus);
    $stmt->bindParam(':recursos_hidricos', $recursos_hidricos);
    $stmt->bindParam(':historial_uso', $historial_uso);
    $stmt->bindParam(':observaciones', $observaciones);
    $stmt->bindParam(':tipo_riego', $tipo_riego);
    // Ejecutar la consulta
    $stmt->execute();

    // Mensaje de éxito y redirección
    $_SESSION['mensaje'] = "El registro se actualizó con éxito.";
    header("Location: ../Espacios.php");

} catch (PDOException $e) {
    // Manejo de errores de la conexión o consulta
    echo "Error: " . $e->getMessage();
}

// Cerrar la conexión
$conn = null;
?>
