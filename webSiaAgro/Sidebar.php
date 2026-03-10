  <?php 

  include_once("conexion/conexion.php");
  $conn = cconexion::ConexionBD();
  
  $id_Usuario = $_SESSION['Id_Perfilp']; 
  
  $lista = array();
  $ffgg  = array();
  
  $lista2 = array();
  $ffgg2  = array();
  
  /* ------------------------------------------------- PROGRAMAS ------------------------------------------------------------------*/
  $a = 0;
  $sql = "SELECT usuarios.*, perfil_programa.\"Id_Programa\" 
        FROM usuarios
        INNER JOIN perfil_programa ON perfil_programa.\"Id_Perfil\" = usuarios.\"Id_Perfilp\"
        WHERE perfil_programa.\"Id_Perfil\" = :id_Usuario
        GROUP BY usuarios.\"Id_Usuario\", usuarios.\"Id_Perfilp\", usuarios.\"Usuario\", usuarios.\"Clave\", usuarios.\"Nombre\", usuarios.\"Apellido\", usuarios.tipo_usuario, usuarios.\"Respuesta_1\", usuarios.\"Respuesta_2\", usuarios.\"Respuesta_3\", usuarios.\"Habilitado\", usuarios.\"Fecha\", usuarios.trial413, perfil_programa.\"Id_Programa\"";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_Usuario', $id_Usuario, PDO::PARAM_INT); 
$stmt->execute();
$resultado_select = $stmt->fetchAll(PDO::FETCH_ASSOC);

      
  foreach ($resultado_select as $fifi) {
      if(isset($fifi['Id_Programa'])) {
          if($fifi['Id_Programa'] == 1) {
              $save2 = "ANIMALES";
          } elseif($fifi['Id_Programa'] == 2) {
              $save3 = "CULTIVOS";
          } elseif($fifi['Id_Programa'] == 3) {
              $save4 = "VENTA";
          } elseif($fifi['Id_Programa'] == 4) {
              $save5 = "FINANZAS";
          } elseif($fifi['Id_Programa'] == 5) {
              $save6 = "EMPLEADOS";
          } elseif($fifi['Id_Programa'] == 7) {
              $save7 = "CONFIGURACION";
          }
      } else {
          echo "La columna 'Id_Programa' no está presente en el conjunto de resultados.";
      }
  }
  
    
  /* ------------------------------------------------------------------------------------------------------------------------------*/
  
  /* ----------------------------------------------- SUB-PROGRAMAS ----------------------------------------------------------------*/
  $sql = "SELECT usuarios.*, perfil_subprograma.\"Id_Subprograma\" 
  FROM usuarios
  INNER JOIN perfil_subprograma ON perfil_subprograma.\"Id_Perfil\" = usuarios.\"Id_Usuario\"
  WHERE perfil_subprograma.\"Id_Perfil\" = :idUsuario
  GROUP BY usuarios.\"Id_Usuario\", usuarios.\"Id_Perfilp\", usuarios.\"Usuario\", usuarios.\"Clave\", usuarios.\"Nombre\", usuarios.\"Apellido\", usuarios.tipo_usuario, usuarios.\"Respuesta_1\", usuarios.\"Respuesta_2\", usuarios.\"Respuesta_3\", usuarios.\"Habilitado\", usuarios.\"Fecha\", usuarios.trial413, perfil_subprograma.\"Id_Subprograma\"";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':idUsuario', $id_Usuario, PDO::PARAM_INT);
