<?php
if (isset($array['pagina'])) {
#acciones cuando se busca usuario externo en visitas y se crean visitas
if (!empty($_POST['registro_visita']) && isset($_POST['registro_visita'])){

	if($_POST['registro_visita']=='Buscar Usuario'){
		$cedula=$_POST['cedula'];
		$busqueda_realizada='1';
		$sql_usuario="SELECT * from usuarios_externos WHERE cedula='".$cedula."'";
		$result_usuario = pg_query($conexion, $sql_usuario) or die ("Error en SQL al buscar usuario: " .pg_last_error());
		$datos_usuario = pg_fetch_array($result_usuario);
    if (empty(pg_affected_rows($result_usuario))) {
      $sql_usuario="SELECT nombre from rep2015 WHERE cedula='".$cedula."'";
      $result_usuario = pg_query($conexion, $sql_usuario) or die ("Error en SQL al buscar usuario rep2015 " .pg_last_error());
      $datos_usuario = pg_fetch_array($result_usuario);
    }
    
	}

	if ($_POST['registro_visita']=='Registrar Visita') {
	
	  $cedula=$_POST['cedula'];
	  $nombre=strtoupper(pg_escape_string($_POST['nombre']));
	  $telefono=$_POST['telefono'];
	  $correo=$_POST['correo'];
	  $propietario=strtoupper(pg_escape_string($_POST['propietario']));
    $observaciones=strtoupper(pg_escape_string($_POST['observaciones']));
	  $id_soli=$_POST['solicitud'];
	  $usuario_existe=$_POST['usuario_existe'];
	  $fecha=date('Y-m-d');

	  if($usuario_existe=='1'){
	  	$sql_usua_reg = "UPDATE usuarios_externos SET nombre='$nombre', telefono='$telefono', correo='$correo' WHERE cedula='$cedula' RETURNING id_usuario";
	  }else{
      	$sql_usua_reg = "INSERT INTO usuarios_externos (cedula, nombre, telefono, correo) VALUES ('$cedula', '$nombre', '$telefono', '$correo') RETURNING id_usuario";
      	}

      $result_ingre_user = pg_query($conexion, $sql_usua_reg) or die ("Error en SQL al registrar al usuario: " .pg_last_error());
      
      $filas_afect_soli=pg_affected_rows($result_ingre_user);
      $id_usuario=pg_fetch_result($result_ingre_user,0,0);

      if (!empty($filas_afect_soli)){
      	
      	$sql_ingre_soli ="INSERT INTO visitas (id_usuario, id_tipo_solicitud, id_atendido_por, fecha,  propietario, observaciones) VALUES ('$id_usuario', '$id_soli', '".$_SESSION['id']."', '$fecha', '$propietario', '$observaciones')";

      	$result_ingre_soli = pg_query($conexion, $sql_ingre_soli) or die ("Error en SQL al registrar solicitud: " .pg_last_error());
               
      	$mostrar_modal=1;
      	$titulo='Registro de visitas';
      	$contenido='<div class="alert alert-success"> Visita creada con exito </div>';
      }
      else{ 
      	$mostrar_modal=1;
      	$titulo='Registro de visitas';
      	$contenido='<div class="alert alert-danger"> Visita no pudo ser creada </div>';
      }
	}
}


#--- sentencia para visualizar registro de visitas -----
if($_GET['pagina'] == 'registro_de_visitas'){

    $clasePadre = 'Geomatica';
	$claseHijo = 'Registro de visitas';

	$sql_solicitud="SELECT * FROM tipo_solicitud  ORDER BY id ASC";
    $resul_solicitud=pg_query($conexion,$sql_solicitud);

    $busqueda = "<strong>No existen</strong> registros de nuevas solicitudes.";
  
    $sql_ver_solic = "SELECT a.id_usuario, a.id_tipo_solicitud, a.id_atendido_por, a.fecha, a.propietario, a.observaciones, b.solicitud, c.*, d.nombre as atendido_por FROM visitas a INNER JOIN tipo_solicitud b ON b.id = a.id_tipo_solicitud 
    INNER JOIN usuarios_externos c ON a.id_usuario = c.id_usuario 
    INNER JOIN sist_geo_tb_usuario d ON d.id_user = a.id_atendido_por ORDER BY a.id_solicitud DESC";

    $res_tabla_solic = pg_query($conexion, $sql_ver_solic) or die("Error en la consulta SQL para mostrar registros." .pg_last_error());
    $numero_filas= pg_affected_rows($res_tabla_solic);
}


#--- sentencia para registros de solicitudes--
if (!empty($_POST['registro_solicitud']) && isset($_POST['registro_solicitud'])){

  if($_POST['registro_solicitud']=='Buscar Usuario'){
    $cedula=$_POST['cedula'];
    
    $sql_usuario="SELECT * from usuarios_externos WHERE cedula='".$cedula."'";
    $result_usuario = pg_query($conexion, $sql_usuario) or die ("Error en SQL al buscar usuario solic: " .pg_last_error());
    if (empty(pg_affected_rows($result_usuario))) {
      $sql_usuario="SELECT nombre from rep2015 WHERE cedula='".$cedula."'";
      $result_usuario = pg_query($conexion, $sql_usuario) or die ("Error en SQL al buscar usuario solicitud rep2015 " .pg_last_error());
    }
    $datos_usuario = pg_fetch_array($result_usuario);
  }

  if ($_POST['registro_solicitud']=='Registrar Solicitud') {
  
    $cedula=$_POST['cedula'];
    $nombre=strtoupper(pg_escape_string($_POST['nombre']));
    $telefono=$_POST['telefono'];
    $correo=$_POST['correo'];
    $propietario=strtoupper(pg_escape_string($_POST['propietario']));
    $id_soli=$_POST['solicitud'];
    $usuario_existe=$_POST['usuario_existe'];
    $fecha=date('Y-m-d');
    $nombre_civico=strtoupper(pg_escape_string($_POST['nombre_civico']));
    $rif=strtoupper(pg_escape_string($_POST['rif']));

    if($usuario_existe=='1'){
      $sql_usua_reg = "UPDATE usuarios_externos SET nombre='$nombre', telefono='$telefono', correo='$correo' WHERE cedula='$cedula' RETURNING id_usuario";
    }else{
        $sql_usua_reg = "INSERT INTO usuarios_externos (cedula, nombre, telefono, correo) VALUES ('$cedula', '$nombre', '$telefono', '$correo') RETURNING id_usuario";
        }

      $result_ingre_user = pg_query($conexion, $sql_usua_reg) or die ("Error en SQL al registrar al usuario: " .pg_last_error());
      
      $filas_afect_soli=pg_affected_rows($result_ingre_user);
      $id_usuario=pg_fetch_result($result_ingre_user,0,0);

      if (!empty($filas_afect_soli)){
        
        $sql_ingre_soli ="INSERT INTO solicitudes (id_usuario, id_tipo_solicitud, id_atendido_por, fecha,  propietario, nombre_civico, rif) VALUES ('$id_usuario', '$id_soli', '".$_SESSION['id']."', '$fecha', '$propietario', '$nombre_civico', '$rif') RETURNING id_solicitud, codigo_solicitud"; 

        $result_ingre_soli = pg_query($conexion, $sql_ingre_soli) or die ("Error en SQL al registrar solicitud: " .pg_last_error());

        $id_solicitud=pg_fetch_result($result_ingre_soli, 0, 0);
        $codigo_solicitud=pg_fetch_result($result_ingre_soli, 0, 1);
        
        $mostrar_modal=1;
        $titulo='Registro de solicitudes';
        $contenido='<div class="alert alert-success"> Solicitud creada con exito </div>';

        #email inicial de registro
        $headers_email = "From: geomatica alcaldia santiago mariño<geomaticamsm@gmail.com>\r\n";
        $headers_email .= "Reply-To: geomatica alcaldia santiago mariño<geomaticamsm@gmail.com>\r\n";
        $headers_email .= "MIME-Version: 1.0\r\n";
        $headers_email .= "Content-Type: text/html; charset=UTF-8\r\n";
        $titulo_email = "Informacion sobre estado de solicitud en Geomatica Municipio Santiago Mariño";

        $contenido_email = "<p>La solicitud codigo: ".$codigo_solicitud.".<br>Propietario: ".$propietario.".<br>Nombre civico: ".$nombre_civico.".<br>Su solicitud ha sido registrada exitosamente, este atento por este medio nos estaremos comunicando con usted.</p><br><br><br><img alt='imagen direccion de geomatica' src='https://i.imgur.com/FIRMA_CORREO.png'>";
        mail($correo, $titulo_email, $contenido_email, $headers_email);
        $sql_email_insert="INSERT INTO correos_enviados (tipo_correo, id_solicitud, id_enviado_por) VALUES ('registro_solicitud', '$id_solicitud', '".$_SESSION['id']."')";
        $result_email_insert=pg_query($conexion,$sql_email_insert) or die ("Error en SQL INSERT de email " .pg_last_error());

      }
      else{ 
        $mostrar_modal=1;
        $titulo='Registro de solicitudes';
        $contenido='<div class="alert alert-danger"> Solicitud no pudo ser creada </div>';
      }
  }
}


#--- sentencia para visualizar registro de solicitudes -----
if($_GET['pagina'] == 'registro_solicitudes'){

    $clasePadre = 'Geomatica';
    $claseHijo = 'Registro de solicitudes';

    $busqueda = "<strong>No existen</strong> registros de nuevas solicitudes.";
  
    $sql_ver_solic = "SELECT a.*, b.solicitud, c.*, d.nombre as atendido_por, e.listo as pestana1, f.apto as pestana2, g.listo as pestana3 , h.listo as pestana4, i.listo as pestana5, j.fecha_pago FROM solicitudes a
                      INNER JOIN tipo_solicitud b ON b.id = a.id_tipo_solicitud 
                      INNER JOIN usuarios_externos c ON a.id_usuario = c.id_usuario 
                      INNER JOIN sist_geo_tb_usuario d ON d.id_user = a.id_atendido_por
                      LEFT JOIN documentos_solicitudes e ON a.id_solicitud = e.id_solicitud
                      LEFT JOIN revision_de_planos f ON a.id_solicitud = f.id_solicitud
                      LEFT JOIN control_equipo g ON a.id_solicitud = g.id_solicitud
                      LEFT JOIN revision_inspeccion h ON a.id_solicitud = h.id_solicitud
                      LEFT JOIN espera_de_plano_dibujo i ON a.id_solicitud = i.id_solicitud
                      LEFT JOIN pago_solicitudes j ON a.id_solicitud = j.id_solicitud
                      WHERE (a.fecha> '2024-10-18')
                      ORDER BY a.id_solicitud DESC";

    $res_tabla_solic = pg_query($conexion, $sql_ver_solic) or die("Error en la consulta SQL para mostrar registros de tabla_solic." .pg_last_error());
    $numero_filas= pg_affected_rows($res_tabla_solic);
}


#-- sentencia para conocer el estatus de solicitudes--
if($_GET['pagina'] == 'estatus_solicitudes'){
        
    $clasePadre = 'Geomatica';
    $claseHijo = '<a href="portal.php?pagina=registro_solicitudes">Estatus de solicitud</a>';

    $clases1='class="nav-link active"';
    $fadein1='class="tab-pane active"';
  
    $id_solicitud = $_POST['id_solicitud'];

    //redirecciona en caso id_solicitud este vacio o no sea numerico
    if(empty($id_solicitud) || !is_numeric($id_solicitud)){
      header("Location: portal.php");
      die();
    }

    ############################modificacion de solicitud#####################################################
    if (!empty($_POST['modificar_solicitud']) && isset($_POST['modificar_solicitud'])) {

      $modif_propietario=strtoupper(pg_escape_string($_POST['propietario']));
      $modif_nombre_civico=strtoupper(pg_escape_string($_POST['nombre_civico']));
      $modif_rif=strtoupper(pg_escape_string($_POST['rif']));
      $modif_tipo_solicitud=$_POST['tipo_solicitud'];

      $sql_modif_solicitud="UPDATE solicitudes SET id_tipo_solicitud='$modif_tipo_solicitud', propietario='$modif_propietario', nombre_civico='$modif_nombre_civico', rif='$modif_rif' WHERE id_solicitud='$id_solicitud'";
      $result_modif_solicitud=pg_query($conexion, $sql_modif_solicitud) or die("Error en UPDATE para modificar la solicitud." .pg_last_error());
      $filas_afectadas_modif_solicitud=pg_affected_rows($result_modif_solicitud);

      if (!empty($filas_afectadas_modif_solicitud)) {
          $mostrar_modal=1;
          $titulo='Modificacion de datos de solicitud';
          $contenido='<div class="alert alert-success"> Guardado con exito </div>';
        }
        else{ 
          $mostrar_modal=1;
          $titulo='Modificacion de datos de solicitud';
          $contenido='<div class="alert alert-danger"> Error al guardar </div>';
        }
    }

    ###############################################################################################################

    $sql_status_solic ="SELECT a.*, b.solicitud, c.*, d.nombre as atendido_por
     FROM solicitudes a INNER JOIN tipo_solicitud b ON b.id = a.id_tipo_solicitud 
    INNER JOIN usuarios_externos c ON a.id_usuario = c.id_usuario 
    INNER JOIN sist_geo_tb_usuario d ON d.id_user = a.id_atendido_por
    WHERE a.id_solicitud = $id_solicitud";
 
    $res_tabla_status = pg_query($conexion, $sql_status_solic) or die("Error en la consulta SQL para mostrar registros de tabla_status." .pg_last_error());
    $numero_filas= pg_affected_rows($res_tabla_status);
    $row_tabla_statu = pg_fetch_array($res_tabla_status);
    $tipo_solicitud = $row_tabla_statu['solicitud'];

#sentencia que ejecuta la consulta de docuemntos para mostrar los estatus--
     $sql_doct_solic = "SELECT a.*, b.*, d.nombre AS atendido_por
       FROM  documentos_solicitudes a
       INNER JOIN solicitudes b ON b.id_solicitud = a.id_solicitud
       INNER JOIN sist_geo_tb_usuario d ON d.id_user = a.id_user_geo
       WHERE a.id_solicitud = $id_solicitud"; 

       #boton submit de documentos
    if (!empty($_POST['registros_documentos']) && isset($_POST['registros_documentos'])){
        
        $carta_motivo=(empty($_POST['carta_motivo']) ? '0' : '1') ;
        $ci_solicitante=(empty($_POST['ci_solicitante']) ? '0' : '1') ;
        $ficha_catastral=(empty($_POST['ficha_catastral']) ? '0' : '1') ;
        $informe_tecnico=(empty($_POST['informe_tecnico']) ? '0' : '1') ;
        $documento_propiedad=(empty($_POST['documento_propiedad']) ? '0' : '1') ;
        $plano_digital=(empty($_POST['plano_digital']) ? '0' : '1') ;
        $plano_fisico=(empty($_POST['plano_fisico']) ? '0' : '1') ;
        $autorizacion_pro=(empty($_POST['autorizacion_pro']) ? '0' : '1') ;
        $ci_propietario=(empty($_POST['ci_propietario']) ? '0' : '1') ;
        $listo=(empty($_POST['listo']) ? '0' : '1') ;
        $observaciones=strtoupper(pg_escape_string($_POST['observacion']));
        $id_solicitud =$_POST['id_solicitud'];
        $fecha= date('Y-m-d');

        $res_tabla_doct = pg_query($conexion, $sql_doct_solic) or die("Error en la consulta SQL para mostrar registros de doct_solic." .pg_last_error());
        #se necesita saber si existe filas para determinar si es insert o update
        $num_doct_filas = pg_affected_rows($res_tabla_doct);

        if (empty($num_doct_filas)){

          $sql_documentos = "INSERT INTO documentos_solicitudes (id_solicitud, carta_motivo, ci_solicitante, ficha_catastral, informe_tecnico, documento_propiedad, plano_digital, plano_fisico, autorizacion_pro, ci_propietario, listo, fecha, id_user_geo, observaciones) VALUES ('$id_solicitud', '$carta_motivo', '$ci_solicitante', '$ficha_catastral', '$informe_tecnico', '$documento_propiedad', '$plano_digital', '$plano_fisico', '$autorizacion_pro', '$ci_propietario', '$listo', '$fecha', '".$_SESSION['id']."', '$observaciones') ";
        } 
        else{
      
          $sql_documentos = "UPDATE documentos_solicitudes SET carta_motivo='$carta_motivo', ci_solicitante='$ci_solicitante', ficha_catastral='$ficha_catastral', informe_tecnico='$informe_tecnico', documento_propiedad='$documento_propiedad', plano_digital='$plano_digital', plano_fisico='$plano_fisico', autorizacion_pro='$autorizacion_pro', ci_propietario='$ci_propietario', listo='$listo', fecha='$fecha', id_user_geo='".$_SESSION['id']."', observaciones='$observaciones' WHERE id_solicitud='$id_solicitud'";
        }
         $result_documentos = pg_query($conexion, $sql_documentos) or die ("Error en SQL al registrar documentos del usuario: " .pg_last_error());
         $num_filas_documentos = pg_affected_rows($result_documentos); 
        
        if (!empty($num_filas_documentos)) {
          $mostrar_modal=1;
          $titulo='Recaudos';
          $contenido='<div class="alert alert-success"> Guardado con exito </div>';
        }
        else{ 
          $mostrar_modal=1;
          $titulo='Recaudos';
          $contenido='<div class="alert alert-danger"> Error al guardar </div>';
        }
    }

    $res_tabla_doct=0;
    #se repite para que se actualize el formulario en caso de insercion o update
    $res_tabla_doct = pg_query($conexion, $sql_doct_solic) or die("Error en la consulta SQL para mostrar registros de doc_solic2." .pg_last_error());
    $row_tabla_doct=pg_fetch_array($res_tabla_doct); 
    $documentos_listos=$row_tabla_doct['listo'];




    #seccion email
    $sql_email="SELECT * FROM correos_enviados WHERE id_solicitud='$id_solicitud'";
    $result_email=pg_query($conexion,$sql_email) or die ("Error en SQL chequeo de email " .pg_last_error());

    $email_recaudos_enviado='0';
    $email_revision_plano_enviado='0';
    $email_control_equipo_enviado='0';
    $email_revision_inspeccion_enviado='0';
    $email_espera_plano_dibujo_enviado='0';

    $propietario = $row_tabla_statu['propietario'];
    $nombre_civico = $row_tabla_statu['nombre_civico'];
    $codigo_solicitud = $row_tabla_statu['codigo_solicitud'];
    $dirigido_email = $row_tabla_statu['correo'];
    $headers_email = "From: geomatica alcaldia santiago mariño<geomaticamsm@gmail.com>\r\n";
    $headers_email .= "Reply-To: geomatica alcaldia santiago mariño<geomaticamsm@gmail.com>\r\n";
    $headers_email .= "MIME-Version: 1.0\r\n";
    $headers_email .= "Content-Type: text/html; charset=UTF-8\r\n";
    $titulo_email = "Informacion sobre estado de solicitud en Geomatica Municipio Santiago Mariño";


    while ($row_email=pg_fetch_array($result_email)) {
      switch ($row_email['tipo_correo']) {
        case 'recaudos':
          $email_recaudos_enviado='1';
          break;
        case 'revision_plano':
          $email_revision_plano_enviado='1';
          break;
        case 'control_equipo':
          $email_control_equipo_enviado='1';
          break;
        case 'revision_inspeccion':
          $email_revision_inspeccion_enviado='1';
          break;
        case 'espera_plano_dibujo':
          $email_espera_plano_dibujo_enviado='1';
          break;
      }
    }

    if (!empty($documentos_listos) && $email_recaudos_enviado=='0') {
      $contenido_email = "<p>La solicitud codigo: ".$codigo_solicitud.".<br>Propietario: ".$propietario.".<br>Nombre civico: ".$nombre_civico.".<br>Ha pasado a revision de plano.</p><br><br><br><img alt='imagen direccion de geomatica' src='https://i.imgur.com/FIRMA_CORREO.png'>";
      mail($dirigido_email, $titulo_email, $contenido_email, $headers_email);
      $sql_email_insert="INSERT INTO correos_enviados (tipo_correo, id_solicitud, id_enviado_por) VALUES ('recaudos', '$id_solicitud', '".$_SESSION['id']."')";
      $result_email_insert=pg_query($conexion,$sql_email_insert) or die ("Error en SQL INSERT de email " .pg_last_error());
    }
   
 #sentencia para contar documentos entregados

    $sql_doc_entregados_conut="SELECT  SUM (carta_motivo + ci_solicitante + ficha_catastral + informe_tecnico + documento_propiedad + plano_digital + plano_fisico + autorizacion_pro + ci_propietario) FROM  documentos_solicitudes WHERE  id_solicitud ='$id_solicitud'";
    $result_doc_entregados_conut=pg_query($conexion,$sql_doc_entregados_conut) or die ("Error en SQL en la suma de documentos consigandos " .pg_last_error());
     $SUM_DOC=pg_fetch_result($result_doc_entregados_conut,0,0);
     
#######################################################################################################################
#sentencias para revision inicial del plano
    
    $sql_doct_revision = "SELECT a.*, b.*, d.nombre AS atendido_por
       FROM  revision_de_planos a
       INNER JOIN solicitudes b ON b.id_solicitud = a.id_solicitud
       INNER JOIN sist_geo_tb_usuario d ON d.id_user = b.id_atendido_por
       WHERE a.id_solicitud = $id_solicitud"; 

    $clases2='class="nav-link '.(($documentos_listos) ? '' : 'disabled').'"';
    $fadein2='class="tab-pane"';


    #si es levantamiento se llena automaticamente la revision de planos todo con ceros y apto=1
    $result_revision_plano = pg_query($conexion, $sql_doct_revision) or die("Error en la consulta SQL para calcular filas de rev de plano." .pg_last_error());
    $numero_filas_rev_planos = pg_affected_rows($result_revision_plano);

    if (!empty($documentos_listos) && $tipo_solicitud=='levantamiento' && empty($numero_filas_rev_planos)) {

        $sql_revision = "INSERT INTO revision_de_planos (id_solicitud, cotas, area, ubicaciones_relativas, escala_grafica, grilla, ubicacion_poligono, rosa_vientos, linderos, direccion, tabla_coordenadas, escala_numerica, fecha_actual, apto, fecha, id_revisado_por, observaciones) VALUES ('$id_solicitud', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '".date('Y-m-d')."', '".$_SESSION['id']."', '')";
        $result_revision = pg_query($conexion, $sql_revision) or die ("Error en SQL INSERT revision de planos levantamiento: " .pg_last_error());
    }


    #boton submit de revision de planos
    if (!empty($_POST['revision']) && isset($_POST['revision'])){
        
        $clases1='class="nav-link"';
        $fadein1='class="tab-pane"';
        $clases2='class="nav-link active"';
        $fadein2='class="tab-pane active"';

        $cotas=(empty($_POST['cotas']) ? '0' : '1') ;
        $area=(empty($_POST['area']) ? '0' : '1') ;
        $ubicaciones_relativas=(empty($_POST['ubicaciones_relativas']) ? '0' : '1') ;
        $escala_grafica=(empty($_POST['escala_grafica']) ? '0' : '1') ;
        $apto=(empty($_POST['apto']) ? '0' : '1') ;
        $ubicacion_poligono=(empty($_POST['ubicacion_poligono']) ? '0' : '1') ;
        $grilla=(empty($_POST['grilla']) ? '0' : '1') ;
        $rosa_vientos=(empty($_POST['rosa_vientos']) ? '0' : '1') ;
        $linderos=(empty($_POST['linderos']) ? '0' : '1') ;
        $direccion=(empty($_POST['direccion']) ? '0' : '1') ;
        $tabla_coordenadas=(empty($_POST['tabla_coordenadas']) ? '0' : '1') ;
        $escala_numerica=(empty($_POST['escala_numerica']) ? '0' : '1') ;
        $fecha_actual=(empty($_POST['fecha_actual']) ? '0' : '1') ;
        $observaciones=strtoupper(pg_escape_string($_POST['observacion']));
        $id_solicitud =$_POST['id_solicitud'];
        $fecha= date('Y-m-d');

        $res_tabla_revision = pg_query($conexion, $sql_doct_revision) or die("Error en la consulta SQL para mostrar rev_plano." .pg_last_error());
        #se necesita saber si existe filas para determinar si es insert o update
        $num_revision_filas = pg_affected_rows($res_tabla_revision);

        if (empty($num_revision_filas)){

          $sql_revision = "INSERT INTO revision_de_planos (id_solicitud, cotas, area, ubicaciones_relativas, escala_grafica, grilla, ubicacion_poligono, rosa_vientos, linderos, direccion, tabla_coordenadas, escala_numerica, fecha_actual, apto, fecha, id_revisado_por, observaciones) VALUES ('$id_solicitud', '$cotas', '$area', '$ubicaciones_relativas', '$escala_grafica', '$grilla', '$ubicacion_poligono', '$rosa_vientos', '$linderos', '$direccion', '$tabla_coordenadas', '$escala_numerica', '$fecha_actual', '$apto', '$fecha', '".$_SESSION['id']."', '$observaciones') ";
        }
        else{
      
          $sql_revision = "UPDATE revision_de_planos SET cotas='$cotas', area='$area', ubicaciones_relativas='$ubicaciones_relativas', escala_grafica='$escala_grafica', grilla='$grilla', ubicacion_poligono='$ubicacion_poligono', rosa_vientos='$rosa_vientos', linderos='$linderos', direccion='$direccion', tabla_coordenadas='$tabla_coordenadas', escala_numerica='$escala_numerica', fecha_actual='$fecha_actual', apto='$apto', fecha='$fecha', id_revisado_por='".$_SESSION['id']."', observaciones='$observaciones' WHERE id_solicitud='$id_solicitud'";
        }
         $result_revision = pg_query($conexion, $sql_revision) or die ("Error en SQL revision inicial de planos: " .pg_last_error());
         $num_filas_rev_planos = pg_affected_rows($result_revision); 
        
        if (!empty($num_filas_rev_planos)) {
          $mostrar_modal=1;
          $titulo='Revision inicial de plano';
          $contenido='<div class="alert alert-success"> Guardado con exito </div>';
        }
        else{ 
          $mostrar_modal=1;
          $titulo='Revision inicial de plano';
          $contenido='<div class="alert alert-danger"> Error al guardar </div>';
        }
    }
    #se repite para que se actualize el formulario en caso de insercion o update
    $res_tabla_revision = pg_query($conexion, $sql_doct_revision) or die("Error en la consulta SQL para mostrar registros de rev_plano2." .pg_last_error());
    $row_tabla_revision=pg_fetch_array($res_tabla_revision); 
    $revision_apto=$row_tabla_revision['apto']; 

    if (!empty($revision_apto) && $email_revision_plano_enviado=='0') {
      $contenido_email = "<p>La solicitud codigo: ".$codigo_solicitud.".<br>Propietario: ".$propietario.".<br>Nombre civico: ".$nombre_civico.".<br>Ha pasado a inspeccion.</p><br><p>Se le informa que las esquinas del perimetro del inmueble deben estar limpias y sin obstaculos esto con el fin de que los inspectores no tengan dificultad para tomar dichos puntos, tambien se le pide la colaboracion en cuanto a transporte, hidratacion y acompañamiento en el lugar.</p><br><br><br><img alt='imagen direccion de geomatica' src='https://i.imgur.com/FIRMA_CORREO.png'>";
      mail($dirigido_email, $titulo_email, $contenido_email, $headers_email);
      $sql_email_insert="INSERT INTO correos_enviados (tipo_correo, id_solicitud, id_enviado_por) VALUES ('revision_plano', '$id_solicitud', '".$_SESSION['id']."')";
      $result_email_insert=pg_query($conexion,$sql_email_insert) or die ("Error en SQL INSERT de email 2" .pg_last_error());
    }

#sentencia para contar los puntos revisados
   $sql_revi_conut="SELECT  SUM (cotas+area+ubicaciones_relativas+escala_grafica+grilla+ubicacion_poligono+rosa_vientos+linderos+direccion+tabla_coordenadas+escala_numerica+fecha_actual) FROM revision_de_planos  WHERE  id_solicitud ='$id_solicitud'";
    $result_revi_conut=pg_query($conexion,$sql_revi_conut) or die ("Error en SQL en la suma de documentos consignados " .pg_last_error());
     $SUM_REVISION=pg_fetch_result($result_revi_conut,0,0);

##########################################################################################################################
    #sentencias control de equipos
  $sql_control_equipo="SELECT *,c.nombre as revisado_por from control_equipo a INNER JOIN control_equipo_funcionarios b ON a.id_control=b.id_control INNER JOIN sist_geo_tb_usuario c ON c.id_user = a.id_revisado_por WHERE a.id_solicitud='".$id_solicitud."'";
  $result_control_equipo=pg_query($conexion,$sql_control_equipo) or die ("Error en SQL control_equipo: " .pg_last_error());
  $filas_afect_control_equipo=pg_affected_rows($result_control_equipo);

  $clases3='class="nav-link '.(($revision_apto) ? '' : 'disabled').'"';
  $fadein3='class="tab-pane"';

  if (!empty($_POST['control_inspeccion']) && isset($_POST['control_inspeccion'])) {
    $clases1='class="nav-link"';
    $fadein1='class="tab-pane"';
    $clases3='class="nav-link active"';
    $fadein3='class="tab-pane active"';

    $rover1=$_POST['rover1'];
    $rover2=$_POST['rover2'];
    $base=$_POST['base'];
    $observaciones_control=strtoupper(pg_escape_string($_POST['observaciones_control']));
    $salida_inspeccion=$_POST['salida_inspeccion'];
    $entrada_inspeccion=$_POST['entrada_inspeccion'];
    $hora=date('H:i:s');
    $fecha=date('Y-m-d');

    if (empty($filas_afect_control_equipo)) {
      $sql_tabla_control_equipo="INSERT INTO control_equipo (id_solicitud, hora_salida, observaciones_control, fecha, id_revisado_por) VALUES ('$id_solicitud', '$hora', '$observaciones_control', '$fecha', '".$_SESSION['id']."') RETURNING id_control";
    }else{
      $sql_tabla_control_equipo="UPDATE control_equipo SET hora_entrada='$hora', observaciones_control='$observaciones_control', fecha='$fecha', id_revisado_por='".$_SESSION['id']."', listo='1' WHERE id_solicitud='$id_solicitud' RETURNING id_control";
    }
    $result_tabla_control_equipo=pg_query($conexion,$sql_tabla_control_equipo) or die ("Error en SQL insert/update_control_equipo: " .pg_last_error());
    $id_control=pg_fetch_result($result_tabla_control_equipo,0,0);

    $num_filas_control_equipo = pg_affected_rows($result_tabla_control_equipo);
    if (!empty($num_filas_control_equipo)) {
          $mostrar_modal=1;
          $titulo='Control de equipo';
          $contenido='<div class="alert alert-success"> Guardado con exito </div>';
    }
    else{ 
          $mostrar_modal=1;
          $titulo='Control de equipo';
          $contenido='<div class="alert alert-danger"> Error al guardar </div>';
    }
   
    if (!empty($rover1) && !empty($salida_inspeccion)) {
      $maletin_r1s=(empty($_POST['maletin_r1s']) ? '0' : '1');
      $correa_mano_r1s=(empty($_POST['correa_mano_r1s']) ? '0' : '1');
      $extension_antena_r1s=(empty($_POST['extension_antena_r1s']) ? '0' : '1');
      $arandela_r1s=(empty($_POST['arandela_r1s']) ? '0' : '1');
      $soporte_campo_r1s=(empty($_POST['soporte_campo_r1s']) ? '0' : '1');
      $antena_r1s=(empty($_POST['antena_r1s']) ? '0' : '1');
      $tope_r1s=(empty($_POST['tope_r1s']) ? '0' : '1');
      $cable_antena_r1s=(empty($_POST['cable_antena_r1s']) ? '0' : '1');
      $cinta_3m_r1s=(empty($_POST['cinta_3m_r1s']) ? '0' : '1');
      $barra_r1s=(empty($_POST['barra_r1s']) ? '0' : '1');
      $adaptador_antena_r1s=(empty($_POST['adaptador_antena_r1s']) ? '0' : '1');
      $prisma_r1s=(empty($_POST['prisma_r1s']) ? '0' : '1');
      $kit_cargador_r1s=(empty($_POST['kit_cargador_r1s']) ? '0' : '1');
      $tripode_r1s=(empty($_POST['tripode_r1s']) ? '0' : '1');
      $bipode_r1s=(empty($_POST['bipode_r1s']) ? '0' : '1');
      $baston_r1s=(empty($_POST['baston_r1s']) ? '0' : '1');
      $cinta_100m_r1s=(empty($_POST['cinta_100m_r1s']) ? '0' : '1');
      $sql_insert_funcionario_r1="INSERT INTO control_equipo_funcionarios (id_control, gps, tipo_hora, id_funcionario, id_revisado_por, maletin, correa_mano, extension_antena, arandela, soporte_campo, antena, tope, cable_antena, cinta_3m, barra, adaptador_antena, prisma, kit_cargador, tripode, bipode, baston, cinta_100m) VALUES ('$id_control', 'R1', 'SALIDA', '$rover1', '".$_SESSION['id']."', '$maletin_r1s', '$correa_mano_r1s', '$extension_antena_r1s', '$arandela_r1s', '$soporte_campo_r1s', '$antena_r1s', '$tope_r1s', '$cable_antena_r1s', '$cinta_3m_r1s', '$barra_r1s', '$adaptador_antena_r1s', '$prisma_r1s', '$kit_cargador_r1s', '$tripode_r1s', '$bipode_r1s', '$baston_r1s', '$cinta_100m_r1s')";
      $result_insert_funcionario_r1=pg_query($conexion,$sql_insert_funcionario_r1) or die ("Error en SQL insert funcionario r1 salida: " .pg_last_error());
    }

    if (!empty($rover2) && !empty($salida_inspeccion)) {
      $maletin_r2s=(empty($_POST['maletin_r2s']) ? '0' : '1');
      $correa_mano_r2s=(empty($_POST['correa_mano_r2s']) ? '0' : '1');
      $extension_antena_r2s=(empty($_POST['extension_antena_r2s']) ? '0' : '1');
      $arandela_r2s=(empty($_POST['arandela_r2s']) ? '0' : '1');
      $soporte_campo_r2s=(empty($_POST['soporte_campo_r2s']) ? '0' : '1');
      $antena_r2s=(empty($_POST['antena_r2s']) ? '0' : '1');
      $tope_r2s=(empty($_POST['tope_r2s']) ? '0' : '1');
      $cable_antena_r2s=(empty($_POST['cable_antena_r2s']) ? '0' : '1');
      $cinta_3m_r2s=(empty($_POST['cinta_3m_r2s']) ? '0' : '1');
      $barra_r2s=(empty($_POST['barra_r2s']) ? '0' : '1');
      $adaptador_antena_r2s=(empty($_POST['adaptador_antena_r2s']) ? '0' : '1');
      $prisma_r2s=(empty($_POST['prisma_r2s']) ? '0' : '1');
      $kit_cargador_r2s=(empty($_POST['kit_cargador_r2s']) ? '0' : '1');
      $tripode_r2s=(empty($_POST['tripode_r2s']) ? '0' : '1');
      $bipode_r2s=(empty($_POST['bipode_r2s']) ? '0' : '1');
      $baston_r2s=(empty($_POST['baston_r2s']) ? '0' : '1');
      $cinta_100m_r2s=(empty($_POST['cinta_100m_r2s']) ? '0' : '1');
      $sql_insert_funcionario_r2="INSERT INTO control_equipo_funcionarios (id_control, gps, tipo_hora, id_funcionario, id_revisado_por, maletin, correa_mano, extension_antena, arandela, soporte_campo, antena, tope, cable_antena, cinta_3m, barra, adaptador_antena, prisma, kit_cargador, tripode, bipode, baston, cinta_100m) VALUES ('$id_control', 'R2', 'SALIDA', '$rover2', '".$_SESSION['id']."', '$maletin_r2s', '$correa_mano_r2s', '$extension_antena_r2s', '$arandela_r2s', '$soporte_campo_r2s', '$antena_r2s', '$tope_r2s', '$cable_antena_r2s', '$cinta_3m_r2s', '$barra_r2s', '$adaptador_antena_r2s', '$prisma_r2s', '$kit_cargador_r2s', '$tripode_r2s', '$bipode_r2s', '$baston_r2s', '$cinta_100m_r2s')";
      $result_insert_funcionario_r2=pg_query($conexion,$sql_insert_funcionario_r2) or die ("Error en SQL insert funcionario r2 salida: " .pg_last_error());
    }

    if (!empty($base) && !empty($salida_inspeccion)) {
      $maletin_bs=(empty($_POST['maletin_bs']) ? '0' : '1');
      $correa_mano_bs=(empty($_POST['correa_mano_bs']) ? '0' : '1');
      $extension_antena_bs=(empty($_POST['extension_antena_bs']) ? '0' : '1');
      $arandela_bs=(empty($_POST['arandela_bs']) ? '0' : '1');
      $soporte_campo_bs=(empty($_POST['soporte_campo_bs']) ? '0' : '1');
      $antena_bs=(empty($_POST['antena_bs']) ? '0' : '1');
      $tope_bs=(empty($_POST['tope_bs']) ? '0' : '1');
      $cable_antena_bs=(empty($_POST['cable_antena_bs']) ? '0' : '1');
      $cinta_3m_bs=(empty($_POST['cinta_3m_bs']) ? '0' : '1');
      $barra_bs=(empty($_POST['barra_bs']) ? '0' : '1');
      $adaptador_antena_bs=(empty($_POST['adaptador_antena_bs']) ? '0' : '1');
      $prisma_bs=(empty($_POST['prisma_bs']) ? '0' : '1');
      $kit_cargador_bs=(empty($_POST['kit_cargador_bs']) ? '0' : '1');
      $tripode_bs=(empty($_POST['tripode_bs']) ? '0' : '1');
      $bipode_bs=(empty($_POST['bipode_bs']) ? '0' : '1');
      $baston_bs=(empty($_POST['baston_bs']) ? '0' : '1');
      $cinta_100m_bs=(empty($_POST['cinta_100m_bs']) ? '0' : '1');
      $sql_insert_funcionario_base="INSERT INTO control_equipo_funcionarios (id_control, gps, tipo_hora, id_funcionario, id_revisado_por, maletin, correa_mano, extension_antena, arandela, soporte_campo, antena, tope, cable_antena, cinta_3m, barra, adaptador_antena, prisma, kit_cargador, tripode, bipode, baston, cinta_100m) VALUES ('$id_control', 'BASE', 'SALIDA', '$base', '".$_SESSION['id']."', '$maletin_bs', '$correa_mano_bs', '$extension_antena_bs', '$arandela_bs', '$soporte_campo_bs', '$antena_bs', '$tope_bs', '$cable_antena_bs', '$cinta_3m_bs', '$barra_bs', '$adaptador_antena_bs', '$prisma_bs', '$kit_cargador_bs', '$tripode_bs', '$bipode_bs', '$baston_bs', '$cinta_100m_bs')";
      $result_insert_funcionario_base=pg_query($conexion,$sql_insert_funcionario_base) or die ("Error en SQL insert funcionario base salida: " .pg_last_error());
    }

    if (!empty($rover1) && !empty($entrada_inspeccion)) {
      $maletin_r1e=(empty($_POST['maletin_r1e']) ? '0' : '1');
      $correa_mano_r1e=(empty($_POST['correa_mano_r1e']) ? '0' : '1');
      $extension_antena_r1e=(empty($_POST['extension_antena_r1e']) ? '0' : '1');
      $arandela_r1e=(empty($_POST['arandela_r1e']) ? '0' : '1');
      $soporte_campo_r1e=(empty($_POST['soporte_campo_r1e']) ? '0' : '1');
      $antena_r1e=(empty($_POST['antena_r1e']) ? '0' : '1');
      $tope_r1e=(empty($_POST['tope_r1e']) ? '0' : '1');
      $cable_antena_r1e=(empty($_POST['cable_antena_r1e']) ? '0' : '1');
      $cinta_3m_r1e=(empty($_POST['cinta_3m_r1e']) ? '0' : '1');
      $barra_r1e=(empty($_POST['barra_r1e']) ? '0' : '1');
      $adaptador_antena_r1e=(empty($_POST['adaptador_antena_r1e']) ? '0' : '1');
      $prisma_r1e=(empty($_POST['prisma_r1e']) ? '0' : '1');
      $kit_cargador_r1e=(empty($_POST['kit_cargador_r1e']) ? '0' : '1');
      $tripode_r1e=(empty($_POST['tripode_r1e']) ? '0' : '1');
      $bipode_r1e=(empty($_POST['bipode_r1e']) ? '0' : '1');
      $baston_r1e=(empty($_POST['baston_r1e']) ? '0' : '1');
      $cinta_100m_r1e=(empty($_POST['cinta_100m_r1e']) ? '0' : '1');
      $sql_insert_funcionario_r1="INSERT INTO control_equipo_funcionarios (id_control, gps, tipo_hora, id_funcionario, id_revisado_por, maletin, correa_mano, extension_antena, arandela, soporte_campo, antena, tope, cable_antena, cinta_3m, barra, adaptador_antena, prisma, kit_cargador, tripode, bipode, baston, cinta_100m) VALUES ('$id_control', 'R1', 'ENTRADA', '$rover1', '".$_SESSION['id']."', '$maletin_r1e', '$correa_mano_r1e', '$extension_antena_r1e', '$arandela_r1e', '$soporte_campo_r1e', '$antena_r1e', '$tope_r1e', '$cable_antena_r1e', '$cinta_3m_r1e', '$barra_r1e', '$adaptador_antena_r1e', '$prisma_r1e', '$kit_cargador_r1e', '$tripode_r1e', '$bipode_r1e', '$baston_r1e', '$cinta_100m_r1e')";
      $result_insert_funcionario_r1=pg_query($conexion,$sql_insert_funcionario_r1) or die ("Error en SQL insert funcionario r1 entrada: " .pg_last_error());
    }

    if (!empty($rover2) && !empty($entrada_inspeccion)) {
      $maletin_r2e=(empty($_POST['maletin_r2e']) ? '0' : '1');
      $correa_mano_r2e=(empty($_POST['correa_mano_r2e']) ? '0' : '1');
      $extension_antena_r2e=(empty($_POST['extension_antena_r2e']) ? '0' : '1');
      $arandela_r2e=(empty($_POST['arandela_r2e']) ? '0' : '1');
      $soporte_campo_r2e=(empty($_POST['soporte_campo_r2e']) ? '0' : '1');
      $antena_r2e=(empty($_POST['antena_r2e']) ? '0' : '1');
      $tope_r2e=(empty($_POST['tope_r2e']) ? '0' : '1');
      $cable_antena_r2e=(empty($_POST['cable_antena_r2e']) ? '0' : '1');
      $cinta_3m_r2e=(empty($_POST['cinta_3m_r2e']) ? '0' : '1');
      $barra_r2e=(empty($_POST['barra_r2e']) ? '0' : '1');
      $adaptador_antena_r2e=(empty($_POST['adaptador_antena_r2e']) ? '0' : '1');
      $prisma_r2e=(empty($_POST['prisma_r2e']) ? '0' : '1');
      $kit_cargador_r2e=(empty($_POST['kit_cargador_r2e']) ? '0' : '1');
      $tripode_r2e=(empty($_POST['tripode_r2e']) ? '0' : '1');
      $bipode_r2e=(empty($_POST['bipode_r2e']) ? '0' : '1');
      $baston_r2e=(empty($_POST['baston_r2e']) ? '0' : '1');
      $cinta_100m_r2e=(empty($_POST['cinta_100m_r2e']) ? '0' : '1');
      $sql_insert_funcionario_r2="INSERT INTO control_equipo_funcionarios (id_control, gps, tipo_hora, id_funcionario, id_revisado_por, maletin, correa_mano, extension_antena, arandela, soporte_campo, antena, tope, cable_antena, cinta_3m, barra, adaptador_antena, prisma, kit_cargador, tripode, bipode, baston, cinta_100m) VALUES ('$id_control', 'R2', 'ENTRADA', '$rover2', '".$_SESSION['id']."', '$maletin_r2e', '$correa_mano_r2e', '$extension_antena_r2e', '$arandela_r2e', '$soporte_campo_r2e', '$antena_r2e', '$tope_r2e', '$cable_antena_r2e', '$cinta_3m_r2e', '$barra_r2e', '$adaptador_antena_r2e', '$prisma_r2e', '$kit_cargador_r2e', '$tripode_r2e', '$bipode_r2e', '$baston_r2e', '$cinta_100m_r2e')";
      $result_insert_funcionario_r2=pg_query($conexion,$sql_insert_funcionario_r2) or die ("Error en SQL insert funcionario r2 entrada: " .pg_last_error());
    }

    if (!empty($base) && !empty($entrada_inspeccion)) {
      $maletin_be=(empty($_POST['maletin_be']) ? '0' : '1');
      $correa_mano_be=(empty($_POST['correa_mano_be']) ? '0' : '1');
      $extension_antena_be=(empty($_POST['extension_antena_be']) ? '0' : '1');
      $arandela_be=(empty($_POST['arandela_be']) ? '0' : '1');
      $soporte_campo_be=(empty($_POST['soporte_campo_be']) ? '0' : '1');
      $antena_be=(empty($_POST['antena_be']) ? '0' : '1');
      $tope_be=(empty($_POST['tope_be']) ? '0' : '1');
      $cable_antena_be=(empty($_POST['cable_antena_be']) ? '0' : '1');
      $cinta_3m_be=(empty($_POST['cinta_3m_be']) ? '0' : '1');
      $barra_be=(empty($_POST['barra_be']) ? '0' : '1');
      $adaptador_antena_be=(empty($_POST['adaptador_antena_be']) ? '0' : '1');
      $prisma_be=(empty($_POST['prisma_be']) ? '0' : '1');
      $kit_cargador_be=(empty($_POST['kit_cargador_be']) ? '0' : '1');
      $tripode_be=(empty($_POST['tripode_be']) ? '0' : '1');
      $bipode_be=(empty($_POST['bipode_be']) ? '0' : '1');
      $baston_be=(empty($_POST['baston_be']) ? '0' : '1');
      $cinta_100m_be=(empty($_POST['cinta_100m_be']) ? '0' : '1');
      $sql_insert_funcionario_base="INSERT INTO control_equipo_funcionarios (id_control, gps, tipo_hora, id_funcionario, id_revisado_por, maletin, correa_mano, extension_antena, arandela, soporte_campo, antena, tope, cable_antena, cinta_3m, barra, adaptador_antena, prisma, kit_cargador, tripode, bipode, baston, cinta_100m) VALUES ('$id_control', 'BASE', 'ENTRADA', '$base', '".$_SESSION['id']."', '$maletin_be', '$correa_mano_be', '$extension_antena_be', '$arandela_be', '$soporte_campo_be', '$antena_be', '$tope_be', '$cable_antena_be', '$cinta_3m_be', '$barra_be', '$adaptador_antena_be', '$prisma_be', '$kit_cargador_be', '$tripode_be', '$bipode_be', '$baston_be', '$cinta_100m_be')";
      $result_insert_funcionario_base=pg_query($conexion,$sql_insert_funcionario_base) or die ("Error en SQL insert funcionario base entrada: " .pg_last_error());
    }
  }

  $row_control_insp_r1s[]=0;
  $row_control_insp_r1e[]=0;
  $row_control_insp_r2s[]=0;
  $row_control_insp_r2e[]=0;
  $row_control_insp_bs[]=0;
  $row_control_insp_be[]=0;
  $disabled_salida=0;
  $disabled_entrada=0;
  $fecha_control_equipo=0;
  $hora_salida=0;
  $hora_entrada=0;
  $textarea_observaciones_control=0;
  $listo_control_equipo=0;
  $select_rover1=0;
  $select_rover2=0;
  $select_base=0;

  $result_control_equipo=pg_query($conexion,$sql_control_equipo) or die ("Error en SQL control_equipo: " .pg_last_error());
  $filas_afect_control_equipo=pg_affected_rows($result_control_equipo);

  if (empty($filas_afect_control_equipo)) {
    $disabled_entrada=1; 
  }else{
    $disabled_salida=1;

    while ($fila_control_equipo=pg_fetch_array($result_control_equipo)) {

      if (empty($fecha_control_equipo)) { #pasa una sola vez en el while
        $fecha_control_equipo=$fila_control_equipo['fecha'];
        $hora_salida=$fila_control_equipo['hora_salida'];
        $hora_entrada=$fila_control_equipo['hora_entrada'];
        $revisado_por=$fila_control_equipo['revisado_por'];
        $textarea_observaciones_control=$fila_control_equipo['observaciones_control'];
        $listo_control_equipo=$fila_control_equipo['listo'];
      }

      if($fila_control_equipo['tipo_hora']=='SALIDA'){
        switch ($fila_control_equipo['gps']) {
            case 'R1':
                $select_rover1=$fila_control_equipo['id_funcionario'];
                $row_control_insp_r1s=$fila_control_equipo;
                break;
            case 'R2':
                $select_rover2=$fila_control_equipo['id_funcionario'];
                $row_control_insp_r2s=$fila_control_equipo;
                break;
            case 'BASE':
                $select_base=$fila_control_equipo['id_funcionario'];
                $row_control_insp_bs=$fila_control_equipo;
                break;
        }
      }elseif($fila_control_equipo['tipo_hora']=='ENTRADA') {
        $disabled_entrada=1;
        switch ($fila_control_equipo['gps']) {
            case 'R1':
                $row_control_insp_r1e=$fila_control_equipo;
                break;
            case 'R2':
                $row_control_insp_r2e=$fila_control_equipo;
                break;
            case 'BASE':
                $row_control_insp_be=$fila_control_equipo;
                break;
        }
      }
    }

  }

  $sql_funcionarios_select="SELECT id_user, nombre FROM sist_geo_tb_usuario WHERE id_dept='1' ORDER BY nombre ASC";
  $result_funcionarios_select=pg_query($conexion,$sql_funcionarios_select) or die ("Error en SQL funcionarios select: " .pg_last_error());
  $funcionario_rover1=$funcionario_rover2=$funcionario_base=pg_fetch_all($result_funcionarios_select);

  if ($disabled_salida && $disabled_entrada && $email_control_equipo_enviado=='0') {
      $contenido_email = "<p>La solicitud codigo: ".$codigo_solicitud.".<br>Propietario: ".$propietario.".<br>Nombre civico: ".$nombre_civico.".<br>Ha pasado a revision de inspeccion.</p><br><br><br><img alt='imagen direccion de geomatica' src='https://i.imgur.com/FIRMA_CORREO.png'>";
      mail($dirigido_email, $titulo_email, $contenido_email, $headers_email);
      $sql_email_insert="INSERT INTO correos_enviados (tipo_correo, id_solicitud, id_enviado_por) VALUES ('control_equipo', '$id_solicitud', '".$_SESSION['id']."')";
      $result_email_insert=pg_query($conexion,$sql_email_insert) or die ("Error en SQL INSERT de email 3" .pg_last_error());
    }

  ##########          envio correo de reinspeccion            ################
  if(isset($_POST['correo_reinspeccion']) && !empty($_POST['correo_reinspeccion'])){
    $clases1='class="nav-link"';
    $fadein1='class="tab-pane"';
    $clases3='class="nav-link active"';
    $fadein3='class="tab-pane active"';

    $contenido_email = "<p>La solicitud codigo: ".$codigo_solicitud.".<br>Propietario: ".$propietario.".<br>Nombre civico: ".$nombre_civico.".<br>Debe ir a reinspeccion, ya que faltaron puntos por tomar.</p><br><p>Se le recuerda que las esquinas del perimetro del inmueble deben estar limpias y sin obstaculos esto con el fin de que los inspectores no tengan dificultad para tomar dichos puntos, tambien se le pide la colaboracion en cuanto a transporte, hidratacion y acompañamiento en el lugar.</p><br><br><br><img alt='imagen direccion de geomatica' src='https://i.imgur.com/FIRMA_CORREO.png'>";
    mail($dirigido_email, $titulo_email, $contenido_email, $headers_email);
    $sql_email_insert="INSERT INTO correos_enviados (tipo_correo, id_solicitud, id_enviado_por) VALUES ('reinspeccion', '$id_solicitud', '".$_SESSION['id']."')";
    $result_email_insert=pg_query($conexion,$sql_email_insert) or die ("Error en SQL INSERT de email reinspeccion" .pg_last_error());

    $filas_afect_reinspeccion=pg_affected_rows($result_email_insert);
    if (!empty($filas_afect_reinspeccion)) {
      $mostrar_modal=1;
      $titulo='Correo de reinspeccion';
      $contenido='<div class="alert alert-success"> Enviado con exito </div>';
    }else{ 
      $mostrar_modal=1;
      $titulo='Correo de reinspeccion';
      $contenido='<div class="alert alert-danger"> Error al enviar </div>';
    }
  }


###########################################################################################################################

  #-- Sentencias para revision de inspecciones ----
$sql_doct_inspeccion = "SELECT a.*, b.*, d.nombre AS atendido_por
       FROM  revision_inspeccion a
       INNER JOIN solicitudes b ON b.id_solicitud = a.id_solicitud
       INNER JOIN sist_geo_tb_usuario d ON d.id_user = b.id_atendido_por
       WHERE a.id_solicitud = $id_solicitud"; 

     $clases4='class="nav-link '.(($listo_control_equipo) ? '' : 'disabled').'"';
     $fadein4='class="tab-pane"';
       #boton submit de documentos
    if (!empty($_POST['inspeccion']) && isset($_POST['inspeccion'])){
       
       $clases1='class="nav-link"';
       $fadein1='class="tab-pane"';
       $clases4='class="nav-link active"';
       $fadein4='class="tab-pane active"';

        $triangulacion=$_POST['triangulacion'] ;
        $vertices_despla=$_POST['vertices_despla'] ;
        $vertices_directos=$_POST['vertices_directos'] ;
        $observaciones=strtoupper(pg_escape_string($_POST['observacion'])) ;
        $id_solicitud =$_POST['id_solicitud'];
        $fecha= date('Y-m-d');

        $res_tabla_inspeccion = pg_query($conexion, $sql_doct_inspeccion) or die("Error en la consulta SQL para mostrar los datos de la inspeccion." .pg_last_error());
        #se necesita saber si existe filas para determinar si es insert o update
        $num_inspeccion_filas = pg_affected_rows($res_tabla_inspeccion);

        if (empty($num_inspeccion_filas)){

          $sql_inspeccion = "INSERT INTO revision_inspeccion (triangulacion, vertices_despla, vertices_directos, observaciones, id_solicitud, fecha, id_revisado_por, listo) VALUES ('$triangulacion', '$vertices_despla', '$vertices_directos', '$observaciones', '$id_solicitud', '$fecha', '".$_SESSION['id']."', '1' )";
        }
        else{
      
          $sql_inspeccion = "UPDATE revision_inspeccion SET triangulacion='$triangulacion', vertices_despla='$vertices_despla', vertices_directos='$vertices_directos', observaciones='$observaciones', fecha='$fecha', id_revisado_por='".$_SESSION['id']."', listo='1' WHERE id_solicitud='$id_solicitud'";
        }
         $result_inspeccion = pg_query($conexion, $sql_inspeccion) or die ("Error en SQL al registrar los datos de la inspeccion: " .pg_last_error());
         $num_filas_rev_inspeccion = pg_affected_rows($result_inspeccion); 
        
        if (!empty($num_filas_rev_inspeccion)) {
          $mostrar_modal=1;
          $titulo='Revision de inspeccion';
          $contenido='<div class="alert alert-success"> Guardado con exito </div>';
        }
        else{ 
          $mostrar_modal=1;
          $titulo='Revision de inspeccion';
          $contenido='<div class="alert alert-danger"> Error al guardar </div>';
        }
    }
    #se repite para que se actualize el formulario en caso de insercion o update
    $res_tabla_inspeccion = pg_query($conexion, $sql_doct_inspeccion) or die("Error en la consulta SQL para mostrar los datos de la inspeccion." .pg_last_error());
    $row_tabla_inspec=pg_fetch_array($res_tabla_inspeccion); 
    $inspeccion_listo=$row_tabla_inspec['listo'];

if (!empty($inspeccion_listo) && $email_revision_inspeccion_enviado=='0') {
      $contenido_email = "<p>La solicitud codigo: ".$codigo_solicitud.".<br>Propietario: ".$propietario.".<br>Nombre civico: ".$nombre_civico.".<br>Ha pasado a Dibujo/Espera de plano.</p><br><br><br><img alt='imagen direccion de geomatica' src='https://i.imgur.com/FIRMA_CORREO.png'>";
      mail($dirigido_email, $titulo_email, $contenido_email, $headers_email);
      $sql_email_insert="INSERT INTO correos_enviados (tipo_correo, id_solicitud, id_enviado_por) VALUES ('revision_inspeccion', '$id_solicitud', '".$_SESSION['id']."')";
      $result_email_insert=pg_query($conexion,$sql_email_insert) or die ("Error en SQL INSERT de email 4" .pg_last_error());
    }

##################################################################################################################
#sentencia espera de planos dibujados 

$sql_espera_plano = "SELECT a.*, d.nombre AS atendido_por
       FROM  espera_de_plano_dibujo a
       INNER JOIN sist_geo_tb_usuario d ON d.id_user = a.id_revisado_por
       WHERE a.id_solicitud = $id_solicitud "; 
 
       $clases5='class="nav-link '.(($inspeccion_listo) ? '' : 'disabled').'"';
       $fadein5='class="tab-pane"';
       #boton submit de espera de planos dibujados
    if (!empty($_POST['espera_plano']) && isset($_POST['espera_plano'])){
       $clases1='class="nav-link"';
       $fadein1='class="tab-pane"';
       $clases5='class="nav-link active"';
       $fadein5='class="tab-pane active"';

        $listo_espera_plano=(empty($_POST['listo']) ? '0' : '1') ;
        $observaciones= strtoupper(pg_escape_string($_POST['observacion'])) ;
        $fecha= date('Y-m-d');

        $res_tabla_espera = pg_query($conexion, $sql_espera_plano) or die("Error en la consulta SQL para mostrar los datos de la espera de planos." .pg_last_error());
        #se necesita saber si existe filas para determinar si es insert o update
        $num_espera_plano_filas = pg_affected_rows($res_tabla_espera);

        if (empty($num_espera_plano_filas)){

          $sql_espera = "INSERT INTO espera_de_plano_dibujo (id_solicitud, observaciones, id_revisado_por, fecha, listo) VALUES ('$id_solicitud', '$observaciones', '".$_SESSION['id']."', '$fecha', '$listo_espera_plano') ";
        }
        else{
      
          $sql_espera = "UPDATE espera_de_plano_dibujo SET observaciones='$observaciones', id_revisado_por='".$_SESSION['id']."', fecha='$fecha', listo='$listo_espera_plano' WHERE id_solicitud='$id_solicitud'";
        }
         $result_espera = pg_query($conexion, $sql_espera) or die ("Error en SQL al registrar los datos de la espera de planos: " .pg_last_error());
         $num_filas_rev_espera = pg_affected_rows($result_espera); 
        
        if (!empty($num_filas_rev_espera)) {
          $mostrar_modal=1;
          $titulo='Revision espera de plano/dibujo';
          $contenido='<div class="alert alert-success"> Guardado con exito </div>';
        }
        else{ 
          $mostrar_modal=1;
          $titulo='Revision espera de plano/dibujo';
          $contenido='<div class="alert alert-danger"> Error al guardar </div>';
        }
    }
    #se repite para que se actualize el formulario en caso de insercion o update
    $res_tabla_espera = pg_query($conexion, $sql_espera_plano) or die("Error en la consulta SQL para mostrar los datos en la espera de planos dibujados." .pg_last_error());
    $row_tabla_espera=pg_fetch_array($res_tabla_espera); 
    $espera_de_plano_listo=$row_tabla_espera['listo'];    


    if (!empty($espera_de_plano_listo) && $email_espera_plano_dibujo_enviado=='0') {
      $contenido_email = "<p>La solicitud codigo: ".$codigo_solicitud.".<br>Propietario: ".$propietario.".<br>Nombre civico: ".$nombre_civico.".<br>Plano esta correcto, espera por aval, comunicarse con el director.</p><br><br><br><img alt='imagen direccion de geomatica' src='https://i.imgur.com/FIRMA_CORREO.png'>";
      mail($dirigido_email, $titulo_email, $contenido_email, $headers_email);
      $sql_email_insert="INSERT INTO correos_enviados (tipo_correo, id_solicitud, id_enviado_por) VALUES ('espera_plano_dibujo', '$id_solicitud', '".$_SESSION['id']."')";
      $result_email_insert=pg_query($conexion,$sql_email_insert) or die ("Error en SQL INSERT de email 5" .pg_last_error());
    }

#########################################################################################################################
#resumen de los correos enviados
    $sql_correos="SELECT a.tipo_correo, a.fecha_enviado, b.nombre FROM correos_enviados a INNER JOIN sist_geo_tb_usuario b ON a.id_enviado_por=b.id_user WHERE a.id_solicitud='$id_solicitud' ORDER BY a.fecha_enviado ASC";

    $result_correos=pg_query($conexion,$sql_correos);

    $fecha_registro_solicitud=$usuario_registro_solicitud=0;
    $fecha_recaudos=$usuario_recaudos=0;
    $fecha_revision_plano=$usuario_revision_plano=0;
    $fecha_control_equipo=$usuario_control_equipo=0;
    $fecha_revision_inspeccion=$usuario_revision_inspeccion=0;
    $fecha_espera_plano_dibujo=$usuario_espera_plano_dibujo=0;

    while ($fila_correos=pg_fetch_array($result_correos)) {
      if ($fila_correos['tipo_correo']=='registro_solicitud') {
        $fecha_registro_solicitud=date('Y-m-d',strtotime($fila_correos['fecha_enviado']));
        $usuario_registro_solicitud=$fila_correos['nombre'];
      }
      if ($fila_correos['tipo_correo']=='recaudos') {
        $fecha_recaudos=date('Y-m-d',strtotime($fila_correos['fecha_enviado']));
        $usuario_recaudos=$fila_correos['nombre'];
      }
      if ($fila_correos['tipo_correo']=='revision_plano') {
        $fecha_revision_plano=date('Y-m-d',strtotime($fila_correos['fecha_enviado']));
        $usuario_revision_plano=$fila_correos['nombre'];
      }
      if ($fila_correos['tipo_correo']=='control_equipo') {
        $fecha_control_equipo=date('Y-m-d',strtotime($fila_correos['fecha_enviado']));
        $usuario_control_equipo=$fila_correos['nombre'];
      }
      if ($fila_correos['tipo_correo']=='revision_inspeccion') {
        $fecha_revision_inspeccion=date('Y-m-d',strtotime($fila_correos['fecha_enviado']));
        $usuario_revision_inspeccion=$fila_correos['nombre'];
      }
      if ($fila_correos['tipo_correo']=='espera_plano_dibujo') {
        $fecha_espera_plano_dibujo=date('Y-m-d',strtotime($fila_correos['fecha_enviado']));
        $usuario_espera_plano_dibujo=$fila_correos['nombre'];
      }
    }
############################################################################################################################
#sentencia agregar_poligono

    $clases6='class="nav-link '.(($espera_de_plano_listo) ? '' : 'disabled').'"';
    $fadein6='class="tab-pane"';

    $error_string=0;
    if(isset($_POST["agregar_poligono"]) && !empty($_POST["agregar_poligono"])) {

      $clases1='class="nav-link"';
      $fadein1='class="tab-pane"';
      $clases6='class="nav-link active"';
      $fadein6='class="tab-pane active"';

      $target_dir = "/var/www/sisGeo/dxf/";
      $sql_dxf= "SELECT count(*) FROM dxf_temporales";
      $result=pg_query($conexion,$sql_dxf) or die("Error en SQL cuenta 1");
      $cuenta1=pg_fetch_result($result,0,0);
      $numero_archivo=$cuenta1+1;
      $target_file = $target_dir . $numero_archivo . '-' . basename($_FILES["fileToUpload"]["name"]);
      $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      $error_type=$_FILES['fileToUpload']['error'];

      switch ($error_type) {
          case '0':
                  $error_string='No hay error, fichero subido con éxito.'; 
                  break;
          case '1':
                  $error_string='El fichero subido excede la directiva upload_max_filesize de php.ini'; 
                  break;
          case '2':
                  $error_string='El fichero subido excede la directiva MAX_FILE_SIZE especificada en el formulario HTML.'; 
                  break;
          case '3':
                  $error_string='El fichero fue sólo parcialmente subido.'; 
                  break;
          case '4':
                  $error_string='No se subió ningún fichero.'; 
                  break;
          case '6':
                  $error_string='Falta la carpeta temporal.';
                  break;
          case '7':
                  $error_string='No se pudo escribir el fichero en el disco.';
                  break;
          case '8':
                  $error_string='Una extensión de PHP detuvo la subida de ficheros.'; 
                  break;
      }


      if ($error_type=='0') { #No hay error, fichero subido con éxito

          if($FileType == 'dxf') {
              if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
                $comando = 'ogr2ogr -a_srs "EPSG:2202" -f "PostgreSQL" PG:"host=localhost user=postgres dbname=marino_db_spatial password=pasword" -s_srs "EPSG:2202" "'.$target_file.'" -append -nln dxf_temporales';

                  exec("$comando");
                  #segunda cuenta
                  $result=pg_query($conexion,$sql_dxf) or die("Error en SQL cuenta 2");
                  $cuenta2=pg_fetch_result($result,0,0);
                  if (($cuenta2-$cuenta1)=='1') {
                      $sql_dxf= "SELECT ST_AsText(ST_GeomFromWKB(wkb_geometry,2202)) as geom_text, ST_IsValid(ST_GeomFromWKB(wkb_geometry,2202)) as geometria_valida, ST_IsClosed(ST_GeomFromWKB(wkb_geometry,2202)) as geometria_cerrada, subclasses, ogc_fid FROM dxf_temporales ORDER BY fecha_creado DESC LIMIT 1";
                      $result=pg_query($conexion,$sql_dxf) or die("Error en SQL extraccion de geom_text");
                      $row=pg_fetch_array($result);
                      if ($row['geometria_valida']=='t') { //se cheque que valor sea TRUE ('t' string)
                          if ($row['geometria_cerrada']=='t') {
                              if ($row['subclasses']=='AcDbEntity:AcDbPolyline') {
                                  $sql_dxf= "INSERT INTO inmuebles (the_geom,nombre_pro,rif,nombre_civ) VALUES (ST_MakePolygon(ST_GeomFromText('".$row['geom_text']."',2202)),'".$row_tabla_statu['propietario']."','".$row_tabla_statu['rif']."','".$row_tabla_statu['nombre_civico']."') RETURNING gid";
                                  $result=pg_query($conexion,$sql_dxf) or die("Error en SQL insercion geometria inmueble");
                                  $affected_rows=pg_affected_rows($result);
                                  $id_result=pg_fetch_result($result, 0, 0);
                                  if (!empty($affected_rows)) {
                                      $error_string= "Geometria del inmueble agregada con exito, gid: ".$id_result;
                                      $error_string_class='alert-success';

                                      $sql_dxf="INSERT INTO dxf_correctos (gid, id_usuario, ruta_archivo, ogc_fid) VALUES('$id_result', '".$_SESSION['id']."', '$target_file', '".$row['ogc_fid']."')";
                                      $result=pg_query($conexion,$sql_dxf) or die("Error en SQL dxf_correctos");

                                      #sentencia para agregar a sist_geo_tb_inm_modif
                                      $sql111 = "INSERT INTO sist_geo_tb_inm_modif (gid, id_usuario_geo, fecha_modif_geo) VALUES ('".$id_result."', '".$_SESSION['id']."', '".date('Y-m-d')."')";
                                      pg_query($conexion, $sql111) or die("Error en la consulta SQL111");

                                      $sql222= "UPDATE solicitudes SET gid='$id_result', id_usuario_poligono='".$_SESSION['id']."', fecha_poligono='".date('Y-m-d')."' WHERE id_solicitud='$id_solicitud'";
                                      pg_query($conexion, $sql222) or die("Error en la consulta SQL222");
                                  }else{
                                      $error_string= "error de insercion en capa inmuebles";
                                      $error_string_class='alert-danger';
                                  }
                              }else{
                                if(empty($row['subclasses'])){
                                  $error_string= "error debe guardar el archivo en formato dxf 2013";
                                } else{
                                  $error_string= "error geometria no es polilinea: ".$row['subclasses'];
                                }
                                  $error_string_class='alert-danger';
                              }
                          }else{
                              $error_string= "error geometria no cerrada";
                              $error_string_class='alert-danger';
                          }
                      }else{
                          $error_string= "error geometria invalida";
                          $error_string_class='alert-danger';
                      }
                  }
                  else{
                    if(($cuenta2-$cuenta1)=='0'){
                        $error_string= "error de insercion ogr2ogr en tabla dxf_temporales";
                      } else{
                          $error_string= "error su archivo contiene mas de una geometria";
                      }
                      $error_string_class='alert-danger';
                  }

          } else {
              $error_string= "Error archivo no subido por submit del formulario.";
              $error_string_class='alert-danger';
          }
              
          } else {
              $error_string= "Archivo debe ser dxf.";
              $error_string_class='alert-danger';
          }
      }else{
        #error string esta en el switch case
          $error_string_class='alert-danger';
      }
    }

    $sql_agregar_poligono="SELECT a.gid, a.fecha_poligono, d.nombre AS agregado_por,
      b.area, b.cod_aval, b.fecha_aval, c.nombre_archivo, c.aprobado
       FROM  solicitudes a LEFT JOIN inmuebles b ON a.gid=b.gid LEFT JOIN sist_geo_tb_inm_modif c ON a.gid=c.gid
       LEFT JOIN sist_geo_tb_usuario d ON d.id_user = a.id_usuario_poligono
       WHERE a.id_solicitud = $id_solicitud";
    $result_agregar_poligono=pg_query($conexion, $sql_agregar_poligono) or die("Error en la consulta SQL agregar poligono." .pg_last_error());
    $row_agregar_poligono=pg_fetch_array($result_agregar_poligono);

    if (!empty($row_agregar_poligono['gid'])){

      $gid_map=$row_agregar_poligono['gid'];

      $sql_ver_nuevo_poli = "SELECT *,st_xmin(the_geom) as xmin,st_ymin(the_geom) as ymin,st_xmax(the_geom) as xmax,st_ymax(the_geom) as ymax FROM inmuebles WHERE gid='$gid_map'";
      $result_ver_nuevo_poli=pg_query($conexion,$sql_ver_nuevo_poli) or die("Error en la consulta SQL ver poligono." .pg_last_error());

      while($fila = pg_fetch_array($result_ver_nuevo_poli)){
      //valores usados para el extent de la imagen del mapa
        $xmin=$fila['xmin'];
        $ymin=$fila['ymin'];
        $xmax=$fila['xmax'];
        $ymax=$fila['ymax'];
        $gid_map= $fila['gid'];
      }
    }


##################################################################################################################
#sentencia pago de solicitudes
    $sql_pago_solicitudes = "SELECT a.*, d.nombre AS atendido_por
             FROM  pago_solicitudes a
             INNER JOIN sist_geo_tb_usuario d ON d.id_user = a.id_revisado_por
             WHERE a.id_solicitud = $id_solicitud "; 
 
     $clases7='class="nav-link '.(($espera_de_plano_listo && !empty($row_agregar_poligono['gid'])) ? '' : 'disabled').'"';
     $fadein7='class="tab-pane"';
       #boton submit pago de solicitud
    if (!empty($_POST['pago_solicitud']) && isset($_POST['pago_solicitud'])){
       $clases1='class="nav-link"';
       $fadein1='class="tab-pane"';
       $clases7='class="nav-link active"';
       $fadein7='class="tab-pane active"';

        $fecha_pago=$_POST['fecha_pago'];
        $monto= $_POST['monto'];
        $codigo_pago=$_POST['codigo_pago'];

        $res_tabla_pago_solicitud = pg_query($conexion, $sql_pago_solicitudes) or die("Error en la consulta SQL para mostrar los pagos de las solicitudes." .pg_last_error());
        #se necesita saber si existe filas para determinar si es insert o update
        $num_pago_solicitudes = pg_affected_rows($res_tabla_pago_solicitud);

        if (empty($num_pago_solicitudes)){

          $sql_pago = "INSERT INTO pago_solicitudes (id_solicitud, fecha_pago, id_revisado_por, monto, codigo_pago) VALUES ('$id_solicitud', '$fecha_pago', '".$_SESSION['id']."', '$monto', '$codigo_pago') ";
        }
        
         $result_pago = pg_query($conexion, $sql_pago) or die ("Error en SQL al registrar los pagos de las solicitudes: " .pg_last_error());
         $num_filas_pago = pg_affected_rows($result_pago); 
        
        if (!empty($num_filas_pago)) {
          $mostrar_modal=1;
          $titulo='Registro de pago';
          $contenido='<div class="alert alert-success"> Guardado con exito </div>';
        }
        else{ 
          $mostrar_modal=1;
          $titulo='Registro de pago';
          $contenido='<div class="alert alert-danger"> Error al guardar </div>';
        }
    }
    #se repite para que se actualize el formulario en caso de insercion o update
    $res_tabla_pago_solicitud = pg_query($conexion, $sql_pago_solicitudes) or die("Error en la consulta SQL para mostrar los pagos de las solicitudes." .pg_last_error());
    $row_tabla_pago=pg_fetch_array($res_tabla_pago_solicitud);  

}


}
?>