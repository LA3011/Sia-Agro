<?php
session_start();
include_once("../conexion/conexion.php");

// Obtener la conexión PDO
$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error al conectar a la base de datos.");
}

// Validar y recibir datos del formulario
$nombre_fertilizante = $_POST["nombre_fertilizante"];
$tipo_fertilizante = $_POST["tipo_fertilizante"];
$tipo_presentacion = $_POST["tipo_presentacion"];
$marca = $_POST["marca"];
$fecha_adquisicion = date("Y-m-d", strtotime($_POST["Fecha_adquisicion"])); // Formato de fecha
$fecha_vencimiento = date("Y-m-d", strtotime($_POST["Fecha_vencimiento"]));
$precio_unitario = (float) $_POST["precio_unitario"];
$cantidad_adquirida = (int) $_POST["cantidad_adquirida"];
$total = $precio_unitario * $cantidad_adquirida;
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

try {
    // Iniciar transacción
    $conn->beginTransaction();

    // Verificar si hay registros con la fecha actual en la tabla inversion_cultivos
    $sql = "SELECT fertilizante FROM inversion_cultivos WHERE fecha = CURRENT_DATE";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Calcular el nuevo valor del campo fertilizante
        $nuevo_fertilizante = $row['fertilizante'] + $total;

        // Actualizar el campo fertilizante
        $sql = "UPDATE inversion_cultivos 
                SET fertilizante = :nuevo_fertilizante 
                WHERE fecha = CURRENT_DATE";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nuevo_fertilizante', $nuevo_fertilizante, PDO::PARAM_STR);
        $stmt->execute();
    } else {
        // Insertar un nuevo registro en la tabla inversion_cultivos
        $sql = "INSERT INTO inversion_cultivos (fecha, fertilizante) 
                VALUES (CURRENT_DATE, :total)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':total', $total, PDO::PARAM_STR);
        $stmt->execute();
    }

    // Insertar el registro en la tabla insumos_fertilizante
    $sql = "INSERT INTO insumos_fertilizante 
            (nombre_fertilizante, tipo_fertilizante, tipo_presentacion, marca, \"Fecha_adquisicion\", \"Fecha_vencimiento\", precio_unitario, cantidad_adquirida,\"Fecha\")
            VALUES (:nombre_fertilizante, :tipo_fertilizante, :tipo_presentacion, :marca, :fecha_adquisicion, :fecha_vencimiento, :precio_unitario, :cantidad_adquirida, NOW())
            RETURNING \"Id_fertilizante\"";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre_fertilizante', $nombre_fertilizante, PDO::PARAM_STR);
    $stmt->bindParam(':tipo_fertilizante', $tipo_fertilizante, PDO::PARAM_STR);
    $stmt->bindParam(':tipo_presentacion', $tipo_presentacion, PDO::PARAM_STR);
    $stmt->bindParam(':marca', $marca, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_adquisicion', $fecha_adquisicion, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_vencimiento', $fecha_vencimiento, PDO::PARAM_STR);
    $stmt->bindParam(':precio_unitario', $precio_unitario, PDO::PARAM_STR);
    $stmt->bindParam(':cantidad_adquirida', $cantidad_adquirida, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener el id del último registro insertado
    $numero_registro = $stmt->fetchColumn();

    // Guardar en la bitácora
    include("../bitacora.php");
    $bitacora = new Bitacora($conn);
    $bitacora->insertbitacora("Fertilizante", $usuario, $id_usuario, $numero_registro);

    // Confirmar la transacción
    $conn->commit();
    $_SESSION['mensaje'] = "El registro se guardó con éxito.";
} catch (PDOException $e) {
    // Revertir los cambios en caso de error
    $conn->rollBack();
    $_SESSION['mensaje'] = "Error al guardar el registro: " . $e->getMessage();
    echo $e->getMessage(); // Mostrar el error
}

header("Location: ../fertilizante.php");

$conn = null;
?>
