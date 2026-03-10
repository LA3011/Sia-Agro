<?php
session_start();
include_once("../conexion/conexion.php");

// Establecer conexión con PDO
$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error al conectar a la base de datos.");
}

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Recibir datos del formulario
$usuario = $_POST['session_acceso'];
$id_usuario = $_POST['session_id'];
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
$Fitosanitario = $_POST["Fitosanitario"];
$Nivel_plaga = $_POST["Nivel_plaga"];


try {
    // Preparar la consulta SQL para insertar datos
    $sql = "INSERT INTO control_plagas
            (\"Parcela\", \"Dosis\", \"Cantidad\", \"tipo_cultivo\", \"Fecha_inicial\", \"Fecha_final\", \"Encargado\", \"maquinaria\", \"Nota\",\"fitosanitario\",\"Nivel_plaga\" ) 
            VALUES (:Parcela, :Dosis, :Cantidad, :Tipo_cultivo,:Fecha_inicial, :Fecha_final, :Encargado, :Maquinaria, :Nota,:Fitosanitario,:Nivel_plaga)
            RETURNING \"Id_plagas\"";

    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bindParam(':Parcela', $Parcela);
    $stmt->bindParam(':Dosis', $Dosis);
    $stmt->bindParam(':Cantidad', $Cantidad);
    $stmt->bindParam(':Tipo_cultivo', $Tipo_cultivo);
    $stmt->bindParam(':Fecha_inicial', $Fecha_inicial);
    $stmt->bindParam(':Fecha_final', $Fecha_final);
    $stmt->bindParam(':Encargado', $Encargado);
    $stmt->bindParam(':Maquinaria', $Maquinaria);
    $stmt->bindParam(':Nota', $Nota);
    $stmt->bindParam(':Fitosanitario', $Fitosanitario);
    $stmt->bindParam(':Nivel_plaga', $Nivel_plaga);
    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Obtener el ID del registro insertado
        $numero_registro = $stmt->fetch(PDO::FETCH_ASSOC)['Id_plagas'];
        echo "Registro insertado correctamente. ID: " . $numero_registro;

        $tabla = "Control Plagas";
        include("../bitacora.php");

        $bitacora = new Bitacora($conn);
        $bitacora->insertbitacora($tabla, $usuario, $id_usuario, $numero_registro);

        $_SESSION['mensaje'] = "El registro se guardó con éxito.";
    } else {
        $_SESSION['mensaje'] = "Error al ejecutar la consulta.";
    }
} catch (PDOException $e) {
    // Manejo de errores
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    die($e->getMessage());
}

// Redirigir al usuario
header("Location: ../control_plagas.php");
?>
