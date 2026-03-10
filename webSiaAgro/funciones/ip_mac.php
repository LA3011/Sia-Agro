<?php
session_start();

$ip = '10.0.12.63'; // IP esperada

// Obtener la dirección IP del cliente
$client_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
if ($client_ip === false) {
    die('Invalid client IP address');
}

// Obtener la dirección MAC del cliente usando la IP
$mac_string = shell_exec("arp -a " . escapeshellarg($client_ip));
if ($mac_string === null) {
    die('Command execution failed');
}

// Buscar la dirección MAC en la salida del comando ARP
preg_match('/([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})/', $mac_string, $matches);
if (isset($matches[0])) {
    $mac = $matches[0];
} else {
    $mac = '';
}

// IP y MAC esperadas para la validación
$expected_ip = '10.0.12.63';
$expected_mac = '00-FF-BA-19-49-C8';

// Comparar la IP y la MAC obtenidas con las esperadas
if ($client_ip === $expected_ip && $mac === $expected_mac) {
    $_SESSION['ip_mac_valida'] = 1;
} else {
    $_SESSION['ip_mac_valida'] = 0;
}
?>
