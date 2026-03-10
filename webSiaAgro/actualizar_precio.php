<?php
session_start();
include_once("conexion/conexion.php");

try {
    $conn = cconexion::ConexionBD();
    if (!$conn) {
        throw new Exception("Error al conectar a la base de datos.");
    }

    // Verificar si se ha enviado un nuevo precio
    if (isset($_POST['nuevo_precio'])) {
        $nuevoPrecio = $_POST['nuevo_precio'];
        
        // Reemplazar la coma por un punto en el nuevo precio
        $nuevoPrecio = str_replace(',', '.', $nuevoPrecio);
        
        // Formatear el precio con dos decimales
        $nuevoPrecio = number_format($nuevoPrecio, 2, '.', '');

        // Actualizar el campo de precio en la tabla precios
        $query = "UPDATE precios SET precio = :nuevoPrecio";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nuevoPrecio', $nuevoPrecio, PDO::PARAM_STR);

        if ($stmt->execute()) {
            header("Location: prueba2.php");
            echo $nuevoPrecio;
        } else {
            throw new Exception("Error al actualizar el precio.");
        }
    }

    //////////////////////////////////////////////////////////////////////////
    //-----------> Asignar precios a los animales <----------------///

    // Obtener el precio de referencia
    $Psql = "SELECT precio FROM precios LIMIT 1";
    $stmt = $conn->query($Psql);
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($fila) {
        $precioReferencia = $fila["precio"];
    } else {
        throw new Exception("No se encontró el precio de referencia en la tabla 'precios'.");
    }

    // Consulta para actualizar el precio de los animales en venta
    $Asql = "UPDATE animales SET precio = \"Peso\" * :precioReferencia WHERE \"Venta\" = 'Venta'";
    $stmt = $conn->prepare($Asql);
    $stmt->bindParam(':precioReferencia', $precioReferencia, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "Se actualizó el precio de los animales en venta correctamente.";
    } else {
        throw new Exception("Error al actualizar el precio de los animales.");
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conn = null; // Cerrar la conexión
}
?>
