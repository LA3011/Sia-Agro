<?php

session_start();
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();  // Asegúrate de que esta función esté configurada para PDO y PostgreSQL

// Obtener el término de búsqueda del campo de usuario
$searchTerm = $_GET['term'];

// Consulta SQL para buscar usuarios coincidentes (sin comillas dobles innecesarias)
$sql = "SELECT \"Usuario\" FROM usuarios WHERE \"Usuario\" ILIKE :searchTerm";

// Preparar la consulta
$stmt = $conn->prepare($sql);

// Escapar el término de búsqueda para evitar inyecciones SQL
$searchTermWithWildcards = '%' . $searchTerm . '%';

// Vincular el parámetro
$stmt->bindParam(':searchTerm', $searchTermWithWildcards, PDO::PARAM_STR);

// Ejecutar la consulta preparada
$stmt->execute();

// Crear un array para almacenar los usuarios coincidentes
$users = array();

// Recorrer los resultados de la consulta y agregar los usuarios al array
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $users[] = $row['Usuario'];
    }
}

// Devolver los usuarios como respuesta JSON
echo json_encode($users);

?>
