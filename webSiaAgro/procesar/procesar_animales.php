<?php
session_start();

// Establecer la conexión con la base de datos
include_once("../conexion/conexion.php");

// Llamada al método estático para establecer la conexión
$conn = cconexion::ConexionBD();

// Verificar si la conexión fue exitosa
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit; // Salir del script si hay un error de conexión
}

// Recopilar los datos del formulario
$num_animales = $_POST['num_animales'];
$ganaderia_animales = $_POST['ganaderia_animales'];
$nombre_animales = $_POST['nombre_animales'];
$raza_animales = $_POST['raza_animales'];
$lote_animales = $_POST['lote_animales'];
$peso_animales = $_POST['peso_animales'];
$venta_animales = $_POST['venta_animales'];
$Sexo = $_POST['Sexo'];
$categoria = $_POST['categoria'];
$usuario = $_POST['session_acceso'];

// Verificar si se ha seleccionado una imagen
if (isset($_FILES['imagen_animales']) && $_FILES['imagen_animales']['error'] === UPLOAD_ERR_OK) {
    // Obtener los datos binarios de la imagen
    $imagen_binaria = file_get_contents($_FILES['imagen_animales']['tmp_name']);

   
    $sql = "INSERT INTO animales (\"N_animal\", \"Ganaderia\", \"Nombre\", \"Raza\", \"Lote\", \"Peso\", \"Venta\", \"Sexo\", \"Imagen\", \"Categoria\") 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bindParam(1, $num_animales);
$stmt->bindParam(2, $ganaderia_animales);
$stmt->bindParam(3, $nombre_animales);
$stmt->bindParam(4, $raza_animales);
$stmt->bindParam(5, $lote_animales);
$stmt->bindParam(6, $peso_animales);
$stmt->bindParam(7, $venta_animales);
$stmt->bindParam(8, $Sexo);
$stmt->bindParam(9, $imagen_binaria, PDO::PARAM_LOB);
$stmt->bindParam(10, $categoria);
    if ($stmt->execute()) {
        $numero_registro = $conn->lastInsertId(); // Obtener el ID insertado
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar agregar un registro.";
        header("Location: ../animales.php");
        exit;
    }
} else {
    // Si no se seleccionó ninguna imagen, insertar en la base de datos sin imagen
    $sql = "INSERT INTO animales (\"N_animal\", \"Ganaderia\", \"Nombre\", \"Raza\", \"Lote\", \"Peso\", \"Venta\", \"Sexo\", \"Categoria\") 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $num_animales);
    $stmt->bindParam(2, $ganaderia_animales);
    $stmt->bindParam(3, $nombre_animales);
    $stmt->bindParam(4, $raza_animales);
    $stmt->bindParam(5, $lote_animales);
    $stmt->bindParam(6, $peso_animales);
    $stmt->bindParam(7, $venta_animales);
    $stmt->bindParam(8, $Sexo);
    $stmt->bindParam(9, $categoria);

    if ($stmt->execute()) {
        $numero_registro = $conn->lastInsertId(); // Obtener el ID insertado
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar agregar un registro.";
        header("Location: ../animales.php");
        exit;
    }
}

// Consulta para obtener el precio de referencia
$Psql = "SELECT precio FROM precios";
$result = $conn->query($Psql);

// Obtener el precio de referencia si se encontró un registro
if ($result && $result->rowCount() > 0) {
    $fila = $result->fetch(PDO::FETCH_ASSOC);
    $precioReferencia = $fila["precio"];
} else {
    $_SESSION['mensaje'] = "No se encontró el precio de referencia en la tabla 'precios'.";
    header("Location: ../animales.php");
    exit;
}

// Consulta para actualizar el precio de los animales en venta
$Asql = "UPDATE animales SET precio = \"Peso\" * :precioReferencia WHERE  \"Venta\" = 'Venta'";
$stmt = $conn->prepare($Asql);
$stmt->bindValue(':precioReferencia', $precioReferencia);
if ($stmt->execute()) {
    $_SESSION['mensaje'] = "Se actualizó el precio de los animales en venta correctamente.";
} else {
    $_SESSION['mensaje'] = "Error al actualizar el precio de los animales: " . implode(" ", $stmt->errorInfo());
}

// Bitácora
$id_usuario = $_POST['session_id'];
$tabla = "Animales";

// Incluir el archivo bitacora.php
include("../bitacora.php");

// Crear una instancia de la clase Bitacora
$bitacora = new Bitacora($conn);

// Llamar al método insertbitacora() con los argumentos correctos
$bitacora->insertbitacora($tabla, $usuario, $id_usuario, $numero_registro);

$_SESSION['mensaje'] = "El registro se guardó con éxito.";

// Cerrar la conexión
$conn = null;

header("Location: ../animales.php");
exit;
?>
