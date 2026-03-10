<?php
if (isset($array['pagina'])) {
#funcion para obtener en inicio y fin de una semana dados el numero de semana y el año
function getStartAndEndDate($week, $year) {
  $dto = new DateTime();
  $dto->setISODate($year, $week);
  $ret['week_start'] = $dto->format('Y-m-d');
  $dto->modify('+6 days');
  $ret['week_end'] = $dto->format('Y-m-d');
  return $ret;
}

if($_GET['pagina']=='registrar_actividad'){
	$clasePadre='Registro';
	$claseHijo='actividades';

	#sql select de parroquias
	$sql_parroquias="SELECT id,parroquias FROM parroquias ORDER BY parroquias";
	$result_parroquias=pg_query($conexion,$sql_parroquias) or die ("Error en SQL parroquias actividades " .pg_last_error());

	if (isset($_POST['registrar_actividad']) && !empty($_POST['registrar_actividad'])) {
		$nombre_act=strtoupper(pg_escape_string($_POST['nombre_act']));
		$descripcion=strtoupper(pg_escape_string($_POST['descripcion']));
		$id_parroquia=$_POST['id_parroquia'];
		$direccion=strtoupper(pg_escape_string($_POST['direccion']));
		$fecha_programada=$_POST['fecha_programada'];
		$responsable=strtoupper(pg_escape_string($_POST['responsable']));
		

		$sql_insert_actividad="INSERT INTO sist_geo_tb_actividades(id_departamento, nombre_act, descripcion, id_parroquia, direccion, fecha_creada, fecha_programada, responsable, numero_semana, anio) VALUES ('".$_SESSION['id_dept']."', '$nombre_act', '$descripcion', '$id_parroquia', '$direccion', '".date("Y-m-d")."', '$fecha_programada', '$responsable', '".date('W')."', '".date('Y')."')";

		$result_insert_actividad=pg_query($conexion,$sql_insert_actividad) or die ("Error en SQL INSERT actividades " .pg_last_error());
		$filas_afect_actividad=pg_affected_rows($result_insert_actividad);
		#se comprueba que haya afectado la tabla y lanza modal acorde si tuvo exito o no
		if (!empty($filas_afect_actividad)) {
			$mostrar_modal=1;
	      	$titulo='Registro de actividad';
	      	$contenido='<div class="alert alert-success"> Actividad creada con exito </div>';
		}else{
			$mostrar_modal=1;
	      	$titulo='Registro de actividad';
	      	$contenido='<div class="alert alert-danger"> Actividad no pudo ser creada </div>';
		}
	}

	//ver todo por defecto
	$ver_semana = '';

// Determinar el nombre del departamento sin depender de la sesión
$nombre_depart = isset($_POST['nombre_depart']) ? $_POST['nombre_depart'] : 'Todos los departamentos';

// Selección de la semana a visualizar
if (isset($_POST['ver_semana']) && !empty($_POST['ver_semana'])) {
    $ver_semana = "WHERE a.id_departamento='" . $_POST['id_depart'] . "' AND a.numero_semana='" . $_POST['ver_semana'] . "'";
}

// Consultas SQL sin validación de sesión para mostrar actividades de todos los departamentos

// Resumen de actividades de todos los departamentos
$sql_semanas = "SELECT count(*) as cuenta, min(fecha_programada) as fecha_menor, a.numero_semana, a.id_departamento, b.nom_dep 
    FROM sist_geo_tb_actividades a 
    INNER JOIN \"sist_geo_reg-departamento\" b ON a.id_departamento=b.id 
    GROUP BY a.numero_semana, a.id_departamento, b.nom_dep, a.anio 
    ORDER BY a.anio, a.numero_semana DESC";
$result_semanas = pg_query($conexion, $sql_semanas) or die ("Error en SQL semanas " . pg_last_error());
$numero_filas_semanas = pg_affected_rows($result_semanas);

// Listado de actividades de todos los departamentos
$sql_actividades = "SELECT a.nombre_act, b.parroquias, a.direccion, a.fecha_creada, a.fecha_programada, a.responsable, a.ejecutada, 
    a.fecha_modif_eje, a.descripcion, a.numero_semana, c.nom_dep 
    FROM sist_geo_tb_actividades a 
    INNER JOIN parroquias b ON a.id_parroquia=b.id 
    INNER JOIN \"sist_geo_reg-departamento\" c ON a.id_departamento=c.id 
    $ver_semana 
    ORDER BY a.fecha_programada DESC";
$result_actividades = pg_query($conexion, $sql_actividades) or die ("Error en SQL actividades " . pg_last_error());
$numero_filas = pg_affected_rows($result_actividades);

}
}

?>