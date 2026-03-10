<?php
session_start();
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}
$Fecha_inicio_dieta = $_POST['Fecha_inicio_dieta'];
$Fecha_final_dieta = $_POST['Fecha_final_dieta'];
$Proteinas = $_POST['Proteinas'];
$Nota = $_POST['Nota'];
$Animal = $_POST['Animal'];
$Ingredientes = $_POST['Ingredientes'];
$Precio = $_POST['Precio'];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];


$sql = "SELECT * FROM inversion WHERE fecha = CURRENT_DATE";

$stmt = $conn->prepare($sql);
$stmt->execute();

if (!$stmt) {
    die("Error al ejecutar la consulta: " . $stmt->errorInfo());
}

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $dieta_actual = $row['dieta'];
  
    $nueva_dieta = $dieta_actual + $Precio;
  
    $sql = "UPDATE inversion SET dieta = :nueva_dieta WHERE fecha = CURRENT_DATE";
  
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nueva_dieta', $nueva_dieta);
    $stmt->execute();
  
    if (!$stmt) {
        die("Error al actualizar datos: " . $stmt->errorInfo());
    }
} else {
    $sql = "INSERT INTO inversion (fecha, dieta) VALUES (CURRENT_DATE, :precio)";
  
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':precio', $Precio);
    $stmt->execute();
  
    if (!$stmt) {
        die("Error al insertar datos: " . $stmt->errorInfo());
    } else {
        echo "Se ha insertado un nuevo registro en inversion_cultivos.";
    }
}

$sql = "INSERT INTO dieta_animal (\"Fecha_inicio_dieta\", \"Fecha_final_dieta\", \"Proteinas\", \"Nota\", id_animal, \"Ingredientes\", \"Precio\")
VALUES (:Fecha_inicio_dieta, :Fecha_final_dieta, :Proteinas, :Nota, :Animal, :Ingredientes, :Precio)";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':Fecha_inicio_dieta', $Fecha_inicio_dieta);
$stmt->bindParam(':Fecha_final_dieta', $Fecha_final_dieta);
$stmt->bindParam(':Proteinas', $Proteinas);
$stmt->bindParam(':Nota', $Nota);
$stmt->bindParam(':Animal', $Animal);
$stmt->bindParam(':Ingredientes', $Ingredientes);
$stmt->bindParam(':Precio', $Precio);

if ($stmt->execute()) {
    $tabla = "Dieta Animal";
    $numero_registro = $conn->lastInsertId();

    include("../bitacora.php");

    $bitacora = new Bitacora($conn);

    $bitacora->insertbitacora($tabla, $usuario, $id_usuario, $numero_registro);

    $_SESSION['mensaje'] = "El registro se guardó con éxito.";
} else {
    $_SESSION['mensaje'] = "Ocurrió un error al intentar agregar un registro.";
}

header("Location: ../dieta_animal.php");
?>
