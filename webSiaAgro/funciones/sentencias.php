<?php
#archivo que contiene las secuencias SQL del sistema y otras variables dependientes de las selecciones
$clasePadre = 'Inicio';
$claseHijo = 'Graficas';

#------------------------------contadores----------------------------------------------------------------------------------
$sql = "SELECT count(*) FROM inmuebles a INNER JOIN sist_geo_tb_inm_modif b ON a.gid = b.gid WHERE (a.rif IS NOT NULL) AND (a.nombre_pro IS NOT NULL) AND (a.nombre_civ IS NOT NULL) AND (a.cod_catast IS NULL) AND (a.sector IS NULL) AND (a.tenencia IS NULL) AND (a.id_parr IS NULL) AND (a.parroquia IS NULL) AND (a.cod_propie IS NULL) AND (a.area_documento IS NULL) AND (a.noreste IS NULL) AND (a.sureste IS NULL) AND (a.suroeste IS NULL) AND (a.noroeste IS NULL) AND (a.norte IS NULL) AND (a.este IS NULL) AND (a.sur IS NULL) AND (a.oeste IS NULL) AND (a.informe_tecnico IS NULL) AND (a.direccion IS NULL) AND (b.fecha_modif_geo > '2020-01-01')";
$res = pg_query($conexion,$sql) or die("Error en la consulta SQL");
$monitor_catastro = pg_fetch_result($res,0,0);

$sql = "SELECT count(*) FROM inmuebles";
$res = pg_query($conexion,$sql) or die("Error en la consulta SQL");
$contador_gid = pg_fetch_result($res,0,0);

$sql = "SELECT count(*) FROM inmuebles a INNER JOIN sist_geo_tb_inm_modif b ON a.gid = b.gid WHERE (a.fecha_aval IS NULL) AND (a.cod_aval IS NULL) AND (a.levantamiento IS NULL) AND (a.codigo_plano IS NULL) AND (b.id_usuario IS NOT NULL) AND (b.fecha_modif_geo > '2019-01-01')";
$res = pg_query($conexion,$sql) or die("Error en la consulta SQL");
$monitor_geomatica = pg_fetch_result($res,0,0);



$sql = "SELECT count(*) FROM inmuebles a INNER JOIN sist_geo_tb_inm_modif b ON a.gid = b.gid WHERE (b.aprobado IS NOT NULL)";
$res = pg_query($conexion,$sql) or die("Error en la consulta SQL");
$cuenta_aprobados=pg_fetch_result($res,0,0);

$sql = "SELECT count(*) FROM sist_geo_tb_monografias";
$res = pg_query($conexion,$sql) or die("Error en la consulta SQL");
$cuenta_monografias=pg_fetch_result($res,0,0);

#--------------------------------------------------------------------------------------------------------------------------
#sql de mensajes de alerta de los monitores en la barra superior
if($_SESSION["id_dept"]==1){   
	$sql = "SELECT a.gid, a.nombre_pro, a.rif, a.nombre_civ, b.fecha_modif_catastro FROM inmuebles a INNER JOIN sist_geo_tb_inm_modif b ON a.gid = b.gid WHERE (a.fecha_aval IS NULL) AND (a.cod_aval IS NULL) AND (a.levantamiento IS NULL) AND (a.codigo_plano IS NULL) AND (b.id_usuario IS NOT NULL) ORDER BY a.gid DESC";
	$res = pg_query($conexion,$sql) or die("Error en la consulta SQL");
} elseif ($_SESSION["id_dept"]==4) {
	$sql = "SELECT a.gid, a.nombre_pro, a.rif, a.nombre_civ, b.fecha_modif_geo FROM inmuebles a INNER JOIN sist_geo_tb_inm_modif b ON a.gid = b.gid WHERE (a.rif IS NOT NULL) AND (a.nombre_pro IS NOT NULL) AND (a.nombre_civ IS NOT NULL) AND (a.cod_catast IS NULL) AND (a.sector IS NULL) AND (a.tenencia IS NULL) AND (a.id_parr IS NULL) AND (a.parroquia IS NULL) AND (a.cod_propie IS NULL) AND (a.area_documento IS NULL) AND (a.noreste IS NULL) AND (a.sureste IS NULL) AND (a.suroeste IS NULL) AND (a.noroeste IS NULL) AND (a.norte IS NULL) AND (a.este IS NULL) AND (a.sur IS NULL) AND (a.oeste IS NULL) AND (a.informe_tecnico IS NULL) AND (a.direccion IS NULL) ORDER BY a.gid DESC";
	$res = pg_query($conexion,$sql) or die("Error en la consulta SQL");
}

function chequear_status($gid,$conexion){
	$sql="SELECT asunto FROM sist_geo_tb_sms WHERE gid='".$gid."' AND id_sms_padre IS NULL AND respondidos='0' AND id_dept='".$_SESSION['id_dept']."' ORDER BY fecha ASC";
	$res_alerta=pg_query($conexion,$sql);
	$fila=pg_fetch_array($res_alerta);
	$arr = array();
	if (!empty($fila['asunto'])) {
		$arr['titulo']="title='$fila[asunto]'";
		$arr['clase']='danger';
	}else{
		$arr['titulo']='';
		$arr['clase']='success';
	}
	return $arr;
}
#---------------------------------------------------------------------------------------------------------------------------


#sql de la busqueda general
if (isset($_REQUEST['busqueda'])){
	$clasePadre = $action = 'Busqueda';  //$action se usa para identificar la sql que ejecutara
	
	$completa = strtoupper(pg_escape_string($_REQUEST['busqueda']));
	$claseHijo = $completa;

	$busqueda = "No existen resultados para la busqueda de: <strong>".$completa."</strong>.";
	
	$sql_mon = "SELECT * FROM inmuebles WHERE (nombre_pro LIKE '%$completa%') OR (rif LIKE '%$completa%') OR (cod_catast LIKE '%$completa%') OR (nombre_civ LIKE '%$completa%') OR (sector LIKE '%$completa%')";
}
if (!isset($_GET['pagina'])) {
    $_GET['pagina'] = null;
}
#sql del monitor de catastro
if($_GET['pagina']=='monitor_catastro'){
	$clasePadre = 'Informe tecnico';  //$action se usa para identificar la sql que ejecutara
	$claseHijo = 'Monitor';
	$action = 'InformeTecnico';

	$busqueda = "<strong>No existen</strong> inmuebles para modificar.";

	$sql_mon = "SELECT * FROM inmuebles a INNER JOIN sist_geo_tb_inm_modif b ON a.gid = b.gid WHERE (a.rif IS NOT NULL) AND (a.nombre_pro IS NOT NULL) AND (a.nombre_civ IS NOT NULL) AND (a.cod_catast IS NULL) AND (a.sector IS NULL) AND (a.tenencia IS NULL) AND (a.id_parr IS NULL) AND (a.parroquia IS NULL) AND (a.cod_propie IS NULL) AND (a.area_documento IS NULL) AND (a.noreste IS NULL) AND (a.sureste IS NULL) AND (a.suroeste IS NULL) AND (a.noroeste IS NULL) AND (a.norte IS NULL) AND (a.este IS NULL) AND (a.sur IS NULL) AND (a.oeste IS NULL) AND (a.informe_tecnico IS NULL) AND (a.direccion IS NULL) AND (b.fecha_modif_geo > '2020-01-01') ORDER BY a.gid ASC";

//En esta sentencia s agrego la condicion fecha modif para nomostrar los casos que tiene mucho tiempo esperando
}



