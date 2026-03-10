<?php
session_start();
include_once("../conexion/conexion.php");

// Establecer conexión con la base de datos
$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error de conexión: No se pudo conectar a la base de datos.");
}

// Recibir datos
$id = (int) $_GET['id'];
$usuario = $_GET['session_acceso'];
$id_usuario = (int) $_GET['session_id'];

try {
    // Iniciar transacción
    $conn->beginTransaction();

    // Obtener los valores necesarios del registro
    $sql = "SELECT cantidad_adquirida, precio_unitario, \"Fecha\" 
            FROM insumos_fertilizante 
            WHERE \"Id_fertilizante\" = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $cantidad_adquirida = (float) $row['cantidad_adquirida'];
        $precio_unitario = (float) $row['precio_unitario'];
        $fecha = date('Y-m-d', strtotime($row['Fecha'])); // Extraer solo la fecha

        // Realizar cálculo
        $resultado = $cantidad_adquirida * $precio_unitario;

        if ($resultado >= 0) {
            // Actualizar la tabla inversion_cultivos
            $sql = "UPDATE inversion_cultivos 
                    SET fertilizante = fertilizante - :resultado 
                    WHERE fecha = :fecha";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':resultado', $resultado, PDO::PARAM_STR);
            $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $stmt->execute();
        } else {
            throw new Exception("El resultado del cálculo es negativo. Verifica los datos.");
        }

        // Deshabilitar el registro en lugar de eliminarlo
        $sql = "UPDATE insumos_fertilizante 
                SET activo = FALSE 
                WHERE \"Id_fertilizante\" = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Registrar en la bitácora
        include("./../bitacora.php");
        $bitacora = new Bitacora($conn);
        $bitacora->deletebitacora("Fertilizante", $usuario, $id_usuario, $id);

        // Confirmar la transacción
        $conn->commit();
        $_SESSION['mensaje'] = "El registro se elimino con éxito.";
    } else {
        throw new Exception("No se encontró el registro con el ID especificado.");
    }
} catch (Exception $e) {
    // Revertir cambios en caso de error
    $conn->rollBack();
    $_SESSION['mensaje'] = "Error al procesar la solicitud: " . $e->getMessage();
}

// Redirigir
header("Location: ./../fertilizante.php");

// Cerrar conexión
$conn = null;
?>
