<?php
session_start();
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}

$tipo_comida = $_POST["tipo_comida"];
$cantidad_adquirida = $_POST["cantidad"];
$precio_unitario = $_POST["precio"];
$notas = $_POST["notas"];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

$total = $precio_unitario * $cantidad_adquirida;

try {
    // Iniciar una transacción
    $conn->beginTransaction();

    // Verificar si hay registros con la fecha actual en la tabla inversion
    $sql = "SELECT * FROM inversion WHERE fecha = CURRENT_DATE";
    $stmt = $conn->query($sql);
    $resultado_select = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($resultado_select) > 0) {
        $row = $resultado_select[0];
        $comida_actual = $row['comida'];
        $nuevo_comida = $comida_actual + $total;

        $sql = "UPDATE inversion SET comida = :nuevo_comida WHERE fecha = CURRENT_DATE";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nuevo_comida', $nuevo_comida, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        $sql = "INSERT INTO inversion (fecha, comida) VALUES (CURRENT_DATE, :total)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':total', $total, PDO::PARAM_INT);
        $stmt->execute();
    }

    $sql = "INSERT INTO comida_animal(tipo_comida, cantidad_kilos, precio_unitario, notas, total_costo)
            VALUES (:tipo_comida, :cantidad_adquirida, :precio_unitario, :notas, :total)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':tipo_comida', $tipo_comida, PDO::PARAM_STR);
    $stmt->bindParam(':cantidad_adquirida', $cantidad_adquirida, PDO::PARAM_INT);
    $stmt->bindParam(':precio_unitario', $precio_unitario, PDO::PARAM_INT);
    $stmt->bindParam(':notas', $notas, PDO::PARAM_STR);
    $stmt->bindParam(':total', $total, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $tabla = "Comida Animal";
        $numero_registro = $conn->lastInsertId();
        include("../bitacora.php");
        $bitacora = new Bitacora($conn);
        $bitacora->insertbitacora($tabla, $usuario, $id_usuario, $numero_registro);
        $_SESSION['mensaje'] = "El registro se guardó con éxito.";
        $conn->commit();
    } else {
        $conn->rollBack();
        $_SESSION['mensaje'] = "Ocurrió un error al intentar agregar un registro.";
    }
} catch (PDOException $e) {
    // Revertir la transacción en caso de excepción
    $conn->rollBack();
    error_log("Error al ejecutar la consulta: " . $e->getMessage(), 3, "/var/log/php_errors.log");
    echo "Error al ejecutar la consulta: " . $e->getMessage();
    exit;
}

header("Location: ../comida_animal.php");
// Cerrar la conexión a la base de datos
$conn = null;
?>
