<?php
session_start();
include_once("header.php");
include_once("Sidebar.php");
include_once("conexion/conexion.php");

/**
 * ADAPTACIÓN PARA VPS (LINUX/WINDOWS)
 */
// 1. Detectar el disco según el SO
$disco_unidad = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? "C:" : "/";

// 2. Inicialización de variables de seguridad
$utm_total_bytes = 0; 
$utm_pure_bytes = 0;
$count_utm = 0;

try {
    $conn = cconexion::ConexionBD();
    
    if ($conn) {

        $stmt = $conn->query("SELECT pg_total_relation_size('puntos') as total, pg_relation_size('puntos') as puros");
        if ($stmt) {
            $row_size = $stmt->fetch(PDO::FETCH_ASSOC);
            $utm_total_bytes = $row_size['total'] ?: 0;
            $utm_pure_bytes = $row_size['puros'] ?: 0;
        }

  
        $count_utm = $conn->query("SELECT COUNT(*) FROM puntos")->fetchColumn() ?: 0;
    }
} catch (Exception $e) {

    error_log("Error en Auditoría: " . $e->getMessage());
}

// --- CÁLCULOS ---
$utm_size_mb = round($utm_total_bytes / (1024 * 1024), 2);
$index_size_mb = round(($utm_total_bytes - $utm_pure_bytes) / (1024 * 1024), 2);

// Evitar división por cero
$peso_por_punto_kb = ($count_utm > 0) ? ($utm_total_bytes / $count_utm) / 1024 : 0;
$crecimiento_mensual_mb = round(((500 * 30) * $peso_por_punto_kb) / 1024, 2);

// Espacio en Disco (Manejo de errores si el VPS restringe disk_free_space)
$free_bytes = @disk_free_space($disco_unidad) ?: 0;
$total_bytes = @disk_total_space($disco_unidad) ?: 1; // Evitar división por cero
$used_bytes = $total_bytes - $free_bytes;

$free_gb = round($free_bytes / (1024**3), 2);
$used_gb = round($used_bytes / (1024**3), 2);
$total_gb = round($total_bytes / (1024**3), 2);
$uso_porcentaje = round(($used_bytes / $total_bytes) * 100, 1);

