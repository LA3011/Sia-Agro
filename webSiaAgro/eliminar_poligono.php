<?php

include("conexion/conexion.php");
$conn = cconexion::ConexionBD();

header('Content-Type: application/json'); // Asegurar que la respuesta sea JSON

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['poligono_id'])) {
        $poligono_id = intval($_POST['poligono_id']);

        // Verificar si el ID coincide con un registro en la tabla ficha_tecnica
        $fichaSql = "SELECT id FROM ficha_tecnica WHERE id = :poligono_id";
        $fichaStmt = $conn->prepare($fichaSql); // Usar $conn en lugar de $pdo
        $fichaStmt->bindParam(':poligono_id', $poligono_id, PDO::PARAM_INT);
        $fichaStmt->execute();
        $fichaResult = $fichaStmt->fetch(PDO::FETCH_ASSOC);

        if ($fichaResult) {
            // Verificar si el ID coincide con un registro en la tabla poligono
            $poligonoSql = "SELECT id FROM poligono WHERE id = :poligono_id";
            $poligonoStmt = $conn->prepare($poligonoSql); // Usar $conn en lugar de $pdo
            $poligonoStmt->bindParam(':poligono_id', $poligono_id, PDO::PARAM_INT);
            $poligonoStmt->execute();
            $poligonoResult = $poligonoStmt->fetch(PDO::FETCH_ASSOC);

            if ($poligonoResult) {
                // Verificar si el polígono tiene registros en cultivos o potreros
                $checkSql = "
                    SELECT 
                        (SELECT COUNT(*) FROM cultivos WHERE id_espacio IN (SELECT \"Id_espacios\" FROM espacios WHERE poligono_id = :poligono_id)) AS cultivos_count,
                        (SELECT COUNT(*) FROM potreros WHERE poligono_id = :poligono_id) AS potreros_count
                ";
                $checkStmt = $conn->prepare($checkSql); // Usar $conn en lugar de $pdo
                $checkStmt->bindParam(':poligono_id', $poligono_id, PDO::PARAM_INT);
                $checkStmt->execute();
                $checkResult = $checkStmt->fetch(PDO::FETCH_ASSOC);

                if ($checkResult['cultivos_count'] == 0 && $checkResult['potreros_count'] == 0) {
                    // Si no hay registros en cultivos ni potreros, eliminar el polígono
                    $deleteSql = "DELETE FROM poligono WHERE id = :poligono_id";
                    $deleteStmt = $conn->prepare($deleteSql); // Usar $conn en lugar de $pdo
                    $deleteStmt->bindParam(':poligono_id', $poligono_id, PDO::PARAM_INT);

                    if ($deleteStmt->execute()) {
                        echo json_encode(['success' => true, 'message' => 'Polígono eliminado correctamente.']);
                        exit;
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error: No se pudo eliminar el polígono.']);
                        exit;
                    }
                } else {
                    // Si hay registros en cultivos o potreros, no eliminar
                    echo json_encode(['success' => false, 'message' => 'El polígono no se puede eliminar porque tiene registros asociados en cultivos o potreros.']);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: El ID no coincide con ningún registro en la tabla poligono.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: El ID no coincide con ningún registro en la tabla ficha_tecnica.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ID de polígono no proporcionado o método no permitido.']);
        exit;
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]);
    exit;
}
?>