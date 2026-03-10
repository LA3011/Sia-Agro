<?php
// 1. Configuración de la base de datos
$host = "localhost";
$port = "5433"; 
$dbname = "siaagro";
$user = "postgres";
$password = "pasword";

// 2. Definir RUTAS (Corregido)
$fecha = date("Y-m-d_H-i-s");
$nombre_archivo = "backup_{$dbname}_{$fecha}.sql";

// Usamos DIRECTORY_SEPARATOR corregido
$ruta_archivo = __DIR__ . DIRECTORY_SEPARATOR . 'backups' . DIRECTORY_SEPARATOR . $nombre_archivo;

// 3. Ruta del ejecutable de Postgres (Ajusta la versión si no es la 16)
$ruta_pg_dump = '"C:\Program Files\PostgreSQL\16\bin\pg_dump.exe"';

// Asegurar que la carpeta 'backups' existe
if (!is_dir(__DIR__ . DIRECTORY_SEPARATOR . 'backups')) {
    mkdir(__DIR__ . DIRECTORY_SEPARATOR . 'backups', 0755, true);
}

// 4. Preparar el comando
putenv("PGPASSWORD=$password");

// El 2>&1 es para capturar errores técnicos
$comando = "$ruta_pg_dump -h $host -p $port -U $user -F p $dbname > " . escapeshellarg($ruta_archivo) . " 2>&1";

// 5. Ejecutar comando
exec($comando, $output, $resultado);

if ($resultado === 0) {
    // Éxito: Forzar descarga
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $nombre_archivo . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($ruta_archivo));
    
    // Limpiar búfer para que el archivo no se dañe
    if (ob_get_level()) ob_end_clean();
    flush();
    
    readfile($ruta_archivo);
    exit;
} else {
    // Si falla, te mostrará el motivo exacto (ej. clave incorrecta o ruta mal puesta)
    echo "<h3>Error al generar el respaldo</h3>";
    echo "Código de error: " . $resultado . "<br>";
    echo "Detalle técnico: <pre>" . implode("\n", $output) . "</pre>";
}
?>