if ($_GET['pagina']=='poligono'){

	
// Verifica si 'coordinates' y otros parámetros están definidos
if (isset($_GET['coordinates']) && isset($_GET['area']) && isset($_GET['geographicLocation']) && isset($_GET['descriptions'])) {
    $coordinates = json_decode(urldecode($_GET['coordinates']), true);
    $area = urldecode($_GET['area']);
    $geographicLocation = json_decode(urldecode($_GET['geographicLocation']), true);
    $descriptions = json_decode(urldecode($_GET['descriptions']), true);
// Recibir el ID del polígono desde la URL
$poligono_id = $_GET['id']; // Asegúrate de que el ID esté presente

    // Comprobamos si bounds está definido.
    if (isset($_GET['bounds'])) {
        $bounds = urldecode($_GET['bounds']);
    } else {
        $bounds = "No se especificaron límites";
    }
} else {
    echo "No se recibieron los datos necesarios.";
    exit;
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualización del Polígono</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Fuente personalizada -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        body {
            margin-top: 50px;
            font-family: 'Poppins', sans-serif;
            background-color: #f0f8ff; /* Fondo azul claro */
            color: #333;
            margin-left: 290px;
        }
        h1 {
            text-align: center;
            margin-bottom: 50px;
            color: #2c3e50;
            font-weight: 600;
            margin-left: 360px;
        }
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15); /* Sombra */
        }
        .card-title {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.2rem;
        }
        .card-subtitle {
            color: #95a5a6;
            font-size: 0.9rem;
        }
        .card-body {
            background-color: #ecf0f1; /* Fondo suave */
            border-top: 5px solid #3498db; /* Barra superior de color */
        }
        .card-link {
            color: #3498db; /* Azul para enlaces */
        }
        .card-link:hover {
            text-decoration: underline;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .card-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 2rem;
            color: #3498db;
        }
        /* Colores personalizados para cada tarjeta */
        .card:nth-child(1) .card-body { border-top: 5px solid #3498db; } /* Azul */
        .card:nth-child(2) .card-body { border-top: 5px solid #e74c3c; } /* Rojo */
        .card:nth-child(3) .card-body { border-top: 5px solid #f1c40f; } /* Amarillo */
        .card:nth-child(4) .card-body { border-top: 5px solid #2ecc71; } /* Verde */
        .card:nth-child(5) .card-body { border-top: 5px solid #9b59b6; } /* Púrpura */
    </style>
</head><body>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="me-auto ">
            Detalles del Polígono
            <i class="fas fa-compass"></i>
        </h1>
        
        <!-- Menú desplegable -->
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Exportar
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
            <li>
    <a class="dropdown-item" href="pdf/formato_pdf_ficha_tecnica.php?id=<?= htmlspecialchars($poligono_id); ?>">Ficha Técnica</a>
</li>

        <li><a class="dropdown-item" href="pdf/formato_dxf_poligono.php?id=<?= htmlspecialchars($poligono_id); ?>">Polígono</a></li>
    </ul>
        </div>
    </div>

    <!-- Coordenadas -->
    <div class="row row-cols-1 row-cols-md-4 g-4 mt-4">
        <?php foreach ($coordinates as $index => $coordinate): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-icon">
                        <i class="fas fa-compass"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Coordenada-punto <?php echo $index + 1; ?></h5>
                        <h6 class="card-subtitle mb-2">Latitud: <?php echo htmlspecialchars($coordinate['lat']); ?></h6>
                        <p class="card-text">Longitud: <?php echo htmlspecialchars($coordinate['lng']); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Área y Ubicación Geográfica en la misma fila -->
    <div class="row my-5">
        <div class="col text-center">
            <h2>Área Total</h2>
            <p class="lead"><?php echo htmlspecialchars($area); ?> Metros Cuadrado</p>
        </div>
        <div class="col text-center">
            <h2>Ubicación Geográfica</h2>
            <p>Latitud: <?php echo htmlspecialchars($geographicLocation['lat']); ?></p>
            <p>Longitud: <?php echo htmlspecialchars($geographicLocation['lng']); ?></p>
        </div>
    </div>

    <!-- Descripciones de los Límites -->
    <div class="row row-cols-1 row-cols-md-4 g-4">
        <?php foreach ($descriptions as $key => $description): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo ucfirst($key); ?></h5>
                        <h6 class="card-subtitle mb-2">Latitud: <?php echo htmlspecialchars($description['lat']); ?></h6>
                        <p class="card-text">Longitud: <?php echo htmlspecialchars($description['lng']); ?></p>
                        <p class="card-text">Descripción: <?php echo htmlspecialchars($description['desc']); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Bootstrap JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

</body>

<?php
}

#sql de agregar inmueble
if ($_GET['pagina']=='gid_geo') {
// Variables globales
$clasePadre = 'Geomatica'; 
$claseHijo = 'Agregar Inmueble';
$action = 'gid_geo'; // Mantén este para identificar la SQL si es necesario

$busqueda = "<strong>No existen</strong> inmuebles para agregado de datos.";

$sql_mon = "SELECT gid, nombre_pro, cod_catast, sector, nombre_civ, rif, area FROM public.inmuebles";

$error_string = 0;

// Proceso para subir el archivo y ejecutar el comando ogr2ogr
if(isset($_POST["enviar_archivo"]) && !empty($_POST["enviar_archivo"])) {
    $target_dir = "C:/xampp/htdocs/sis_geo/dxf/";
    $sql_dxf = "SELECT count(*) FROM dxf_temporales";
    
    // Primer conteo de registros
    $result = pg_query($conexion, $sql_dxf) or die("Error en SQL cuenta 1");
    $cuenta1 = pg_fetch_result($result, 0, 0);
    
    // Construir el nombre de archivo único
    $numero_archivo = $cuenta1 + 1;
    $target_file = $target_dir . $numero_archivo . '-' . basename($_FILES["fileToUpload"]["name"]);
    $FileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $error_type = $_FILES['fileToUpload']['error'];

    // Manejo de errores en la subida del archivo
    switch ($error_type) {
        case 0:
            $error_string = 'No hay error, fichero subido con éxito.';
            break;
        case 1:
            $error_string = 'El fichero subido excede la directiva upload_max_filesize de php.ini';
            break;
        case 2:
            $error_string = 'El fichero subido excede la directiva MAX_FILE_SIZE especificada en el formulario HTML.';
            break;
        case 3:
            $error_string = 'El fichero fue solo parcialmente subido.';
            break;
        case 4:
            $error_string = 'No se subió ningún fichero.';
            break;
        case 6:
            $error_string = 'Falta la carpeta temporal.';
            break;
        case 7:
            $error_string = 'No se pudo escribir el fichero en el disco.';
            break;
        case 8:
            $error_string = 'Una extensión de PHP detuvo la subida de ficheros.';
            break;
    }

    // Si no hay error en la subida
    if ($error_type == 0) {
        // Verificar si el archivo es de tipo DXF
        if ($FileType == 'dxf') {
            // Mover el archivo al directorio de destino
            if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
                // Ejecutar el comando ogr2ogr
                $comando = 'ogr2ogr -a_srs "EPSG:2202" -f "PostgreSQL" PG:"host=localhost user=postgres dbname=marino_db_spatial password=pasword" -s_srs "EPSG:2202" "'.$target_file.'" -append -nln dxf_temporales';
                exec($comando, $output, $return_var);

                if ($return_var === 0) {
                    // Comprobación de inserción de datos en dxf_temporales
                    $result = pg_query($conexion, $sql_dxf) or die("Error en SQL cuenta 2");
                    $cuenta2 = pg_fetch_result($result, 0, 0);

                    if (($cuenta2 - $cuenta1) == 1) {
                        // Obtener la geometría válida
                        $sql_dxf = "SELECT ST_AsText(ST_GeomFromWKB(wkb_geometry,2202)) as geom_text, ST_IsValid(ST_GeomFromWKB(wkb_geometry,2202)) as geometria_valida, ST_IsClosed(ST_GeomFromWKB(wkb_geometry,2202)) as geometria_cerrada, subclasses, ogc_fid FROM dxf_temporales ORDER BY fecha_creado DESC LIMIT 1";
                        $result = pg_query($conexion, $sql_dxf) or die("Error en SQL extraccion de geom_text");
                        $row = pg_fetch_array($result);

                        if ($row['geometria_valida'] == 't' && $row['geometria_cerrada'] == 't' && $row['subclasses'] == 'AcDbEntity:AcDbPolyline') {
                            // Insertar en inmuebles
                            $sql_dxf = "INSERT INTO inmuebles (the_geom) VALUES (ST_MakePolygon(ST_GeomFromText('".$row['geom_text']."',2202))) RETURNING gid";
                            $result = pg_query($conexion, $sql_dxf) or die("Error en SQL insercion geometria inmueble");
                            $affected_rows = pg_affected_rows($result);
                            $id_result = pg_fetch_result($result, 0, 0);

                            if (!empty($affected_rows)) {
                                $error_string = "Geometría del inmueble agregada con éxito, gid: " . $id_result;
                                $error_string_class = 'alert-success';

                                // Insertar en dxf_correctos
                                $sql_dxf = "INSERT INTO dxf_correctos (gid, id_usuario, ruta_archivo, ogc_fid) VALUES('$id_result', '".$_SESSION['id']."', '$target_file', '".$row['ogc_fid']."')";
                                $result = pg_query($conexion, $sql_dxf) or die("Error en SQL dxf_correctos");
                            } else {
                                $error_string = "Error de inserción en capa inmuebles";
                                $error_string_class = 'alert-danger';
                            }
                        } else {
                            $error_string = "Geometría no válida o no cerrada o no es polilínea.";
                            $error_string_class = 'alert-danger';
                        }
                    } else {
                        $error_string = "Error en la inserción de ogr2ogr en tabla dxf_temporales.";
                        $error_string_class = 'alert-danger';
                    }
                } else {
                    $error_string = "Error ejecutando ogr2ogr.";
                    $error_string_class = 'alert-danger';
                }
            } else {
                $error_string = "Error al mover el archivo subido.";
                $error_string_class = 'alert-danger';
            }
        } else {
            $error_string = "El archivo debe ser de tipo DXF.";
            $error_string_class = 'alert-danger';
        }
    }
}

}


#sql del monitor geomatica
if ($_GET['pagina']=='monitor_geomatica'){
	$clasePadre = $action = 'Geomatica';  //$action se usa para identificar la sql que ejecutara
	$claseHijo = 'Monitor';

	$busqueda= "<strong>No existen</strong> inmuebles lppistos para avalar.";

    $sql_mon = "SELECT * FROM inmuebles a INNER JOIN sist_geo_tb_inm_modif b ON a.gid = b.gid  ORDER BY a.gid ASC";
}

#sql de ver datos del inmueble
if ($_GET['pagina'] == 'ver') {

    $gid = pg_escape_string($_GET["gid"]);

    // Verifica si 'update' está definido antes de usarlo
    $action = isset($_REQUEST['update']) ? pg_escape_string($_REQUEST['update']) : '';

    $clasePadre = $action;

    $claseHijo = "<a href='".(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '#' )."'>vista inmueble</a>";

    // Consulta SQL para obtener la información del inmueble
    $sql_b = "SELECT *, st_xmin(the_geom) as xmin, st_ymin(the_geom) as ymin, st_xmax(the_geom) as xmax, st_ymax(the_geom) as ymax 
              FROM inmuebles 
              WHERE gid = '$gid'";
}


#sql de edicion del inmueble
if ($_GET['pagina']=='editar'){              //no se esta usando ya que el modal hace lo mismo
	$gid = pg_escape_string($_GET["gid"]);

    $sql_b = "SELECT * FROM inmuebles WHERE gid='$gid'";

}

#sql de avales
if ($_GET['pagina']=='avales'){
	$clasePadre = 'Geomatica';
	$claseHijo = 'Avales';
}


function enviarArchivo($email,$nombrearchivo,$propietario,$nombre_civico){

    $path = 'avales';
    $file = $path . "/" . $nombrearchivo;

    $subject = 'ALCALDIA SANTIAGO MARIÑO (AVAL TOPOGRAFICO)(pdf) - '.$nombre_civico.' - '.$propietario;
    $message = "Buenas, Saludos Cordiales

Por medio del presente le remito adjunto documentos contentivos de Aval Topográfico  del cual debe traer Tres (03) juegos impresos para su firma y sello.
De igual forma se le estara enviando la Monografía de la cual debe consignar Tres (03) impresiones para su firma y sello  posterior sera emisión de recibos respectivos.

Sin más punto que tratar o hacer referencia nos despedimos quedando de usted.


Atentamente;
.
        <br><br><br>
        <img alt='Equipo de Geomatica' src='https://i.imgur.com/FIRMA_CORREO.png'>";

    $content = file_get_contents($file);
    $content = chunk_split(base64_encode($content));

    // a random hash will be necessary to send mixed content
    $separator = md5(time());

    // carriage return type (RFC)
    $eol = "\r\n";

    // main header (multipart mandatory)
    $headers = "From: coordinacion de geomatica alcaldia santiago mariño<geomaticamsm@gmail.com>" . $eol;
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
    $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
    $headers .= "This is a MIME encoded message." . $eol;

    // message
    $body = "--" . $separator . $eol;
    $body .= "Content-Type: text/html; charset=\"UTF-8\"" . $eol;
    $body .= "Content-Transfer-Encoding: 7bit" . $eol;
    $body .= $message . $eol;

    // attachment
    $body .= "--" . $separator . $eol;
    $body .= "Content-Type: application/octet-stream; name=\"" . $nombrearchivo . "\"" . $eol;
    $body .= "Content-Transfer-Encoding: base64" . $eol;
    $body .= "Content-Disposition: attachment" . $eol;
    $body .= $content . $eol;
    $body .= "--" . $separator . "--";

    //SEND Mail
    if (mail($email, $subject, $body, $headers)) {
        return true; // or use booleans here
    } else {
        return false;
    }

}


if (isset($_POST['enviar_aval_gid']) && !empty($_POST['enviar_aval_gid'])) {

	$gid=$_POST['enviar_aval_gid'];
	$sql_enviar_aval="SELECT a.propietario, a.nombre_civico, b.nombre, b.correo, b.telefono, c.nombre_archivo FROM solicitudes a INNER JOIN usuarios_externos b ON a.id_usuario=b.id_usuario INNER JOIN sist_geo_tb_inm_modif c ON a.gid=c.gid WHERE a.gid='".$gid."'";
	$result_enviar_aval=pg_query($conexion, $sql_enviar_aval);
	$row_enviar_aval=pg_fetch_array($result_enviar_aval);

	if(enviarArchivo($row_enviar_aval['correo'],$row_enviar_aval['nombre_archivo'],$row_enviar_aval['propietario'],$row_enviar_aval['nombre_civico'])){

		$sql_enviar_aval="INSERT INTO correos_avales (gid, fecha_enviado, id_usuario, nombre_archivo, correo_destino, nombre_solicitante, telefono_solicitante) VALUES ('".$gid."', '".date('Y-m-d')."', '".$_SESSION['id']."', '".$row_enviar_aval['nombre_archivo']."', '".$row_enviar_aval['correo']."', '".$row_enviar_aval['nombre']."', '".$row_enviar_aval['telefono']."')";
		pg_query($conexion, $sql_enviar_aval);

		echo "<script type='text/javascript'>alert('Aval enviado de forma exitosa'); window.location.href=\"portal.php?pagina=avales&avalado=1\";</script>";
	} else{

		echo "<script type='text/javascript'>alert('Error el aval no pudo ser enviado'); window.location.href=\"portal.php?pagina=avales&avalado=1\";</script>";
	}

}

#=================================================================
#               sql generar avales
#=================================================================
if ($_GET['pagina']=='generar_aval'){

	$gid=pg_escape_string($_GET['gid']);
	$motivo=pg_escape_string($_GET['motivo']); //motivo de regeneracion del aval

	#setlocale (LC_TIME, "es_VE.UTF-8"); se llama en portal.php

	$sql = "SELECT * FROM inmuebles where gid=$gid";
	$result = pg_query($conexion, $sql) or die("Error en la Consulta SQL");

	while ($row=pg_fetch_array($result)) {

		$sql_inm_modif = "SELECT * FROM sist_geo_tb_inm_modif where gid=$gid";
		$result_inm_modif = pg_query($conexion, $sql_inm_modif) or die("Error en la Consulta SQL 1");
		$row_inm_modif=pg_fetch_array($result_inm_modif);


	if($row['levantamiento']=='EXTERNO'){

		$codigo_plano='';
		$tipo_de_aval='INFORME TECNICO DEL INMUEBLE';
		if($row_inm_modif['avalado']!=1){
		$sql_a = "SELECT nextval('secuencia_aval')";                                                 //crea el numero de aval
		$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQL 2");
		$row_a = pg_fetch_result($result_a, 0, 0);
		$numero_aval = sprintf("%04d", $row_a);         //formato numero 4 digitos con ceros a la izquierda
		$codigo_aval = 'DG-APT-'.$numero_aval.'-'.date('Ymd');
		}	

	} else if($row['levantamiento']=='ALCALDIA'){

		$codigo_plano= '<b>CÓDIGO DE PLANO:</b> '.$row['codigo_plano'].'<br>';
		$tipo_de_aval='INFORME TECNICO DEL INMUEBLE';
		if($row_inm_modif['avalado']!=1){
		$sql_a = "SELECT nextval('nueva_secuencia_aval')";    //crea el numero de aval
		$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQL 3");
		$row_a = pg_fetch_result($result_a, 0, 0);
		$numero_aval = sprintf("%04d", $row_a);    //formato numero 4 digitos con ceros a la izquierda
		$codigo_aval = 'DG-ALT-'.$numero_aval.'-'.date('Ymd');
		}

	}

	//si se regenera el aval se imprime el codigo de aval original
	if($row_inm_modif['avalado']==1){
		$codigo_aval = $row['cod_aval'];
	}

	//se necesita el codigo_aval para generar el nombre del archivo
	$nombre_archivo=explode(', ', $row['nombre_pro']);
	$nombre_archivo[0]=$codigo_aval.'-'.$nombre_archivo[0].'-'.str_replace("/", "-", $row['nombre_civ']).'.pdf'; //evitar / en el nombre del archivo

	$fecha_aval = date('Y-m-d');

	//este if necesita el nombre_archivo[0]
	if($row_inm_modif['avalado']==1){
		$generado='ReGenerado';        
		$fecha_larga = strftime('Turmero, %A %d de %B de %Y', strtotime($row['fecha_aval']));
		$veces_generado = $row_inm_modif['veces_generado']+1;
		$sql_a = "UPDATE sist_geo_tb_inm_modif SET nombre_archivo = '$nombre_archivo[0]', fecha_aval_modif = '$fecha_aval', veces_generado = '$veces_generado', id_usuario_avalado = '".$_SESSION['id']."' WHERE gid=$gid"; 
		$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQL 4");
		$sql_a = "INSERT INTO sist_geo_tb_avales_regen (gid, fecha_aval_modificado, motivo, id_usuario) VALUES ('$gid', '$fecha_aval', '$motivo', '".$_SESSION['id']."')";
		$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQL 44");
	}

	if($row_inm_modif['avalado']!=1){
		$generado='Generado';
		$fecha_larga = strftime('Turmero, %A %d de %B de %Y');
		$sql_a = "UPDATE sist_geo_tb_inm_modif SET avalado = '1', nombre_archivo = '$nombre_archivo[0]', fecha_aval_modif = '$fecha_aval', veces_generado = '1', id_usuario_avalado = '".$_SESSION['id']."' WHERE gid=$gid"; //marca inmueble como avalado
		$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQL 5");

		//fecha_aval y codigo_aval solo se guardan la primera vez que se hace el aval
		$sql_a = "UPDATE inmuebles SET cod_aval = '$codigo_aval', fecha_aval = '$fecha_aval' WHERE gid=$gid";  //guarda codigo y fecha de aval
		$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQL 6");
	}

	
	$tenencia=$row['tenencia'];
	if($tenencia=='PROPIO'){
		$tenencia=ucwords(strtolower($tenencia)).' (Segun Documento)';
	}
	

	if($row['noreste'] != '0'){
		$lindero1= $row['noreste'];
		$nombre_lindero1= 'NORESTE';
	} else if($row['norte'] != '0'){
		$lindero1= $row['norte'];
		$nombre_lindero1= 'NORTE';
	}
	if($row['sureste'] != '0'){
		$lindero2= $row['sureste'];
		$nombre_lindero2= 'SURESTE';
	} else if($row['este'] != '0'){
		$lindero2= $row['este'];
		$nombre_lindero2= 'ESTE';
	}
	if($row['suroeste'] != '0'){
		$lindero3= $row['suroeste'];
		$nombre_lindero3= 'SUROESTE';
	} else if($row['sur'] != '0'){
		$lindero3= $row['sur'];
		$nombre_lindero3= 'SUR';
	}
	if($row['noroeste'] != '0'){
		$lindero4= $row['noroeste'];
		$nombre_lindero4= 'NOROESTE';
	} else if($row['oeste'] != '0'){
		$lindero4= $row['oeste'];
		$nombre_lindero4= 'OESTE';
	}


	if($row['informe_tecnico'] == '0'){
		$informe_tecnico= '.';	
	} else if($row['informe_tecnico'] != '0'){
		$informe_tecnico=' según informe técnico '.$row['informe_tecnico'].'.';
	}

#=============================================================
#
# Include the main TCPDF library (search for installation path).
#
#===================================================================0
	require_once('funciones/phpWord/vendor/tecnickcom/tcpdf/examples/config/tcpdf_config_alt.php');
	$tcpdf_include_dirs = array(
		realpath('funciones/phpWord/vendor/tecnickcom/tcpdf/tcpdf.php'),

		'C:/xampp/htdocs/tu_proyecto/funciones/phpWord/vendor/tecnickcom/tcpdf/tcpdf.php',
		
	);
	foreach ($tcpdf_include_dirs as $tcpdf_include_path) {
		if (@file_exists($tcpdf_include_path)) {
			require_once($tcpdf_include_path);
			break;
		}
	}

	if (!class_exists('MYPDF')) {
		class MYPDF extends TCPDF {
			//Page header
			public function Header() {
				// get the current page break margin
				$bMargin = $this->getBreakMargin();
				// get current auto-page-break mode
				$auto_page_break = $this->AutoPageBreak;
				// disable auto-page-break
				$this->SetAutoPageBreak(false, 0);
				// set bacground image
				$img_file = K_PATH_IMAGES.'fondo_aval_nue1.jpg';
				$this->Image($img_file, 0, 0, 216, 279, '', '', '', false, 300, 'C', false, false, 0);
				// restore auto-page-break status
				$this->SetAutoPageBreak($auto_page_break, $bMargin);
				// set the starting point for the page content
				$this->setPageMark();
			}
		}
	}
	
	// create new PDF document
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	


	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Direccion de geomatica');
	$pdf->SetTitle('aval '.$codigo_aval);
	$pdf->SetSubject('aval topografico');
	$pdf->SetKeywords('aval, direccion, geomatica, alcaldia, turmero');

	// set header and footer fonts

	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(20);
	$pdf->SetFooterMargin(20);

	// remove default footer
	$pdf->setPrintFooter(false);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf->setImageScale(1.25);

	// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/funciones/phpWord/vendor/tecnickcom/tcpdf/examples/lang/spa.php')) {
		require_once(dirname(__FILE__).'/funciones/phpWord/vendor/tecnickcom/tcpdf/examples/lang/spa.php');
		$pdf->setLanguageArray($l);
	}

	// set font
	$pdf->SetFont('arialnarrow','', 11);

	// add a page
	$pdf->AddPage('P', 'LETTER');
	$style = array(
		'border' => 0,
		'padding' => 0,
		'fgcolor' => array(0,0,0),
		'bgcolor' => array(255,255,255)
	);
	
'<p></p>';
'<p></p>';

	$pdf->write2DBarcode($codigo_aval, 'QRCODE,Q', 15, 31, 20, 20, $style, 'N');

	$pdf->SetXY(15,30);
	// Print a text
	$html = '<br/><br/><p style= "text-align:right;">	
	<b>'.$fecha_larga.'.</b><br>
	<b>Informe Técnico:&nbsp;&nbsp;</b><span style="text-decoration:none;background-color:#BDBDBD ;color:red;"><b>N° '.$codigo_aval.'.</b></span></p>
	<br/>
	<p style= "text-align:center;"><b><ins>'.$tipo_de_aval.'</ins></b></p><br>
	<b>PROPIETARIO:</b> '.$row['nombre_pro'].' <br>
	<b>CEDULA/RIF:</b> '.$row['rif'].' <br>
	<b>CÓDIGO/NOMENCLATURA CATASTRAL:</b> '.$row['cod_catast'].'<br>
	'.$codigo_plano.'
	<p><b style="text-decoration:none;background-color:#BDBDBD ;">DATOS DEL INMUEBLE</b></p>
	<b>AV. O CALLE:</b> '.ucwords(strtolower($row['direccion'])).'<br>
	<b>CÍVICO:</b> '.$row['nombre_civ'].' <br>
	<b>SECTOR, BARRÍO O URB.:</b> '.ucwords(strtolower($row['sector'])).'<br>
	<b>PARROQUIA:</b> '.ucwords(strtolower($row['parroquia'])).'<br>
	<table>
	      <tr>
	        <td width="70%"><b>AREA DE TERRENO SEGÚN ÚLTIMO DOCUMENTO REGISTRADO:</b></td>
	        <td width="30%"><span style= "text-align:right;">'.(empty((float)number_format($row['area_documento'], 2, '.', '')) ? 'No refleja' : number_format($row['area_documento'], 2, ',', '.').' m<sup>2</sup>').'.</span></td> 
	      </tr>
	      <tr>
	        <td><b>AREA DE TERRENO SEGÚN PLANO TOPOGRÁFICO:</b></td>
	        <td><span style= "text-align:right;">'.number_format($row['area'], 2, ',', '.').' m<sup>2</sup>.</span></td>
	      </tr>
	      <tr>
	       <td><b>TENENCIA:</b></td>
	       <td><span style= "text-align:right;">'.$tenencia.'.</span></td>
	      </tr>
	</table>
	<br><p><b style="text-decoration:none;background-color:#BDBDBD ;color:black;">DESCRIPCIÓN DE LINDEROS</b></p><br>
	<b>'.$nombre_lindero1.':</b> '.$lindero1.'<br>
	<b>'.$nombre_lindero2.':</b> '.$lindero2.'<br>
	<b>'.$nombre_lindero3.':</b> '.$lindero3.'<br>
	<b>'.$nombre_lindero4.':</b> '.$lindero4.'
	<br/><br/>

<p></p>
	<p></p>
	<p></p>
	<p></p>
	<p></p>
	<p style= "text-align:center;">
	___________________________________________<br>
	<b>Director de Catastro.</b><br>
	<b><small></small></b></p>';


	$pdf->writeHTML($html, true, false, true, false, '');

	$style = array(
		'border' => 0,
		'padding' => 0,
		'fgcolor' => array(0,0,0),
		'bgcolor' => array(255,255,255)
	);



	}


	$pdf->Output('C:/xampp/htdocs/sis_geo/avales/'.$nombre_archivo[0], 'F');


	echo "<script type='text/javascript'>alert('Aval $generado de forma exitosa'); window.location.href=\"portal.php?pagina=avales&avalado=1\";</script>";


	
	require_once 'funciones/phpWord/bootstrap.php';

	$gid=pg_escape_string($_GET['gid']);
	$motivo=pg_escape_string($_GET['motivo']);          //motivo de regeneracion del aval

	setlocale (LC_TIME, "es_VE.UTF-8");
	$sql = "SELECT * FROM inmuebles where gid=$gid";
	$result = pg_query($conn, $sql) or die("Error en la Consulta SQL");

	while ($row=pg_fetch_array($result)) {

		$sql_inm_modif = "SELECT * FROM sist_geo_tb_inm_modif where gid=$gid";
		$result_inm_modif = pg_query($conexion, $sql_inm_modif) or die("Error en la Consulta SQL 1");
		$row_inm_modif=pg_fetch_array($result_inm_modif);


	if($row['levantamiento']=='EXTERNO'){
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('avaltemplate/avaltemplate.docx');

		if($row_inm_modif['avalado']!=1){
		$sql_a = "SELECT nextval('secuencia_aval')";                                                 //crea el numero de aval
		$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQL 2");
		$row_a = pg_fetch_result($result_a, 0, 0);
		$numero_aval = sprintf("%04d", $row_a);                     //formato numero 4 digitos con ceros a la izquierda
		$codigo_aval = 'DG-APT-'.$numero_aval.'-'.date('Ymd');
		}	

	} else if($row['levantamiento']=='ALCALDIA'){
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('avaltemplate/avaltemplate_levant.docx');

		if($row_inm_modif['avalado']!=1){
		$sql_a = "SELECT nextval('secuencia_aval_levant')";          //crea el numero de aval
		$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQL 3");
		$row_a = pg_fetch_result($result_a, 0, 0);
		$numero_aval = sprintf("%04d", $row_a);                     //formato numero 4 digitos con ceros a la izquierda
		$codigo_aval = 'DG-ALT-'.$numero_aval.'-'.date('Ymd');
		}

	}

	//si se regenera el aval se imprime el codigo de aval original
	if($row_inm_modif['avalado']==1){
		$codigo_aval = $row['cod_aval'];
	}

	//se necesita el codigo_aval para generar el nombre del archivo
	$nombre_archivo=explode(', ', $row['nombre_pro']);
	$nombre_archivo[0]=$codigo_aval.'-'.$nombre_archivo[0].'-'.str_replace("/", "-", $row['nombre_civ']).'.docx'; //evitar / en el nombre del archivo

	$fecha_aval = date('Y-m-d');

	//este if necesita el nombre_archivo[0]
	if($row_inm_modif['avalado']==1){
		$generado='ReGenerado';        
		$fecha_larga = strftime('Turmero, %A %d de %B de %Y', strtotime($row['fecha_aval']));
		$veces_generado = $row_inm_modif['veces_generado']+1;
		$sql_a = "UPDATE sist_geo_tb_inm_modif SET nombre_archivo = '$nombre_archivo[0]', fecha_aval_modif = '$fecha_aval', veces_generado = '$veces_generado', id_usuario_avalado = '".$_SESSION['id']."' WHERE gid=$gid"; 
		$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQL 4");
		$sql_a = "INSERT INTO sist_geo_tb_avales_regen (gid, fecha_aval_modificado, motivo, id_usuario) VALUES ('$gid', '$fecha_aval', '$motivo', '".$_SESSION['id']."')";
		$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQL 44");
	}

	if($row_inm_modif['avalado']!=1){
		$generado='Generado';
		$fecha_larga = strftime('Turmero, %A %d de %B de %Y');
		$sql_a = "UPDATE sist_geo_tb_inm_modif SET avalado = '1', nombre_archivo = '$nombre_archivo[0]', fecha_aval_modif = '$fecha_aval', veces_generado = '1', id_usuario_avalado = '".$_SESSION['id']."' WHERE gid=$gid"; //marca inmueble como avalado
		$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQL 5");

		//fecha_aval y codigo_aval solo se guardan la primera vez que se hace el aval
		$sql_a = "UPDATE inmuebles SET cod_aval = '$codigo_aval', fecha_aval = '$fecha_aval' WHERE gid=$gid";  //guarda codigo y fecha de aval
		$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQL 6");
	}

	$templateProcessor->setValue('fecha_larga', $fecha_larga);

	$templateProcessor->setValue('propietario', $row['nombre_pro']);
	$templateProcessor->setValue('rif', $row['rif']);
	$templateProcessor->setValue('codigo_catastral', $row['cod_catast']);
	$templateProcessor->setValue('codigo_propietario', $row['cod_propie']);
	$templateProcessor->setValue('nombre_civico', $row['nombre_civ']);
	$templateProcessor->setValue('sector', ucwords(strtolower($row['sector'])));
	$templateProcessor->setValue('parroquia', ucwords(strtolower($row['parroquia'])));
	$templateProcessor->setValue('area_documento', $row['area_documento']);
	$templateProcessor->setValue('area', $row['area']);

	$tenencia=$row['tenencia'];
	if($tenencia=='PROPIO'){
		$tenencia=ucwords(strtolower($tenencia)).' (Segun Documento)';
	}
	$templateProcessor->setValue('tenencia', $tenencia);

	$templateProcessor->setValue('codigo_aval', $codigo_aval);

	if($row['noreste'] != '0'){
		$lindero11='NorEste: '.$row['noreste'];
		$templateProcessor->setValue('lindero1', $row['noreste']);
		$templateProcessor->setValue('nombre_lindero1', 'Noreste');
	} else if($row['norte'] != '0'){
		$templateProcessor->setValue('lindero1', $row['norte']);
		$templateProcessor->setValue('nombre_lindero1', 'Norte');
	}
	if($row['sureste'] != '0'){
		$templateProcessor->setValue('lindero2', $row['sureste']);
		$templateProcessor->setValue('nombre_lindero2', 'Sureste');
	} else if($row['este'] != '0'){
		$templateProcessor->setValue('lindero2', $row['este']);
		$templateProcessor->setValue('nombre_lindero2', 'Este');
	}
	if($row['suroeste'] != '0'){
		$templateProcessor->setValue('lindero3', $row['suroeste']);
		$templateProcessor->setValue('nombre_lindero3', 'Suroeste');
	} else if($row['sur'] != '0'){
		$templateProcessor->setValue('lindero3', $row['sur']);
		$templateProcessor->setValue('nombre_lindero3', 'Sur');
	}
	if($row['noroeste'] != '0'){
		$templateProcessor->setValue('lindero4', $row['noroeste']);
		$templateProcessor->setValue('nombre_lindero4', 'Noroeste');
	} else if($row['oeste'] != '0'){
		$templateProcessor->setValue('lindero4', $row['oeste']);
		$templateProcessor->setValue('nombre_lindero4', 'Oeste');
	}

	$templateProcessor->setValue('codigo_plano', $row['codigo_plano']);
	$templateProcessor->setValue('direccion', ucwords(strtolower($row['direccion'])));

	if($row['informe_tecnico'] == '0'){
		$templateProcessor->setValue('informe_tecnico', '.');	
	} else if($row['informe_tecnico'] != '0'){
		$informe_tecnico='según informe técnico '.$row['informe_tecnico'].'.';
		$templateProcessor->setValue('informe_tecnico', $informe_tecnico);
	}

	}

	$templateProcessor->saveAs('avales/'.$nombre_archivo[0].'.docx');

	echo "<script type='text/javascript'>alert('Aval $generado de forma exitosa'); window.location.href=\"portal.php?pagina=avales&avalado=1\";</script>";
	
}


#sql aprobar avales
if ($_GET['pagina']=='aprobar_aval'){
	$gid=pg_escape_string($_GET['gid']);
	$sql_a = "UPDATE sist_geo_tb_inm_modif SET aprobado = '1', fecha_aprobado = current_date WHERE gid=$gid"; //marca inmueble como aprobado
	$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQL");
	$filas_afect = pg_affected_rows($result_a);
	if(!empty($filas_afect)){
		echo "<script type='text/javascript'>alert('Aval ha sido marcado como aprobado'); window.location.href=\"portal.php?pagina=avales&aprobado=1\";</script>";
	}else{
		echo "<script type='text/javascript'>alert('Error'); window.location.href=\"portal.php?pagina=avales#error\";</script>";
	}
}


#sql de update de agregar inmueble
if ($_GET['pagina']=='update_gid_geo'){

	# Se reciben los datos y se colocan en mayusculas
	$gid = pg_escape_string($_POST["gid"]);
	$nombre_pro = strtoupper(pg_escape_string($_POST["nombre_pro"]));
	$rif = strtoupper(pg_escape_string($_POST["rif"]));
	$nombre_civ = strtoupper(pg_escape_string($_POST["nombre_civ"]));

	$sql = "UPDATE inmuebles SET nombre_pro='".$nombre_pro."', rif='".$rif."', nombre_civ='".$nombre_civ."' WHERE gid=".$gid;

	# Ejecutamos la consulta (se devolverá true o false)
	# Luego mostrara un mensaje para saber si fue exitosa la edición de datos
	# Y recargara la pagina.
	if(pg_query($conexion, $sql)){

		//sql para identificar que geomatica agrego inmueble al sistema
		$sql1 = "INSERT INTO sist_geo_tb_inm_modif (gid, id_usuario_geo, fecha_modif_geo) VALUES ('".$gid."', '".$_SESSION['id']."', '".date('Y-m-d')."')";
		pg_query($conexion, $sql1) or die("Error en la consulta SQL44");

		$sql2=str_replace("'", "\"", $sql);
		$sql2=str_replace("\n", "", $sql2);

		$sql_bitacora="INSERT INTO sist_geo_tb_bitacora (id_user, sql, gid) VALUES ('".$_SESSION['id']."', '".$sql2."', '".$gid."')";
		
		pg_query($conexion, $sql_bitacora) or die("Error en la consulta SQL 0");

	    echo "<script type='text/javascript'>alert('Edición Exitosa'); window.location.href='portal.php?pagina=gid_geo';
	    	</script>";
	}else{
	    echo "<script type='text/javascript'>alert('No se pudo realizar la Edicion'); window.location.href='portal.php?pagina=gid_geo';</script>";
	}
}


#sql monografias
if($_GET['pagina']=='monografias'){
	$clasePadre = 'Geomatica';
	$claseHijo = 'Monografias';
	$sql_m = "SELECT * FROM sist_geo_tb_monografias ORDER BY fecha DESC";
	$busqueda = "No existen monografias generadas.";
}

#sql generar monografia
if ($_GET['pagina']=='crear_Monografia') {
	$solicitante = strtoupper(pg_escape_string($_POST["solicitante"]));
	$rif = strtoupper(pg_escape_string($_POST["rif"]));
	$telefono = pg_escape_string($_POST["telefono"]);
	$vertice = $_POST["vertice"];

	switch ($vertice) {

		case "Alcaldia":
			$parroquia='Santiago Mariño';
			$template_monografia='monografiatemplate/alcaldia.docx';
			break;
		
		case "La Morita":
			$parroquia='Saman de Guere';
			$template_monografia='monografiatemplate/la_morita.docx';
			break;

		case "Los Overos":
			$parroquia='Santiago Mariño';
			$template_monografia='monografiatemplate/los_overos.docx';
			break;

		case "Pantin":
			$parroquia='Pedro Arevalo Aponte';
			$template_monografia='monografiatemplate/pantin.docx';
			break;

		case "Pedregal":
			$parroquia='Pedro Arevalo Aponte';
			$template_monografia='monografiatemplate/pedregal.docx';
			break;

		case "Rosario de Paya":
			$parroquia='Pedro Arevalo Aponte';
			$template_monografia='monografiatemplate/rosario_de_paya.docx';
			break;

		case "San Carlos":
			$parroquia='Santiago Mariño';
			$template_monografia='monografiatemplate/san_carlos.docx';
			break;
	}

	setlocale (LC_TIME, "es_VE.UTF-8");
	$fecha=strftime('%A %d de %B de %Y');

	$sql_a = "SELECT nextval('secuencia_monografia')";   //genera numero de monografia  
	$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQL secuencia monografia");
	$row_a = pg_fetch_result($result_a, 0, 0);
	$numero_monografia = sprintf("%04d", $row_a);    

	$codigo_monografia='DG-MP-RGM-'.$numero_monografia.'-'.date('Ymd');
	$nombre_archivo=$codigo_monografia.'-'.$solicitante;

	$sql_a = "INSERT INTO sist_geo_tb_monografias (codigo, solicitante, rif, telefono, fecha, vertice, parroquia, nombre_archivo) VALUES ('".$codigo_monografia."', '".$solicitante."', '".$rif."', '".$telefono."', '".date('Y-m-d')."', UPPER('".$vertice."'), UPPER('".$parroquia."'), '".$nombre_archivo."')";
	$result_a = pg_query($conexion, $sql_a) or die("Error en la Consulta SQL insertar monografia");
	$filas_afectadas = pg_affected_rows($result_a);

	if (!empty($filas_afectadas)) {

		require_once 'funciones/phpWord/bootstrap.php';
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($template_monografia);

		$templateProcessor->setValue('codigo_monografia', $codigo_monografia);
		$templateProcessor->setValue('rif', $rif);
		$templateProcessor->setValue('fecha', $fecha);
		$templateProcessor->setValue('solicitante', $solicitante);
		$templateProcessor->setValue('telefono', $telefono);
		$templateProcessor->setValue('parroquia', $parroquia);

		$templateProcessor->saveAs('monografias/'.$nombre_archivo.'.docx');

		echo "<script type='text/javascript'>window.location.href='portal.php?pagina=monografias&generada=1';
	    	</script>";
	}else{
		echo "<script type='text/javascript'>alert('Error al generar monografia'); window.location.href='portal.php?pagina=monografias';
	    	</script>";
	}
}

if (isset($_GET['pagina']) && $_GET['pagina'] == 'bitacora') {
    $clasePadre = 'Geomatica';
    $claseHijo = 'Bitacora';

    // Verificar si la clave 'usuario_bitacora' está definida en POST
    if (isset($_POST['usuario_bitacora'])) {
        $usuario_bitacora = $_POST['usuario_bitacora'];
    } else {
        $usuario_bitacora = ''; // O manejar el caso de no estar definido según lo que necesites
    }

    $sql = "SELECT usuario, nombre FROM sist_geo_tb_usuario";

    $query_todos = "SELECT a.fecha, b.nombre, b.usuario, a.sql, c.nom_dep FROM \"sist_geo_tb_bitacora\" a INNER JOIN \"sist_geo_tb_usuario\" b ON a.id_user=b.id_user INNER JOIN \"sist_geo_reg-departamento\" c ON c.id=b.id_dept ORDER BY a.fecha DESC";

    $query_usuario = "SELECT a.fecha, b.nombre, b.usuario, a.sql, c.nom_dep FROM \"sist_geo_tb_bitacora\" a INNER JOIN \"sist_geo_tb_usuario\" b ON a.id_user=b.id_user INNER JOIN \"sist_geo_reg-departamento\" c ON c.id=b.id_dept WHERE b.usuario='$usuario_bitacora' ORDER BY a.fecha DESC";
}


#sql de update del inmueble Busqueda General
if ($_GET['pagina']=='update_Busqueda'){

	# Se reciben los datos y se colocan en mayusculas
	$gid = pg_escape_string($_POST["gid"]);

	$nombre_pro = strtoupper(pg_escape_string($_POST["nombre_pro"]));
	$rif = strtoupper(pg_escape_string($_POST["rif"]));
	$nombre_civ = strtoupper(pg_escape_string($_POST["nombre_civ"]));

	if($_SESSION["id_dept"]==1){      //si es geomatica
		$codigo_plano = strtoupper(pg_escape_string($_POST["codigo_plano"]));
		$levantamiento = strtoupper(pg_escape_string($_POST["levantamiento"]));


		$sector = strtoupper(pg_escape_string($_POST["sector"]));
		$id_parroquia = $_POST["parroquia"];
		$cod_catast = strtoupper(pg_escape_string($_POST["cod_catast"]));
		$direccion = strtoupper(pg_escape_string($_POST["direccion"]));
		$area_documento = strtoupper(pg_escape_string($_POST["area_documento"]));
		$cod_propie = strtoupper(pg_escape_string($_POST["cod_propie"]));
		$layer = strtoupper(pg_escape_string($_POST["layer"]));
		$informe_tecnico = strtoupper(pg_escape_string($_POST["informe_tecnico"]));
		$tenencia = strtoupper(pg_escape_string($_POST["tenencia"]));
		$norte = strtoupper(pg_escape_string($_POST["norte"]));
		$sur = strtoupper(pg_escape_string($_POST["sur"]));
		$este = strtoupper(pg_escape_string($_POST["este"]));
		$oeste = strtoupper(pg_escape_string($_POST["oeste"]));
		$noreste = strtoupper(pg_escape_string($_POST["noreste"]));
		$noroeste = strtoupper(pg_escape_string($_POST["noroeste"]));
		$sureste = strtoupper(pg_escape_string($_POST["sureste"]));
		$suroeste = strtoupper(pg_escape_string($_POST["suroeste"]));
	}

	/*if($_SESSION["id_dept"]==4){	  //si es catastro
		$sector = strtoupper(pg_escape_string($_POST["sector"]));
		$id_parroquia = $_POST["parroquia"];
		$cod_catast = strtoupper(pg_escape_string($_POST["cod_catast"]));
		$direccion = strtoupper(pg_escape_string($_POST["direccion"]));
		$area_documento = strtoupper(pg_escape_string($_POST["area_documento"]));
		$cod_propie = strtoupper(pg_escape_string($_POST["cod_propie"]));
		$layer = strtoupper(pg_escape_string($_POST["layer"]));
		$informe_tecnico = strtoupper(pg_escape_string($_POST["informe_tecnico"]));
		$tenencia = strtoupper(pg_escape_string($_POST["tenencia"]));
		$norte = strtoupper(pg_escape_string($_POST["norte"]));
		$sur = strtoupper(pg_escape_string($_POST["sur"]));
		$este = strtoupper(pg_escape_string($_POST["este"]));
		$oeste = strtoupper(pg_escape_string($_POST["oeste"]));
		$noreste = strtoupper(pg_escape_string($_POST["noreste"]));
		$noroeste = strtoupper(pg_escape_string($_POST["noroeste"]));
		$sureste = strtoupper(pg_escape_string($_POST["sureste"]));
		$suroeste = strtoupper(pg_escape_string($_POST["suroeste"]));
	}*/
	
	switch ($id_parroquia) {
		case '1':
			$parroquia='SANTIAGO MARINO';
			break;

		case '2':
			$parroquia='PEDRO AREVALO APONTE';
			break;

		case '3':
			$parroquia='CHUAO';
			break;

		case '4':
			$parroquia='SAMAN DE GUERE';
			break;

		case '5':
			$parroquia='ALFREDO PACHECO MIRANDA';
			break;
	}

	# Se hace la edición de los datos.
	if($_SESSION["id_dept"]==1){

		#$sql = "UPDATE inmuebles SET nombre_pro='".$nombre_pro."', rif='".$rif."', nombre_civ='".$nombre_civ."', levantamiento='".$levantamiento."', codigo_plano='".$codigo_plano."' WHERE gid='".$gid."'";

		$sql = "UPDATE inmuebles SET nombre_pro='".$nombre_pro."', rif='".$rif."', nombre_civ='".$nombre_civ."', levantamiento='".$levantamiento."', codigo_plano='".$codigo_plano."', sector='".$sector."', id_parr='".$id_parroquia."', parroquia='".$parroquia."', cod_catast='".$cod_catast."', direccion='".$direccion."', area_documento='".$area_documento."', cod_propie='".$cod_propie."', layer='".$layer."', informe_tecnico='".$informe_tecnico."', tenencia='".$tenencia."', norte='".$norte."', sur='".$sur."', este='".$este."', oeste='".$oeste."', noreste='".$noreste."', noroeste='".$noroeste."', sureste='".$sureste."', suroeste='".$suroeste."' WHERE gid='".$gid."'";

	} /*elseif($_SESSION["id_dept"]==4){

		$sql = "UPDATE inmuebles SET nombre_pro='".$nombre_pro."', rif='".$rif."', nombre_civ='".$nombre_civ."', sector='".$sector."', id_parr='".$id_parroquia."', parroquia='".$parroquia."', cod_catast='".$cod_catast."', direccion='".$direccion."', area_documento='".$area_documento."', cod_propie='".$cod_propie."', layer='".$layer."', informe_tecnico='".$informe_tecnico."', tenencia='".$tenencia."', norte='".$norte."', sur='".$sur."', este='".$este."', oeste='".$oeste."', noreste='".$noreste."', noroeste='".$noroeste."', sureste='".$sureste."', suroeste='".$suroeste."' WHERE gid='".$gid."'";
	}*/

	#el siguiente sql guarda una copia del inmueble a ser modificado en inmuebles_bitacora
	$sql_inmueble_bitacora="INSERT INTO inmuebles_bitacora SELECT *,'".$_SESSION['id']."' FROM inmuebles WHERE gid='".$gid."'";
	pg_query($conexion, $sql_inmueble_bitacora) or die("Error en la consulta SQL1");

	# Ejecutamos la consulta (se devolverá true o false)
	# Luego mostrara un mensaje para saber si fue exitosa la edición de datos
	# Y recargara la pagina.
	if(pg_query($conexion, $sql)){

		$sql2=str_replace("'", "\"", $sql);
		$sql2=str_replace("\n", "", $sql2);

		$sql_bitacora="INSERT INTO sist_geo_tb_bitacora (id_user, sql, gid) VALUES ('".$_SESSION['id']."', '".$sql2."', '".$gid."')";
		
		pg_query($conexion, $sql_bitacora) or die("Error en la consulta SQL2");

	    echo "<script type='text/javascript'>alert('Edición Exitosa'); window.location.href='portal.php?busqueda=".$rif."';</script>";
	}else{
	    echo "<script type='text/javascript'>alert('No se pudo realizar la Edicion'); window.location.href='portal.php?busqueda=".$rif."';</script>";
	}
}

#sql de update del inmueble monitor catastro
if ($_GET['pagina']=='update_InformeTecnico'){

	# Se reciben los datos y se colocan en mayusculas
	$gid = pg_escape_string($_POST["gid"]);

	$nombre_pro = strtoupper(pg_escape_string($_POST["nombre_pro"]));
	$rif = strtoupper(pg_escape_string($_POST["rif"]));
	$nombre_civ = strtoupper(pg_escape_string($_POST["nombre_civ"]));

	#if($_SESSION["id_dept"]==4){    //si es catastro

	if($_SESSION["id_dept"]==1){    //si es geomatica
		$sector = strtoupper(pg_escape_string($_POST["sector"]));
		$id_parroquia = $_POST["parroquia"];
		$cod_catast = strtoupper(pg_escape_string($_POST["cod_catast"]));
		$direccion = strtoupper(pg_escape_string($_POST["direccion"]));
		$area_documento = strtoupper(pg_escape_string($_POST["area_documento"]));
		$cod_propie = strtoupper(pg_escape_string($_POST["cod_propie"]));
		$layer = strtoupper(pg_escape_string($_POST["layer"]));
		$informe_tecnico = strtoupper(pg_escape_string($_POST["informe_tecnico"]));
		$tenencia = strtoupper(pg_escape_string($_POST["tenencia"]));
		$norte = strtoupper(pg_escape_string($_POST["norte"]));
		$sur = strtoupper(pg_escape_string($_POST["sur"]));
		$este = strtoupper(pg_escape_string($_POST["este"]));
		$oeste = strtoupper(pg_escape_string($_POST["oeste"]));
		$noreste = strtoupper(pg_escape_string($_POST["noreste"]));
		$noroeste = strtoupper(pg_escape_string($_POST["noroeste"]));
		$sureste = strtoupper(pg_escape_string($_POST["sureste"]));
		$suroeste = strtoupper(pg_escape_string($_POST["suroeste"]));
	}
	
	switch ($id_parroquia) {
		case '1':
			$parroquia='SANTIAGO MARINO';
			break;

		case '2':
			$parroquia='PEDRO AREVALO APONTE';
			break;

		case '3':
			$parroquia='CHUAO';
			break;

		case '4':
			$parroquia='SAMAN DE GUERE';
			break;

		case '5':
			$parroquia='ALFREDO PACHECO MIRANDA';
			break;
	}

	# Se hace la edición de los datos.
	#if($_SESSION["id_dept"]==4){    //si es catastro

	if($_SESSION["id_dept"]==1){    //si es geomatica

	$sql = "UPDATE inmuebles SET nombre_pro='".$nombre_pro."', rif='".$rif."', nombre_civ='".$nombre_civ."', sector='".$sector."', id_parr='".$id_parroquia."', parroquia='".$parroquia."', cod_catast='".$cod_catast."', direccion='".$direccion."', area_documento='".$area_documento."', cod_propie='".$cod_propie."', layer='".$layer."', informe_tecnico='".$informe_tecnico."', tenencia='".$tenencia."', norte='".$norte."', sur='".$sur."', este='".$este."', oeste='".$oeste."', noreste='".$noreste."', noroeste='".$noroeste."', sureste='".$sureste."', suroeste='".$suroeste."' WHERE gid='".$gid."'";

		#el siguiente sql guarda una copia del inmueble a ser modificado en inmuebles_bitacora
		$sql_inmueble_bitacora="INSERT INTO inmuebles_bitacora SELECT *,'".$_SESSION['id']."' FROM inmuebles WHERE gid='".$gid."'";
		pg_query($conexion, $sql_inmueble_bitacora) or die("Error en la consulta SQL3");
	}

	# Ejecutamos la consulta (se devolverá true o false)
	# Luego mostrara un mensaje para saber si fue exitosa la edición de datos
	# Y recargara la pagina.
	if(pg_query($conexion, $sql)){

		//sql para identificar que catastro modifico inmueble desde su monitor
		$sql1 = "UPDATE sist_geo_tb_inm_modif SET id_usuario='".$_SESSION['id']."', fecha_modif_catastro='".date('Y-m-d')."' WHERE gid='".$gid."'";
		pg_query($conexion, $sql1) or die("Error en la consulta SQL4");

		$sql2=str_replace("'", "\"", $sql);
		$sql2=str_replace("\n", "", $sql2);

		$sql_bitacora="INSERT INTO sist_geo_tb_bitacora (id_user, sql, gid) VALUES ('".$_SESSION['id']."', '".$sql2."', '".$gid."')";
		
		pg_query($conexion, $sql_bitacora) or die("Error en la consulta SQL5");

	    echo "<script type='text/javascript'>alert('Edición Exitosa'); window.location.href='portal.php?pagina=monitor_catastro';</script>";
	}else{
	    echo "<script type='text/javascript'>alert('No se pudo realizar la Edicion'); window.location.href='portal.php?pagina=monitor_catastro';</script>";
	}
}

#sql de update del inmueble monitor geomatica
if ($_GET['pagina']=='update_Geomatica'){
	# Se reciven los datos y se colocan en mayusculas
	$gid = pg_escape_string($_POST["gid"]);

	$nombre_pro = strtoupper(pg_escape_string($_POST["nombre_pro"]));
	$rif = strtoupper(pg_escape_string($_POST["rif"]));
	$nombre_civ = strtoupper(pg_escape_string($_POST["nombre_civ"]));

	if($_SESSION["id_dept"]==1){      //si es geomatica
		$codigo_plano = strtoupper(pg_escape_string($_POST["codigo_plano"]));
		$levantamiento = strtoupper(pg_escape_string($_POST["levantamiento"]));
	}
	
	switch ($id_parroquia) {
		case '1':
			$parroquia='SANTIAGO MARINO';
			break;

		case '2':
			$parroquia='PEDRO AREVALO APONTE';
			break;

		case '3':
			$parroquia='CHUAO';
			break;

		case '4':
			$parroquia='SAMAN DE GUERE';
			break;

		case '5':
			$parroquia='ALFREDO PACHECO MIRANDA';
			break;
	}

	# Se hace la edición de los datos.
	if($_SESSION["id_dept"]==1){

		$sql = "UPDATE inmuebles SET nombre_pro='".$nombre_pro."', rif='".$rif."', nombre_civ='".$nombre_civ."', levantamiento='".$levantamiento."', codigo_plano='".$codigo_plano."' WHERE gid='".$gid."'"; 

		#el siguiente sql guarda una copia del inmueble a ser modificado en inmuebles_bitacora
		$sql_inmueble_bitacora="INSERT INTO inmuebles_bitacora SELECT *,'".$_SESSION['id']."' FROM inmuebles WHERE gid='".$gid."'";
		pg_query($conexion, $sql_inmueble_bitacora) or die("Error en la consulta SQL6");

	}

	# Ejecutamos la consulta (se devolverá true o false)
	# Luego mostrara un mensaje para saber si fue exitosa la edición de datos
	# Y recargara la pagina.
	if(pg_query($conexion, $sql)){

		//sql para identificar que geomatica modifico inmueble desde su monitor
		$sql1 = "UPDATE sist_geo_tb_inm_modif SET id_usuario_geo2='".$_SESSION['id']."', fecha_modif_geo2='".date('Y-m-d')."' WHERE gid='".$gid."'";
		pg_query($conexion, $sql1) or die("Error en la consulta SQL4");

		$sql2=str_replace("'", "\"", $sql);
		$sql2=str_replace("\n", "", $sql2);

		$sql_bitacora="INSERT INTO sist_geo_tb_bitacora (id_user, sql, gid) VALUES ('".$_SESSION['id']."', '".$sql2."', '".$gid."')";
		
		pg_query($conexion, $sql_bitacora) or die("Error en la consulta SQL7");

	    echo "<script type='text/javascript'>alert('Edición Exitosa'); window.location.href='portal.php?pagina=monitor_geomatica';</script>";
	}else{
	    echo "<script type='text/javascript'>alert('No se pudo realizar la Edicion'); window.location.href='portal.php?pagina=monitor_geomatica';</script>";
	}
}

#---------------------------------------salida del sistema----------------------------------------------
if ($_GET['pagina']=='salir') {
	$params = session_get_cookie_params();

	// Destruir la cookie de la sesión
	setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
	
	// Destruir variables de sesión
	session_unset();
	
	// Destruir la sesión
	session_destroy();
	
	// Redirigir a login.php
	header('Location: login.php');
	exit();
}

#----------------------------funcion para calcula de dias entre 2 fechas-----------------------------
function calculaDias($date_1 , $date_2 )
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);
   
    $interval = date_diff($datetime1, $datetime2);
   
    if ($interval->format('%a')=='1') {
    	$formato='%a dia';
    }else{
    	$formato='%a dias';
    }

    return $interval->format($formato);
}

