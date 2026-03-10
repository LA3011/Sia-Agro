<?php session_start(); ?>
<?php if(!isset($_SESSION['Aceso'])){
  header("location: index.html");
}?>
<?php include_once("header.php") ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Backups</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 20px;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 0;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestión de Backups Diarios</h1>

        <?php
        if (isset($_SESSION['mensaje_backup'])) {
            echo "<script>alert('" . $_SESSION['mensaje_backup'] . "');</script>";
            unset($_SESSION['mensaje_backup']);
        }
        ?>

        <h2>Configurar Frecuencia de Backups</h2>
        <?php
        $configFile = __DIR__ . '/config_backup.txt';
        $config = file_exists($configFile) ? json_decode(file_get_contents($configFile), true) : null;
        if (!is_array($config)) {
            $config = ['frecuencia' => 'diaria', 'hora' => '02:00'];
        }
        $frecuencia_actual = $config['frecuencia'];
        $hora_actual = $config['hora'];
        ?>
        <p>Frecuencia actual: <?php echo ucfirst($frecuencia_actual); ?>, Hora: <?php echo $hora_actual; ?></p>
        <form method="POST" action="procesar_backup.php">
            <div class="form-group">
                <label for="frecuencia">Frecuencia de Backup:</label>
                <select name="frecuencia" id="frecuencia">
                    <option value="diaria" <?php if($frecuencia_actual == 'diaria') echo 'selected'; ?>>Diaria</option>
                    <option value="semanal" <?php if($frecuencia_actual == 'semanal') echo 'selected'; ?>>Semanal</option>
                    <option value="mensual" <?php if($frecuencia_actual == 'mensual') echo 'selected'; ?>>Mensual</option>
                </select>
            </div>
            <div class="form-group">
                <label for="hora">Hora del Backup (HH:MM):</label>
                <input type="time" name="hora" id="hora" value="<?php echo $hora_actual; ?>" required>
            </div>
            <button type="submit" class="button">Guardar Configuración</button>
        </form>

        <h2>Hacer Backup Ahora</h2>
        <form method="POST" action="hacer_backup.php">
            <button type="submit" class="button">Realizar Backup</button>
        </form>

        <h2>Backups Realizados</h2>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Ruta del Archivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $backupDir = __DIR__ . '/backups/';
                if (!is_dir($backupDir)) {
                    mkdir($backupDir, 0755, true);
                }
                $files = glob($backupDir . '*.sql');
                usort($files, function($a, $b) {
                    return filemtime($b) - filemtime($a);
                });
                foreach ($files as $file) {
                    $fecha = date('Y-m-d H:i:s', filemtime($file));
                    $ruta_relativa = 'backups/' . basename($file);
                    echo "<tr>
                            <td>$fecha</td>
                            <td>$ruta_relativa</td>
                            <td><a href='$ruta_relativa' download>Descargar</a></td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        const frecuencia = '<?php echo $frecuencia_actual; ?>';
        const horaBackup = '<?php echo $hora_actual; ?>';

        function checkBackupTime() {
            const now = new Date();
            const currentHour = now.getHours().toString().padStart(2, '0');
            const currentMinute = now.getMinutes().toString().padStart(2, '0');
            const currentTime = `${currentHour}:${currentMinute}`;

            let shouldBackup = false;

            if (frecuencia === 'diaria') {
                shouldBackup = currentTime === horaBackup;
            } else if (frecuencia === 'semanal') {
                // Asumir lunes (0 = domingo, 1 = lunes)
                shouldBackup = now.getDay() === 1 && currentTime === horaBackup;
            } else if (frecuencia === 'mensual') {
                // Día 1 del mes
                shouldBackup = now.getDate() === 1 && currentTime === horaBackup;
            }

            if (shouldBackup) {
                fetch('hacer_backup_ajax.php')
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) {
                            location.reload(); // Recargar para actualizar la lista
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al realizar el backup automático.');
                    });
            }
        }

        // Verificar cada minuto
        setInterval(checkBackupTime, 60000);
    </script>
</body>
</html>