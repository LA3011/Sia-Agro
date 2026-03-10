<?php
session_start();
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();

if(isset($_POST['id_Perfil'])){ $id_Perfil = $_POST['id_Perfil']; }
if(isset($_POST['nombre_perfil'])){ $nombre_perfil = $_POST['nombre_perfil']; }
if(isset($_POST['estado'])){ $estado = $_POST['estado']; }

$contIssetModul = 0;
$contIssetSubProgram = 0;
$Sub_program = [];
$program[0] = null;
$Sub_program[0] = null;
// $modulo[0] = null;

// programas (string)
$cp = array();
$cp[0] = "ANIMALES";
$cp[1] = "CULTIVOS";
$cp[2] = "VENTA";
$cp[3] = "FINANZAS";
$cp[4] = "RECURSOS_HUMANOS";
$cp[5] = null;
$cp[6] = "CONFIGURACION";
// sub-programa (string)
$csp = array();
$csp[0] = "general_animales";
$csp[1] = "movimiento_animal";
$csp[2] = "general_cultivos";
$csp[3] = "seguimiento_cultivos";
$csp[4] = "venta";
$csp[5] = "general_finanzas";
$csp[6] = "costos";
$csp[7] = "empleados";
$csp[8] = "ajustes";
// modulos (string)
$mdl = array();
$mdl[1] = "registro_animales";
$mdl[2] = "reproducciones_animales";
$mdl[3] = "registro_potreros";
$mdl[4] = "actividad_animal";
$mdl[5] = "pastoreo";
$mdl[6] = "insumos_animal";
$mdl[7] = "siembra";
$mdl[8] = "espacios";
$mdl[9] = "actividades";
$mdl[10] = "control_fertilizante";
$mdl[11] = "control_plagas";
$mdl[12] = "insumos_cultivo";
$mdl[13] = null;
$mdl[14] = "orden_salida";
$mdl[15] = "animales_venta";
$mdl[16] = "animales";
$mdl[17] = "cultivo";
$mdl[18] = "costo_fijo";
$mdl[19] = "costo_variable";
$mdl[20] = "usuarios";
$mdl[21] = "permisos";
$mdl[22] = "bitacora";



// --- PROGRAMAS (asignacion)
for ($i=0; $i<7 ; $i++) { 
  if ( (isset($_POST[$cp[$i]])) && ($cp[$i] != null) ){
    $program[$i] = $i + 1;
  }
}
// --- SUB-PROGRAMAS (asignacion)
for ($i=0; $i<9 ; $i++) { 
  if (isset($_POST[$csp[$i]])){
    $Sub_program[$i] = $i + 1;
  }else{
    $contIssetSubProgram++;
  }
}
// --- MODULOS (asignacion)
for ($i=1; $i<23 ; $i++) { 
  if ( (isset($_POST[$mdl[$i]])) ){
    $modulo[$i] = $i;
  }else{
    $contIssetModul++;
  }
}

// --- COMBOS (aignacion)
if ( (isset($_POST['general_animales']) && (isset($_POST['movimiento_animal'])) )) {    $program[0] = 1; }
if ( (isset($_POST['general_cultivos']) && (isset($_POST['seguimiento_cultivos'])) )) { $program[1] = 2; }
if ( (isset($_POST['venta']) )) {                                                       $program[2] = 3; }
if ( (isset($_POST['general_finanzas']) && (isset($_POST['costos'])) )) {               $program[3] = 4; }
if ( (isset($_POST['empleados']) )) {                                                   $program[4] = 5; }
if ( (isset($_POST['ajustes']) )) {                                                     $program[6] = 7; }

