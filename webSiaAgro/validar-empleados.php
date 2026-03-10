
<?php
include('conexion.php');
$rif = $_REQUEST['rif'];
$telefono = $_REQUEST['numero_telefonico'];

// Verificar si el RIF ya existe en la base de datos
$selectQuery = "SELECT rif FROM empleados WHERE rif = '$rif'";
$query = mysqli_query($con, $selectQuery);
$totalRIF = mysqli_num_rows($query);

// Verificar si el número telefónico ya existe en la base de datos
$selectQuery = "SELECT numero_telefonico FROM empleados WHERE numero_telefonico = '$telefono'";
$query = mysqli_query($con, $selectQuery);
$totalTelefono = mysqli_num_rows($query);

// Preparar la respuesta en formato JSON
$response = array();

if ($totalRIF > 0) {
    $response['success'] = 0;
    $response['message'] = 'El RIF ya existe en la base de datos.';
} elseif ($totalTelefono > 0) {
    $response['success'] = 0;
    $response['message'] = 'El número telefónico ya existe en la base de datos.';
} else {
    $response['success'] = 1;
    $response['message'] = 'Los datos son válidos y pueden ser enviados.';
}

// Mostrar la respuesta en formato JSON
header('Content-type: application/json; charset=utf-8');
echo json_encode($response);
?>