#-- ***********************************contadores sms*********************************************--
$usuario=$_SESSION["login"];
$id_dept=$_SESSION["id_dept"];

$sql_nom2 = "SELECT a.nombre as nombre_usuario, b.nom_dep FROM sist_geo_tb_usuario a
            INNER JOIN \"sist_geo_reg-departamento\" b ON b.id = a.id_dept
             WHERE usuario = '$usuario'";


$resp2 = pg_query($conexion,$sql_nom2) or die ("Error en Nombre de Usuario");
$row2=pg_fetch_array($resp2);
$nombre_usuario=$row2['nombre_usuario'];
$nom_dep=$row2['nom_dep'];

if($_SESSION["escribir"]==1){

	$var=" para= '".$_SESSION["departamento"]."'";

}else if($_SESSION["escribir"]!=1){

    $var=" dirigido ='".$nombre_usuario."'"; 
}

if (!empty($nombre_usuario)){

$sql_nuevo = "SELECT count(*) FROM sist_geo_tb_sms 
WHERE ".$var."  AND leidos='0' AND mostrar_recibido='1'";
$res_sms_nuevo = pg_query($conexion,$sql_nuevo) or die("Error en la consulta SQL 2");
$row_sms_nuevo=pg_fetch_array($res_sms_nuevo);
$cuenta_sms_no_leidos=$row_sms_nuevo['count'];
  
}


