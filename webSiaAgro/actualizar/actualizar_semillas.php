<?php
session_start();
include_once("../conexion/conexion.php");

// Verificar la conexión
$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error de conexión: No se pudo conectar a la base de datos.");
}

$id_semillas = $_POST["Id_semillas"];
$nombre = $_POST["nombre"];
$tipo = $_POST["tipo"];
$cantidad_adquirida = $_POST["cantidad_adquirida"];
$Fecha_adquisicion = $_POST["Fecha_adquisicion"];
$Fecha_vencimiento = $_POST["Fecha_vencimiento"];
$precio_unitario = $_POST["precio_unitario"];
$codigo = $_POST["codigo"];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

try {
    // Preparar la consulta SQL con parámetros nombrados
    $sql = "UPDATE semillas 
            SET nombre = :nombre,
                tipo = :tipo,
                cantidad_adquirida = :cantidad_adquirida,
               fecha_adquisicion = :Fecha_adquisicion,
                fecha_vencimiento = :Fecha_vencimiento,
                precio_unitario = :precio_unitario,
                codigo = :codigo
            WHERE id_semillas = :id_semillas";

    $stmt = $conn->prepare($sql);

    // Asignar los valores a los parámetros
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    $stmt->bindParam(':cantidad_adquirida', $cantidad_adquirida, PDO::PARAM_INT);
    $stmt->bindParam(':Fecha_adquisicion', $Fecha_adquisicion, PDO::PARAM_STR);
    $stmt->bindParam(':Fecha_vencimiento', $Fecha_vencimiento, PDO::PARAM_STR);
    $stmt->bindParam(':precio_unitario', $precio_unitario, PDO::PARAM_STR);
    $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
    $stmt->bindParam(':id_semillas', $id_semillas, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $tabla = "Semillas";
        $numero_registro = $id_semillas;

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

header("Location: ../semillas.php");
// Cerrar la conexión a la base de datos
$conn = null;
?>
