<?php
session_start();
include_once("conexion/conexion.php");
include_once("header.php");
include_once("Sidebar.php");

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['sqlFile']) && isset($_POST['password'])) {
    $uploadedFile = $_FILES['sqlFile'];
    $password = $_POST['password'];

    // Verificar sesión
    if (!isset($_SESSION['Usuario'])) {
        echo "<script>alert('Sesión no válida.'); window.location.href='backup_gui.php';</script>";
        exit;
    }

    $usuario = $_SESSION['Usuario'];

    // Verificar contraseña contra la BD
    $conn = cconexion::ConexionBD();
    $query = "SELECT \"Clave\" FROM usuarios WHERE \"Usuario\" ILIKE :usuario AND \"Habilitado\" = '1'";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result || !password_verify($password, $result['Clave'])) {
        echo "<script>alert('Contraseña incorrecta.'); window.location.href='backup_gui.php';</script>";
        exit;
    }

    // Verificar archivo
    if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
        if ($uploadedFile['error'] === UPLOAD_ERR_NO_FILE) {
            echo "<script>alert('No se seleccionó ningún archivo.'); window.location.href='backup_gui.php';</script>";
        } else {
            echo "<script>alert('Error al subir el archivo.'); window.location.href='backup_gui.php';</script>";
        }
        exit;
    }

    $filePath = $uploadedFile['tmp_name'];
    $fileName = $uploadedFile['name'];

    if (pathinfo($fileName, PATHINFO_EXTENSION) !== 'sql') {
        echo "<script>alert('Solo se permiten archivos .sql.'); window.location.href='backup_gui.php';</script>";
        exit;
    }

    // Leer contenido del archivo (se usará como respaldo antes de intentar restaurar)
    $sqlContent = file_get_contents($filePath);
    if ($sqlContent === false) {
        echo "<script>alert('Error al leer el archivo.'); window.location.href='backup_gui.php';</script>";
        exit;
    }

    // Guardar el archivo recibido en la carpeta backups para que psql pueda leerlo
    $backupsDir = __DIR__ . DIRECTORY_SEPARATOR . 'backups' . DIRECTORY_SEPARATOR;
    if (!is_dir($backupsDir)) mkdir($backupsDir, 0755, true);
    $destPath = $backupsDir . basename($fileName);
    if (!move_uploaded_file($filePath, $destPath)) {
        // Intentar escritura alternativa si move_uploaded_file falla
        if (@file_put_contents($destPath, $sqlContent) === false) {
            echo "<script>alert('No se pudo almacenar el archivo en el servidor.'); window.location.href='backup_gui.php';</script>";
            exit;
        }
    }

    // Mostrar interfaz de progreso (HTML inicial)
    echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Restaurando Base de Datos</title>
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: rgba(255,255,255,0.1); padding: 40px; border-radius: 20px; box-shadow: 0 8px 32px rgba(0,0,0,0.3); backdrop-filter: blur(10px); text-align: center; max-width: 500px; }
        h1 { margin-bottom: 20px; font-size: 2rem; }
        .info { margin-bottom: 30px; font-size: 1.1rem; }
        .progress-bar { width: 100%; height: 30px; background: rgba(255,255,255,0.2); border-radius: 15px; overflow: hidden; margin-bottom: 20px; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, #4caf50, #8bc34a); width: 0%; transition: width 0.5s ease; border-radius: 15px; }
        .percentage { font-size: 1.5rem; font-weight: bold; margin-bottom: 20px; }
        .spinner { border: 4px solid rgba(255,255,255,0.3); border-top: 4px solid #fff; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 20px auto; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔄 Restaurando Base de Datos</h1>
        <div class='info'>
            <p><strong>Usuario:</strong> " . htmlspecialchars($usuario) . "</p>
            <p><strong>Archivo:</strong> " . htmlspecialchars($fileName) . "</p>
            <p>Por favor, espera mientras se restaura la base de datos...</p>
        </div>
        <div class='percentage' id='percentage'>0%</div>
        <div class='progress-bar'>
            <div class='progress-fill' id='progressFill'></div>
        </div>
        <div class='spinner'></div>
    </div>
    <script>
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 10;
            if (progress > 90) progress = 90;
            document.getElementById('percentage').innerText = Math.round(progress) + '%';
            document.getElementById('progressFill').style.width = progress + '%';
        }, 500);
    </script>
</body>
</html>";
    flush();

    // Intentaremos usar la utilidad psql (más fiable para dumps completos)
    // Ajusta la ruta de psql si tu instalación está en otro lugar
    $db_host = 'localhost';
    $db_port = '5433';
    $db_name = 'siaagro';
    $db_user = 'postgres';
    $db_password = 'pasword';

    // Ruta típica en Windows (entre comillas por espacios)
    $ruta_psql = '"C:\\Program Files\\PostgreSQL\\16\\bin\\psql.exe"';

    // Preparar la variable de entorno para la contraseña y el comando
    putenv("PGPASSWORD={$db_password}");
    $cmd = $ruta_psql . " -h " . escapeshellarg($db_host) . " -p " . escapeshellarg($db_port) . " -U " . escapeshellarg($db_user) . " -d " . escapeshellarg($db_name) . " -f " . escapeshellarg($destPath) . " 2>&1";

    // Ejecutar el comando y capturar salida
    exec($cmd, $cmdOutput, $cmdStatus);

    if ($cmdStatus === 0) {
        echo "<script>
            try { clearInterval(interval); } catch(e) {}
            document.getElementById('percentage').innerText = '100%';
            document.getElementById('progressFill').style.width = '100%';
            setTimeout(() => {
                alert('Restauración completada exitosamente.');
                window.location.href = 'backup_gui.php';
            }, 800);
        </script>";
    } else {
        $detalle = addslashes(implode("\n", $cmdOutput));
        echo "<script>
            try { clearInterval(interval); } catch(e) {}
            alert('Error durante la restauración. Detalle técnico:\n{$detalle}');
            window.location.href = 'backup_gui.php';
        </script>";
    }
} else {
    header("Location: backup_gui.php");
    exit;
}
?>