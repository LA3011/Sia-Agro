<?php
session_start();
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();
if (!$conn) {
    echo "Error al conectar a la base de datos.";
    exit;
}

$animal = $_POST['animal'];
$tipo_Tratamiento = $_POST['tipo_Tratamiento'];
$name_Tratamiento = $_POST['name_Tratamiento'];
$Veterinario = $_POST['Veterinario'];
$Diagnostico = $_POST['Diagnostico'];
$Fecha_Tratamiento = $_POST['Fecha_Tratamiento'];
$Precio = $_POST['Precio'];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

try {
    $sql = "SELECT * FROM inversion WHERE fecha = CURRENT_DATE";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $veterinario_actual = $row['veterinario'];
        $nuevo_veterinario = $veterinario_actual + $Precio;
        $sql = "UPDATE inversion SET veterinario = :nuevo_veterinario WHERE fecha = CURRENT_DATE";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nuevo_veterinario', $nuevo_veterinario);
        $stmt->execute();
    } else {
        $sql = "INSERT INTO inversion (fecha, veterinario) VALUES (CURRENT_DATE, :precio)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':precio', $Precio);
        $stmt->execute();
    }
    $sql = "INSERT INTO datos_veterinarios (id_animal, \"Tipo_Tratamiento\", \"Nombre_tratamiento\", \"Veterinario\", \"Diagnostico\", \"Dias_tratamiento\", \"Precio\")
    VALUES (:animal, :tipo_Tratamiento, :name_Tratamiento, :Veterinario, :Diagnostico, :Fecha_Tratamiento, :Precio)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':animal', $animal);
    $stmt->bindParam(':tipo_Tratamiento', $tipo_Tratamiento);
    $stmt->bindParam(':name_Tratamiento', $name_Tratamiento);
    $stmt->bindParam(':Veterinario', $Veterinario);
    $stmt->bindParam(':Diagnostico', $Diagnostico);
    $stmt->bindParam(':Fecha_Tratamiento', $Fecha_Tratamiento);
    $stmt->bindParam(':Precio', $Precio);
    $stmt->execute();
    $numero_registro = $conn->lastInsertId();
    $tabla = "datos_veterinarios";

    include("../bitacora.php");
    $bitacora = new Bitacora($conn);
    $bitacora->insertbitacora($tabla, $usuario, $id_usuario, $numero_registro);

    $_SESSION['mensaje'] = "El registro se guardó con éxito.";

    header("Location: ../datos_veterinarios.php");
} catch (PDOException $e) {
    echo "Error al insertar datos: " . $e->getMessage();
}
$conn = null;
?>
