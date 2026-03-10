<?php
require_once ('../librerias/dompdf/autoload.inc.php');
include("../conexion/conexion.php");
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$conn = cconexion::ConexionBD();
try {
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $poligono_id = $_GET['id'];
    } else {
        die('ID de polígono no válido.');
    }
    $sql = "SELECT 
        pol.id AS poligono_id,
        pol.nombre AS nombre_poligono,
        pol.estado,
        ft.id AS ficha_tecnica_id,
        CASE 
            WHEN t.plano = 1 THEN 'Plano' 
            WHEN t.sobre_nivel = 1 THEN 'Sobre Nivel' 
            WHEN t.bajo_nivel = 1 THEN 'Bajo Nivel' 
            WHEN t.corte = 1 THEN 'Corte' 
            WHEN t.relleno = 1 THEN 'Relleno' 
            WHEN t.inclinado = 1 THEN 'Inclinado' 
            WHEN t.irregular = 1 THEN 'Irregular' 
            ELSE 'No especificado' 
        END AS descripcion_topografia,
        CASE 
            WHEN f.regular = 1 THEN 'Regular' 
            WHEN f.irregular = 1 THEN 'Irregular' 
            WHEN f.muy_irregular = 1 THEN 'Muy Irregular' 
            ELSE 'No especificado' 
        END AS descripcion_forma,
        CASE 
            WHEN u.convencional = 1 THEN 'Convencional' 
            WHEN u.esquina = 1 THEN 'Esquina' 
            WHEN u.interior_manzana = 1 THEN 'Interior de Manzana' 
            ELSE 'No especificado' 
        END AS descripcion_ubicacion,
        CASE 
            WHEN e.zona_urbanizada = 1 THEN 'Zona Urbanizada' 
            WHEN e.zona_no_urbanizada = 1 THEN 'Zona No Urbanizada' 
            WHEN e.rio_quebrada = 1 THEN 'Río/Quebrada' 
            WHEN e.barranco_talud = 1 THEN 'Barranco/Talud' 
            WHEN e.otro = 1 THEN 'Otro' 
            ELSE 'No especificado' 
        END AS descripcion_entorno_fisico,
        CASE 
            WHEN m.muro_contencion = 1 THEN 'Muro de Contención' 
            WHEN m.nivelacion = 1 THEN 'Nivelación' 
            WHEN m.cercado = 1 THEN 'Cercado' 
            WHEN m.pozo_septico = 1 THEN 'Pozo Séptico' 
            WHEN m.lagunas_artificiales = 1 THEN 'Lagunas Artificiales' 
            WHEN m.otro = 1 THEN 'Otro' 
            ELSE 'No especificado' 
        END AS descripcion_mejoras,

        c.norte AS coordenada_norte, 
        c.este AS coordenada_este, 
        c.area AS area_terreno,
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
    WHERE 
        pol.id = :poligono_id;
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':poligono_id', $poligono_id, PDO::PARAM_INT);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = '
    <style>
        @page { margin: 10px; }
        body {
            margin: 0;
            padding: 0;
            background-image: url("informe-tecnico.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            width: 100vw;
        }
        .container { position: relative; z-index: 2; font-family: Arial, sans-serif; color: #000; }
        h1 { font-size: 20px; margin: 0; text-align: center; }
        .section-title { font-weight: bold; text-transform: uppercase; margin: 20px 0 10px; font-size: 14px; }
        .section-header-row { display: flex; justify-content: space-between; align-items: center; }
        .coords-area { font-size: 12px; text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th, table td { border: 1px solid #000; padding: 2px; text-align: left; font-size: 12px; }
        table th { background-color: #f2f2f2; }
        .croquis { text-align: center; margin-bottom: 20px; }
        .croquis img { max-width: 100%; height: auto; border: 1px solid #000; width: 100px; border: 5px solid white; }
        .signature { margin-top: 50px; text-align: center; }
    </style>
    <div class="container">
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
        <h1>Informe Técnico del terreno</h1>
    ';

    foreach ($resultados as $fila) {
        $coordenada_norte = htmlspecialchars($fila['coordenada_norte']);
        $coordenada_este = htmlspecialchars($fila['coordenada_este']);
        $area_terreno = htmlspecialchars($fila['area_terreno']);

        $html .= "
        <div class='section'>
            <div class='section-header-row'>
                <div class='section-title'>Croquis del terreno</div>
                <div class='coords-area'>
                    <b>Norte:</b> {$coordenada_norte}<br>
                    <b>Este:</b> {$coordenada_este}<br>
                    <b>Área:</b> {$area_terreno} m<sup>2</sup>
                </div>
            </div>
            <div class='croquis'>
                <img src=\"../croquis/img_terreno.png\" alt=\"Croquis del terreno\">
            </div>
        </div>
        <div class='section'>
            <div class='section-title'>Puntos y Coordenadas</div> 
            <table>
                <thead>
                    <tr>
                        <th>Punto</th>
                        <th>Este</th>
                        <th>N</th>
                    </tr>
                </thead>
                <tbody>
        ";
        for ($i = 1; $i <= 20; $i++) {
            $punto = $fila["punto$i"];
            if ($punto) {
                $punto = str_replace(['POINT(', ')'], '', $punto);
                $coordenadas = explode(' ', $punto);
                if (count($coordenadas) === 2) {
                    $latitud = trim($coordenadas[0]);
                    $longitud = trim($coordenadas[1]);
                    $html .= "
                        <tr>
                            <td>Punto $i</td>
                            <td>" . htmlspecialchars($latitud) . "</td>
                            <td>" . htmlspecialchars($longitud) . "</td>
                        </tr>
                    ";
                } else {
                    $html .= "
                        <tr>
                            <td>Punto $i</td>
                            <td colspan='2'>Formato inválido</td>
                        </tr>
                    ";
                }
            }
        }
        $html .= "
                </tbody>
            </table>
        </div>
        <div class='section'>
            <div class='section-title'>Características del Terreno</div>
            <table>
                <tr>
                    <td><b>Topografía:</b> {$fila["descripcion_topografia"]}</td>
                    <td><b>Mejoras al Terreno:</b> {$fila['descripcion_mejoras']}</td>
                </tr>
                <tr>
                    <td><b>Entorno Físico:</b> {$fila['descripcion_entorno_fisico']}</td>
                    <td><b>Forma:</b> {$fila['descripcion_forma']}</td>
                    <td><b>Ubicación:</b> {$fila['descripcion_ubicacion']}</td>
                </tr>
            </table>
        </div>
        <div style='font-family: Arial, sans-serif; margin: 20px; font-size: 12px; line-height: 1.5; text-align: justify;'>
            <b><u>OBSERVACIONES:</u></b> 
            Una vez cumplidos los requerimientos exigidos por la ley emitidas por el 
            <b>Instituto Geográfico de Venezuela Simón Bolívar (IGVSB)</b>, con respecto al aval y certificación de información cartográfica estipulado en el 
            <b>Art. 6</b> (Planos Topográficos en el sistema de referencia geodésico: Coordenadas UTM 'Universal Transverse Mercator', Datum: Sirgas-Regven/GRS-80). 
            Se certifica que el levantamiento presentado cumple con las normas mencionadas, utilizando técnicas y equipos de precisión geomática para el levantamiento topográfico y cartográfico, ajustados a los parámetros de exactitud y precisión requeridos por ley.
            El área del terreno medida en el levantamiento y plasmada en planos se encuentra dentro de los márgenes aceptables de tolerancia topográfica, representando la situación actual del terreno.
            Este Informe tecnico es válido únicamente para fines informativos referentes al esclarecimiento del área del terreno, según la metodología de levantamiento topográfico utilizando técnicas relacionadas con la geomática (<b>levantamiento/enlaces GPS</b>). 
        </div>
        <div class='signature'>
            <p>___________________________</p>
            <p>Firma del Técnico Responsable</p>
        </div>
        ";
    }
    $html .= '</div>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("aval_tecnico.pdf", ["Attachment" => false]);
    exit;
} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
}
?>
