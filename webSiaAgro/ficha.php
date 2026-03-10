<?php
// archivo: ficha.php
include("conexion/conexion.php");

// Establecer el encabezado para indicar que el contenido es JSON
header('Content-Type: application/json');

// Conectar a la base de datos
$conn = cconexion::ConexionBD();

try {
    // Consulta para obtener los datos de la tabla poligono
    $query = "SELECT nombre, ficha_tecnica_id, estado, fecha_hora, id FROM poligono";
    $statement = $conn->prepare($query);
    $statement->execute();

    // Recuperar los datos
    $data = [];
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }

    // Verifica si hay datos en la consulta
    if (empty($data)) {
        echo json_encode(['data' => [], 'message' => 'No hay datos disponibles']);
    } else {
        // Convertir los datos a JSON
        echo json_encode(['data' => $data]);
    }
} catch (PDOException $e) {
    echo json_encode(['data' => [], 'error' => $e->getMessage()]);
}
?>
