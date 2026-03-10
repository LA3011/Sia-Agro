<?php
include("../conexion/conexion.php");
$conn = cconexion::ConexionBD();

try {
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $poligono_id = $_GET['id'];
    } else {
        die('ID de polígono no válido.');
    }

    $sql = "
    SELECT 
        pol.id AS poligono_id, 
        pol.nombre AS nombre_poligono,
        ft.id AS ficha_tecnica_id,
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
        puntos p ON ft.puntos_id = p.id
    WHERE 
        pol.id = :poligono_id;
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':poligono_id', $poligono_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $puntos = [];
        for ($i = 1; $i <= 20; $i++) {
            if (!empty($result["punto{$i}"])) {
                preg_match('/POINT\(([-\d\.]+) ([-\d\.]+)\)/', $result["punto{$i}"], $matches);
                if (count($matches) == 3) {
                    $puntos[] = ['x' => $matches[1], 'y' => $matches[2], 'z' => 0];
                }
            }
        }
        // Cerrar el polígono si no está cerrado
        if (count($puntos) > 2 && ($puntos[0]['x'] != $puntos[count($puntos)-1]['x'] || $puntos[0]['y'] != $puntos[count($puntos)-1]['y'])) {
            $puntos[] = $puntos[0];
        }
        generarDXF($puntos, 'plano_topografico_' . $poligono_id . '.dxf', $result['nombre_poligono']);
    } else {
        echo "No se encontraron datos para el ID de polígono especificado.";
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}


