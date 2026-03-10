<?php
session_start();
if(!isset($_SESSION['Aceso'])){
  header("location: index.html");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $frecuencia = $_POST['frecuencia'];
    $hora = $_POST['hora'];
    $config = ['frecuencia' => $frecuencia, 'hora' => $hora];
    // Guardar en un archivo de configuración
    $configFile = __DIR__ . '/config_backup.txt';
    file_put_contents($configFile, json_encode($config));
    $_SESSION['mensaje_backup'] = "Configuración guardada: Frecuencia $frecuencia a las $hora";
}

header("Location: backup.php");
?>