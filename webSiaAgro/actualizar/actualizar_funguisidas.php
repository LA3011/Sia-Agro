<?php
session_start();
include_once("../conexion/conexion.php");

// Verificar la conexión
$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error de conexión: No se pudo conectar a la base de datos.");
}

if (isset($_POST['actualizar'])) {
    // Capturar datos del formulario
    $Id_funguisida = $_POST["Id_funguisida"];
    $nombre_funguisida = $_POST["nombre_funguisida"];
    $tipo_funguisida = $_POST["tipo_funguisida"];
    $tipo_presentacion = $_POST["tipo_presentacion"];
    $marca = $_POST["marca"];
    $Fecha_adquisicion = $_POST["Fecha_adquisicion"];
    $Fecha_vencimiento = $_POST["Fecha_vencimiento"];
    $precio_unitario = $_POST["precio_unitario"];
    $cantidad_adquirida = $_POST["cantidad_adquirida"];
    $unidad_medida = $_POST["unidad_medida"];
    $composicion = $_POST["composicion"];
    $usuario = $_POST['session_acceso'];
    $id_usuario = $_POST['session_id'];

    try {
        // Habilitar manejo de excepciones PDO
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparar la consulta para actualizar
        $sql = "UPDATE insumos_funguisidas
                SET nombre_funguisida = :nombre_funguisida,
                    tipo_funguisida = :tipo_funguisida,
                    tipo_presentacion = :tipo_presentacion,
                    marca = :marca,
                    \"Fecha_adquisicion\" = :Fecha_adquisicion,
                    \"Fecha_vencimiento\" = :Fecha_vencimiento,
                    precio_unitario = :precio_unitario,
                    cantidad_adquirida = :cantidad_adquirida,
                    unidad_medida = :unidad_medida,
                    composicion = :composicion
                WHERE \"Id_funguisida\" = :Id_funguisida";

        $stmt = $conn->prepare($sql);

        // Vincular los parámetros
        $stmt->bindParam(':Id_funguisida', $Id_funguisida, PDO::PARAM_INT);
        $stmt->bindParam(':nombre_funguisida', $nombre_funguisida, PDO::PARAM_STR);
        $stmt->bindParam(':tipo_funguisida', $tipo_funguisida, PDO::PARAM_STR);
        $stmt->bindParam(':tipo_presentacion', $tipo_presentacion, PDO::PARAM_STR);
        $stmt->bindParam(':marca', $marca, PDO::PARAM_STR);
        $stmt->bindParam(':Fecha_adquisicion', $Fecha_adquisicion, PDO::PARAM_STR);
        $stmt->bindParam(':Fecha_vencimiento', $Fecha_vencimiento, PDO::PARAM_STR);
        $stmt->bindParam(':precio_unitario', $precio_unitario, PDO::PARAM_STR);
        $stmt->bindParam(':cantidad_adquirida', $cantidad_adquirida, PDO::PARAM_INT);
        $stmt->bindParam(':unidad_medida', $unidad_medida, PDO::PARAM_STR);
        $stmt->bindParam(':composicion', $composicion, PDO::PARAM_STR);

        // Ejecutar la consulta
        $stmt->execute();

        // Registro en la bitácora
        $tabla = "Agroquimicos";
        $numero_registro = $Id_funguisida;

        // Incluir y utilizar bitacora.php
        include("../bitacora.php");
        $bitacora = new Bitacora($conn);
        $bitacora->updatebitacora($tabla, $usuario, $id_usuario, $numero_registro);

        // Mensaje de éxito
        $_SESSION['mensaje'] = "El registro se actualizó con éxito.";
    } catch (PDOException $e) {
        // Mensaje de error
        $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    }
}

// Redirigir al usuario
header("Location: ../funguisidas.php");

// Cerrar la conexión a la base de datos
$conn = null;
?>
