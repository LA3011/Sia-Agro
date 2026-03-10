<?php
session_start();
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}

$id = $_POST['id_comida'];
$tipo_comida = $_POST["tipo_comida"];
$cantidad_adquirida = $_POST["cantidad"];
$precio_unitario = $_POST["precio"];
$notas = $_POST["notas"];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

try {
    $conn->beginTransaction();
    $sql = "SELECT precio_unitario, cantidad_kilos FROM comida_animal WHERE id_comida = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        $precio_actual = $resultado['precio_unitario'];
        $cantidad_actual = $resultado['cantidad_kilos'];
        if (!empty($precio_unitario)) {
            $precio_actual = $precio_unitario;
        }

        if (!empty($cantidad_adquirida)) {
            $cantidad_actual = $cantidad_adquirida;
        }

        $total = $precio_actual * $cantidad_actual;

        $sql = "UPDATE comida_animal SET tipo_comida = :tipo_comida, cantidad_kilos = :cantidad_actual, precio_unitario = :precio_actual, total_costo = :total, notas = :notas WHERE id_comida = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':tipo_comida', $tipo_comida, PDO::PARAM_STR);
        $stmt->bindParam(':cantidad_actual', $cantidad_actual, PDO::PARAM_INT);
        $stmt->bindParam(':precio_actual', $precio_actual, PDO::PARAM_INT);
        $stmt->bindParam(':total', $total, PDO::PARAM_INT);
        $stmt->bindParam(':notas', $notas, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $tabla = "Comida Animal";
            $numero_registro = $id;
            include("../bitacora.php");
    $bitacora = new Bitacora($conn);
    $bitacora->updatebitacora($tabla, $usuario, $id_usuario, $numero_registro);

    $_SESSION['mensaje'] = "El registro se actualizó con éxito.";
            $conn->commit();
        }
    }else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar actualizar un registro.";
    }
} catch (PDOException $e) {
    $conn->rollBack();
    error_log("Error al ejecutar la consulta: " . $e->getMessage(), 3, "/var/log/php_errors.log");
    echo "Error al ejecutar la consulta: " . $e->getMessage();
    exit;
}
header("Location: ../comida_animal.php");
$conn = null;
?>
