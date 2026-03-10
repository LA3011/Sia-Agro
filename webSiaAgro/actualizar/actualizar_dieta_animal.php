<?php
session_start();
include_once("../conexion/conexion.php");

// Verificar la conexión
$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error de conexión: No se pudo conectar a la base de datos.");
}

$Id_Dieta = $_POST["Id_Dieta"];
$Fecha_inicio_dieta = $_POST['Fecha_inicio_dieta'];
$Fecha_final_dieta = $_POST['Fecha_final_dieta'];
$Proteinas = $_POST['Proteinas'];
$Nota = $_POST['Nota'];
$Animal = $_POST['Animal'];
$Ingredientes = $_POST['Ingredientes'];
$Precio = $_POST['Precio'];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

// Consulta SQL para actualizar la dieta animal
$sql = "UPDATE dieta_animal SET \"Fecha_inicio_dieta\" = :Fecha_inicio_dieta, \"Precio\" = :Precio, \"Fecha_final_dieta\" = :Fecha_final_dieta, \"Proteinas\" = :Proteinas, \"Nota\" = :Nota, id_animal = :Animal, \"Ingredientes\" = :Ingredientes WHERE \"Id_Dieta\" = :Id_Dieta";

try {
    // Preparar la consulta para actualizar la dieta animal
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':Fecha_inicio_dieta', $Fecha_inicio_dieta);
    $stmt->bindParam(':Fecha_final_dieta', $Fecha_final_dieta);
    $stmt->bindParam(':Proteinas', $Proteinas);
    $stmt->bindParam(':Nota', $Nota);
    $stmt->bindParam(':Animal', $Animal);
    $stmt->bindParam(':Ingredientes', $Ingredientes);
    $stmt->bindParam(':Precio', $Precio);
    $stmt->bindParam(':Id_Dieta', $Id_Dieta);

    // Ejecutar la consulta para actualizar la dieta animal
    if ($stmt->execute()) {
        // Guardar el nombre de la tabla y el número de registro en variables
        $tabla = "Dieta Animal";
        $numero_registro = $Id_Dieta;

        // Incluir el archivo bitacora.php
        include("../bitacora.php");

        // Crear una instancia de la clase Bitacora
        $bitacora = new Bitacora($conn);

        // Llamar al método updatebitacora() con los argumentos correctos
        $bitacora->updatebitacora($tabla, $usuario, $id_usuario, $numero_registro);

        $_SESSION['mensaje'] = "El registro se actualizó con éxito.";
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar actualizar el registro.";
    }
} catch(PDOException $e) {
    $_SESSION['mensaje'] = "Ocurrió un error: " . $e->getMessage();
}

// Redirigir de vuelta a la página de dieta_animal.php
header("Location: ../dieta_animal.php");
exit;
?>
