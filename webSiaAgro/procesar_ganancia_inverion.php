<?php
include("../conexion/conexion.php");
$conn = cconexion::ConexionBD(); // Asegúrate de que esta función devuelva una conexión PDO válida

try {
    // Obtener el mes actual
    $mesActual = date('m');

    // Realizar la consulta para obtener los animales vendidos en el mismo mes
    $consulta = "
        SELECT 
            animales.id_factura, 
            animales.raza, 
            factura.fecha, 
            TO_CHAR(factura.fecha, 'Day') AS diaSemana, 
            EXTRACT(WEEK FROM factura.fecha) - EXTRACT(WEEK FROM DATE_TRUNC('month', factura.fecha)) + 1 AS semanaMes
        FROM 
            animales
        INNER JOIN 
            factura ON animales.id_factura = factura.id
        WHERE 
            EXTRACT(MONTH FROM factura.fecha) = :mesActual
    ";
    $stmt = $conn->prepare($consulta);
    $stmt->bindParam(':mesActual', $mesActual, PDO::PARAM_INT);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si se obtuvieron resultados
    if ($resultados && count($resultados) > 0) {
        // Imprimir la cantidad de animales vendidos en el mes actual
        $cantidadAnimales = count($resultados);
        echo "Se vendieron $cantidadAnimales animales en el mes actual.<br>";

        // Recorrer los resultados y obtener el nombre del día de la semana, la semana del mes y el precio de venta de cada animal
        foreach ($resultados as $fila) {
            $idFactura = $fila['id_factura'];
            $fechaVenta = $fila['fecha'];
            $diaSemana = trim($fila['diaSemana']); // Eliminar espacios en blanco
            $semanaMes = $fila['semanaMes'];
            $razaAnimal = $fila['raza'];

            // Consulta para obtener el precio de la raza del animal
            $consultaRaza = "SELECT precio FROM raza_animales WHERE raza = :razaAnimal";
            $stmtRaza = $conn->prepare($consultaRaza);
            $stmtRaza->bindParam(':razaAnimal', $razaAnimal, PDO::PARAM_STR);
            $stmtRaza->execute();
            $resultadoRaza = $stmtRaza->fetch(PDO::FETCH_ASSOC);

            // Verificar si se obtuvieron resultados de la consulta de la raza
            if ($resultadoRaza) {
                $precioAnimal = $resultadoRaza['precio'];
                echo "El animal con ID de factura $idFactura se vendió el día $diaSemana, $fechaVenta. Su raza es $razaAnimal y tiene un precio de $precioAnimal. La venta se realizó en la semana $semanaMes del mes.<br>";
            } else {
                echo "No se encontró el precio para la raza $razaAnimal.<br>";
            }
        }
    } else {
        echo "No se encontraron animales vendidos en el mes actual.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Cerrar la conexión
$conn = null;
?>