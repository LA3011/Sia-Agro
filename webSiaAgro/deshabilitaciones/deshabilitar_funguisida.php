<?php
session_start();
include_once("../conexion/conexion.php");

// Establecer conexión con la base de datos
$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error de conexión: No se pudo conectar a la base de datos.");
}

$id = $_GET['id'];
$usuario = $_GET['session_acceso'];
$id_usuario = $_GET['session_id'];

try {
    // Habilitar manejo de errores en PDO
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Obtener datos del fungicida
    $sql = "SELECT cantidad_adquirida, precio_unitario, \"Fecha\" FROM insumos_funguisidas WHERE \"Id_funguisida\" = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $cantidad_adquirida = $row['cantidad_adquirida'];
        $precio_unitario = $row['precio_unitario'];
        $fecha = $row['Fecha'];

        // Realizar cálculo
        $resultado = $cantidad_adquirida * $precio_unitario;

        // Extraer solo la parte de fecha
        $fecha = date('Y-m-d', strtotime($fecha));

        // Actualizar tabla inversiones
        $sql = "UPDATE inversion_cultivos 
                SET funguisida = funguisida - :resultado 
                WHERE fecha = :fecha";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':resultado', $resultado, PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->execute();
    }

    // Cambiar el estado del fungicida a 'inactivo' en lugar de eliminarlo
    $sql = "UPDATE insumos_funguisidas 
            SET estado = 'inactivo' 
            WHERE \"Id_funguisida\" = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Registrar acción en la bitácora
    $tabla = "Agroquimicos";
    $numero_registro = $id;

    include("./../bitacora.php");

    $bitacora = new Bitacora($conn);
    $bitacora->deletebitacora($tabla, $usuario, $id_usuario, $numero_registro);

    $_SESSION['mensaje'] = "El registro fue deshabilitado con éxito.";
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
}

// Redirigir al usuario
header("Location: ./../funguisidas.php");
?>
