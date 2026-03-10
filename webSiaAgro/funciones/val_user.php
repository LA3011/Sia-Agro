<?php
session_name("sisgeo");
session_start();
include("conexion/conn.php");

$user = $_POST['login'];
$pass = $_POST['password'];
$redireccion = header('location:../login.php'); 

$strsql = "SELECT
             id_user,
             estatus,
             id_dept,
             usuario,
             nombre,
             id_tipo_usuario,
             id_perfil_usuario
           FROM
             sist_geo_tb_usuario
           WHERE
             usuario = '$user'
             AND pass = '$pass'
             AND estatus = 1";

$result = pg_query($conexion, $strsql);

if ($row = pg_fetch_array($result)) {
    $_SESSION["login"] = $row['usuario'];
    $_SESSION["admin"] = $row[0];
    $_SESSION['id'] = $row['id_user'];
    $_SESSION['id_tipo_usuario'] = $row['id_tipo_usuario'];
    $_SESSION['id_dept'] = $row['id_dept'];
    $_SESSION['nombre'] = $row['nombre'];
    $_SESSION['id_perfil_usuario'] = $row['id_perfil_usuario'];

        if (!empty($user)) {
            $sql_res = "SELECT * FROM sist_geo_tb_permisos WHERE id_dept = '".$_SESSION['id_dept']."'";
            $resulta = pg_query($conexion, $sql_res); 

            while ($fila = pg_fetch_array($resulta)) {
                $_SESSION["nivel_uno"] = $fila['nivel_uno'];
                $_SESSION["nivel_dos"] = $fila['nivel_dos'];
                $_SESSION["nivel_tres"] = $fila['nivel_tres'];
                $_SESSION["nivel_cuatro"] = $fila['nivel_cuatro'];
                $_SESSION["nivel_cinco"] = $fila['nivel_cinco'];
                $_SESSION["nivel_seis"] = $fila['nivel_seis'];
                $_SESSION["nivel_siete"] = $fila['nivel_siete'];
                $_SESSION["nivel_ocho"] = $fila['nivel_ocho'];
                $_SESSION["nivel_nueve"] = $fila['nivel_nueve'];
                $_SESSION["nivel_diez"] = $fila['nivel_diez'];
                $_SESSION["nivel_once"] = $fila['nivel_once'];
            }

            $sql_a = "SELECT * FROM sist_geo_tb_tipo_usuario WHERE id_tipo_usuario = '".$_SESSION['id_tipo_usuario']."'";
            $resultado = pg_query($conexion, $sql_a); 

            while ($fila1 = pg_fetch_array($resultado)) {
                $_SESSION["tipo_usuario"] = $fila1['tipo_usuario'];
                $_SESSION["leer"] = $fila1['leer'];
                $_SESSION["escribir"] = $fila1['escribir'];
                $_SESSION["borrar"] = $fila1['borrar'];
            }

            $sql_a = "SELECT * FROM sist_geo_tb_perfiles_usuario WHERE id_perfil = '".$_SESSION['id_perfil_usuario']."'";
            $resultado = pg_query($conexion, $sql_a); 

            while ($fila2 = pg_fetch_array($resultado)) {
                $_SESSION["nombre_perfil"] = $fila2['nombre_perfil'];
                $_SESSION["monitor_catastro"] = $fila2['monitor_catastro'];
                $_SESSION["agregar_inmueble"] = $fila2['agregar_inmueble'];
                $_SESSION["monitor_geomatica"] = $fila2['monitor_geomatica'];
                $_SESSION["avales"] = $fila2['avales'];
                $_SESSION["monografias"] = $fila2['monografias'];
                $_SESSION["bitacora"] = $fila2['bitacora'];
                $_SESSION["registro_de_visitas"] = $fila2['registro_de_visitas'];
                $_SESSION["registro_solicitudes"] = $fila2['registro_solicitudes'];
                $_SESSION["registrar_actividad"] = $fila2['registrar_actividad'];
            }

            $sql_a = "SELECT nom_dep FROM \"sist_geo_reg-departamento\" WHERE id = '".$_SESSION['id_dept']."'";
            $resultado = pg_query($conexion, $sql_a);

            while ($fila = pg_fetch_array($resultado)) {
                $_SESSION["departamento"] = $fila['nom_dep'];
            }

            header('location:../portal.php');
            exit(); 
        } else {
            echo $redireccion;
        }
    } else {
        echo "Validación fallida";
    }

?>