$sql_nom1 = "SELECT nombre as nombre_quien FROM sist_geo_tb_usuario WHERE usuario = '$usuario'";
$resp = pg_query($conexion,$sql_nom1) or die ("Error en Nombre de Usuario");
$row1=pg_fetch_array($resp);
$nombre_quien=$row1['nombre_quien'];

if (!empty($nombre_quien)){

$sql_enviado = "SELECT count(*) FROM 
 sist_geo_tb_sms  WHERE  quien='$nombre_quien' AND mostrar_enviado='1'";
$res_sms_enviado = pg_query($conexion,$sql_enviado) or die("Error en la consulta SQL 1");
$row_sms_enviado=pg_fetch_array($res_sms_enviado);
$cuenta_sms_enviados=$row_sms_enviado['count'];

}


#-- Fin de query para contadores de sms--
#--******************sentencias para los Mensajes*************************************** --

#--Nuevos mensajes en parte de portal--
if($_SESSION["escribir"]==1){

       $var=" a.para= '".$_SESSION["departamento"]."'";

 }else if($_SESSION["escribir"]!=1){

       $var=' d.nombre = a.dirigido'; 
 }

    $sql_msm = "SELECT
    a.mensaje, a.fecha, a.para, a.gid, a.id_sms, a.quien, a.id_dept,
    a.dirigido, a.asunto, b.nom_dep, c.cod_catast, c.rif, c.nombre_civ, a.leidos, a.respondidos 
