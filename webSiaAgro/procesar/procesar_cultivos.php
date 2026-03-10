<?php
session_start();
include("../conexion/conexion.php");
$conn = cconexion::ConexionBD();

try {
    // Recuperar los valores del formulario
    $nombre_cultivo = $_POST['nombre_cultivo'];
    $tipo_cultivo = $_POST['tipo_cultivo'];
    $espacio = $_POST['espacio']; // Esto contiene "id_espacios:nombre_espacio"
    list($id_espacios, $nombre_espacio) = explode(':', $espacio); // Separar los valores
    $cosecha_estimada = $_POST['cosecha_estimada'];
    $fecha_siembra = $_POST['fecha_siembra'];
    $fecha_cosecha = $_POST['fecha_cosecha'];
    $fecha_aspercion = $_POST['fecha_aspercion'];
    $nombre_producto = $_POST['nombre_producto'];
    $dosis = $_POST['dosis'];
    $tipo_aspercion = $_POST['tipo_aspercion'];
    $fecha_fertilizacion = $_POST['fecha_fertilizacion'];
    $tipo_fertilizante = $_POST['tipo_fertilizante'];
    $observaciones = $_POST['observaciones'];
    $tipo_riego = $_POST['tipo_riego'];
    $cantidad_fertilizante = $_POST['cantidad_fertilizante'];
    $usuario = $_POST['session_acceso'];
    $id_usuario = $_POST['session_id'];

    // Preparar la consulta SQL para insertar en la tabla cultivos
    $sql = "INSERT INTO cultivos 
        (nombre, tipo, id_espacio, espacio, cosecha_estimada, fecha_siembra, fecha_cosecha, fecha_aspercion, nombre_producto, 
        dosis, tipo_aspercion, tipo_fertilizante, fecha_fertilizacion, observaciones, tipo_riego, cantidad_fertilizante, estado) 
        VALUES 
        (:nombre_cultivo, :tipo_cultivo, :id_espacios, :nombre_espacio, :cosecha_estimada, :fecha_siembra, :fecha_cosecha, 
        :fecha_aspercion, :nombre_producto, :dosis, :tipo_aspercion, :tipo_fertilizante, :fecha_fertilizacion, :observaciones, 
        :tipo_riego, :cantidad_fertilizante, FALSE)";

    $stmt = $conn->prepare($sql);

    // Vincular los parámetros con los valores del formulario
    $stmt->bindParam(':nombre_cultivo', $nombre_cultivo);
    $stmt->bindParam(':tipo_cultivo', $tipo_cultivo);
    $stmt->bindParam(':id_espacios', $id_espacios, PDO::PARAM_INT); // Insertar el id_espacios
    $stmt->bindParam(':nombre_espacio', $nombre_espacio); // Insertar el nombre del espacio
    $stmt->bindParam(':cosecha_estimada', $cosecha_estimada, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_siembra', $fecha_siembra);
    $stmt->bindParam(':fecha_cosecha', $fecha_cosecha);
    $stmt->bindParam(':fecha_aspercion', $fecha_aspercion);
    $stmt->bindParam(':nombre_producto', $nombre_producto);
    $stmt->bindParam(':dosis', $dosis, PDO::PARAM_INT);
    $stmt->bindParam(':tipo_aspercion', $tipo_aspercion);
    $stmt->bindParam(':tipo_fertilizante', $tipo_fertilizante);
    $stmt->bindParam(':fecha_fertilizacion', $fecha_fertilizacion);
    $stmt->bindParam(':observaciones', $observaciones);
    $stmt->bindParam(':tipo_riego', $tipo_riego);
    $stmt->bindParam(':cantidad_fertilizante', $cantidad_fertilizante, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Registrar en la bitácora
        $tabla = "cultivos";
        $numero_registro = $conn->lastInsertId();
        include("../bitacora.php");
        $bitacora = new Bitacora($conn);
        $bitacora->insertbitacora($tabla, $usuario, $id_usuario, $numero_registro);

        // Verificar si el id_espacios coincide con Id_espacios en la tabla espacios
        $consultaEspacios = "SELECT \"Id_espacios\" FROM espacios WHERE \"Id_espacios\" = :id_espacios";
        $stmtEspacios = $conn->prepare($consultaEspacios);
        $stmtEspacios->bindParam(':id_espacios', $id_espacios, PDO::PARAM_INT);
        $stmtEspacios->execute();
        $resultadoEspacios = $stmtEspacios->fetch(PDO::FETCH_ASSOC);

        if ($resultadoEspacios) {
            // Actualizar el campo estado de la tabla espacios a FALSE
            $actualizarEstado = "UPDATE espacios SET estado = FALSE WHERE \"Id_espacios\" = :id_espacios";
            $stmtActualizar = $conn->prepare($actualizarEstado);
            $stmtActualizar->bindParam(':id_espacios', $id_espacios, PDO::PARAM_INT);
            $stmtActualizar->execute();
        }

        $_SESSION['mensaje'] = "El registro se guardó con éxito.";
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar agregar un registro.";
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error al insertar: " . $e->getMessage();
}

// Redirigir al usuario
header("Location: ../cultivos.php");
$conn = null;
?>