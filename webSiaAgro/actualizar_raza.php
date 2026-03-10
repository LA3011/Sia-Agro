<?php
include_once("conexion/conexion.php");

$conn = cconexion::ConexionBD();
if (!$conn) {
    echo json_encode(["success" => false, "message" => "Error de conexión"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["id_raza"]) || empty($_POST["id_raza"]) || !isset($_POST["nombre_raza"]) || empty($_POST["nombre_raza"])) {
        echo json_encode(["success" => false, "message" => "Datos incompletos"]);
        exit;
    }

    $id_raza = intval($_POST["id_raza"]);
    $nombre_raza = trim($_POST["nombre_raza"]);

    try {
        // Si hay una nueva imagen, la actualizamos, de lo contrario solo se actualiza el nombre.
        if (isset($_FILES["imagen_raza"]) && $_FILES["imagen_raza"]["size"] > 0) {
            $imagen = file_get_contents($_FILES["imagen_raza"]["tmp_name"]);
            $query = "UPDATE raza_animales SET raza = :raza, imagen_raza = :imagen WHERE id_raza = :id_raza";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":imagen", $imagen, PDO::PARAM_LOB);
        } else {
            $query = "UPDATE raza_animales SET raza = :raza WHERE id_raza = :id_raza";
            $stmt = $conn->prepare($query);
        }

        $stmt->bindParam(":raza", $nombre_raza);
        $stmt->bindParam(":id_raza", $id_raza, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

$conn = null;
?>
