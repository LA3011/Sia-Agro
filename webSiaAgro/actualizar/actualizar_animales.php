<?php
session_start();
include_once("../conexion/conexion.php");

// Llamada al método estático para establecer la conexión
$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error de conexión: " . pg_last_error());
}

// Asegurar que la conexión esté usando UTF-8
$conn->exec("SET NAMES 'UTF8'");

$id_usuario = $_POST['session_id'];
$Id_Animal = $_POST['Id_Animal'];
$num_animales = mb_convert_encoding($_POST['num_animales'], 'UTF-8', 'auto');
$ganaderia_animales = mb_convert_encoding($_POST['ganaderia_animales'], 'UTF-8', 'auto');
$nombre_animales = mb_convert_encoding($_POST['nombre_animales'], 'UTF-8', 'auto');
$lote_animales = mb_convert_encoding($_POST['lote_animales'], 'UTF-8', 'auto');
$peso_animales = $_POST['peso_animales'];
$venta_animales = mb_convert_encoding($_POST['venta_animales'], 'UTF-8', 'auto');
$Sexo = mb_convert_encoding($_POST['Sexo'], 'UTF-8', 'auto');
$categoria = mb_convert_encoding($_POST['categoria'], 'UTF-8', 'auto');
$usuario = $_POST['session_acceso'];

$numero_registro = ''; // Variable para almacenar el ID del registro actualizado

// Verificar si se ha seleccionado una imagen
if (isset($_FILES['imagen_animales']) && $_FILES['imagen_animales']['error'] === UPLOAD_ERR_OK) {
    // Obtener los datos binarios de la imagen
    $imagen_binaria = file_get_contents($_FILES['imagen_animales']['tmp_name']);

    // Actualizar el registro con imagen
    $sql = "UPDATE animales SET \"N_animal\" = :num_animales, \"Ganaderia\" = :ganaderia_animales, \"Nombre\" = :nombre_animales, \"Lote\" = :lote_animales, \"Peso\" = :peso_animales, \"Venta\" = :venta_animales, \"Sexo\" = :Sexo, \"Imagen\" = :imagen_binaria, \"Categoria\" = :categoria WHERE \"Id_animal\" = :Id_Animal";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':num_animales', $num_animales);
    $stmt->bindParam(':ganaderia_animales', $ganaderia_animales);
    $stmt->bindParam(':nombre_animales', $nombre_animales);
    $stmt->bindParam(':lote_animales', $lote_animales);
    $stmt->bindParam(':peso_animales', $peso_animales);
    $stmt->bindParam(':venta_animales', $venta_animales);
    $stmt->bindParam(':Sexo', $Sexo);
    $stmt->bindParam(':imagen_binaria', $imagen_binaria, PDO::PARAM_LOB);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':Id_Animal', $Id_Animal);
    $result = $stmt->execute();

    if ($result) {
        $numero_registro = $Id_Animal; // Obtener el ID del registro actualizado
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar actualizar el registro.";
        header("Location: ../animales.php");
        exit;
    }
} else {
    // Si no se seleccionó ninguna imagen, actualizar en la base de datos sin imagen
    $sql = "UPDATE animales SET \"N_animal\" = :num_animales, \"Ganaderia\" = :ganaderia_animales, \"Nombre\" = :nombre_animales, \"Lote\" = :lote_animales, \"Peso\" = :peso_animales, \"Venta\" = :venta_animales, \"Sexo\" = :Sexo, \"Categoria\" = :categoria WHERE \"Id_animal\" = :Id_Animal";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':num_animales', $num_animales);
    $stmt->bindParam(':ganaderia_animales', $ganaderia_animales);
    $stmt->bindParam(':nombre_animales', $nombre_animales);
    $stmt->bindParam(':lote_animales', $lote_animales);
    $stmt->bindParam(':peso_animales', $peso_animales);
    $stmt->bindParam(':venta_animales', $venta_animales);
    $stmt->bindParam(':Sexo', $Sexo);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':Id_Animal', $Id_Animal);
    $result = $stmt->execute();

    if ($result) {
        $numero_registro = $Id_Animal; // Obtener el ID del registro actualizado
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar actualizar el registro.";
        header("Location: ../animales.php");
        exit;
    }
}

// Consulta para obtener el precio de referencia
$Psql = "SELECT precio FROM precios";
$stmt = $conn->query($Psql);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener el precio de referencia si se encontró un registro
if ($result) {
    $precioReferencia = $result['precio'];
} else {
    $_SESSION['mensaje'] = "No se encontró el precio de referencia en la tabla 'precios'.";
    header("Location: ../animales.php");
    exit;
}

// Consulta para actualizar el precio de los animales en venta
$Asql = "UPDATE animales SET precio = \"Peso\" * :precioReferencia WHERE  \"Venta\" = 'Venta'";
$stmt = $conn->prepare($Asql);
$stmt->bindParam(':precioReferencia', $precioReferencia);
$result = $stmt->execute();

if ($result) {
    $_SESSION['mensaje'] = "Se actualizó el precio de los animales en venta correctamente."; 
} else {
    $_SESSION['mensaje'] = "Error al actualizar el precio de los animales.";
}

// Bitácora
$tabla = "Animales";
include("../bitacora.php");
$bitacora = new Bitacora($conn);
$bitacora->updatebitacora($tabla, $usuario, $id_usuario, $numero_registro);

$_SESSION['mensaje'] = "El registro se actualizó con éxito.";
echo '<script>window.location.href = "../animales.php";</script>';
exit;
?>