FROM  sist_geo_tb_sms a 
     INNER JOIN \"sist_geo_reg-departamento\" b ON b.id = a.id_dept
     INNER JOIN inmuebles c ON a.gid = c.gid
     INNER JOIN sist_geo_tb_usuario d ON d.usuario = '$usuario'
     
       WHERE  ".$var." AND a.leidos=0 
       ORDER BY  a.fecha DESC";
     $res_panel = pg_query($conexion,$sql_msm) or die("Error en la consulta SQL para mostrar mensaje");
     $row_panel = pg_numrows($res_panel);
 

#sql de mensajes
if ($_GET['pagina']=='mensajes'){

	if ($id_dept==1){
		$clasePadre = 'Geomatica';}
	elseif ($id_dept==4) {
		$clasePadre = 'Catastro';
	      }

	$claseHijo = 'Mensajes';

	if (isset($_POST['borrar_enviado']) && !empty($_POST['borrar_enviado']) && !empty($_POST['marca'])){
			foreach ($_POST['marca'] as $value) {
				$sql_ocultar_sms = "UPDATE sist_geo_tb_sms SET mostrar_enviado='0' WHERE (id_sms='$value')";
        		$res_ocultar_sms = pg_query($conexion,$sql_ocultar_sms) or die ("Error SQL en la modificaciones de mensajes");
				
			     }
 
	}elseif (isset($_POST['borrar_recibido']) && !empty($_POST['borrar_recibido']) && !empty($_POST['marca'])) {
			foreach ($_POST['marca'] as $value) {
				$sql_ocultar_sms = "UPDATE sist_geo_tb_sms SET mostrar_recibido='0' WHERE (id_sms='$value')";
        		$res_ocultar_sms = pg_query($conexion,$sql_ocultar_sms) or die ("Error SQL en la modificaciones de mensajes");
				
			     }
		}
}



