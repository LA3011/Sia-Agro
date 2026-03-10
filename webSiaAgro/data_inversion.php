<?php
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();

$sql = "SELECT fecha, veterinario, comida, dieta FROM inversion";
$stmt = $conn->prepare($sql);

try {
    $stmt->execute();
    $salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si se encontraron resultados
    if (count($salesData) > 0) {
        // Convertir el array a formato JSON y enviarlo al cliente
        echo json_encode($salesData, JSON_UNESCAPED_UNICODE);
    } else {
        echo "[]";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Cerrar la conexión a la base de datos
$conn = null;
?>
