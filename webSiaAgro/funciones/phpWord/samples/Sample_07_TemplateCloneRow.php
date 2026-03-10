<?php
include_once 'Sample_Header.php';
include_once 'conn.php';
$gid=$_GET['gid'];

setlocale (LC_TIME, "es_VE.UTF-8");

// Template processor instance creation
echo date('H:i:s'), ' Creating new TemplateProcessor instance...', EOL;
echo date('l, d \d\e F \d\e Y'), EOL;
echo strftime('%A, %d de %B de %Y'), EOL;

$sql = "SELECT * FROM inmuebles where gid=583";
$result = pg_query($conexion, $sql) or die("Error en la Consulta SQL");


while ($row=pg_fetch_array($result)) {

if($row['levantamiento']=='EXTERNO'){
	$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('resources/avaltemplate.docx');

	$sql_a = "INSERT INTO sist_geo_tb_sequencia_aval (gid) VALUES (583)";          //crea el numero de aval
	$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQLa");

	$sql_a = "SELECT id FROM sist_geo_tb_sequencia_aval WHERE gid=583";             //obtiene el numero de aval
	$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQLb");
	$row_a = pg_fetch_array($result_a);
	$numero_aval = sprintf("%04d", $row_a['id']);                     //formato numero 4 digitos con ceros a la izquierda

	$codigo_aval = 'DG-APT-'.$numero_aval.'-'.date('Ymd');

	$nombre_archivo=explode(', ', $row['nombre_pro']);
	$nombre_archivo[0]=$codigo_aval.'-'.$nombre_archivo[0].'-'.$row['nombre_civ'];
	
} else if($row['levantamiento']=='ALCALDIA'){
	$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('resources/avaltemplate_levant.docx');

	$sql_a = "INSERT INTO sist_geo_tb_sequencia_aval_levant (gid) VALUES (583)";          //crea el numero de aval
	$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQLd");

	$sql_a = "SELECT id FROM sist_geo_tb_sequencia_aval_levant WHERE gid=583";             //obtiene el numero de aval
	$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQLe");
	$row_a = pg_fetch_array($result_a);
	$numero_aval = sprintf("%04d", $row_a['id']);                     //formato numero 4 digitos con ceros a la izquierda

	$codigo_aval = 'DG-ALT-'.$numero_aval.'-'.date('Ymd');

	$nombre_archivo=explode(', ', $row['nombre_pro']);
	$nombre_archivo[0]=$codigo_aval.'-'.$nombre_archivo[0].'-'.$row['nombre_civ'];

}


//$sql_a = "UPDATE sist_geo_tb_inm_modif SET avalado = '1', nombre_archivo = '$nombre_archivo[0]' WHERE gid=$gid"; //marca inmueble como avalado
//$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQLg");

$fecha_aval = date('Y-m-d');
//$sql_a = "UPDATE inmuebles SET cod_aval = '$codigo_aval', fecha_aval = '$fecha_aval' WHERE gid=$gid";  //guarda codigo y fecha de aval
//$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQLh");

$templateProcessor->setValue('fecha_larga', strftime('Turmero, %A %d de %B de %Y'));

$templateProcessor->setValue('propietario', $row['nombre_pro']);
$templateProcessor->setValue('rif', $row['rif']);
$templateProcessor->setValue('codigo_catastral', $row['cod_catast']);
$templateProcessor->setValue('codigo_propietario', $row['cod_propie']);
$templateProcessor->setValue('nombre_civico', ucwords(strtolower($row['nombre_civ'])));
$templateProcessor->setValue('sector', ucwords(strtolower($row['sector'])));
$templateProcessor->setValue('parroquia', ucwords(strtolower($row['parroquia'])));
$templateProcessor->setValue('area_documento', $row['area_documento']);
$templateProcessor->setValue('area', $row['area']);
$templateProcessor->setValue('tenencia', $row['tenencia']);

$templateProcessor->setValue('codigo_aval', $codigo_aval);

if($row['noreste'] != '0'){
	$lindero11='NorEste: '.$row['noreste'];
	$templateProcessor->setValue('lindero1', ucwords(strtolower($row['noreste'])));
	$templateProcessor->setValue('nombre_lindero1', 'Noreste');
} else if($row['norte'] != '0'){
	$templateProcessor->setValue('lindero1', ucwords(strtolower($row['norte'])));
	$templateProcessor->setValue('nombre_lindero1', 'Norte');
}
if($row['sureste'] != '0'){
	$templateProcessor->setValue('lindero2', ucwords(strtolower($row['sureste'])));
	$templateProcessor->setValue('nombre_lindero2', 'Sureste');
} else if($row['este'] != '0'){
	$templateProcessor->setValue('lindero2', ucwords(strtolower($row['este'])));
	$templateProcessor->setValue('nombre_lindero2', 'Este');
}
if($row['suroeste'] != '0'){
	$templateProcessor->setValue('lindero3', ucwords(strtolower($row['suroeste'])));
	$templateProcessor->setValue('nombre_lindero3', 'Suroeste');
} else if($row['sur'] != '0'){
	$templateProcessor->setValue('lindero3', ucwords(strtolower($row['sur'])));
	$templateProcessor->setValue('nombre_lindero3', 'Sur');
}
if($row['noroeste'] != '0'){
	$templateProcessor->setValue('lindero4', ucwords(strtolower($row['noroeste'])));
	$templateProcessor->setValue('nombre_lindero4', 'Noroeste');
} else if($row['oeste'] != '0'){
	$templateProcessor->setValue('lindero4', ucwords(strtolower($row['oeste'])));
	$templateProcessor->setValue('nombre_lindero4', 'Oeste');
}

$templateProcessor->setValue('codigo_plano', $row['codigo_plano']);
$templateProcessor->setValue('direccion', ucwords(strtolower($row['direccion'])));

if($row['informe_tecnico'] == '0'){
	$templateProcessor->setValue('informe_tecnico', '.');	
} else if($row['informe_tecnico'] != '0'){
	$informe_tecnico=' según informe técnico '.$row['informe_tecnico'].'.';
	$templateProcessor->setValue('informe_tecnico', $informe_tecnico);
}

}


echo date('H:i:s'), ' Saving the result document... nombre de aval ', $nombre_archivo[0], EOL;

$templateProcessor->saveAs('results/'.$nombre_archivo[0].'.docx');

echo getEndingNotes(array('Word2007' => 'docx'));
if (!CLI) {
	echo "<br><a href='/prueba/PHPWord_0_13/samples/results/$nombre_archivo[0].docx' class='btn btn-primary'>archivo DOCX</a> ";
    include_once 'Sample_Footer.php';
}
