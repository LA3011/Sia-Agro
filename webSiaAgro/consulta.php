<?php
include("../conexion/conexion.php");
$conn = cconexion::ConexionBD(); // Asegúrate de que esta función devuelva una conexión PDO válida



// Consulta SQL ajustada para obtener información de todos los polígonos
$sql = "
SELECT 
    pol.nombre AS nombre_poligono,
    pol.estado,
    ft.id AS ficha_tecnica_id,
    l.id AS linderos_id,
    leste.latitud AS latitud_este, leste.longitud AS longitud_este, leste.descripcion AS descripcion_este,
    lnoreste.latitud AS latitud_noreste, lnoreste.longitud AS longitud_noreste, lnoreste.descripcion AS descripcion_noreste,
    lnoroeste.latitud AS latitud_noroeste, lnoroeste.longitud AS longitud_noroeste, lnoroeste.descripcion AS descripcion_noroeste,
    lnorte.latitud AS latitud_norte, lnorte.longitud AS longitud_norte, lnorte.descripcion AS descripcion_norte,
    loeste.latitud AS latitud_oeste, loeste.longitud AS longitud_oeste, loeste.descripcion AS descripcion_oeste,
    lsur.latitud AS latitud_sur, lsur.longitud AS longitud_sur, lsur.descripcion AS descripcion_sur,
    lsureste.latitud AS latitud_sureste, lsureste.longitud AS longitud_sureste, lsureste.descripcion AS descripcion_sureste,
    lsuroeste.latitud AS latitud_suroeste, lsuroeste.longitud AS longitud_suroeste, lsuroeste.descripcion AS descripcion_suroeste,
    c.latitud AS coordenada_latitud, c.longitud AS coordenada_longitud, c.area AS area_terreno,
    p.id AS punto_id, 
    ST_AsText(p.punto1) AS punto1, 
    ST_AsText(p.punto2) AS punto2, 
    ST_AsText(p.punto3) AS punto3, 
    ST_AsText(p.punto4) AS punto4, 
    ST_AsText(p.punto5) AS punto5, 
    ST_AsText(p.punto6) AS punto6, 
    ST_AsText(p.punto7) AS punto7, 
    ST_AsText(p.punto8) AS punto8, 
    ST_AsText(p.punto9) AS punto9, 
    ST_AsText(p.punto10) AS punto10, 
    ST_AsText(p.punto11) AS punto11, 
    ST_AsText(p.punto12) AS punto12, 
    ST_AsText(p.punto13) AS punto13, 
    ST_AsText(p.punto14) AS punto14, 
    ST_AsText(p.punto15) AS punto15, 
    ST_AsText(p.punto16) AS punto16, 
    ST_AsText(p.punto17) AS punto17, 
    ST_AsText(p.punto18) AS punto18, 
    ST_AsText(p.punto19) AS punto19, 
    ST_AsText(p.punto20) AS punto20 
FROM 
    poligono pol
INNER JOIN 
    ficha_tecnica ft ON pol.ficha_tecnica_id = ft.id
INNER JOIN 
    linderos l ON ft.lindero_id = l.id
LEFT JOIN 
    lindero_este leste ON l.lindero_este_id = leste.id
LEFT JOIN 
    lindero_noreste lnoreste ON l.lindero_noreste_id = lnoreste.id
LEFT JOIN 
    lindero_noroeste lnoroeste ON l.lindero_noroeste_id = lnoroeste.id
LEFT JOIN 
    lindero_norte lnorte ON l.lindero_norte_id = lnorte.id
LEFT JOIN 
    lindero_oeste loeste ON l.lindero_oeste_id = loeste.id
LEFT JOIN 
    lindero_sur lsur ON l.lindero_sur_id = lsur.id
LEFT JOIN 
    lindero_sureste lsureste ON l.lindero_sureste_id = lsureste.id
LEFT JOIN 
    lindero_suroeste lsuroeste ON l.lindero_suroeste_id = lsuroeste.id
LEFT JOIN 
    coordenadas c ON c.ficha_tecnica_id = ft.id
