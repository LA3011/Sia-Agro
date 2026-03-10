<?php
session_start();
include_once("conexion/conexion.php");

try {
    // Establecer conexión con la base de datos PostgreSQL
    $conn = cconexion::ConexionBD();  // Asegúrate de que esta función esté configurada para PDO y PostgreSQL

    // Consulta SQL para obtener los datos de la tabla bitacora
    $sql = "SELECT \"Fecha\", \"Hora\", \"Accion\", \"Numero_Registro\", \"Tabla_Modificada\", \"Usuario\" FROM bitacora";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Ejecutar la consulta preparada
    $stmt->execute();

    // Obtener los datos en un array asociativo
    $bitacoraData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Establecer encabezado para JSON
    header('Content-Type: application/json; charset=utf-8');

    // Convertir los datos a formato JSON
    echo json_encode($bitacoraData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    // Manejar excepciones y enviar un mensaje de error en JSON
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(["error" => $e->getMessage()], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}
?>
