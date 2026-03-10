<?php
session_start();
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();

$start_time = microtime(true);

 $vertices = $_POST['vertices'] ?? [];
try {
    $conn->beginTransaction();

    // 1. Insertar en la tabla 'topografia'
    $topografia_data = [
        'plano' => isset($_POST['plano']) ? 1 : 0,
        'sobre_nivel' => isset($_POST['sobre_nivel']) ? 1 : 0,
        'bajo_nivel' => isset($_POST['bajo_nivel']) ? 1 : 0,
        'corte' => isset($_POST['corte']) ? 1 : 0,
        'relleno' => isset($_POST['relleno']) ? 1 : 0,
        'inclinado' => isset($_POST['inclinado']) ? 1 : 0,
        'irregular' => isset($_POST['irregular']) ? 1 : 0
    ];
    $stmt = $conn->prepare("INSERT INTO topografia (plano, sobre_nivel, bajo_nivel, corte, relleno, inclinado, irregular) 
                            VALUES (:plano, :sobre_nivel, :bajo_nivel, :corte, :relleno, :inclinado, :irregular)");
    $stmt->execute($topografia_data);
    $topografia_id = $conn->lastInsertId();

    // 2. Insertar en la tabla 'forma'
    $forma_data = [
        'regular' => isset($_POST['regular']) ? 1 : 0,
        'irregular' => isset($_POST['irregular']) ? 1 : 0,
        'muy_irregular' => isset($_POST['muy_irregular']) ? 1 : 0
    ];
    $stmt = $conn->prepare("INSERT INTO forma (regular, irregular, muy_irregular) 
                            VALUES (:regular, :irregular, :muy_irregular)");
    $stmt->execute($forma_data);
    $forma_id = $conn->lastInsertId();

    // 3. Insertar en la tabla 'ubicacion'
    $ubicacion_data = [
        'convencional' => isset($_POST['convencional']) ? 1 : 0,
        'esquina' => isset($_POST['esquina']) ? 1 : 0,
        'interior_manzana' => isset($_POST['interior_manzana']) ? 1 : 0
    ];
    $stmt = $conn->prepare("INSERT INTO ubicacion (convencional, esquina, interior_manzana) 
                            VALUES (:convencional, :esquina, :interior_manzana)");
    $stmt->execute($ubicacion_data);
    $ubicacion_id = $conn->lastInsertId();

    // 4. Insertar en la tabla 'entorno_fisico'
    $entorno_fisico_data = [
        'zona_urbanizada' => isset($_POST['zona_urbanizada']) ? 1 : 0,
        'zona_no_urbanizada' => isset($_POST['zona_no_urbanizada']) ? 1 : 0,
        'rio_quebrada' => isset($_POST['rio_quebrada']) ? 1 : 0,
        'barranco_talud' => isset($_POST['barranco_talud']) ? 1 : 0,
        'otro_entorno' => isset($_POST['otro_entorno']) ? 1 : 0
    ];
    $stmt = $conn->prepare("INSERT INTO entorno_fisico (zona_urbanizada, zona_no_urbanizada, rio_quebrada, barranco_talud, otro) 
                            VALUES (:zona_urbanizada, :zona_no_urbanizada, :rio_quebrada, :barranco_talud, :otro_entorno)");
    $stmt->execute($entorno_fisico_data);
    $entorno_fisico_id = $conn->lastInsertId();

    // 5. Insertar en la tabla 'mejoras_al_terreno'
    $mejoras_data = [
        'muro_contencion' => isset($_POST['muro_contencion']) ? 1 : 0,
        'nivelacion' => isset($_POST['nivelacion']) ? 1 : 0,
        'cercado' => isset($_POST['cercado']) ? 1 : 0,
        'pozo_septico' => isset($_POST['pozo_septico']) ? 1 : 0,
        'lagunas_artificiales' => isset($_POST['lagunas_artificiales']) ? 1 : 0,
        'otro_mejoras' => isset($_POST['otro_mejoras']) ? 1 : 0
    ];
    $stmt = $conn->prepare("INSERT INTO mejoras_al_terreno (muro_contencion, nivelacion, cercado, pozo_septico, lagunas_artificiales, otro
    ) 
                            VALUES (:muro_contencion, :nivelacion, :cercado, :pozo_septico, :lagunas_artificiales, :otro_mejoras)");
    $stmt->execute($mejoras_data);
    $mejoras_id = $conn->lastInsertId();

   
// 9. puntos (vértices)
$puntos = [];
foreach ($vertices as $i => $v) {
    $lat = isset($v['norte']) ? floatval($v['norte']) : 0;
    $lng = isset($v['este']) ? floatval($v['este']) : 0;

    // WKT: 'POINT(lng lat)' para PostGIS
    $puntos[] = "ST_GeomFromText('POINT($lng $lat)', 4326)";
}

// Construir columnas y valores dinámicamente
$columns = [];
$values = [];
for ($i = 1; $i <= count($puntos); $i++) {
    $columns[] = "punto$i";
    $values[] = $puntos[$i - 1];
}

// Insertar en la tabla 'puntos'
$sql = "INSERT INTO puntos (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ")";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Obtener el ID insertado en la tabla 'puntos'
$puntos_id = $conn->lastInsertId();

// 6. Insertar en la tabla 'ficha_tecnica'
$stmt = $conn->prepare("
    INSERT INTO ficha_tecnica 
    (topografia_id, forma_id, ubicacion_id, entorno_fisico_id, mejoras_id, puntos_id)
    VALUES (?, ?, ?, ?, ?, ?)
    RETURNING id
");
$stmt->execute([
    $topografia_id,
    $forma_id,
    $ubicacion_id,
    $entorno_fisico_id,
    $mejoras_id,
    $puntos_id // Agregar el ID de la tabla 'puntos'
]);
$ficha_tecnica_id = $stmt->fetchColumn();

if (!$ficha_tecnica_id) {
    throw new Exception('No se pudo insertar la ficha técnica.');
}

    // 7. Insertar en la tabla 'coordenadas'
    $stmt = $conn->prepare("
        INSERT INTO coordenadas 
        (ficha_tecnica_id, norte, este, area)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([
        $ficha_tecnica_id,
        $_POST['ubicacion_norte'],
        $_POST['ubicacion_este'],
        $_POST['area']
    ]);

    // 9. Insertar en la tabla 'poligono'
    $stmt = $conn->prepare("
        INSERT INTO poligono 
        (nombre, ficha_tecnica_id, fecha_hora, estado, \"Hemisferio\", \"zonaUTM\", linderos)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $_POST['nombre'],
        $ficha_tecnica_id,
        $_POST['fecha_registro'],
        'activo',
        'N', // Hemisferio
        '19', // Zona UTM
        $_POST['observaciones']
    ]);
  $end_time = microtime(true);
  $transaction_time = $end_time - $start_time;
  $_SESSION['mensaje'] = "El formulario se registró exitosamente. Tiempo de transacción: " . round($transaction_time, 4) . " segundos.";

header("Location: ../agregar_ficha.php");
    $conn->commit();
    echo "✅ Datos insertados correctamente.";
} catch (Exception $e) {
    $conn->rollBack();
    $_SESSION['mensaje'] = "Error al registrar el formulario: " . $e->getMessage();
    header("Location: ../agregar_ficha.php");
}