if ( (isset($program[0])) ) {
  $Sub_program[0] = 1; 
  $Sub_program[1] = 2;
}
if(isset($_POST['general_animales'])){
  $modulo[1] = 1; 
  $modulo[2] = 2;
  $modulo[3] = 3;
}
if(isset($_POST['movimiento_animal'])){
  $modulo[4] = 4;
  $modulo[5] = 5;
  $modulo[6] = 6;
}
if(isset($_POST['seguimiento_cultivos'])){
  $modulo[7] = 7;
  $modulo[8] = 8;
  $modulo[9] = 9;
}
if(isset($_POST['general_cultivos'])){
  $modulo[10] = 10;
  $modulo[11] = 11;
  $modulo[12] = 12;
}
if(isset($_POST['venta'])){
  $modulo[14] = 14;
  $modulo[15] = 15;
}
if(isset($_POST['general_finanzas'])){
  $modulo[16] = 16;
  $modulo[17] = 17;
}
if(isset($_POST['costos'])){
  $modulo[18] = 18;
  $modulo[19] = 19;
}
if(isset($_POST['ajustes'])){
  $modulo[20] = 20;
  $modulo[21] = 21;
}
if(isset($_POST['CONFIGURACION'])){
  $Sub_program[8] = 9;
}
if(isset($_POST['RECURSOS_HUMANOS'])){
  $Sub_program[7] = 8;
}
if(isset($_POST['empleados'])){
  $program[4] = 5;
}  
// --- ALL (asignacion)
if (isset($programaG)) {
  for ($i=0; $i<7 ; $i++) { 
    if($i != 5){
      $program[$i] = $i + 1;        
    }
  }
  for ($j=0; $j<9 ; $j++) { 
    $Sub_program[$j] = $j + 1;
  }
  for ($k=1; $k<23 ; $k++) { 
    if ($k != 13) {
      $modulo[$k] = $k;
    }
  }
}
// --- PRIVILEGIOS (asignacion)
if(isset($_POST['ver'])){ $ver = $_POST['ver']; }
if(!isset($_POST['ver'])){ $ver = "false"; }
if(isset($_POST['editar'])){ $editar = $_POST['editar']; }
if(!isset($_POST['editar'])){ $editar = "false"; }
if(isset($_POST['eliminar'])){ $eliminar = $_POST['eliminar']; }
if(!isset($_POST['eliminar'])){ $eliminar = "false"; }
if(isset($_POST['imprimir'])){ $imprimir = $_POST['imprimir']; }
if(!isset($_POST['imprimir'])){ $imprimir = "false"; }
if(isset($_POST['agregar'])){ $agregar = $_POST['agregar']; }
if(!isset($_POST['agregar'])){ $agregar = "false"; }


// --- VALIDACIONES
  // "SIN MODULOS/SUBP" -- POR ENDE SE SALTARA EL REGISTRO DEL "PERFIL"
  if ( ($contIssetModul == 22) && ($contIssetSubProgram == 0) ){
    $_SESSION['validacion_permisos'] = "VALIDACION #0005: <br> PERFIL SIN ASIGNACIONES... <br> <p>Asegurese que el 
    perfil Obtenga como minimo 1 Modulo/Sub_programa, para poder Crear ese Perfil.</p>";
    header('location: ../permisos_agro.php');
    exit();
  }

// ---
// $num_opc    = sizeof($program);
// $num_Subopc = sizeof($Sub_program);

// ----------------------------- ESTADO -------------------------------------------------
$sql = "INSERT INTO perfil (nombre_perfil, estado) VALUES ('$nombre_perfil','$estado')";
mysqli_query($conn, $sql);
$id_perfil = mysqli_insert_id($conn);
//---------------------------  PROGRAMAS -----------------------------------------
  for($i=0; $i<7; $i++){
    if( $program[$i] != 0 ){
      $sql2 = "INSERT INTO perfil_programa (Id_Perfil, Id_Programa) VALUES ('$id_perfil','$program[$i]')";
      mysqli_query($conn, $sql2);
      // $cont++;
    }
  }

//---------------------------- SUB-PROGRAMAS ----------------------------------------
  for($i2=0; $i2<12; $i2++){
    if($Sub_program[$i2] != 0){
      $sql2 = "INSERT INTO perfil_subprograma (Id_Perfil, Id_Subprograma) VALUES ('$id_perfil','$Sub_program[$i2]')";
      mysqli_query($conn, $sql2);
      // $cont++;
    }
  }
//---------------------------- MODULOS ----------------------------------------
  for($z=1; $z<=22; $z++){
    if(isset($modulo[$z])){
      $sql2 = "INSERT INTO perfil_modulo (Id_Perfil, Id_Modulo) VALUES ('$id_perfil','$modulo[$z]')";
      mysqli_query($conn, $sql2);
    }
  }
//---------------------------- PRIVILEGIOS ------------------------------------------
  $sql3 = "INSERT INTO privilegios (id_Perfil, ver, editar, eliminar, imprimir, agregar) VALUES 
('$id_perfil','$ver','$editar','$eliminar','$imprimir','$agregar')";
mysqli_query($conn, $sql3);



$_SESSION['mensaje'] = "El registro se guardó con éxito.";
header("location: ../permisos_agro.php");
?>


