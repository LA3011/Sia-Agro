<?php
session_start();
include("../conexion/conexion.php");
$conn = cconexion::ConexionBD();

// Habilitar el modo de errores de PDO
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // Verificar los datos recibidos
    if (empty($_POST['espacio']) || empty($_POST['Tipo_suelo']) || empty($_POST['Cantidad_dias_secos']) || empty($_POST['Cantidad_dias_verdes']) || empty($_POST['Tipo_pasto']) || empty($_POST['area_expresada']) || empty($_POST['area'])) {
        die("Error: Faltan datos obligatorios.");
    }

    $espacio = $_POST['espacio'];
    if (!strpos($espacio, '-')) {
        die("Error: El formato de 'espacio' no es válido.");
    }

    list($poligono_id, $nombre_poligono) = explode('-', $espacio);

    $Tipo_suelo = $_POST['Tipo_suelo'];
    $Cantidad_dias_secos = $_POST['Cantidad_dias_secos'];
    $Cantidad_dias_verdes = $_POST['Cantidad_dias_verdes'];
    $Tipo_pasto = $_POST['Tipo_pasto'];
    $area_expresada = $_POST['area_expresada'];
    $area = $_POST['area'];

    // Insertar el potrero
    $sql = "INSERT INTO potreros (poligono_id, \"Nombre\", \"Tipo_suelo\", \"Cantidad_dias_secos\", \"Cantidad_dias_verdes\", \"Tipo_pasto\", area, area_expresada)
            VALUES (:poligono_id, :nombre_poligono, :Tipo_suelo, :Cantidad_dias_secos, :Cantidad_dias_verdes, :Tipo_pasto, :area, :area_expresada)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':poligono_id', $poligono_id);
    $stmt->bindParam(':nombre_poligono', $nombre_poligono);
    $stmt->bindParam(':Tipo_suelo', $Tipo_suelo);
    $stmt->bindParam(':Cantidad_dias_secos', $Cantidad_dias_secos);
    $stmt->bindParam(':Cantidad_dias_verdes', $Cantidad_dias_verdes);
    $stmt->bindParam(':Tipo_pasto', $Tipo_pasto);
    $stmt->bindParam(':area', $area);
    $stmt->bindParam(':area_expresada', $area_expresada);

    try {
        $stmt->execute();
    } catch (PDOException $e) {
        die("Error al guardar el potrero: " . $e->getMessage());
    }

    // Actualizar el estado del polígono
    $updateSql = "UPDATE poligono SET estado = 'Activo' WHERE id = :poligono_id";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':poligono_id', $poligono_id);

    try {
        $updateStmt->execute();
    } catch (PDOException $e) {
        die("Error al actualizar el estado del polígono: " . $e->getMessage());
    }

    $_SESSION['mensaje'] = "El registro se guardó con éxito.";
    header("Location: ../Espacios.php");
} catch (PDOException $e) {
    die("Error general: " . $e->getMessage());
}

$conn = null;
?>