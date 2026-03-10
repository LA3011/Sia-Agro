<?php
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();

// Obtener total registros aproximado
$query_total = "SELECT SUM(reltuples) FROM pg_class c JOIN pg_namespace n ON c.relnamespace = n.oid WHERE n.nspname = 'public' AND c.relkind = 'r'";
$total_registros = $conn->query($query_total)->fetchColumn() ?: 0;

$count_utm = 0;
try {
    $res = $conn->query("SELECT COUNT(*) FROM puntos");
    $count_utm = $res->fetchColumn();
} catch (Exception $e) { $count_utm = 0; }

// Densidad: puntos UTM por registro total o algo
$densidad = $total_registros > 0 ? round($count_utm / $total_registros, 4) : 0;

// Leer historial
$fecha_hoy = date('Y-m-d');
$query_hist = "SELECT fecha, total_registros, utm_count FROM monitoreo_historial 
               WHERE fecha >= DATE '$fecha_hoy' - INTERVAL '6 days' ORDER BY fecha";
$historial = $conn->query($query_hist)->fetchAll(PDO::FETCH_ASSOC);

// Si no hay historial, agregar el día actual
if (empty($historial)) {
    $historial[] = [
        'fecha' => $fecha_hoy,
        'total_registros' => $total_registros,
        'utm_count' => $count_utm
    ];
}

echo json_encode([
    'total_registros' => $total_registros,
    'count_utm' => $count_utm,
    'densidad' => $densidad,
    'historial' => $historial
]);
?>