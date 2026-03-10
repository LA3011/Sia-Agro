<?php
include("../conexion/conexion.php");
$conn = cconexion::ConexionBD();

$rif = $_REQUEST['rif'] ?? null;
$telefono = $_REQUEST['telefono'] ?? null;
$jsonData = array();

try {
    // Validar si se proporcionó el RIF
    if ($rif) {
        $selectQueryRif = "SELECT COUNT(*) AS total FROM empleados WHERE rif = :rif";
        $stmtRif = $conn->prepare($selectQueryRif);
        $stmtRif->bindParam(':rif', $rif, PDO::PARAM_STR);
        $stmtRif->execute();
        $resultadoRif = $stmtRif->fetch(PDO::FETCH_ASSOC);

        if ($resultadoRif['total'] > 0) {
            $jsonData['rif_success'] = 1;
            $jsonData['rif_message'] = '<p style="color:red;">Ya existe el RIF <strong>(' . htmlspecialchars($rif) . ')</strong></p>';
        } else {
            $jsonData['rif_success'] = 0;
            $jsonData['rif_message'] = '';
        }
    }

    // Validar si se proporcionó el número de teléfono
    if ($telefono) {
        $selectQueryTelefono = "SELECT COUNT(*) AS total FROM empleados WHERE numero_telefonico = :telefono";
        $stmtTelefono = $conn->prepare($selectQueryTelefono);
        $stmtTelefono->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $stmtTelefono->execute();
        $resultadoTelefono = $stmtTelefono->fetch(PDO::FETCH_ASSOC);

        if ($resultadoTelefono['total'] > 0) {
            $jsonData['telefono_success'] = 1;
            $jsonData['telefono_message'] = '<p style="color:red;">Ya existe el número telefónico <strong>(' . htmlspecialchars($telefono) . ')</strong></p>';
        } else {
            $jsonData['telefono_success'] = 0;
            $jsonData['telefono_message'] = '';
        }
    }
} catch (PDOException $e) {
    $jsonData['error'] = 'Error: ' . $e->getMessage();
}

header('Content-type: application/json; charset=utf-8');
echo json_encode($jsonData);