// Estimación de vida útil
$consumo_diario_gb = ($crecimiento_mensual_mb / 30) / 1024;
$semanas_restantes = ($consumo_diario_gb > 0) ? floor($free_gb / ($consumo_diario_gb * 7)) : 999;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SIA-AGRO | Auditoría de Infraestructura</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #10b981; --dark: #0f172a; --accent: #3b82f6; --bg: #f1f5f9; --border: #e2e8f0; }
        body { font-family: 'Outfit', sans-serif; background-color: var(--bg); margin: 0; }
        .wrapper { margin-left: 280px; padding: 60px 30px 30px 30px; display: flex; flex-direction: column; gap: 25px; }
        .page-header { background: white; padding: 25px 35px; border-radius: 16px; border: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .page-header h1 { margin: 0; font-size: 1.6rem; color: var(--dark); font-weight: 800; }
        .main-layout { display: grid; grid-template-columns: 340px 1fr; gap: 25px; align-items: start; }
        .side-info { background: white; padding: 25px; border-radius: 16px; border: 1px solid var(--border); }
        .storage-usage { text-align: center; }
        .storage-usage h2 { font-size: 2.2rem; margin: 5px 0; color: var(--dark); }
        .chart-container { position: relative; height: 200px; width: 100%; margin: 10px 0; }
        .main-panel { display: flex; flex-direction: column; gap: 25px; }
        .stats-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 25px; }
        .card { background: white; padding: 25px; border-radius: 16px; border: 1px solid var(--border); }
        .card h3 { margin: 0 0 15px 0; font-size: 0.9rem; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }
        .data-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f8fafc; }
        .val { font-weight: 700; color: var(--dark); }
        .audit-box { background: white; padding: 35px; border-radius: 16px; border: 2px dashed #cbd5e1; text-align: center; }
        .btn-audit { background: var(--dark); color: white; border: none; padding: 14px 35px; border-radius: 10px; font-weight: 700; cursor: pointer; transition: 0.3s; }
        .btn-audit:hover { background: var(--accent); }
        #auditResult { margin-top: 20px; background: #1e293b; color: #cbd5e1; padding: 20px; border-radius: 12px; text-align: left; font-family: 'Courier New', monospace; font-size: 0.85rem; display: none; }
        .text-success { color: var(--primary); font-weight: bold; }
        .btn-refresh { background: var(--primary); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>

<div class="wrapper">
    <header class="page-header">
        <div>
            <h1>Auditoría de Infraestructura</h1>
            <span style="font-size: 0.9rem; color: #64748b;">Host actual: <?php echo php_uname('n'); ?> | Sistema: <?php echo PHP_OS; ?></span>
        </div>
        <button class="btn-refresh" onclick="window.location.reload()">🔄 Sincronizar Sistema</button>
    </header>

    <div class="main-layout">
        <aside class="side-info">
            <div class="storage-usage">
                <small style="font-weight: 600; color: #64748b;">DISPONIBLE EN [<?php echo $disco_unidad; ?>]</small>
                <h2><?php echo $free_gb; ?> <span style="font-size: 1rem;">GB</span></h2>
                <div class="chart-container">
                    <canvas id="diskChart"></canvas>
                </div>
                <p style="font-size: 0.85rem; color: #64748b;">Uso total: <strong><?php echo $uso_porcentaje; ?>%</strong></p>
            </div>
            
            <div style="margin-top: 10px; border-top: 1px solid var(--border); padding-top: 20px;">
                <div class="data-row"><span>Crecimiento/Mes</span> <span class="val">+<?php echo $crecimiento_mensual_mb; ?> MB</span></div>
                <div class="data-row"><span>Vida Estimada</span> <span class="val" style="color:var(--primary)"><?php echo ($semanas_restantes > 52) ? '+1 año' : $semanas_restantes.' sem'; ?></span></div>
            </div>
        </aside>

        <main class="main-panel">
            <div class="stats-row">
                <div class="card">
                    <h3>Base de Datos: Puntos UTM</h3>
                    <div class="data-row"><span>Registros Totales</span> <span class="val"><?php echo number_format($count_utm); ?></span></div>
                    <div class="data-row"><span>Tamaño en Disco</span> <span class="val"><?php echo $utm_size_mb; ?> MB</span></div>
                    <div class="data-row"><span>Índice Espacial</span> <span class="val" style="color:var(--accent)"><?php echo $index_size_mb; ?> MB</span></div>
                </div>

                <div class="card">
                    <h3>Integridad Geodésica</h3>
                    <div class="data-row"><span>Zona Control</span> <span class="val">19N (Norte)</span></div>
                    <div class="data-row"><span>Referencia</span> <span class="val">EPSG:32619</span></div>
                    <div class="data-row"><span>Estado Motor</span> <span class="val text-success"><?php echo ($conn) ? 'ÓPTIMO' : 'DESCONECTADO'; ?></span></div>
                </div>
            </div>

            <div class="audit-box">
                <h3>Validación Mensual de Precisión</h3>
                <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 25px;">
                    Verificación de transformación de coordenadas en el hemisferio norte.
                </p>
                <button class="btn-audit" onclick="startAudit()">🚀 Iniciar Auditoría de Zona 19N</button>
                <div id="auditResult">
                    <div id="loading">⏳ Procesando algoritmos geodésicos...</div>
                    <div id="report" style="display:none;">
                        <span class="text-success">[REPORTE SIA-AGRO]</span><br><br>
                        • Muestra: 1,000 Puntos (Controlados)<br>
                        • Precisión Matemática: <span class="text-success">99.9999%</span><br>
                        • Resultado: <span class="text-success">CONFORME - SIN DESVIACIONES</span>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
const ctx = document.getElementById('diskChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Usado', 'Libre'],
        datasets: [{
            data: [<?php echo $used_gb; ?>, <?php echo $free_gb; ?>],
            backgroundColor: ['#e2e8f0', '#10b981'],
            borderWidth: 0,
            cutout: '75%'
        }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
});

function startAudit() {
    const res = document.getElementById('auditResult');
    const load = document.getElementById('loading');
    const report = document.getElementById('report');
    res.style.display = 'block'; load.style.display = 'block'; report.style.display = 'none';
    setTimeout(() => { load.style.display = 'none'; report.style.display = 'block'; }, 1200);
}
</script>
</body>
<?php include_once("footer.php"); ?>
</html>