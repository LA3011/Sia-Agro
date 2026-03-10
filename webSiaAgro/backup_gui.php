<?php
session_start();
include_once("header.php");
include_once("Sidebar.php");

// Configuración de rutas
$backupDirName = 'backups';
$backupDirPath = __DIR__ . DIRECTORY_SEPARATOR . $backupDirName . DIRECTORY_SEPARATOR;

// Crear la carpeta si no existe para evitar errores de glob
if (!is_dir($backupDirPath)) {
    mkdir($backupDirPath, 0755, true);
}

// Fecha de inicio (Se cargaría de tu BD)
$fecha_configuracion = "2026-01-25 10:00:00"; 
$frecuencia_configurada = "anual"; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestor de Backups Pro</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg: #f8fafc;
            --text: #1e293b;
            --white: #ffffff;
            --border: #e2e8f0;
            --sidebar-width: 300px; 
            --header-height: 85px; 
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); margin: 0; }

        .page-wrapper {
            margin-left: var(--sidebar-width);
            padding-top: calc(var(--header-height) + 20px);
            padding-left: 30px; padding-right: 30px; padding-bottom: 40px;
            box-sizing: border-box;
        }

        .main-layout { display: flex; gap: 25px; max-width: 1600px; margin: 0 auto; align-items: flex-start; }

        .controls-section {
            flex: 0 0 320px;
            background: var(--white);
            padding: 33px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        .table-section {
            flex: 1;
            background: var(--white);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        .button { 
            width: 100%; padding: 10px; background: var(--primary); color: #fff; 
            border: none; border-radius: 8px; font-weight: 600; cursor: pointer; 
            margin-bottom: 10px; font-size: 0.9rem;
        }

        .btn-dark { background: #0f172a; }

        .chart-container {
            margin-top: 30px;
            padding-top: 4px;
            border-top: 1px solid var(--border);
            text-align: center;
        }

        .countdown-box {
            margin-top: 20px;
            padding: 15px;
            background: #f1f5f9;
            border-radius: 10px;
            border: 1px dashed #cbd5e1;
        }

        #timer {
            font-size: 1.05rem;
            font-weight: 800;
            color: #2563eb;
            font-family: 'Courier New', monospace;
            display: block;
            margin-top: 5px;
            line-height: 1.4;
        }

        table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        th { background: #f8fafc; padding: 12px; text-align: left; border-bottom: 2px solid var(--border); color: #64748b; }
        td { padding: 12px; border-bottom: 1px solid #f1f5f9; }

        .pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid var(--border); }

        .modal { display: none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); }
        .modal-content { background: #fff; margin: 10% auto; padding: 30px; width: 90%; max-width: 300px; border-radius: 15px; }
    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="main-layout">
        <div class="controls-section">
            <h1>Respaldo</h1>
            <button class="button" onclick="openModal()">⚙️ Configurar</button>
            <form action="respaldar.php" method="post">
                <button class="button btn-dark" type="submit">⚡ Backup Ahora</button>
            </form>
            <button class="button" onclick="openRestoreModal()" style="background: #dc2626;">🔄 Restaurar Ahora</button>

            <div class="chart-container">
                <h3 style="font-size: 0.85rem; margin-bottom: 10px; color: #64748b;">Distribución de Frecuencia</h3>
                <div style="height: 180px; position: relative;">
                    <canvas id="backupPieChart"></canvas>
                </div>

                <div class="countdown-box">
                    <span style="font-size: 0.70rem; font-weight: bold; color: #64748b; text-transform: uppercase;">Próximo respaldo en:</span>
                    <span id="timer">Calculando...</span>
                    <small id="freqLabel" style="font-size: 0.65rem; color: #94a3b8; display:block; margin-top:5px;">Configurado el: <span id="startDateLabel"><?php echo date("d/m/Y H:i", strtotime($fecha_configuracion)); ?></span></small>
                </div>
            </div>
        </div>

        <div class="table-section">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h2>Historial de Backups</h2>
                <input type="text" id="search" onkeyup="filterTable()" style="padding:8px; border:1px solid var(--border); border-radius:8px; width:220px;" placeholder="🔍 Buscar...">
            </div>
            <table id="backupTable">
                <thead><tr><th>Fecha de Creación</th><th>Nombre del Archivo</th><th style="text-align: center;">Acción</th></tr></thead>
                <tbody>
                <?php
                // Solo lee archivos .sql reales generados
                $files = glob($backupDirPath . "*.sql");
                
                if (count($files) > 0) {
                    // Ordenar por fecha de modificación (el más reciente arriba)
                    usort($files, function($a, $b) {
                        return filemtime($b) - filemtime($a);
                    });

                    foreach ($files as $file) {
                        $nombre = basename($file);
                        $fechaM = date("d/m/Y H:i:s", filemtime($file));
                        // Ruta relativa para la descarga directa desde el navegador
                        $downloadPath = $backupDirName . '/' . $nombre;
                        
                        echo "<tr>";
                        echo "<td>{$fechaM}</td>";
                        echo "<td><code>{$nombre}</code></td>";
                        echo "<td style='text-align: center;'><a href='{$downloadPath}' download class='button' style='width:auto; padding:5px 15px; text-decoration:none;'>Descargar</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' style='text-align:center; color:#94a3b8;'>No se han encontrado respaldos aún.</td></tr>";
                }
                ?>
                </tbody>
            </table>
            <div class="pagination">
                <div id="pageInfo" style="font-size: 0.8rem; color:#64748b;"></div>
                <div style="display: flex; gap: 5px;">
                    <button class="button" id="prevBtn" onclick="prevPage()" style="width:auto; padding:5px 12px; background:#f1f5f9; color:#475569;">Ant.</button>
                    <button class="button" id="nextBtn" onclick="nextPage()" style="width:auto; padding:5px 12px;">Sig.</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="configModal" class="modal">
    <div class="modal-content">
        <h2 style="margin-top:0;">Configurar Ciclo</h2>
        <p style="font-size:0.9rem; color:#64748b;">Selecciona la frecuencia del respaldo automático:</p>
        <div style="margin-bottom:20px;">
            <label style="font-size:0.8rem; font-weight:bold;">Frecuencia</label>
            <select id="selFrecuencia" style="width:100%; padding:10px; border-radius:8px; border:1px solid #e2e8f0; margin-top:5px;">
                <option value="diaria">Diaria</option>
                <option value="semanal">Semanal</option>
                <option value="mensual">Mensual</option>
                <option value="anual" selected>Anual</option>
            </select>
        </div>
        <div style="display:flex; gap:10px;">
            <button type="button" onclick="guardarConfig()" class="button">Guardar e Iniciar</button>
            <button type="button" onclick="closeModal()" class="button btn-dark" style="background:#64748b;">Cerrar</button>
        </div>
    </div>
</div>
<div id="restoreModal" class="modal">
    <div class="modal-content">
        <h2 style="margin-top:0;">Restaurar Base de Datos</h2>
        <p style="font-size:0.9rem; color:#64748b;">Selecciona un archivo .sql para restaurar:</p>
        <form id="restoreForm" action="restaurar.php" method="post" enctype="multipart/form-data">
            <input type="file" name="sqlFile" accept=".sql" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #e2e8f0; margin-bottom:20px;">
            <input type="hidden" name="password" id="restorePassword">
            <div style="display:flex; gap:10px;">
                <button type="button" onclick="confirmRestore()" class="button" style="background: #dc2626;">Restaurar Base de Datos</button>
                <button type="button" onclick="closeRestoreModal()" class="button btn-dark" style="background:#64748b;">Cerrar</button>
            </div>
        </form>
    </div>
</div>
<script>
    function guardarConfig() {
    const ahora = new Date();
    const nuevaFrecuencia = document.getElementById('selFrecuencia').value;
    
    // 1. Actualizamos el objeto global
    configActual.frecuencia = nuevaFrecuencia;
    configActual.fechaInicio = ahora;
    
    // 2. Actualizamos el texto visual de "Configurado el:"
    const fechaFormateada = ahora.getDate().toString().padStart(2, '0') + '/' + 
                          (ahora.getMonth() + 1).toString().padStart(2, '0') + '/' + 
                          ahora.getFullYear() + ' ' + 
                          ahora.getHours().toString().padStart(2, '0') + ':' + 
                          ahora.getMinutes().toString().padStart(2, '0');
    
    document.getElementById('startDateLabel').innerText = fechaFormateada;
    
    // 3. Cerramos la ventana y refrescamos el contador
    closeModal();
    updateCountdown(); 
    
    console.log("Nueva configuración aplicada:", configActual);
}
</script>
<script>
// Lógica de cronómetro igual a la anterior
let configActual = {
    frecuencia: "<?php echo $frecuencia_configurada; ?>",
    fechaInicio: new Date("<?php echo $fecha_configuracion; ?>")
};

function updateCountdown() {
    const ahora = new Date();
    let proximo = new Date(configActual.fechaInicio);

    while (proximo <= ahora) {
        if (configActual.frecuencia === 'diaria') proximo.setDate(proximo.getDate() + 1);
        else if (configActual.frecuencia === 'semanal') proximo.setDate(proximo.getDate() + 7);
        else if (configActual.frecuencia === 'mensual') proximo.setMonth(proximo.getMonth() + 1);
        else if (configActual.frecuencia === 'anual') proximo.setFullYear(proximo.getFullYear() + 1);
    }

    const diff = proximo - ahora;
    const seg_total = Math.floor(diff / 1000);
    const min_total = Math.floor(seg_total / 60);
    const hrs_total = Math.floor(min_total / 60);
    const dias_total = Math.floor(hrs_total / 24);

    let meses = 0;
    let tempDate = new Date(ahora);
    while (new Date(tempDate).setMonth(tempDate.getMonth() + 1) <= proximo) {
        tempDate.setMonth(tempDate.getMonth() + 1);
        meses++;
    }
    let diasRestantesTrasMeses = Math.floor((proximo - tempDate) / (1000 * 60 * 60 * 24));
    let semanas = Math.floor(diasRestantesTrasMeses / 7);
    let dias = diasRestantesTrasMeses % 7;

    const h = (hrs_total % 24).toString().padStart(2, '0');
    const m = (min_total % 60).toString().padStart(2, '0');
    const s = (seg_total % 60).toString().padStart(2, '0');

    let display = "";
    if (configActual.frecuencia === 'anual') display = `${meses}m, ${semanas}s, ${dias}d<br>${h}:${m}:${s}`;
    else if (configActual.frecuencia === 'mensual') display = `${semanas}s, ${dias}d<br>${h}:${m}:${s}`;
    else if (configActual.frecuencia === 'semanal') display = `${dias_total}d<br>${h}:${m}:${s}`;
    else display = `${h}:${m}:${s}`;

    document.getElementById('timer').innerHTML = display;
}

setInterval(updateCountdown, 1000);
updateCountdown();

// --- Lógica de la Tabla ---
let currentPage = 1;
const recordsPerPage = 9;
let allRows = Array.from(document.querySelectorAll('#backupTable tbody tr'));

function updateTable() {
    // Si la tabla dice "No se han encontrado...", no paginar
    if(allRows.length === 1 && allRows[0].cells.length === 1) return;

    const totalPages = Math.ceil(allRows.length / recordsPerPage) || 1;
    const start = (currentPage - 1) * recordsPerPage;
    const end = start + recordsPerPage;

    allRows.forEach((row, i) => {
        row.style.display = (i >= start && i < end) ? '' : 'none';
    });

    document.getElementById('pageInfo').innerText = `Página ${currentPage} de ${totalPages}`;
    document.getElementById('prevBtn').disabled = currentPage === 1;
    document.getElementById('nextBtn').disabled = currentPage === totalPages;
}

function prevPage() { if(currentPage > 1) { currentPage--; updateTable(); } }
function nextPage() { if(currentPage < Math.ceil(allRows.length / recordsPerPage)) { currentPage++; updateTable(); } }

function filterTable() {
    let input = document.getElementById("search").value.toLowerCase();
    allRows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}

// Inicializar tabla y gráfica
updateTable();

// --- GRÁFICA ---
Chart.register(ChartDataLabels);
new Chart(document.getElementById('backupPieChart'), {
    type: 'pie',
    data: {
        labels: ['Diario', 'Semanal', 'Mensual', 'Anual'],
        datasets: [{
            data: [70, 15, 10, 5], // Datos que representan la frecuencia de uso
            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
            borderWidth: 2, borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } },
            datalabels: { color: '#fff', font: { weight: 'bold', size: 11 } }
        }
    }
});

function openModal() { document.getElementById('configModal').style.display = 'block'; }
function closeModal() { document.getElementById('configModal').style.display = 'none'; }

function openRestoreModal() { document.getElementById('restoreModal').style.display = 'block'; }
function closeRestoreModal() { document.getElementById('restoreModal').style.display = 'none'; }

function confirmRestore() {
    const fileInput = document.querySelector('input[name="sqlFile"]');
    if (!fileInput.files || fileInput.files.length === 0) {
        alert('No se seleccionó ningún archivo.');
        return;
    }
    const password = prompt('Ingresa tu contraseña para confirmar la restauración:');
    if (password) {
        document.getElementById('restorePassword').value = password;
        document.getElementById('restoreForm').submit();
    }
}

</script>
</body>
<?php include_once("footer.php"); ?>
</html>