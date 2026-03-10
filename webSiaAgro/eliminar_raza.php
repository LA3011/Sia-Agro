<?php
include_once("conexion/conexion.php");

$conn = cconexion::ConexionBD();
if (!$conn) {
    echo json_encode(["success" => false, "message" => "Error de conexión"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true); // Recibe JSON

if (!isset($data["id_raza"]) || empty($data["id_raza"])) {
    echo json_encode(["success" => false, "message" => "ID de raza no proporcionado"]);
    exit;
}

$id_raza = intval($data["id_raza"]);

try {
    // Obtener el nombre de la raza basado en el ID
    $queryGetRaza = "SELECT raza FROM raza_animales WHERE id_raza = :id_raza";
    $stmtGetRaza = $conn->prepare($queryGetRaza);
    $stmtGetRaza->bindParam(":id_raza", $id_raza, PDO::PARAM_INT);
    $stmtGetRaza->execute();
    $razaRow = $stmtGetRaza->fetch(PDO::FETCH_ASSOC);

    if (!$razaRow) {
        echo json_encode(["success" => false, "message" => "⚠️ La raza no existe en la base de datos."]);
        exit;
    }

    $nombre_raza = $razaRow["raza"];

    // Verificar si hay animales con esta raza
    $queryCheck = "SELECT COUNT(*) AS total FROM animales WHERE \"Raza\" = :nombre_raza";
    $stmtCheck = $conn->prepare($queryCheck);
    $stmtCheck->bindParam(":nombre_raza", $nombre_raza, PDO::PARAM_STR);
    $stmtCheck->execute();
    $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($result["total"] > 0) {
        echo json_encode(["success" => false, "message" => "⚠️ No se puede eliminar la raza porque tiene animales registrados."]);
        exit;
    }

    // Si no hay registros en 'animales', proceder a eliminar la raza
    $queryDelete = "DELETE FROM raza_animales WHERE id_raza = :id_raza";
    $stmtDelete = $conn->prepare($queryDelete);
    $stmtDelete->bindParam(":id_raza", $id_raza, PDO::PARAM_INT);
    $stmtDelete->execute();

    if ($stmtDelete->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "✅ Raza eliminada con éxito"]);
    } else {
        echo json_encode(["success" => false, "message" => "⚠️ No se encontró la raza o ya fue eliminada"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "❌ Error en la consulta: " . $e->getMessage()]);
}

$conn = null;
?>
