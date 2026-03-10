<?php
session_start();
include_once("../conexion/conexion.php");

// Obtén la conexión PDO
$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}

$Id_equipo = $_POST["Id_equipo"];
$nombre_equipo = $_POST["nombre_equipo"];
$tipo_equipo = $_POST["tipo_equipo"];
$marca = $_POST["marca"];
$Fecha_adquisicion = $_POST["Fecha_adquisicion"];
$precio_unitario = $_POST["precio_unitario"];
$cantidad_adquirida = $_POST["cantidad_adquirida"];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

try {
    // Preparar la consulta SQL
    $sql = "UPDATE insumos_equipos 
            SET nombre_equipo = :nombre_equipo,
                tipo_equipo = :tipo_equipo,
                marca = :marca,
                \"Fecha_adquisicion\" = :Fecha_adquisicion,
                precio_unitario = :precio_unitario,
                cantidad_adquirida = :cantidad_adquirida
            WHERE \"Id_equipo\" = :Id_equipo";

    $stmt = $conn->prepare($sql);

    // Asignar los valores a los parámetros
    $stmt->bindParam(':Id_equipo', $Id_equipo, PDO::PARAM_INT);
    $stmt->bindParam(':nombre_equipo', $nombre_equipo, PDO::PARAM_STR);
    $stmt->bindParam(':tipo_equipo', $tipo_equipo, PDO::PARAM_STR);
    $stmt->bindParam(':marca', $marca, PDO::PARAM_STR);
    $stmt->bindParam(':Fecha_adquisicion', $Fecha_adquisicion, PDO::PARAM_STR);
    $stmt->bindParam(':precio_unitario', $precio_unitario, PDO::PARAM_STR);
    $stmt->bindParam(':cantidad_adquirida', $cantidad_adquirida, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $tabla = "Equipos";
        $numero_registro = $Id_equipo;

        // Incluir el archivo bitacora.php
        include("../bitacora.php");

        // Crear una instancia de la clase Bitacora
        $bitacora = new Bitacora($conn);

        // Llamar al método insertbitacora() con los argumentos correctos
        $bitacora->updatebitacora($tabla, $usuario, $id_usuario, $numero_registro);

        $_SESSION['mensaje'] = "El registro se actualizó con éxito.";
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar actualizar un registro.";
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error de base de datos: " . $e->getMessage();
}

header("Location: ../equipos.php");
// Cerrar la conexión a la base de datos
$conn = null;
?>
