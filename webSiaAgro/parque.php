<?php
// Generador DXF profesional: Plano de parque 6 x 32 m
// Producción: archivo DXF compatible con AutoCAD (R2000+)
// Este script genera un plano limpio con title-block (según COVENIN 3476 simplificado),
// leyenda, simbología y notas que referencian normas venezolanas aplicables.
// Guardar este archivo como `generador_plano_parque_profesional.php` y ejecutarlo en un servidor PHP
// para descargar el DXF: plano_parque_6x32_prof.dxf

// -------------------- Helpers DXF (texto plano DXF R12-like) --------------------
function e($k,$v){ return $k."
".$v."
"; }

function header_section(){
  $s  = e(0,'SECTION');
  $s .= e(2,'HEADER');
  $s .= e(9,'$INSUNITS');
  $s .= e(70,6); // metros
  $s .= e(0,'ENDSEC');
  return $s;
}

function tables_section($layers){
  $s  = e(0,'SECTION').e(2,'TABLES');
  $s .= e(0,'TABLE').e(2,'LAYER');
  $s .= e(70,count($layers));
  foreach($layers as $name=>$color){
    $s .= e(0,'LAYER');
    $s .= e(2,$name);
    $s .= e(70,0);
    $s .= e(62,$color);
    $s .= e(6,'CONTINUOUS');
  }
  $s .= e(0,'ENDTAB');
  $s .= e(0,'ENDSEC');
  return $s;
}

function blocks_section(){
  $s  = e(0,'SECTION').e(2,'BLOCKS');
  // BANCA
  $s .= e(0,'BLOCK').e(2,'BANCA').e(70,0).e(10,0).e(20,0).e(30,0);
  $s .= lwpoly([[0,0],[1.2,0],[1.2,0.45],[0,0.45]],'BLOQUE',true);
  $s .= e(0,'ENDBLK');
  // PAPELERA
  $s .= e(0,'BLOCK').e(2,'PAPELERA').e(70,0).e(10,0).e(20,0).e(30,0);
  $s .= circle(0,0,0.15,'BLOQUE');
  $s .= e(0,'ENDBLK');
  // POSTE
  $s .= e(0,'BLOCK').e(2,'POSTE').e(70,0).e(10,0).e(20,0).e(30,0);
  $s .= circle(0,0,0.10,'BLOQUE');
  $s .= e(0,'ENDBLK');
  // MAQUINA
  $s .= e(0,'BLOCK').e(2,'MAQUINA').e(70,0).e(10,0).e(20,0).e(30,0);
  $s .= lwpoly([[0,0],[2.0,0],[2.0,0.9],[0,0.9]],'BLOQUE',true);
  $s .= e(0,'ENDBLK');
  $s .= e(0,'ENDSEC');
  return $s;
}

function lwpoly($pts,$layer,$closed=false){
  $s = e(0,'LWPOLYLINE');
  $s .= e(8,$layer);
  $s .= e(90,count($pts));
  $s .= e(70,$closed?1:0);
  foreach($pts as $p){ $s .= e(10,$p[0]).e(20,$p[1]); }
  return $s;
}

function circle($x,$y,$r,$layer){
  $s = e(0,'CIRCLE');
  $s .= e(8,$layer);
  $s .= e(10,$x).e(20,$y).e(40,$r);
  return $s;
}

function textEnt($x,$y,$h,$txt,$layer,$rot=0){
  $s = e(0,'TEXT');
  $s .= e(8,$layer);
  $s .= e(10,$x).e(20,$y);
  $s .= e(40,$h);
  $s .= e(1,$txt);
  if($rot!=0) $s .= e(50,$rot);
  return $s;
}

function insert($name,$x,$y,$layer,$sx=1,$sy=1,$rot=0){
  $s = e(0,'INSERT');
  $s .= e(8,$layer);
  $s .= e(2,$name);
  $s .= e(10,$x).e(20,$y);
  $s .= e(41,$sx).e(42,$sy);
  if($rot!=0) $s .= e(50,$rot);
  return $s;
}

// -------------------- Parámetros del proyecto --------------------
$W = 32.0; // largo X (m)
$H = 6.0;  // ancho Y  (m)
$acera = 0.8; // ancho acera perimetral (m)
$verde_h = 0.7; // franja verde (m)

$layers = [
  'TERRENO'=>7,
  'ACERA'=>8,
  'VERDE'=>3,
  'MAQUINAS'=>1,
  'BANCAS'=>2,
  'ARBOLES'=>34,
  'PAPELERAS'=>30,
  'POSTES'=>140,
  'COTAS'=>5,
  'TEXTO'=>7,
  'BLOQUE'=>250,
  'SIMBO'=>6,
  'TRAZO'=>9
];

// -------------------- ENTIDADES --------------------
$entities = '';
// Terreno
$entities = lwpoly([[0,0],[$W,0],[$W,$H],[0,$H],[0,0]],'TERRENO',true);

// Acera interior
$entities .= lwpoly([[$acera,$acera],[$W-$acera,$acera],[$W-$acera,$H-$acera],[$acera,$H-$acera],[$acera,$acera]],'ACERA',true);

// Franjas verdes
$entities .= lwpoly([[$acera,$acera],[$W-$acera,$acera],[$W-$acera,$acera+$verde_h],[$acera,$acera+$verde_h],[$acera,$acera]],'VERDE',true);
$entities .= lwpoly([[$W-$acera-$verde_h,$acera+$verde_h],[$W-$acera,$acera+$verde_h],[$W-$acera,$H-$acera],[$W-$acera-$verde_h,$H-$acera],[$W-$acera-$verde_h,$acera+$verde_h]],'VERDE',true);

// Title block (más detallado)
$tb_x = $W + 1.0; $tb_y = 0.0; $tb_w = 11.0; $tb_h = 5.0;
$entities .= lwpoly([[$tb_x,$tb_y],[$tb_x+$tb_w,$tb_y],[$tb_x+$tb_w,$tb_y+$tb_h],[$tb_x,$tb_y+$tb_h],[$tb_x,$tb_y]],'TRAZO',true);
$entities .= textEnt($tb_x+0.3,$tb_y+$tb_h-0.4,0.35,'PLANO DE PARQUE 6 x 32 m','TEXTO');
$entities .= textEnt($tb_x+0.3,$tb_y+$tb_h-0.8,0.22,'Escala: 1:50','TEXTO');
$entities .= textEnt($tb_x+0.3,$tb_y+$tb_h-1.2,0.18,'Elaborado por: ____________________','TEXTO');
$entities .= textEnt($tb_x+6.2,$tb_y+$tb_h-1.2,0.18,'Fecha: ____/____/____','TEXTO');
$entities .= textEnt($tb_x+0.3,$tb_y+$tb_h-1.6,0.18,'Proyecto: Parque recreativo','TEXTO');
$entities .= textEnt($tb_x+0.3,$tb_y+$tb_h-2.0,0.18,'Ubicación: Sector X, Ciudad Y','TEXTO');

// Leyenda y simbologia
$entities .= textEnt($tb_x+0.3,$tb_y+1.2,0.18,'LEYENDA:','TEXTO');
$entities .= textEnt($tb_x+0.3,$tb_y+0.9,0.16,'Banca: 1.20 x 0.45 m', 'TEXTO');
$entities .= textEnt($tb_x+0.3,$tb_y+0.65,0.16,'Papelera: Ø 0.30 m', 'TEXTO');
$entities .= textEnt($tb_x+0.3,$tb_y+0.4,0.16,'Poste iluminación: Ø 0.20 m','TEXTO');
$entities .= textEnt($tb_x+0.3,$tb_y+0.15,0.16,'Máquina: 2.00 x 0.90 m','TEXTO');

// Bancas (distribuidas y cotadas)
$banca_dist = ($W - 2*$acera - 1.2)/4;
for($i=0;$i<5;$i++){
  $x = $acera + 0.6 + $i*$banca_dist; $y = $acera + 0.12;
  $entities .= insert('BANCA',$x,$y,'BANCAS');
  $entities .= textEnt($x+0.6,$y+0.5,0.16,"Banca ".($i+1)." (X=".number_format($x,2)." m)",'TEXTO');
  // Línea de cota horizontal desde origen
  $entities .= lwpoly([[0,$y+0.7],[$x,$y+0.7]],'COTAS');
  $entities .= textEnt($x/2,$y+0.8,0.14,number_format($x,2).' m','COTAS');
}

// Árboles (con cotas)
$trees = [
  [$acera+4.0,$acera+$verde_h+0.9],
  [$W-$acera-1.2,$acera+$verde_h+2.1],
  [$acera+14.0,$H-$acera-0.9]
];
foreach($trees as $i=>$t){
  $entities .= circle($t[0],$t[1],0.25,'ARBOLES');
  $entities .= textEnt($t[0]+0.3,$t[1]+0.3,0.16,"Árbol ".($i+1)." (X=".number_format($t[0],2)." Y=".number_format($t[1],2)." m)",'TEXTO');
  // Línea de cota desde origen X
  $entities .= lwpoly([[0,$t[1]+0.4],[$t[0],$t[1]+0.4]],'COTAS');
  $entities .= textEnt($t[0]/2,$t[1]+0.5,0.14,number_format($t[0],2).' m','COTAS');
}

// Máquinas (con cotas)
$mm_x0 = $W-$acera-$verde_h-10.0; $mm_y0 = $acera+$verde_h+0.4;
$entities .= lwpoly([[$mm_x0,$mm_y0],[$mm_x0+10.0,$mm_y0],[$mm_x0+10.0,$mm_y0+3.0],[$mm_x0,$mm_y0+3.0],[$mm_x0,$mm_y0]],'MAQUINAS',true);
$entities .= textEnt($mm_x0+0.3,$mm_y0+1.5,0.22,'Máquina Multiuso','TEXTO');
$entities .= lwpoly([[0,$mm_y0+3.2],[$mm_x0,$mm_y0+3.2]],'COTAS');
$entities .= textEnt($mm_x0/2,$mm_y0+3.3,0.14,number_format($mm_x0,2).' m','COTAS');

$mt_x0 = $acera+2.5; $mt_y0 = $acera+$verde_h+1.6;
$entities .= lwpoly([[$mt_x0,$mt_y0],[$mt_x0+8.0,$mt_y0],[$mt_x0+8.0,$mt_y0+1.0],[$mt_x0,$mt_y0+1.0],[$mt_x0,$mt_y0]],'MAQUINAS',true);
$entities .= textEnt($mt_x0+0.2,$mt_y0+0.35,0.2,'Máquina de Tracción','TEXTO');
$entities .= lwpoly([[0,$mt_y0+1.2],[$mt_x0,$mt_y0+1.2]],'COTAS');
$entities .= textEnt($mt_x0/2,$mt_y0+1.3,0.14,number_format($mt_x0,2).' m','COTAS');

// Papelera y postes (con cotas)
$pap_x = $W-$acera-1.8; $pap_y = $acera+0.2;
$entities .= insert('PAPELERA',$pap_x,$pap_y,'PAPELERAS');
$entities .= textEnt($pap_x-0.6,$pap_y+0.45,0.16,'Papelera','TEXTO');
$entities .= lwpoly([[0,$pap_y+0.6],[$pap_x,$pap_y+0.6]],'COTAS');
$entities .= textEnt($pap_x/2,$pap_y+0.7,0.14,number_format($pap_x,2).' m','COTAS');

$postes = [
  [$acera+0.6,$H-$acera-0.6],
  [$acera+2.4,$H-$acera-0.6]
];
foreach($postes as $i=>$p){
  $entities .= insert('POSTE',$p[0],$p[1],'POSTES');
  $entities .= textEnt($p[0]+0.2,$p[1]-0.2,0.16,"Poste ".($i+1),'TEXTO');
  $entities .= lwpoly([[0,$p[1]+0.2],[$p[0],$p[1]+0.2]],'COTAS');
  $entities .= textEnt($p[0]/2,$p[1]+0.3,0.14,number_format($p[0],2).' m','COTAS');
}

// Acceso simple (con cota)
$ax=1.6; $ay=$acera; $aw=1.6; $ah=0.6;
$entities .= lwpoly([[$ax,$ay],[$ax+$aw,$ay],[$ax+$aw,$ay+$ah],[$ax,$ay+$ah],[$ax,$ay]],'TRAZO',true);
$entities .= textEnt($ax+0.1,$ay+$ah+0.1,0.18,'Acceso','TEXTO');
$entities .= lwpoly([[0,$ay+$ah+0.3],[$ax,$ay+$ah+0.3]],'COTAS');
$entities .= textEnt($ax/2,$ay+$ah+0.4,0.14,number_format($ax,2).' m','COTAS');

// Cotas principales (texto representativo)
$entities .= textEnt($W/2-0.9,-0.6,0.18,'32.00 m','COTAS');
$entities .= textEnt(-1.25,$H/2-0.1,0.18,'6.00 m','COTAS',90);

// Notas y referencias normativas (texto en rótulo)
$notes = [
  'Notas:','- Plano conforme a normas COVENIN 3476 (Rotulado) y 3477 (Formato y plegado).',
  '- Acotaciones y simbología según COVENIN Dibujo Técnico aplicable.',
  '- Verificar requerimientos sismorresistentes: COVENIN 1756.',
  '- Este plano es representativo; medidas finales validar en obra.'
];
$ny = $tb_y + $tb_h - 2.3;
foreach($notes as $i=>$ln){ $entities .= textEnt($tb_x+0.3,$ny - $i*0.22,0.16,$ln,'TEXTO'); }

// -------------------- Armar DXF final --------------------
$dxf = '';
$dxf .= e(0,'SECTION').e(2,'ENTITIES');
$dxf = header_section() . tables_section($layers) . blocks_section();
$dxf .= e(0,'SECTION').e(2,'ENTITIES');
$dxf .= $entities;
$dxf .= e(0,'ENDSEC').e(0,'EOF');

// Descarga forzada
header('Content-Type: application/dxf');
header('Content-Disposition: attachment; filename="plano_parque_6x32_prof.dxf"');
echo $dxf;
exit;
?>
