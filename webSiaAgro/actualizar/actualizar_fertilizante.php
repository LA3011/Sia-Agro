<?php
session_start();
include_once("../conexion/conexion.php");

// Obtener la conexión PDO
$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}

$Id_fertilizante = $_POST["Id_fertilizante"];
$nombre_fertilizante = $_POST["nombre_fertilizante"];
$tipo_fertilizante = $_POST["tipo_fertilizante"];
$tipo_presentacion = $_POST["tipo_presentacion"];
$marca = $_POST["marca"];
$Fecha_adquisicion = $_POST["Fecha_adquisicion"];
$Fecha_vencimiento = $_POST["Fecha_vencimiento"];
$precio_unitario = $_POST["precio_unitario"]; 
$cantidad_adquirida = $_POST["cantidad_adquirida"];
$unidad_medida = $_POST["unidad_medida"];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

try {
    // Preparar la consulta SQL con parámetros
    $sql = "UPDATE insumos_fertilizante 
            SET nombre_fertilizante = :nombre_fertilizante,
                tipo_fertilizante = :tipo_fertilizante,
                tipo_presentacion = :tipo_presentacion,
                marca = :marca,
                \"Fecha_adquisicion\" = :Fecha_adquisicion,
                \"Fecha_vencimiento\" = :Fecha_vencimiento,
                precio_unitario = :precio_unitario,
                cantidad_adquirida = :cantidad_adquirida,
                unidad_medida = :unidad_medida
            WHERE \"Id_fertilizante\" = :Id_fertilizante";

    $stmt = $conn->prepare($sql);

    // Asignar valores a los parámetros
    $stmt->bindParam(':nombre_fertilizante', $nombre_fertilizante, PDO::PARAM_STR);
    $stmt->bindParam(':tipo_fertilizante', $tipo_fertilizante, PDO::PARAM_STR);
    $stmt->bindParam(':tipo_presentacion', $tipo_presentacion, PDO::PARAM_STR);
    $stmt->bindParam(':marca', $marca, PDO::PARAM_STR);
    $stmt->bindParam(':Fecha_adquisicion', $Fecha_adquisicion, PDO::PARAM_STR);
    $stmt->bindParam(':Fecha_vencimiento', $Fecha_vencimiento, PDO::PARAM_STR);
    $stmt->bindParam(':precio_unitario', $precio_unitario, PDO::PARAM_STR);
    $stmt->bindParam(':cantidad_adquirida', $cantidad_adquirida, PDO::PARAM_INT);
    $stmt->bindParam(':unidad_medida', $unidad_medida, PDO::PARAM_STR);
    $stmt->bindParam(':Id_fertilizante', $Id_fertilizante, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $tabla = "Fertilizante";
        $numero_registro = $Id_fertilizante;

        // Incluir el archivo bitacora.php
        include("../bitacora.php");

        // Crear una instancia de la clase Bitacora
        $bitacora = new Bitacora($conn);

        // Llamar al método updatebitacora() con los argumentos correctos
        $bitacora->updatebitacora($tabla, $usuario, $id_usuario, $numero_registro);

        $_SESSION['mensaje'] = "El registro se actualizó con éxito.";
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar actualizar un registro.";
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error de base de datos: " . $e->getMessage();
}

header("Location: ../fertilizante.php");

// Cerrar la conexión a la base de datos
$conn = null;
?>
