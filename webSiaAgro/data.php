<?php
include("conexion/conexion.php");
$conn = cconexion::ConexionBD();

try {
    // Consulta SQL para obtener los datos de la tabla factura
    $sql = "SELECT fecha, tipopublico,cantidad_animales,precio, ganancia FROM factura";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Verificar si se encontraron resultados
    $salesData = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $salesData[] = $row;
    }

    // Convertir el array a formato JSON y enviarlo al cliente
    echo json_encode($salesData, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo "Error al conectar con la base de datos: " . $e->getMessage();
}

// Cerrar la conexión
$conn = null;
?>
