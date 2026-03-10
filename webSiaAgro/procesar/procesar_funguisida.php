<?php
session_start();
include_once("../conexion/conexion.php");

try {
    $conn = cconexion::ConexionBD();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

// Capturar datos del formulario
$usuario = $_POST['session_acceso'] ?? null;
$id_usuario = $_POST['session_id'] ?? null;
$nombre_funguisida = $_POST["nombre_funguisida"] ?? null;
$tipo_funguisida = $_POST["tipo_funguisida"] ?? null;
$tipo_presentacion = $_POST["tipo_presentacion"] ?? null;
$marca = $_POST["marca"] ?? null;
$Fecha_adquisicion = $_POST["Fecha_adquisicion"] ?? null;
$Fecha_vencimiento = $_POST["Fecha_vencimiento"] ?? null;
$precio_unitario = $_POST["precio_unitario"] ?? 0;
$cantidad_adquirida = $_POST["cantidad_adquirida"] ?? 0;
$unidad_medida = $_POST["unidad_medida"] ?? null;
$composicion = $_POST["composicion"] ?? null;
$total = $precio_unitario * $cantidad_adquirida;

try {
    // Verificar si hay registros con la fecha actual en inversion_cultivos
    $sql = "SELECT id_inversion, funguisida FROM inversion_cultivos WHERE fecha = CURRENT_DATE";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Actualizar el valor sumando el total
        $id_inversion = $row['id_inversion'];
        $nuevo_fungicida = $row['funguisida'] + $total;
        $sql = "UPDATE inversion_cultivos SET funguisida = :nuevo_fungicida WHERE id_inversion = :id_inversion";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':nuevo_fungicida' => $nuevo_fungicida, ':id_inversion' => $id_inversion]);
    } else {
        // Insertar un nuevo registro y obtener el ID
        $sql = "INSERT INTO inversion_cultivos (fecha, funguisida, fertilizante, semillas, equipos, trial360) 
                VALUES (CURRENT_DATE, :total, 0, 0, 0, 'N')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':total' => $total]);
        $id_inversion = $conn->lastInsertId();
    }

    // Insertar en insumos_funguisidas con el ID correcto
    $sql = "INSERT INTO insumos_funguisidas (id_inversion, nombre_funguisida, tipo_funguisida, tipo_presentacion, marca, \"Fecha_adquisicion\", \"Fecha_vencimiento\", unidad_medida, precio_unitario, cantidad_adquirida, composicion) 
            VALUES (:id_inversion, :nombre_funguisida, :tipo_funguisida, :tipo_presentacion, :marca, :Fecha_adquisicion, :Fecha_vencimiento, :unidad_medida, :precio_unitario, :cantidad_adquirida, :composicion)";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id_inversion' => $id_inversion,
        ':nombre_funguisida' => $nombre_funguisida,
        ':tipo_funguisida' => $tipo_funguisida,
        ':tipo_presentacion' => $tipo_presentacion,
        ':marca' => $marca,
        ':Fecha_adquisicion' => $Fecha_adquisicion,
        ':Fecha_vencimiento' => $Fecha_vencimiento,
        ':unidad_medida' => $unidad_medida,
        ':precio_unitario' => $precio_unitario,
        ':cantidad_adquirida' => $cantidad_adquirida,
        ':composicion' => $composicion
    ]);

    $numero_registro = $conn->lastInsertId();

    // Registrar en bitácora
    include("../bitacora.php");
    $bitacora = new Bitacora($conn);
    $bitacora->insertbitacora("Agroquimicos", $usuario, $id_usuario, $numero_registro);

    $_SESSION['mensaje'] = "El registro se guardó con éxito.";
} catch (PDOException $e) {
    echo "Ocurrió un error: " . $e->getMessage();
    exit();
}

header("Location: ../funguisidas.php");
exit();
?>
