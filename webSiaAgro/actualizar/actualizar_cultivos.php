<?php
session_start();
include("../conexion/conexion.php");
$conn = cconexion::ConexionBD();

try {
    // Recuperar los valores del formulario
    $nombre_cultivo = $_POST['nombre_cultivo'];
    $tipo_cultivo = $_POST['tipo_cultivo'];
    $espacio = $_POST['espacio'];
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

    // Preparar la consulta SQL
    $sql = "INSERT INTO cultivos 
        (nombre, tipo, espacio, cosecha_estimada, fecha_siembra, fecha_cosecha, fecha_aspercion, nombre_producto, 
        dosis, tipo_aspercion, tipo_fertilizante, fecha_fertilizacion, observaciones, tipo_riego, cantidad_fertilizante) 
        VALUES 
        (:nombre_cultivo, :tipo_cultivo, :espacio, :cosecha_estimada, :fecha_siembra, :fecha_cosecha, :fecha_aspercion, :nombre_producto, 
        :dosis, :tipo_aspercion, :tipo_fertilizante, :fecha_fertilizacion, :observaciones, :tipo_riego, :cantidad_fertilizante)";

    $stmt = $conn->prepare($sql);

    // Vincular los parámetros con los valores del formulario
    $stmt->bindParam(':nombre_cultivo', $nombre_cultivo);
    $stmt->bindParam(':tipo_cultivo', $tipo_cultivo);
    $stmt->bindParam(':espacio', $espacio);
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

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Guardar el nombre de la tabla y el número de registro en variables
        $tabla = "cultivos";
        $numero_registro = $conn->lastInsertId(); // Obtener el ID del último registro insertado

        // Incluir el archivo bitacora.php y registrar el evento
        include("../bitacora.php");
        $bitacora = new Bitacora($conn);
        $bitacora->insertbitacora($tabla, $usuario, $id_usuario, $numero_registro);

        $_SESSION['mensaje'] = "El registro se guardó con éxito.";
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar agregar un registro.";
    }
} catch (PDOException $e) {
    // Manejo de errores
    $_SESSION['mensaje'] = "Error al insertar: " . $e->getMessage();
}

// Redirigir de vuelta a la página de cultivos
header("Location: ../cultivos.php");

// Cerrar la conexión
$conn = null;
?>