#--- sentencia para modificar mensajes--

if($_GET['pagina']=='update_sms'){


$id_sms =pg_escape_string($_GET["id_sms"]);
$leido =pg_escape_string($_GET["leido"]);

    if (!empty($leido)){
		
		$sql_up_sms = "UPDATE sist_geo_tb_sms SET leidos='1' WHERE (id_sms='$id_sms')";
        $res_up_sms = pg_query($conexion,$sql_up_sms) or die ("Error SQL en la edicion  leidos");

    echo "<script type='text/javascript'>alert('El mensaje ha sido marcado como leido.'); window.location.href=\"portal.php?pagina=mensajes\";</script>";
  
} else{
		echo "<script type='text/javascript'>alert('No se pudo actualizar su mensaje.'); window.location.href=\"portal.php?pagina=mensajes\";</script>";

}

 
}


#---- Sentencia para insertar respuestas de los mensajes---
 if ($_GET['pagina']=='insert_resp'){

    $gid_sms=$_POST['gid'];
    $mensaje=$_POST['mensaje_resp'];
    $para=$_POST['para'];
    $dirigido=$_POST['dirigido'];
    $id_sms_padre=$_POST['id_sms_padre'];
    $quien=$_SESSION['nombre'];
    $dept=$_SESSION['id_dept'];
    $asunto=$_POST['asunto'];
    $leidos=0;
    $respondidos=1;
    $mostrar_enviado=1;
    $mostrar_recibido=1;
 

    $sql_resp_sms = "INSERT INTO sist_geo_tb_sms (mensaje, para, gid, quien, id_dept, dirigido, id_sms_padre, asunto, leidos, respondidos, mostrar_enviado, mostrar_recibido)
     VALUES ('$mensaje', '$para', '$gid_sms', '$quien', '$dept', '$dirigido', '$id_sms_padre', '$asunto', '$leidos', '$respondidos', '$mostrar_enviado', '$mostrar_recibido')";

     $sql_respuesta_id="UPDATE sist_geo_tb_sms SET respondidos='1' WHERE id_sms='$id_sms_padre'";
       
       $result_resp_sms = pg_query($conexion, $sql_resp_sms) or die ("Error en SQL al insertar los datos 01: " .pg_last_error());
       $filas_afect = pg_affected_rows($result_resp_sms);

       $result_resp_sms = pg_query($conexion, $sql_respuesta_id) or die ("Error en SQL al insertar los datos 02: " .pg_last_error());
      
     
	if(!empty($filas_afect)){
        
    echo "<script type='text/javascript'>alert('Mensaje enviado con exito!');
          window.location.href=\"portal.php?pagina=mensajes\";</script>";
      }else{

    echo "<script type='text/javascript'>alert('No se pudo responder su mensaje intentelo de nuevo'); window.location.href=\"portal.php?pagina=mensajes\";</script>";
       }  
}


