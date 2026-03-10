<?php
include_once("conexion/conexion.php");

$conn = cconexion::ConexionBD();
if (!$conn) {
    echo json_encode(["success" => false, "message" => "Error de conexión"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["nombre_raza"]) || empty($_POST["nombre_raza"]) || !isset($_FILES["imagen_raza"])) {
        echo json_encode(["success" => false, "message" => "Datos incompletos"]);
        exit;
    }

    $nombre_raza = trim($_POST["nombre_raza"]);
    $imagen = file_get_contents($_FILES["imagen_raza"]["tmp_name"]);

    try {
        $query = "INSERT INTO raza_animales (raza, vendidas, venta, precio, imagen_raza) VALUES (:raza, 0, 0, 0, :imagen)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":raza", $nombre_raza);
        $stmt->bindParam(":imagen", $imagen, PDO::PARAM_LOB);
        $stmt->execute();

        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
$conn = null;
?>
