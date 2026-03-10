<?php
session_start();
include_once("../conexion/conexion.php");

// Establecer conexión con la base de datos
$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error al conectar a la base de datos.");
}

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Recibir datos del formulario
$id_actividades = $_POST["id_actividades"];
$nombre_actividad = $_POST["nombre_actividad"];
$detalle_actividad = $_POST["detalle_actividad"];
$fecha_inicio = $_POST["fechainicio"];
$fecha_final = $_POST["fechafinal"];
$espacio_usado = $_POST["espacio_usado"];
$tipo_cultivo = $_POST["tipo_cultivo"];
$responsable = $_POST["elabora"];
$cantidad_trabajadores = $_POST["cantidad_trabajadores"];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

try {
    // Preparar la consulta SQL para actualizar datos
    $sql = "UPDATE actividades 
            SET \"nombre_actividad\" = :nombre_actividad, 
                \"detalle_actividad\" = :detalle_actividad, 
                \"fechainicio\" = :fecha_inicio, 
                \"fechafinal\" = :fecha_final, 
                \"espacio_usado\" = :espacio_usado, 
                \"tipo_cultivo\" = :tipo_cultivo, 
                \"elaborada\" = :responsable, 
                \"cantidad_trabajadores\" = :cantidad_trabajadores 
            WHERE \"Id_actividades\" = :id_actividades";

    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bindParam(':nombre_actividad', $nombre_actividad);
    $stmt->bindParam(':detalle_actividad', $detalle_actividad);
    $stmt->bindParam(':fecha_inicio', $fecha_inicio);
    $stmt->bindParam(':fecha_final', $fecha_final);
    $stmt->bindParam(':espacio_usado', $espacio_usado);
    $stmt->bindParam(':tipo_cultivo', $tipo_cultivo);
    $stmt->bindParam(':responsable', $responsable);
    $stmt->bindParam(':cantidad_trabajadores', $cantidad_trabajadores, PDO::PARAM_INT);
    $stmt->bindParam(':id_actividades', $id_actividades, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $tabla = "Actividades Cultivos";
        $numero_registro = $id_actividades;

        // Incluir el archivo bitacora.php
        include("../bitacora.php");

        // Crear una instancia de la clase Bitacora
        $bitacora = new Bitacora($conn);

        // Llamar al método updatebitacora() con los argumentos correctos
        $bitacora->updatebitacora($tabla, $usuario, $id_usuario, $numero_registro);

        // Guardar mensaje en la sesión
        $_SESSION['mensaje'] = "El registro se actualizó con éxito.";
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar actualizar el registro.";
    }
} catch (PDOException $e) {
    // Manejo de errores
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
}

// Redirigir al usuario
header("Location: ../actividades_cultivos.php");
?>
