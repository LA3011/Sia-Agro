<?php
header('Content-Type: application/json');
session_start();
include("../conexion/conexion.php");

$conn = cconexion::ConexionBD();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $idCultivo = intval($_POST['id']);

    try {
        // Buscar el registro del cultivo por ID
        $sqlSelectCultivo = "SELECT id_espacio FROM cultivos WHERE \"ID\" = :id";
        $stmtSelectCultivo = $conn->prepare($sqlSelectCultivo);
        $stmtSelectCultivo->bindParam(':id', $idCultivo, PDO::PARAM_INT);
        $stmtSelectCultivo->execute();
        $cultivo = $stmtSelectCultivo->fetch(PDO::FETCH_ASSOC);

        if ($cultivo) {
            $idEspacioCultivo = $cultivo['id_espacio'];

            // Actualizar el estado del cultivo a TRUE
            $sqlUpdateCultivo = "UPDATE cultivos SET estado = TRUE WHERE \"ID\" = :id";
            $stmtUpdateCultivo = $conn->prepare($sqlUpdateCultivo);
            $stmtUpdateCultivo->bindParam(':id', $idCultivo, PDO::PARAM_INT);

            if ($stmtUpdateCultivo->execute()) {
                // Comparar id_espacio del cultivo con Id_espacios en la tabla espacios
                $sqlUpdateEspacios = "UPDATE espacios SET estado = TRUE WHERE \"Id_espacios\" = :id_espacio";
                $stmtUpdateEspacios = $conn->prepare($sqlUpdateEspacios);
                $stmtUpdateEspacios->bindParam(':id_espacio', $idEspacioCultivo, PDO::PARAM_INT);

                if ($stmtUpdateEspacios->execute()) {
                    echo json_encode(['success' => true, 'message' => 'La cosecha se registró correctamente y el estado del espacio se actualizó.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado del espacio.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado del cultivo.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró el cultivo con el ID proporcionado.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida.']);
}
?>