function generarDXF($puntos, $nombreArchivo, $nombrePoligono = 'Polígono') {
    $n = count($puntos);
    if ($n < 3) return;

 
// --- ÁREA DE DIBUJO DISPONIBLE ---
$draw_x = 0;
$draw_y = 0;
$draw_w = 300; // Más angosto, vertical
$draw_h = 400; // Más alto, vertical

// --- ÁREA DEL CARTUCHO ---
$cart_w = 190;
$cart_h = $draw_h; // igual al alto del área de dibujo
$cart_x = $draw_x + $draw_w + 30; // a la derecha del área de dibujo
$cart_y = $draw_y;

    // --- ESCALAR Y CENTRAR POLÍGONO Y GRILLA ---
    $minx = min(array_column($puntos, 'x'));
    $maxx = max(array_column($puntos, 'x'));
    $miny = min(array_column($puntos, 'y'));
    $maxy = max(array_column($puntos, 'y'));

    $poly_w = $maxx - $minx;
    $poly_h = $maxy - $miny;

    $margin = 60; // margen interno GRANDE para que el polígono se vea pequeño
    $scale = min(
        ($draw_w - 2 * $margin) / ($poly_w ?: 1),
        ($draw_h - 2 * $margin) / ($poly_h ?: 1),
        1 // nunca escales a más de 1:1, así siempre se ve pequeño
    );

    // Centrar el polígono en el área de dibujo
    $offset_x = $draw_x + ($draw_w - $poly_w * $scale) / 2 - $minx * $scale;
    $offset_y = $draw_y + ($draw_h - $poly_h * $scale) / 2 - $miny * $scale;

    // Escalar y trasladar puntos
    $scaled = [];
    foreach ($puntos as $pt) {
        $scaled[] = [
            'x' => $pt['x'] * $scale + $offset_x,
            'y' => $pt['y'] * $scale + $offset_y,
            'z' => 0
        ];
    }

    // Calcular área y perímetro reales (sin escalar)
    $area = 0;
    $perimetro = 0;
    for ($i = 0; $i < $n - 1; $i++) {
        $x1 = $puntos[$i]['x'];
        $y1 = $puntos[$i]['y'];
        $x2 = $puntos[$i + 1]['x'];
        $y2 = $puntos[$i + 1]['y'];
        $area += ($x1 * $y2 - $x2 * $y1);
        $dx = $x2 - $x1;
        $dy = $y2 - $y1;
        $perimetro += sqrt($dx * $dx + $dy * $dy);
    }
    $area = abs($area) / 2;
    $area_ha = $area / 10000;

    // HEADER + LAYERS (se añadió la capa MARCO)
    $dxf = "0\nSECTION\n2\nHEADER\n9\n$INSUNITS\n70\n6\n0\nENDSEC\n";
    $dxf .= "0\nSECTION\n2\nTABLES\n0\nTABLE\n2\nLAYER\n";
    $dxf .= "0\nLAYER\n2\nPoligono\n70\n0\n62\n5\n6\nCONTINUOUS\n";
    $dxf .= "0\nLAYER\n2\nCartucho\n70\n0\n62\n7\n6\nCONTINUOUS\n";
    $dxf .= "0\nLAYER\n2\nMarco\n70\n0\n62\n1\n6\nCONTINUOUS\n";
    $dxf .= "0\nENDTAB\n0\nENDSEC\n0\nSECTION\n2\nBLOCKS\n0\nENDSEC\n";
    $dxf .= "0\nSECTION\n2\nENTITIES\n";

    // --- MARGEN PERIMETRAL DEL PLANO (más bajo y más ancho) ---
    $margen_extra = 30;
    $marco_x0 = -$margen_extra; // más a la izquierda
    $marco_y0 = -$margen_extra; // más abajo
    $marco_x1 = $cart_x + $cart_w + $margen_extra;
    $marco_y1 = max($draw_y + $draw_h, $cart_y + $cart_h) + $margen_extra;

    $dxf .= "0\nLINE\n8\nMarco\n10\n$marco_x0\n20\n$marco_y0\n30\n0.0\n11\n$marco_x1\n21\n$marco_y0\n31\n0.0\n";
    $dxf .= "0\nLINE\n8\nMarco\n10\n$marco_x1\n20\n$marco_y0\n30\n0.0\n11\n$marco_x1\n21\n$marco_y1\n31\n0.0\n";
    $dxf .= "0\nLINE\n8\nMarco\n10\n$marco_x1\n20\n$marco_y1\n30\n0.0\n11\n$marco_x0\n21\n$marco_y1\n31\n0.0\n";
    $dxf .= "0\nLINE\n8\nMarco\n10\n$marco_x0\n20\n$marco_y1\n30\n0.0\n11\n$marco_x0\n21\n$marco_y0\n31\n0.0\n";

    // --- GRILLA ---
// Calcula el área mínima necesaria para el polígono escalado
$min_grid_x = min(array_column($scaled, 'x'));
$max_grid_x = max(array_column($scaled, 'x'));
$min_grid_y = min(array_column($scaled, 'y'));
$max_grid_y = max(array_column($scaled, 'y'));

$grid_size = 40;

// Ajusta los límites de la grilla al polígono (redondeando a múltiplos de grid_size)
$grid_x0 = floor($min_grid_x / $grid_size) * $grid_size;
$grid_x1 = ceil($max_grid_x / $grid_size) * $grid_size;
$grid_y0 = floor($min_grid_y / $grid_size) * $grid_size;
$grid_y1 = ceil($max_grid_y / $grid_size) * $grid_size;

// Líneas verticales
for ($gx = $grid_x0; $gx <= $grid_x1; $gx += $grid_size) {
    $dxf .= "0\nLINE\n8\nCartucho\n10\n$gx\n20\n$grid_y0\n30\n0.0\n11\n$gx\n21\n$grid_y1\n31\n0.0\n";
}
// Líneas horizontales
for ($gy = $grid_y0; $gy <= $grid_y1; $gy += $grid_size) {
    $dxf .= "0\nLINE\n8\nCartucho\n10\n$grid_x0\n20\n$gy\n30\n0.0\n11\n$grid_x1\n21\n$gy\n31\n0.0\n";
}
    // --- POLÍGONO ---
    $dxf .= "0\nPOLYLINE\n8\nPoligono\n66\n1\n70\n9\n62\n5\n";
    foreach ($scaled as $punto) {
        $dxf .= "0\nVERTEX\n8\nPoligono\n10\n{$punto['x']}\n20\n{$punto['y']}\n30\n0.0\n";
    }
    $dxf .= "0\nSEQEND\n";

    // --- ETIQUETAS DE VÉRTICES Y LADOS ---
    foreach ($scaled as $i => $pt) {
        $label = chr(65 + $i); // A, B, C, D...
        $dxf .= "0\nTEXT\n8\nPoligono\n10\n" . ($pt['x'] + 4) . "\n20\n" . ($pt['y'] + 4) . "\n30\n0.0\n40\n3\n1\nP$label\n";
        // Lado
        if ($i < $n - 1) {
            $mid_x = ($pt['x'] + $scaled[$i+1]['x']) / 2;
            $mid_y = ($pt['y'] + $scaled[$i+1]['y']) / 2;
            $dist = sqrt(pow($puntos[$i+1]['x'] - $puntos[$i]['x'], 2) + pow($puntos[$i+1]['y'] - $puntos[$i]['y'], 2));
            $dxf .= "0\nTEXT\n8\nPoligono\n10\n" . ($mid_x + 4) . "\n20\n" . ($mid_y + 4) . "\n30\n0.0\n40\n3\n1\n" . number_format($dist, 2) . "\n";
        }
    }
    // Área dentro del polígono
    $cx = array_sum(array_column($scaled, 'x')) / $n;
    $cy = array_sum(array_column($scaled, 'y')) / $n;
    $dxf .= "0\nTEXT\n8\nPoligono\n10\n$cx\n20\n$cy\n30\n0.0\n40\n4\n1\nÁrea: " . number_format($area, 2) . " m²\n";

    // --- CARTUCHO DERECHO ---
    $dxf .= "0\nLWPOLYLINE\n8\nCartucho\n90\n5\n70\n1\n";
    $dxf .= "10\n$cart_x\n20\n$cart_y\n";
    $dxf .= "10\n" . ($cart_x + $cart_w) . "\n20\n$cart_y\n";
    $dxf .= "10\n" . ($cart_x + $cart_w) . "\n20\n" . ($cart_y + $cart_h) . "\n";
    $dxf .= "10\n$cart_x\n20\n" . ($cart_y + $cart_h) . "\n";
    $dxf .= "10\n$cart_x\n20\n$cart_y\n";

    // Títulos y datos principales (con más espacio)
    $y_text = $cart_y + $cart_h - 12;
    $dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($cart_x + 12) . "\n20\n$y_text\n30\n0.0\n40\n5\n1\nDIVISION DE PARCELA\n";
    $y_text -= 12;
    $dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($cart_x + 12) . "\n20\n$y_text\n30\n0.0\n40\n4\n1\nPLANO TOPOGRAFICO\n";
    $y_text -= 10;
    $dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($cart_x + 12) . "\n20\n$y_text\n30\n0.0\n40\n3\n1\nPropietario: HACIENDA LOS TUCUPIDOS\n";
    $y_text -= 8;
    $dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($cart_x + 12) . "\n20\n$y_text\n30\n0.0\n40\n3\n1\nRIF: J-00000000-0\n";
    $y_text -= 8;
    $dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($cart_x + 12) . "\n20\n$y_text\n30\n0.0\n40\n3\n1\nEscala: 1:2000\n";
    $y_text -= 8;
    $dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($cart_x + 12) . "\n20\n$y_text\n30\n0.0\n40\n3\n1\nFecha: " . date("d/m/Y") . "\n";
    $y_text -= 8;
    $dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($cart_x + 12) . "\n20\n$y_text\n30\n0.0\n40\n3\n1\nArea: " . number_format($area, 2) . " metros cuadrados (" . number_format($area_ha, 5) . " ha)\n";
    $y_text -= 8;
    $dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($cart_x + 12) . "\n20\n$y_text\n30\n0.0\n40\n3\n1\nPerimetro: " . number_format($perimetro, 2) . " m\n";
// --- UBICACIÓN ---
$table_y = $cart_y + $cart_h - 90; // antes -120, ahora más arriba
$dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($cart_x + 8) . "\n20\n" . ($table_y) . "\n30\n0.0\n40\n3\n1\nUBICACION:\n";

$table_y -= 4; // antes -6
$dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($cart_x + 8) . "\n20\n" . ($table_y) . "\n30\n0.0\n40\n2.5\n1\nAVENIDA CASANOVA GODOY SECTOR LA PURICA\n";

$table_y -= 4;
$dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($cart_x + 8) . "\n20\n" . ($table_y) . "\n30\n0.0\n40\n2.5\n1\nMUNICIPIO SANTIAGO MARINO ESTADO ARAGUA\n";

// --- PROYECTO ---
$table_y -= 8; // antes -10
$dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($cart_x + 8) . "\n20\n" . ($table_y) . "\n30\n0.0\n40\n3\n1\nPROYECTO:\n";

$table_y -= 4;
$dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($cart_x + 8) . "\n20\n" . ($table_y) . "\n30\n0.0\n40\n2.5\n1\nPROPUESTA DIVISION PARCELA NUMERO {$nombrePoligono}\n";

// --- CUADRO DE CONSTRUCCIÓN ---
// Aumenta el tamaño del cuadro y el texto
$table_y -= 20; // más espacio desde PROYECTO

// Título del cuadro
$dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($cart_x + 8) . "\n20\n" . ($table_y) .
    "\n30\n0.0\n40\n5\n1\n                 CUADRO DE CONSTRUCCION\n";

$table_y -= 12; // más espacio debajo del título

// Aumenta el ancho de columnas y alto de filas
$cuadro_x = $cart_x + 10; // más a la izquierda
$cuadro_y = $table_y;
$col_widths = [18, 25, 25, 25, 32, 32]; // columnas más anchas
$col_names = ['PUNTO' ,'LADO', 'DIST.', 'ÁNGULO', 'ESTE', 'NORTE'];
$row_height = 10; // filas más altas

// Header
for ($i = 0, $x = $cuadro_x, $header_y = $cuadro_y; $i < count($col_names); $i++) {
    $dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($x + 2) . "\n20\n" . ($header_y) .
        "\n30\n0.0\n40\n4\n1\n" . $col_names[$i] . "\n";
    $x += $col_widths[$i];
}

// Filas
for ($i = 0; $i < $n - 1; $i++) {
    $vertice = chr(65 + $i);
    $lado = chr(65 + $i) . "-" . chr(65 + $i + 1);
    $x1 = $puntos[$i]['x'];
    $y1 = $puntos[$i]['y'];
    $x2 = $puntos[$i + 1]['x'];
    $y2 = $puntos[$i + 1]['y'];
    $dist = sqrt(pow($x2 - $x1, 2) + pow($y2 - $y1, 2));
    $angulo = rad2deg(atan2($y2 - $y1, $x2 - $x1));
    if ($angulo < 0) $angulo += 360;

    $row_y = $cuadro_y - ($i + 1) * $row_height;
    $values = [
        $vertice,
        $lado,
        number_format($dist, 2),
        number_format($angulo, 2),
        number_format($x1, 2),
        number_format($y1, 2)
    ];

    $x = $cuadro_x;
    foreach ($values as $j => $val) {
        $dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($x + 2) .
            "\n20\n" . ($row_y) . "\n30\n0.0\n40\n4\n1\n$val\n";
        $x += $col_widths[$j];
    }
}

// Líneas horizontales
$total_rows = ($n - 1) + 1;
for ($i = 0; $i <= $total_rows; $i++) {
    $y_line = $cuadro_y - ($i * $row_height);
    $dxf .= "0\nLINE\n8\nCartucho\n10\n$cuadro_x\n20\n$y_line\n30\n0.0\n11\n" .
        ($cuadro_x + array_sum($col_widths)) . "\n21\n$y_line\n31\n0.0\n";
}

// Líneas verticales
$x = $cuadro_x;
for ($i = 0; $i <= count($col_widths); $i++) {
    $dxf .= "0\nLINE\n8\nCartucho\n10\n$x\n20\n" . ($cuadro_y) .
        "\n30\n0.0\n11\n$x\n21\n" . ($cuadro_y - $total_rows * $row_height) . "\n31\n0.0\n";
    if ($i < count($col_widths)) {
        $x += $col_widths[$i];
    }
}
 
// --- ESPACIO PARA FIRMA Y SELLO ---
$firma_y = $cart_y + 35; // ajusta más abajo si lo deseas

// Texto "FIRMA:"
$dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($cart_x + 7) . "\n20\n" . ($firma_y) . "\n30\n0.0\n40\n2.5\n1\nFIRMA:\n";

// Raya para firma
$dxf .= "0\nLINE\n8\nCartucho\n10\n" . ($cart_x + 20) . "\n20\n" . ($firma_y - 1) . "\n30\n0.0\n11\n" . ($cart_x + 70) . "\n21\n" . ($firma_y - 1) . "\n31\n0.0\n";

// Texto "SELLO:" al lado de la firma
$dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($cart_x + 75) . "\n20\n" . ($firma_y) . "\n30\n0.0\n40\n2.5\n1\nSELLO:\n";

// --- LEYENDA LEGAL ---
$dxf .= "0\nTEXT\n8\nCartucho\n10\n" . ($cart_x + 5) . "\n20\n" . ($cart_y + 5) . "\n30\n0.0\n40\n2.2\n1\nEste plano cumple con la Ley del Instituto Geográfico de Venezuela Simón Bolívar\n";

     $dxf .= "0\nENDSEC\n0\nSECTION\n2\nOBJECTS\n0\nENDSEC\n0\nEOF";

    // --- SALIDA DEL ARCHIVO DXF ---
    if (!headers_sent()) {
        header('Content-Type: application/dxf');
        header('Content-Disposition: attachment; filename="' . basename($nombreArchivo) . '"');
        header('Content-Length: ' . strlen($dxf));
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: 0');
        ob_clean(); // Limpia el buffer de salida
        flush();
        echo $dxf;
        exit;
    } else {
        echo "Error: los encabezados ya fueron enviados. No se puede forzar descarga del DXF.";
    }
}
?>