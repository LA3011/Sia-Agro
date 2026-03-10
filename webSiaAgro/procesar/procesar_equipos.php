<?php
session_start();
include_once("../conexion/conexion.php");

$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}

// Obtener los valores del formulario
$nombre_equipo = isset($_POST["nombre_equipo"]) ? $_POST["nombre_equipo"] : null;
$tipo_equipo = isset($_POST["tipo_equipo"]) ? $_POST["tipo_equipo"] : null;
$marca = isset($_POST["marca"]) ? $_POST["marca"] : null;
$Fecha_adquisicion = isset($_POST["Fecha_adquisicion"]) ? $_POST["Fecha_adquisicion"] : null;
$precio_unitario = isset($_POST["precio_unitario"]) ? $_POST["precio_unitario"] : null;
$cantidad_adquirida = isset($_POST["cantidad_adquirida"]) ? $_POST["cantidad_adquirida"] : null;
$usuario = isset($_POST['session_acceso']) ? $_POST['session_acceso'] : null;
$id_usuario = isset($_POST['session_id']) ? $_POST['session_id'] : null;

// Verificar que los valores necesarios no sean nulos
if ($nombre_equipo == null || $tipo_equipo == null || $marca == null || $Fecha_adquisicion == null || $precio_unitario == null || $cantidad_adquirida == null || $usuario == null || $id_usuario == null) {
    echo "Error: Todos los campos son requeridos.";
    exit;
}

// Calcular el total
$total = $precio_unitario * $cantidad_adquirida;

try {
    $conn->beginTransaction();

    // Verificar si hay registros con la fecha actual en la tabla inversion_cultivos
    $sql = "SELECT id_inversion, equipos FROM inversion_cultivos WHERE fecha = CURRENT_DATE";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $id_inversion = null;

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Si existe un registro, actualizar el campo equipos
        $id_inversion = $row['id_inversion'];
        $equipos_actual = $row['equipos'] ?? 0;
        $nuevo_equipos = $equipos_actual + $total;

        $update_sql = "UPDATE inversion_cultivos 
                       SET equipos = :nuevo_equipos 
                       WHERE id_inversion = :id_inversion";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bindParam(':nuevo_equipos', $nuevo_equipos);
        $stmt_update->bindParam(':id_inversion', $id_inversion);
        $stmt_update->execute();
    } else {
        // Si no existe un registro, insertar uno nuevo
        $insert_sql = "INSERT INTO inversion_cultivos (fecha, equipos, fertilizante, funguisida, semillas, trial360) 
                       VALUES (CURRENT_DATE, :total, 0, 0, 0, 0) RETURNING id_inversion";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bindParam(':total', $total);
        $stmt_insert->execute();

        // Recuperar el ID del nuevo registro
        $id_inversion = $stmt_insert->fetchColumn();
    }

    // Inserción en la tabla 'insumos_equipos' con el id_inversion
    $sql_insert = "INSERT INTO insumos_equipos(nombre_equipo, tipo_equipo, marca, cantidad_adquirida, \"Fecha_adquisicion\", precio_unitario, id_inversion) 
                   VALUES (:nombre_equipo, :tipo_equipo, :marca, :cantidad_adquirida, :Fecha_adquisicion, :precio_unitario, :id_inversion)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bindParam(':nombre_equipo', $nombre_equipo);
    $stmt_insert->bindParam(':tipo_equipo', $tipo_equipo);
    $stmt_insert->bindParam(':marca', $marca);
    $stmt_insert->bindParam(':cantidad_adquirida', $cantidad_adquirida);
    $stmt_insert->bindParam(':Fecha_adquisicion', $Fecha_adquisicion);
    $stmt_insert->bindParam(':precio_unitario', $precio_unitario);
    $stmt_insert->bindParam(':id_inversion', $id_inversion);
    $stmt_insert->execute();

    // Obtener el ID del último registro insertado en la tabla 'insumos_equipos'
    $numero_registro = $conn->lastInsertId();

    // Registrar en la bitácora
    include("../bitacora.php");
    $bitacora = new Bitacora($conn);
    $bitacora->insertbitacora("Equipos", $usuario, $id_usuario, $numero_registro);

    $_SESSION['mensaje'] = "El registro se guardó con éxito.";
    $conn->commit();

    header("Location: ../equipos.php");
    exit;
} catch (PDOException $e) {
    $conn->rollBack();
    echo "Error al procesar los datos: " . $e->getMessage();
    exit;
} finally {
    $conn = null;
}
?>
