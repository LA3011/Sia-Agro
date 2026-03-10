<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("conexion/conexion.php");
require_once 'vendor/autoload.php';

use proj4php\Proj4php;
use proj4php\Proj;
use proj4php\Point;

$conn = cconexion::ConexionBD();

try {
    $sql = "
SELECT 
    pol.id AS poligono_id, 
    pol.nombre AS nombre_poligono,
    ft.id AS ficha_tecnica_id,
    c.norte AS coordenada_norte, 
    c.este AS coordenada_este, 
    c.area AS area_terreno,
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
    ST_AsText(p.punto20) AS punto20,

    --  CAMPOS CORREGIDOS
    esp.\"Id_espacios\" AS espacio_id,
    esp.estatus AS espacio_estatus,
    cul.\"ID\" AS cultivo_id,
    (SELECT COUNT(*) FROM potreros pt WHERE pt.poligono_id = pol.id) AS potreros_count

FROM 
    poligono pol
INNER JOIN 
    ficha_tecnica ft ON pol.ficha_tecnica_id = ft.id
LEFT JOIN 
    coordenadas c ON c.ficha_tecnica_id = ft.id
INNER JOIN 
    puntos p ON ft.puntos_id = p.id

LEFT JOIN espacios esp ON esp.poligono_id = pol.id
LEFT JOIN cultivos cul ON cul.id_espacio = esp.\"Id_espacios\"

WHERE 
    pol.ficha_tecnica_id = ft.id
";


    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Inicializar Proj4PHP
    $proj4 = new Proj4php();
    $projUTM = new Proj('EPSG:32619', $proj4);
    $projWGS84 = new Proj('EPSG:4326', $proj4);

    $jsonResult = [];

    foreach ($resultados as $fila) {

        $terreno = [
            'poligono_id' => $fila['poligono_id'],
            'nombre' => $fila['nombre_poligono'],
            'ficha_tecnica_id' => $fila['ficha_tecnica_id'],
            'coordenadas' => [
                'norte' => $fila['coordenada_norte'],
                'este' => $fila['coordenada_este'],
                'area' => $fila['area_terreno']
            ],

            //  CAMPOS AGREGADOS
            'espacio_id' => $fila['espacio_id'] ?? null,
            'espacio_estatus' => $fila['espacio_estatus'] ?? null,
            'cultivo_id' => $fila['cultivo_id'] ?? null,
            'potreros_count' => (int)($fila['potreros_count'] ?? 0),

            'puntosUTM' => [],
            'puntos' => []
        ];

        // Extraer puntos UTM y convertirlos
        for ($i = 1; $i <= 20; $i++) {
            $punto = $fila["punto$i"];
            if ($punto) {
                preg_match('/POINT\(([-\d.]+) ([-\d.]+)\)/', $punto, $matches);
                if (count($matches) === 3) {
                    $utmEste = floatval($matches[1]);
                    $utmNorte = floatval($matches[2]);
                    $terreno['puntosUTM'][] = ['x' => $utmEste, 'y' => $utmNorte];

                    $pointUTM = new Point($utmEste, $utmNorte, $projUTM);
                    $pointWGS84 = $proj4->transform($projWGS84, $pointUTM);

                    $longitud = $pointWGS84->x;
                    $latitud = $pointWGS84->y;

                    // Ajuste opcional
                    if ($longitud > -66 || $longitud < -68) {
                        $longitud -= 1;
                    }

                    $terreno['puntos'][] = [
                        'latitud' => $latitud,
                        'longitud' => $longitud
                    ];
                }
            }
        }

        $jsonResult[] = $terreno;
    }

    // Enviar al JS
    header('Content-Type: application/json');
    echo json_encode($jsonResult, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en consulta: ' . $e->getMessage()]);
}
?>
