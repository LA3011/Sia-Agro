<?php
session_start();
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();

$Id_Perfil = $_GET['id'];

$valid = array();
$sqlt = 'SELECT * FROM usuarios WHERE Id_Perfilp = ' . $Id_Perfil . ' ';
$resultt = mysqli_query($conn, $sqlt);
while($filatt = mysqli_fetch_array($resultt)){
	array_push($valid,$filatt['Usuario']);
}

$size_valid = sizeof($valid);

if($size_valid > 0) {
	$_SESSION['validacion_permisos'] = "VALIDACION #0001: <br> SE ENCUENTO USUARIO(S) BAJO ESE PERFIL... <br> <p>Asegurese que Todos los Usuarios 							Tengan un Perfil diferente al que desea Elimar, para poder realizar esta operacion</p>";

}elseif($Id_Perfil == 1){
   $_SESSION['validacion_permisos'] = "VALIDACION #0003: <br> PERFIL NO ADMITIDO... <br> <p>Este perfil no puede ser 'Eliminado' 
                      por Seguridad</p>";
}else{

$sql1 = "DELETE FROM perfil WHERE Id_Perfil = $Id_Perfil";
mysqli_query($conn, $sql1);

$sql2 = "DELETE FROM perfil_programa WHERE Id_Perfil = $Id_Perfil";
mysqli_query($conn, $sql2);

$sql3 = "DELETE FROM perfil_subprograma WHERE Id_Perfil = $Id_Perfil";
mysqli_query($conn, $sql3);

$sql4 = "DELETE FROM privilegios WHERE Id_Perfil = $Id_Perfil";
mysqli_query($conn, $sql4);

$sql4 = "DELETE FROM perfil_modulo WHERE Id_Perfil = $Id_Perfil";
mysqli_query($conn, $sql4);

  $_SESSION['mensaje'] = "El registro se eliminó con éxito.";
}

header("location: ../permisos_agro.php");


?>