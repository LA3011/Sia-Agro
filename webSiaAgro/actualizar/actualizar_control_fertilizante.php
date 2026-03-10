<?php
session_start();
include_once("../conexion/conexion.php");

// Establecer conexión con la base de datos
$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error al conectar a la base de datos.");
}

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Recibir datos del formulario
$Id_fertilizante = $_POST["Id_fertilizante"];
$Parcela = $_POST["Parcela"];
$Dosis = $_POST["Dosis"];
$Cantidad = $_POST["Cantidad"];
$Tipo_cultivo = $_POST["Tipo_cultivo"];
$Prioridad = $_POST["Prioridad"];
$Fecha_inicial = $_POST["Fecha_inicial"];
$Fecha_final = $_POST["Fecha_final"];
$Encargado = $_POST["Encargado"];
$Maquinaria = $_POST["Maquinaria"];
$Nota = $_POST["Nota"];
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];

try {
    // Preparar la consulta SQL para actualizar datos
    $sql = "UPDATE control_fertilizante 
            SET \"Parcela\" = :Parcela, 
                \"Dosis\" = :Dosis, 
                \"Cantidad\" = :Cantidad, 
                \"Tipo_cultivo\" = :Tipo_cultivo, 
                \"Prioridad\" = :Prioridad, 
                \"Fecha_inicial\" = :Fecha_inicial, 
                \"Fecha_final\" = :Fecha_final, 
                \"Encargado\" = :Encargado, 
                \"Maquinaria\" = :Maquinaria, 
                \"Nota\" = :Nota 
            WHERE \"Id_fertilizante\" = :Id_fertilizante";

    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bindParam(':Parcela', $Parcela);
    $stmt->bindParam(':Dosis', $Dosis);
    $stmt->bindParam(':Cantidad', $Cantidad);
    $stmt->bindParam(':Tipo_cultivo', $Tipo_cultivo);
    $stmt->bindParam(':Prioridad', $Prioridad);
    $stmt->bindParam(':Fecha_inicial', $Fecha_inicial);
    $stmt->bindParam(':Fecha_final', $Fecha_final);
    $stmt->bindParam(':Encargado', $Encargado);
    $stmt->bindParam(':Maquinaria', $Maquinaria);
    $stmt->bindParam(':Nota', $Nota);
    $stmt->bindParam(':Id_fertilizante', $Id_fertilizante);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $tabla = "Control Fertilizante";
        $numero_registro = $Id_fertilizante;

        // Incluir el archivo bitacora.php
        include("../bitacora.php");

        // Crear una instancia de la clase Bitacora
        $bitacora = new Bitacora($conn);

        // Llamar al método updatebitacora() con los argumentos correctos
        $bitacora->updatebitacora($tabla, $usuario, $id_usuario, $numero_registro);

        // Guardar mensaje en la sesión
        $_SESSION['mensaje'] = "El registro se actualizó con éxito.";
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar actualizar el registro.";
    }
} catch (PDOException $e) {
    // Manejo de errores
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
}

// Redirigir al usuario
header("Location: ../control_fertilizante.php");
?>