#--**************sentencia para el ingreso de usuarios************--
if (isset($_POST['registrar_usuario']) && !empty($_POST['registrar_usuario'])) {

    $nombre = pg_escape_string($_POST['nombre']);
    $usuario1 = pg_escape_string($_POST['usuario']);
    $pass = pg_escape_string($_POST['pass']);
    $id_dept = $_POST['depto'];
    $estatus = $_POST['estatus'];
    $ip = $_POST['ip'];
    $id_tipo_usuario = $_POST['id_usua'];
    $id_perfil_usuario = $_POST['perfil_usuario'];

    // Modificar la consulta para no insertar manualmente el valor de id_user
    $sql_ingre_user = "INSERT INTO sist_geo_tb_usuario (pass, estatus, id_dept, usuario, ip, id_tipo_usuario, nombre, id_perfil_usuario)
    VALUES ('$pass', '$estatus', '$id_dept', '$usuario1', '$ip', '$id_tipo_usuario', '$nombre', '$id_perfil_usuario')";

    $result_ingre_user = pg_query($conexion, $sql_ingre_user) or die("Error en SQL al registrar al usuario: " . pg_last_error());
    $fil_afect_ingre_user = pg_affected_rows($result_ingre_user);

    if (!empty($fil_afect_ingre_user)) {
        $mostrar_modal = 1;
        $titulo = 'Registro de usuario';
        $contenido = '<div class="alert alert-success"> Usuario registrado con éxito. </div>';
    } else {
        $mostrar_modal = 1;
        $titulo = 'Registro de usuario';
        $contenido = '<div class="alert alert-danger"> Usuario no pudo ser registrado. </div>';
    }
}



