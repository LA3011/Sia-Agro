<?php
session_start();
include_once("../conexion/conexion.php");

// Obtén la conexión PDO
$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}

$nombre = $_POST["nombre"];
$tipo = $_POST["tipo"];
$cantidad_adquirida = $_POST["cantidad_adquirida"];
$Fecha_adquisicion = $_POST["Fecha_adquisicion"];
$Fecha_vencimiento = $_POST["Fecha_vencimiento"];
$precio_unitario = $_POST["precio_unitario"];
$codigo = $_POST["codigo"];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

$total = $precio_unitario * $cantidad_adquirida;

try {
    // Inicia una transacción
    $conn->beginTransaction();

    // Verificar si hay registros con la fecha actual en la tabla inversion_cultivos
    $sql = "SELECT semillas FROM inversion_cultivos WHERE fecha = CURRENT_DATE";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Actualizar el campo semillas sumando el valor total
        $nuevo_fertilizante = $row['semillas'] + $total;
        $sql = "UPDATE inversion_cultivos SET semillas = :nuevo_fertilizante WHERE fecha = CURRENT_DATE";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nuevo_fertilizante', $nuevo_fertilizante, PDO::PARAM_STR);
        $stmt->execute();
    } else {
        // Insertar un nuevo registro en inversion_cultivos
        $sql = "INSERT INTO inversion_cultivos (fecha, semillas) VALUES (CURRENT_DATE, :total)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':total', $total, PDO::PARAM_STR);
        $stmt->execute();
    }

    // Insertar en la tabla semillas con la fecha y hora actual
    $sql = "INSERT INTO semillas (nombre, tipo, cantidad_adquirida, Fecha_adquisicion, Fecha_vencimiento, precio_unitario, codigo, fecha) 
            VALUES (:nombre, :tipo, :cantidad_adquirida, :Fecha_adquisicion, :Fecha_vencimiento, :precio_unitario, :codigo, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    $stmt->bindParam(':cantidad_adquirida', $cantidad_adquirida, PDO::PARAM_INT);
    $stmt->bindParam(':Fecha_adquisicion', $Fecha_adquisicion, PDO::PARAM_STR);
    $stmt->bindParam(':Fecha_vencimiento', $Fecha_vencimiento, PDO::PARAM_STR);
    $stmt->bindParam(':precio_unitario', $precio_unitario, PDO::PARAM_STR);
    $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
    $stmt->execute();

    // Obtener el ID del último registro insertado
    $numero_registro = $conn->lastInsertId();

    // Registrar en la bitácora
    include("../bitacora.php");
    $bitacora = new Bitacora($conn);
    $bitacora->insertbitacora("Semillas", $usuario, $id_usuario, $numero_registro);

    // Confirmar la transacción
    $conn->commit();
    $_SESSION['mensaje'] = "El registro se guardó con éxito.";
} catch (PDOException $e) {
    // Revertir los cambios si ocurre un error
    $conn->rollBack();
    $_SESSION['mensaje'] = "Ocurrió un error: " . $e->getMessage();
}

// Redirigir a la página semillas.php
header("Location: ../semillas.php");
