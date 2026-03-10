<?php
session_start();
include_once("../conexion/conexion.php");

try {
    // Establecer conexión con PDO
    $conn = cconexion::ConexionBD();
    if (!$conn) {
        throw new Exception("Error al conectar a la base de datos.");
    }
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener los datos del formulario
    $usuario = $_POST['session_acceso'];
    $id_usuario = $_POST['session_id'];
    $Id_plagas = $_POST["Id_plagas"];
    $Parcela = $_POST["Parcela"];
    $Dosis = $_POST["Dosis"];
    $Cantidad = $_POST["Cantidad"];
    $Tipo_cultivo = $_POST["Tipo_cultivo"];
    $Fitosanitario = $_POST["Fitosanitario"];
    $Nivel_plaga = $_POST["Nivel_plaga"];
    $Fecha_inicial = $_POST["Fecha_inicial"];
    $Fecha_final = $_POST["Fecha_final"];
    $Encargado = $_POST["Encargado"];
    $Maquinaria = $_POST["Maquinaria"];
    $Nota = $_POST["Nota"];

    // Preparar la consulta SQL
    $sql = "UPDATE control_plagas
            SET \"Parcela\" = :Parcela,
                \"Dosis\" = :Dosis,
                \"Cantidad\" = :Cantidad,
                \"tipo_cultivo\" = :Tipo_cultivo,
                \"fitosanitario\" = :Fitosanitario,
                \"Nivel_plaga\" = :Nivel_plaga,
                \"Fecha_inicial\" = :Fecha_inicial,
                \"Fecha_final\" = :Fecha_final,
                \"Encargado\" = :Encargado,
                \"maquinaria\" = :Maquinaria,
                \"Nota\" = :Nota
            WHERE \"Id_plagas\" = :Id_plagas";

    $stmt = $conn->prepare($sql);

    // Vincular los parámetros
    $stmt->bindParam(':Parcela', $Parcela);
    $stmt->bindParam(':Dosis', $Dosis);
    $stmt->bindParam(':Cantidad', $Cantidad);
    $stmt->bindParam(':Tipo_cultivo', $Tipo_cultivo);
    $stmt->bindParam(':Fitosanitario', $Fitosanitario);
    $stmt->bindParam(':Nivel_plaga', $Nivel_plaga);
    $stmt->bindParam(':Fecha_inicial', $Fecha_inicial);
    $stmt->bindParam(':Fecha_final', $Fecha_final);
    $stmt->bindParam(':Encargado', $Encargado);
    $stmt->bindParam(':Maquinaria', $Maquinaria);
    $stmt->bindParam(':Nota', $Nota);
    $stmt->bindParam(':Id_plagas', $Id_plagas);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Registrar en la bitácora
        $tabla = "Control Plagas";
        $numero_registro = $Id_plagas;
        
        include("../bitacora.php");
        $bitacora = new Bitacora($conn);
        $bitacora->updatebitacora($tabla, $usuario, $id_usuario, $numero_registro);

        $_SESSION['mensaje'] = "El registro se actualizó con éxito.";
    } else {
        $_SESSION['mensaje'] = "Ocurrió un error al intentar actualizar el registro.";
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
} catch (Exception $e) {
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
}

// Redirigir al usuario
header("Location: ../control_plagas.php");
?>