INNER JOIN 
    puntos p ON ft.puntos_id = p.id 
LEFT JOIN 
    topografia t ON ft.topografia_id = t.id
LEFT JOIN 
    forma f ON ft.forma_id = f.id
LEFT JOIN 
    ubicacion u ON ft.ubicacion_id = u.id
LEFT JOIN 
    entorno_fisico e ON ft.entorno_fisico_id = e.id
LEFT JOIN 
    mejoras_al_terreno m ON ft.mejoras_id = m.id
LEFT JOIN 
    tipo tp ON ft.tipo_id = tp.id
";

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($sql);
$stmt->execute();

// Obtener los resultados
$poligonos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar los resultados
foreach ($poligonos as $poligono) {
    echo '<div style="border: 1px solid #ccc; padding: 10px; margin: 10px;">';
    echo '<h3>Polígono: ' . htmlspecialchars($poligono['nombre_poligono']) . '</h3>';
    echo '<p><strong>Estado:</strong> ' . htmlspecialchars($poligono['estado']) . '</p>';
    echo '<p><strong>Ficha Técnica ID:</strong> ' . htmlspecialchars($poligono['ficha_tecnica_id']) . '</p>';
    echo '<p><strong>Linderos ID:</strong> ' . htmlspecialchars($poligono['linderos_id']) . '</p>';
    echo '<p><strong>Coordenadas:</strong> (' . htmlspecialchars($poligono['coordenada_latitud']) . ', ' . htmlspecialchars($poligono['coordenada_longitud']) . ')</p>';
    echo '<p><strong>Área del Terreno:</strong> ' . htmlspecialchars($poligono['area_terreno']) . '</p>';
    echo '<p><strong>Puntos:</strong></p>';
    for ($i = 1; $i <= 20; $i++) {
        echo '<strong>Punto ' . $i . ':</strong> ' . htmlspecialchars($poligono['punto' . $i]) . '<br>';
    }
    // Mostrar linderos
    echo '<h4>Linderos:</h4>';
    echo '<ul>';
    echo '<li>Este: ' . htmlspecialchars($poligono['descripcion_este']) . ' (' . htmlspecialchars($poligono['latitud_este']) . ', ' . htmlspecialchars($poligono['longitud_este']) . ')</li>';
    echo '<li>Noreste: ' . htmlspecialchars($poligono['descripcion_noreste']) . ' (' . htmlspecialchars($poligono['latitud_noreste']) . ', ' . htmlspecialchars($poligono['longitud_noreste']) . ')</li>';
    echo '<li>Noroeste: ' . htmlspecialchars($poligono['descripcion_noroeste']) . ' (' . htmlspecialchars($poligono['latitud_noroeste']) . ', ' . htmlspecialchars($poligono['longitud_noroeste']) . ')</li>';
    echo '<li>Norte: ' . htmlspecialchars($poligono['descripcion_norte']) . ' (' . htmlspecialchars($poligono['latitud_norte']) . ', ' . htmlspecialchars($poligono['longitud_norte']) . ')</li>';
    echo '<li>Oeste: ' . htmlspecialchars($poligono['descripcion_oeste']) . ' (' . htmlspecialchars($poligono['latitud_oeste']) . ', ' . htmlspecialchars($poligono['longitud_oeste']) . ')</li>';
    echo '<li>Sur: ' . htmlspecialchars($poligono['descripcion_sur']) . ' (' . htmlspecialchars($poligono['latitud_sur']) . ', ' . htmlspecialchars($poligono['longitud_sur']) . ')</li>';
    echo '<li>Sureste: ' . htmlspecialchars($poligono['descripcion_sureste']) . ' (' . htmlspecialchars($poligono['latitud_sureste']) . ', ' . htmlspecialchars($poligono['longitud_sureste']) . ')</li>';
    echo '<li>Suroeste: ' . htmlspecialchars($poligono['descripcion_suroeste']) . ' (' . htmlspecialchars($poligono['latitud_suroeste']) . ', ' . htmlspecialchars($poligono['longitud_suroeste']) . ')</li>';
    echo '</ul>';
    echo '</div>';
}
?>
