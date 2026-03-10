<?php
    $servidor='host=localhost';
    $database='dbname=marino_db_spatial';
    $usuario='user=postgres';
    $clave_server='password=a3421842';
    $port='port=5432';
    //echo $usuario." ".$clave_server." ".$database." ".$port." ".$servidor."";
    $conexion = pg_connect($usuario." ".$clave_server." ".$database." ".$port." ".$servidor."");
    if ($conexion){
        //echo "conectado";
    }
    if (!$conexion) {
        $errormessage = pg_last_error(); 
        echo "Ocurrio un error. " . $errormessage;
        exit;
    }
?>
