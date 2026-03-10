<?php
session_start();
include_once("../conexion/conexion.php");

// Verificar la conexión
$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error de conexión: No se pudo conectar a la base de datos.");
}

$id = $_GET['id'];
$usuario = $_GET['session_acceso'];
$id_usuario = $_GET['session_id'];

try {
    // Obtener información del registro
    $sql = "SELECT cantidad_adquirida, precio_unitario, \"Fecha\" FROM insumos_equipos WHERE \"Id_equipo\" = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $cantidad_adquirida = $row['cantidad_adquirida'];
        $precio_unitario = $row['precio_unitario'];
        $fecha = $row['Fecha'];

        // Realizar cálculo
        $resultado = floatval($cantidad_adquirida) * floatval($precio_unitario);

        // Extraer solo la parte de fecha
        $fecha = date('Y-m-d', strtotime($fecha));

        // Verificar si el resultado es mayor o igual a cero
        if ($resultado >= 0) {
            // Actualizar la tabla inversiones
            $sql = "UPDATE inversion_cultivos 
                    SET equipos = equipos - :resultado 
                    WHERE fecha = :fecha";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':resultado', $resultado, PDO::PARAM_STR);
            $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $stmt->execute();
        } else {
            echo "El resultado es negativo. Verifica los valores de cantidad_adquirida y precio_unitario.";
            exit;
        }
    }

    // Deshabilitar el registro en lugar de eliminarlo
    $sql = "UPDATE insumos_equipos 
            SET estado = 0 
            WHERE \"Id_equipo\" = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $tabla = "Equipos";
        $numero_registro = $id;

        // Incluir el archivo bitacora.php
        include("./../bitacora.php");

        // Crear una instancia de la clase Bitacora
        $bitacora = new Bitacora($conn);

        // Llamar al método deletebitacora() con los argumentos correctos
        $bitacora->deletebitacora($tabla, $usuario, $id_usuario, $numero_registro);

        $_SESSION['mensaje'] = "El registro se elimino con éxito.";
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar deshabilitar el registro.";
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error de base de datos: " . $e->getMessage();
}

header("Location: ./../equipos.php");
// Cerrar la conexión
$conn = null;
?>
