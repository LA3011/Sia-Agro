<?php
// Verificar si la variable $_GET['pagina'] está definida
if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
} else {
    $pagina = null;
}

// Definir un array con las páginas y las rutas de sus respectivas vistas
$paginas = [
    'monitor_catastro' => 'vistas/tabla.php',
    'monitor_geomatica' => 'vistas/tabla.php',
    'gid_geo' => 'vistas/tabla.php',
    'ver' => 'vistas/ver.php',
    'editar' => 'vistas/editar.php',
    'avales' => 'vistas/avales.php',
    'monografias' => 'vistas/tabla_monografias.php',
    'mensajes' => 'vistas/mensajes01.php',
    'inicio' => 'vistas/inicio.php',
    'bitacora' => 'vistas/bitacora.php',
    'registro_de_visitas' => 'vistas/registro_visitas.php',
    'registro_solicitudes' => 'vistas/registro_solicitudes.php',
    'estatus_solicitudes' => 'vistas/estatus_solicitudes.php',
    'registrar_actividad' => 'vistas/registrar_actividad.php',
    'vista_calendario' => 'vistas/vista_calendario.php',
    'poligonos' => 'vistas/GeoAgri_gestion.php',
    'poligono' => 'vistas/poligono.php',
    'ficha' => 'vistas/ficha_tecnica.php',
    'sector' => 'vistas/agregar_ficha.php'


];

// Verificar si la página solicitada existe en el array $paginas
if (array_key_exists($pagina, $paginas) && file_exists($paginas[$pagina])) {
    // Cargar la vista correspondiente según el valor de $pagina
    require_once($paginas[$pagina]);
} else {
    // Si $pagina no coincide con ninguna opción válida o el archivo no existe, cargar la vista de inicio por defecto
    require_once('vistas/inicio.php');
}

?>