#------sentencia para modificar usurios---#
$sql_modifi_user="SELECT a.nombre, a.usuario, a.estatus, a.ip, a.mac,
                  a.id_user, a.id_tipo_usuario, b.nom_dep, c.tipo_usuario
                 FROM 	sist_geo_tb_usuario a
                  INNER JOIN \"sist_geo_reg-departamento\" b ON b.id = a.id_dept
                  INNER JOIN sist_geo_tb_tipo_usuario c ON c.id_tipo_usuario = a.id_tipo_usuario
                 WHERE  a.usuario = '".$_SESSION['login']."'";
                 $res_modifi_user = pg_query($conexion,$sql_modifi_user) or die("Error en la consulta SQL para mostrar informacion de usuarios");


#----sentencia para ingresar modificaciones de usurios----#
if (!empty($_POST['modif_user']) && isset($_POST['modif_user'])) {

    // Escapamos los datos ingresados por el usuario para evitar inyecciones SQL
    $pass1 = pg_escape_string($_POST['pass']);
    $pass_nueva = pg_escape_string($_POST['pass_nueva']);
    $pass_nueva1 = pg_escape_string($_POST['pass_nueva1']);

    // Consulta para verificar la contraseña actual del usuario
    $sql_use = "SELECT usuario FROM sist_geo_tb_usuario 
                WHERE hashed_pass = crypt('$pass1', hashed_pass) 
                AND usuario = '" . $_SESSION['login'] . "'";

    $res_use = pg_query($conexion, $sql_use) or die("Error al seleccionar datos del usuario");

    // Verificamos cuántas filas han sido afectadas por la consulta anterior
    $filas_afectadas = pg_num_rows($res_use); // Cambié a pg_num_rows para contar filas

    // Verificamos si la nueva contraseña coincide con la confirmación y si la contraseña actual es válida
    if ($pass_nueva == $pass_nueva1 && $filas_afectadas > 0) {
        // Actualizamos la contraseña en la base de datos, encriptándola con un nuevo salt
        $sql_modif_pass = "UPDATE sist_geo_tb_usuario 
                           SET hashed_pass = crypt('$pass_nueva', gen_salt('bf')) 
                           WHERE id_user = '" . $_SESSION['id'] . "' 
                           AND hashed_pass = crypt('$pass1', hashed_pass)";

        $res_update_user = pg_query($conexion, $sql_modif_pass) or die("Error SQL en la modificación de password");

        // Mostramos el modal de éxito si la contraseña fue actualizada
        $mostrar_modal = 1;
        $titulo = 'Modificación de datos';
        $contenido = '<div class="alert alert-success">Clave modificada con éxito</div>';
    } else {
        // Mostramos un mensaje de error si la contraseña actual no es válida o las nuevas no coinciden
        $mostrar_modal = 1;
        $titulo = 'Modificación de datos';
        $contenido = '<div class="alert alert-danger">Clave actual incorrecta o clave nueva no coinciden</div>';
    }
}


#-----sentencias para los select asunto en modales ----
$sql_asun="SELECT * FROM tb_sms_asunto  ORDER BY id_asunto ASC";
    $resul_asun=pg_query($conexion,$sql_asun);


#----- sentencia para el select de departamento  -----
$sql_dept_portal="SELECT * FROM  \"sist_geo_reg-departamento\" ORDER BY id ASC";
    $resul_dept_portal=pg_query($conexion, $sql_dept_portal);

#sentencia para select de perfil de usuario en registro de usuario
$sql_perfil_usuario="SELECT * FROM  sist_geo_tb_perfiles_usuario ORDER BY id_perfil ASC";
$resul_perfil_usuario=pg_query($conexion, $sql_perfil_usuario);

 #--- sentencia para cargar variable y validar boton sms---
    $sql_bot_sms="SELECT * FROM sist_geo_tb_inm_modif";
    $result_bot_sms=pg_query($conexion,$sql_bot_sms);
    $row_bot_sms=pg_num_rows($result_bot_sms);


#---- sentnecia para nuevo mensaje desde monitores---

           
#--- Sentencia para insertar nuevos mensajes desde monitores---
  
  if ($_GET['pagina']=='nuevo_sms'){

    $gid_sms=$_POST['gid'];
    $mensaje=$_POST['mensaje'];
    $para=$_POST['nom_dep'];
    $dirigido=$_POST['para'];
    $quien=$_POST['quien'];
    $dept=$_POST['id_dept'];
    $asunto=strtoupper(pg_escape_string($_POST['asunto']));
    $respondidos=$leidos=0;
    $mostrar_recibido=$mostrar_enviado=1;
   
    $sql_nuevo_sms = "INSERT INTO sist_geo_tb_sms (mensaje, para, gid, quien, id_dept, dirigido, asunto, leidos, respondidos, mostrar_enviado, mostrar_recibido)
     VALUES ('$mensaje', '$para', '$gid_sms', '$quien', '$dept', '$dirigido','$asunto', '$leidos', '$respondidos', '$mostrar_enviado', '$mostrar_recibido')";
       
       $result_nuevo_sms = pg_query($conexion, $sql_nuevo_sms) or die ("Error en SQL al insertar los datos 01:" .pg_last_error());
         
    $filas_afectadas=pg_affected_rows($result_nuevo_sms);
      if (!empty($filas_afectadas)){ 
           echo "<script type='text/javascript'>alert('Mensaje enviado con exito!');
           window.location.href=\"portal.php?pagina=ver&gid=$gid_sms&update=".($_SESSION['id_dept']==5 ? 'Geomatica' : 'Catastro')."\";</script>";
        }
        else{ 
           echo "<script type='text/javascript'>alert('No se pudo enviar su Mensaje!');
           window.location.href=\"portal.php?pagina=ver&gid=$gid_sms&update=".($_SESSION['id_dept']==5 ? 'Geomatica' : 'Catastro')."\";</script>";
        }
       
    }

       
 ?>