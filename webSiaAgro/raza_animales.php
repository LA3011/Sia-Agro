<?php
session_start();
include_once("conexion/conexion.php");

$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}

try {
    // Actualizar los resultados de cantidad en venta y vendido en la tabla raza_animales
    $actualizar_query = "UPDATE raza_animales
                        SET venta = COALESCE(t.total_animales - t.vendidos, 0),
                            vendidas = COALESCE(t.vendidos, 0)
                        FROM (
                            SELECT \"Raza\" AS raza, COUNT(*) AS total_animales, 
                                   SUM(CASE WHEN \"Venta\" = 'Vendido' THEN 1 ELSE 0 END) AS vendidos
                            FROM animales
                            GROUP BY \"Raza\"
                        ) AS t
                        WHERE raza_animales.raza = t.raza";

    $actualizar_result = $conn->prepare($actualizar_query);
    $actualizar_result->execute();

    // Verificar si no hay animales en venta ni vendidos
    $verificar_query = "SELECT COUNT(*) AS sin_animales FROM raza_animales WHERE venta = 0 AND vendidas = 0";
    $verificar_result = $conn->prepare($verificar_query);
    $verificar_result->execute();
    $sin_animales = $verificar_result->fetch(PDO::FETCH_ASSOC)['sin_animales'];

    // Si no hay animales en venta ni vendidos, actualizar con cero
    if ($sin_animales > 0) {
        $actualizar_sin_animales_query = "UPDATE raza_animales SET venta = 0, vendidas = 0 WHERE venta = 0 AND vendidas = 0";
        $actualizar_sin_animales_result = $conn->prepare($actualizar_sin_animales_query);
        $actualizar_sin_animales_result->execute();
    }

    header("Location: prueba2.php");
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
$conn = null;
?>