$stmt->execute();
$resultado_select = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($resultado_select as $fifi2) { 
array_push($ffgg2, $fifi2['Id_Subprograma']);

if($fifi2['Id_Subprograma'] == 1){
  $save2_1 = "general_animales";
}elseif($fifi2['Id_Subprograma'] == 2){
  $save2_2 = "movimiento_animal";
}elseif($fifi2['Id_Subprograma'] == 4){
  $save2_4 = "seguimiento_cultivos";
}elseif($fifi2['Id_Subprograma'] == 3){
  $save2_3 = "general_cultivos";
}elseif($fifi2['Id_Subprograma'] == 5){
  $save2_5 = "venta";
}elseif($fifi2['Id_Subprograma'] == 6){
  $save2_6 = "general_finanzas";
}elseif($fifi2['Id_Subprograma'] == 7){
  $save2_7 = "costos";
}elseif($fifi2['Id_Subprograma'] == 8){
  $save2_8 = "empleados";
}elseif($fifi2['Id_Subprograma'] == 10){
  $save2_10 = "ajustes";
}
}

  /* ------------------------------------------------------------------------------------------------------------------------------*/
  
  
  /* -------------------------------------------------- MODULOS ------------------------------------------------------------------*/
  $moduls = array();
  
  $c = 1;
  $sql3 = "SELECT perfil_modulo.\"Id_Perfil\", modulo.nombre_modulo  
           FROM perfil_modulo 
           INNER JOIN modulo ON perfil_modulo.\"Id_Modulo\" = modulo.\"Id_Modulos\" 
           WHERE perfil_modulo.\"Id_Perfil\" = :idUsuario";
  $stmt = $conn->prepare($sql3);
  $stmt->bindValue(':idUsuario', $id_Usuario, PDO::PARAM_INT);
  $stmt->execute();
  $resultado_select2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
  $moduls = [];
  foreach ($resultado_select2 as $plp) { 
      switch($plp['nombre_modulo']) {
          case "registro_animales":
              $moduls[1] = "registro_animales";
              break;
          case "reproducciones_animales":
              $moduls[2] = "reproducciones_animales";
              break;
          case "registro_potreros":
              $moduls[3] = "registro_potreros";
              break;
          case "actividad_animal":
              $moduls[4] = "actividad_animal";
              break;
          case "pastoreo":
              $moduls[5] = "pastoreo";
              break;
          case "insumos_animal":
              $moduls[6] = "insumos_animal";
              break;
          case "siembra":
              $moduls[7] = "siembra";
              break;
          case "espacios":
              $moduls[8] = "espacios";
              break;
          case "actividades":
              $moduls[9] = "actividades";
              break;
          case "control_fertilizante":
              $moduls[10] = "control_fertilizante";
              break;
          case "control_plagas":
              $moduls[11] = "control_plagas";
              break;
          case "insumos_cultivo":
              $moduls[12] = "insumos_cultivo";
              break;
          case "orden_salida":
              $moduls[14] = "orden_salida";
              break;
          case "animales_venta":
              $moduls[15] = "animales_venta";
              break;
          case "animales":
              $moduls[16] = "animales";
              break;
          case "cultivo":
              $moduls[17] = "cultivo";
              break;
          case "costo_fijo":
              $moduls[18] = "costo_fijo";
              break;
          case "costo_variable":
              $moduls[19] = "costo_variable";
              break;
          case "usuarios":
              $moduls[20] = "usuarios";
              break;
          case "roles":
              $moduls[21] = "roles";
              break;
          case "parametros":
              $moduls[22] = "parametros";
              break;
          case "resumen":
              $moduls[23] = "resumen";
              break;
          case "accesos":
              $moduls[24] = "accesos";
              break;
          case "modulos":
              $moduls[25] = "modulos";
              break;
          case "gestion_modulos":
              $moduls[26] = "gestion_modulos";
              break;
          case "gestion_finca":
              $moduls[27] = "gestion_finca";
              break;
          case "costos_mano_obra":
              $moduls[28] = "costos_mano_obra";
              break;
          case "configuraciones":
              $moduls[29] = "configuraciones";
              break;
          case "control_animales":
              $moduls[30] = "control_animales";
              break;
      }
  }
  
  /* ------------------------------------------------------------------------------------------------------------------------------*/
  
  $conn = null;
  ?>
  <aside id="sidebar" class="sidebar" style="background-color:#1c2833;">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item" style="background-color: #1c2833;">
          <div class="sb-sidenav-menu-heading" style="color: #abb2b9; font-family: Arial; font-weight: bold;">INICIO</div>
          <li class="nav-item" style="background-color: #1c2833;"> 
            <a class="nav-link" href="inicio.php" style="background-color: #1c2833;">
              <i class="bi bi-house-door"></i>
              <span style="color: #abb2b9; font-family: Arial; font-weight: bold;">inicio</span>
            </a>
          </li>
        </li>

      <?php  if( (((isset($save2)) && $save2 == "ANIMALES") OR (isset($save2_1)) OR (isset($save2_2))) or isset($moduls[1]) or isset($moduls[2]) or isset($moduls[3]) ){ ?>
        <li class="nav-item" style="background-color: #1c2833;">
          <div class="sb-sidenav-menu-heading" style="color: #abb2b9; font-family: Arial; font-weight: bold; background-color: #1c2833;">ANIMALES</div>

        <?php if(((isset($save2_1))&&$save2_1=="general_animales") or isset($moduls[1]) or isset($moduls[2]) or isset($moduls[3])){ ?>
            <li class="nav-item" style="background-color: #1c2833;"> 
              <a class="nav-link collapsed" data-bs-target="#components-nav5" data-bs-toggle="collapse" href="#" style="background-color: #1c2833;">
                <span style="color: #abb2b9; font-family: Arial; font-weight: bold;">General</span><i class="bi bi-chevron-down ms-auto"></i>
              </a>
              <ul id="components-nav5" class="nav-content collapse" data-bs-parent="#sidebar-nav" style="background-color: #1c2833;">
                <?php  if( (isset($moduls[1])) ){ ?>
                  <li style="background-color: #1c2833;">
                    <a href="animales.php" style="color: #abb2b9;">
                      <i class="bi bi-circle"></i><span>Registro de animales</span>
                    </a>
                  </li>
                <?php } ?>
                <?php  if( (isset($moduls[2])) ){ ?>
                <li style="background-color: #1c2833;">
                  <a href="reproducciones.php" style="color: #abb2b9;">
                    <i class="bi bi-circle"></i><span>Reproducciones Animales</span>
                  </a>
                </li>
                <?php } ?>
                <?php  if( (isset($moduls[3])) ){ ?>
                  <li style="background-color: #1c2833;">
                    <a href="calculo_potreros.php" style="color: #abb2b9;">
                      <i class="bi bi-circle"></i><span>Registro de Potreros</span>
                    </a>
                  </li>
                <?php } ?>
              </ul>
            </li>
          <?php } ?>

          <?php  if( ((isset($save2_2)) && $save2_2 == "movimiento_animal") or isset($moduls[4]) or isset($moduls[5]) or isset($moduls[6]) ){ ?>
            <li class="nav-item" style="background-color: #1c2833;"> 
              <a class="nav-link collapsed" data-bs-target="#components-nav6" data-bs-toggle="collapse" href="#" style="background-color: #1c2833;">
                <span style="color: #abb2b9; font-family: Arial; font-weight: bold;">Movimiento Animal</span><i class="bi bi-chevron-down ms-auto"></i>
              </a>
              <ul id="components-nav6" class="nav-content collapse" data-bs-parent="#sidebar-nav" style="background-color: #1c2833;">

                <?php  if( isset($moduls[4]) ){ ?>
                  <li style="background-color: #1c2833;">
                    <a href="movimiento_animal.php" style="color: #abb2b9;">
                      <i class="bi bi-circle"></i><span>Actividad  animal</span>
                    </a>
                  </li>
                <?php } ?>

                <?php  if( (isset($moduls[5])) ){ ?>
                  <li style="background-color: #1c2833;">
                    <a href="prueba.php" style="color: #abb2b9;">
                      <i class="bi bi-circle"></i><span>Pastoreo</span>
                    </a>
                  </li>
                <?php } ?>

                <?php  if( (isset($moduls[6])) ){ ?>
                  <li style="background-color: #1c2833;">
                    <a href="insumos_animal.php" style="color: #abb2b9;">
                      <i class="bi bi-circle"></i><span>Insumos</span>
                    </a>
                  </li>
                <?php } ?>
              </ul>
            </li>
          <?php } ?>
        </li>
      <?php } ?>


      <!-- cultivo -->
      <?php if( ((isset($save3)) && $save3 == "CULTIVOS") OR isset($save2_3) OR isset($save2_4) or isset($moduls[7]) or isset($moduls[8]) or isset($moduls[9]) or isset($moduls[10]) or isset($moduls[11]) or isset($moduls[12]) ){ ?>

        <li class="nav-item" style="background-color: #1c2833;">
          <div class="sb-sidenav-menu-heading" style="color: #abb2b9; font-family: Arial; font-weight: bold; background-color: #1c2833;">CULTIVOS</div>

          <?php if( ((isset($save2_4)) && $save2_4 == "seguimiento_cultivos") or isset($moduls[7]) or isset($moduls[8]) or isset($moduls[9])){?>
            <li class="nav-item" style="background-color: #1c2833;"> 
              <a class="nav-link collapsed" data-bs-target="#components-nav7" data-bs-toggle="collapse" href="#" style="background-color: #1c2833;">
                <span style="color: #abb2b9; font-family: Arial; font-weight: bold;">Seguimiento</span><i class="bi bi-chevron-down ms-auto"></i>
              </a>
              <ul id="components-nav7" class="nav-content collapse" data-bs-parent="#sidebar-nav" style="background-color: #1c2833;">
                <?php  if( isset($moduls[7]) ){ ?>
                <li style="background-color: #1c2833;">
                  <a href="cultivos.php" style="color: #abb2b9;">
                    <i class="bi bi-circle"></i><span>Siembra</span>
                  </a>
                </li>
                <?php } ?>
                <?php  if( isset($moduls[8]) ){ ?>
                <li style="background-color: #1c2833;">
                  <a href="Espacios.php" style="color: #abb2b9;">
                    <i class="bi bi-circle"></i><span>Espacios</span>
                  </a>
                </li>
                <?php } ?>
                <?php  if( isset($moduls[9]) ){ ?>
                <li style="background-color: #1c2833;">
                  <a href="actividades_cultivos.php" style="color: #abb2b9;">
                    <i class="bi bi-circle"></i><span>Actividades</span>
                  </a>
                </li>
              <?php } ?>
              </ul>
            </li>
          <?php } ?>

          <?php  if( ((isset($save2_3)) && $save2_3 == "general_cultivos") or isset($moduls[10]) or isset($moduls[11]) or isset($moduls[12])){ ?>
            <li class="nav-item" style="background-color: #1c2833;"> 
              <a class="nav-link collapsed" data-bs-target="#components-nav8" data-bs-toggle="collapse" href="#" style="background-color: #1c2833;">
                <span style="color: #abb2b9; font-family: Arial; font-weight: bold;">General</span><i class="bi bi-chevron-down ms-auto"></i>
              </a>
              <ul id="components-nav8" class="nav-content collapse" data-bs-parent="#sidebar-nav" style="background-color: #1c2833;">
                <?php  if( isset($moduls[10]) ){ ?>
                <li style="background-color: #1c2833;">
                  <a href="control_fertilizante.php" style="color: #abb2b9;">
                    <i class="bi bi-circle"></i><span>Control Fertilizante</span>
                  </a>
                </li>
                <?php } ?>
                <?php  if( isset($moduls[11]) ){ ?>
                <li style="background-color: #1c2833;">
                  <a href="control_plagas.php" style="color: #abb2b9;">
                    <i class="bi bi-circle"></i><span>Control Plagas</span>
                  </a>
                </li>
                <?php } ?>
                <?php  if( isset($moduls[12]) ){ ?>
                <li style="background-color: #1c2833;">
                  <a href="insumos.php" style="color: #abb2b9;">
                    <i class="bi bi-circle"></i><span>Insumos</span>
                  </a>
                </li>
                <?php } ?>
              </ul>
            </li>
          <?php } ?>

        </li>
      <?php }?>

      <?php  // if( ((isset($save6)) && $save6 == "EMPLEADOS") OR (isset($save2_8)) ){ ?>
        <!---- empleados --------->
        <li class="nav-item" style="background-color: #1c2833;">
          <div class="sb-sidenav-menu-heading" style="color: #abb2b9; font-family: Arial; font-weight: bold;">GEOPOSICIONAMIENTO SATELITAL</div>
          <li class="nav-item" style="background-color: #1c2833;"> 
            <a class="nav-link" href="GeoAgri_Gestion.php" style="background-color: #1c2833;">
              <span style="color: #abb2b9; font-family: Arial; font-weight: bold;">Poligonales</span>
            </a>
          </li>
        </li>
      <?php // } ?>


      <!------- Ventas -------->
      <?php  if( (((isset($save4)) && $save4 == "VENTA") OR (isset($save2_5))) or isset($moduls[14]) or isset($moduls[15]) ){ ?>
        <li class="nav-item" style="background-color: #1c2833;">
          <div class="sb-sidenav-menu-heading" style="color: #abb2b9; font-family: Arial; font-weight: bold; background-color: #1c2833;">VENTA</div>
          <li class="nav-item" style="background-color: #1c2833;"> 
            <a class="nav-link collapsed" data-bs-target="#components-nav9" data-bs-toggle="collapse" href="#" style="background-color: #1c2833;">
              <span style="color: #abb2b9; font-family: Arial; font-weight: bold;">Ventas</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav9" class="nav-content collapse" data-bs-parent="#sidebar-nav" style="background-color: #1c2833;">
              <?php  if( isset($moduls[14]) ){ ?>
              <li style="background-color: #1c2833;">
                <a href="tabla_orden_salida.php" style="color: #abb2b9;">
                  <i class="bi bi-circle"></i><span>Orden Salida</span>
                </a>
              </li>
              <?php } ?>
              <?php  if( isset($moduls[15]) ){ ?>
              <li style="background-color: #1c2833;">
                <a href="galeria.php" style="color: #abb2b9;">
                  <i class="bi bi-circle"></i><span>Animales en Venta</span>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li>
        </li>
      <?php } ?>
      <!---- FINANZAS ------>
      <?php if((((isset($save5)) && $save5 == "FINANZAS") OR (isset($save2_6)) OR (isset($save2_7))) or isset($moduls[16]) or isset($moduls[17]) or isset($moduls[18]) or isset($moduls[19]) ){ ?>
        <li class="nav-item" style="background-color: #1c2833;">
          <div class="sb-sidenav-menu-heading" style="color: #abb2b9; font-family: Arial; font-weight: bold; background-color: #1c2833;">FINANZAS</div>
          <?php  if( (((isset($save2_6)) && $save2_6 == "general_finanzas")) or isset($moduls[16]) or isset($moduls[17])){ ?>
            <li class="nav-item" style="background-color: #1c2833;"> 
              <a class="nav-link collapsed" data-bs-target="#components-nav10" data-bs-toggle="collapse" href="#" style="background-color: #1c2833;">
                <span style="color: #abb2b9; font-family: Arial; font-weight:bold;">General</span><i class="bi bi-chevron-down ms-auto"></i>
              </a>
              <ul id="components-nav10" class="nav-content collapse" data-bs-parent="#sidebar-nav" style="background-color: #1c2833;">
                <?php  if( isset($moduls[16]) ){ ?>
                <li style="background-color: #1c2833;">
                  <a href="ganancia_inversion.php" style="color: #abb2b9;">
                    <i class="bi bi-circle"></i><span>Animales</span>
                  </a>
                </li>
                <?php } ?>
                <?php  if( isset($moduls[17]) ){ ?>
                <li style="background-color: #1c2833;">
                  <a href="inversion_cultivos.php" style="color: #abb2b9;">
                    <i class="bi bi-circle"></i><span>Cultivos</span>
                  </a>
                </li>
                <?php } ?>
              </ul>
            </li>
          <?php } ?>
          <?php  if( ((isset($save2_7)) && $save2_7 == "costos") or isset($moduls[18]) or isset($moduls[19])){ ?>
            <li class="nav-item" style="background-color: #1c2833;"> 
              <a class="nav-link collapsed" data-bs-target="#components-nav11" data-bs-toggle="collapse" href="#" style="background-color: #1c2833;">
                <span style="color: #abb2b9; font-family: Arial; font-weight: bold;">Costos</span><i class="bi bi-chevron-down ms-auto"></i>
              </a>
              <ul id="components-nav11" class="nav-content collapse" data-bs-parent="#sidebar-nav" style="background-color: #1c2833;">
                <?php  if( isset($moduls[18]) ){ ?>
                  <li style="background-color: #1c2833;">
                    <a href="costo_fijo.php" style="color: #abb2b9;">
                      <i class="bi bi-circle"></i><span>Costo Fijo</span>
                    </a>
                  </li>
                <?php } ?>
                <?php  if( isset($moduls[19]) ){ ?>
                  <li style="background-color: #1c2833;">
                    <a href="costo_variable.php" style="color: #abb2b9;">
                      <i class="bi bi-circle"></i><span>Costo Variables</span>
                    </a>
                  </li>
                <?php } ?>
              </ul>
            </li>
          <?php } ?>
            <li class="nav-item" style="background-color: #1c2833;"> 
              <a class="nav-link" href="empleados.php" style="background-color: #1c2833;">
                <span style="color: #abb2b9; font-family: Arial; font-weight: bold;">Empleados</span>
              </a>
            </li>
        </li>
      <?php } ?> 



      <!-------- Configuracion ---------------------->
      <?php if((((isset($save7)) && $save7=="CONFIGURACION") OR (isset($save2_10))) or isset($moduls[20]) or isset($moduls[21]) or isset($moduls[22])){ ?>
        <li class="nav-item" style="background-color: #1c2833;">
          <div class="sb-sidenav-menu-heading" style="color: #abb2b9; font-family: Arial; font-weight: bold; background-color: #1c2833;">CONFIGURACION</div>
          <li class="nav-item" style="background-color: #1c2833;"> 
            <a class="nav-link collapsed" data-bs-target="#components-nav12" data-bs-toggle="collapse" href="#" style="background-color: #1c2833;">
              <span style="color: #abb2b9; font-family: Arial; font-weight: bold;">Ajustes</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav12" class="nav-content collapse" data-bs-parent="#sidebar-nav" style="background-color: #1c2833;">
            
              <li style="background-color: #1c2833;">
                <a href="usuario.php" style="color: #abb2b9;">
                  <i class="bi bi-circle"></i><span>Usuarios</span>
                </a>
              </li>
           
             
              <li style="background-color: #1c2833;">
                <a href="permisos_agro.php" style="color: #abb2b9;">
                  <i class="bi bi-circle"></i><span>Permisos</span>
                </a>
              </li>
              <li style="background-color: #1c2833;">
                <a href="index_bitacora.php" style="color: #abb2b9;">
                  <i class="bi bi-circle"></i><span>Bitacora</span>
                </a>
              </li>
          <li style="background-color: #1c2833;">
                <a href="backup_gui.php" style="color: #abb2b9;">
                  <i class="bi bi-circle"></i><span>Respaldo</span>
                </a>
              </li>
              <li style="background-color: #1c2833;">
                <a href="monitoreo.php" style="color: #abb2b9;">
                  <i class="bi bi-circle"></i><span>Monitoreo</span>
                </a>
              </li>
            </ul>
          </li>
        </li>
      <?php } ?>
      <!-------------------------------------------------------------------------------------------->


    </ul>
  </aside>




  ?>
