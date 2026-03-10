<?php
session_start();
if(!isset($_SESSION['Aceso'])){
  header("location: index.html");
}

$config = [
    'frecuencia' => $_POST['frecuencia'],
    'hora' => $_POST['hora']
];

if ($_POST['frecuencia'] == 'semanal') {
    $config['dia_semana'] = $_POST['dia_semana'];
}
if ($_POST['frecuencia'] == 'mensual') {
    $config['dia_mes'] = $_POST['dia_mes'];
}
if ($_POST['frecuencia'] == 'anual') {
    $config['mes_anual'] = $_POST['mes_anual'];
    $config['dia_anual'] = $_POST['dia_anual'];
}

$configFile = __DIR__ . '/config_backup.txt';
file_put_contents($configFile, json_encode($config));
$_SESSION['mensaje_backup'] = "Configuración guardada.";

header("Location: backup_gui.php");
?>