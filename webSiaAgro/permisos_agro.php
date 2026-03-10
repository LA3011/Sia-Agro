<?php session_start();            ?> 

<?php if (!isset($_SESSION['Aceso'])) {
  header("location: index.html");
} ?>


<?php include_once("header.php")  ?>
<?php include_once("Sidebar.php") ?> 



<?php
function privilegios($id_pefilp, $id_perfil_actual, $perfiles) {
  require_once 'conexion/conexion.php';

    try {
        $conn = cconexion::ConexionBD(); // Obtiene la conexión
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT * FROM privilegios WHERE id_perfil = :id_perfil_actual";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_perfil_actual', $id_perfil_actual, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return;
        }

        // Convertir valores a booleanos de manera segura
        $ver = filter_var($row['ver'], FILTER_VALIDATE_BOOLEAN);
        $editar = filter_var($row['editar'], FILTER_VALIDATE_BOOLEAN);
        $eliminar = filter_var($row['eliminar'], FILTER_VALIDATE_BOOLEAN);


        if (!isset($_SESSION['Usuario'])) {
            $_SESSION['Usuario'] = "";
        }

        if ($id_pefilp != 1) {
            if ($perfiles == $_SESSION['Usuario']) { ?>
                <span style="font-size: 20px;" class="badge bg-dark">
                    <i class="bi bi-info-circle me-1"></i> Perfil en Uso
                </span>
            <?php } else {
                if ($ver) { ?>
                    <a type="button" data-bs-toggle="modal" data-bs-target="#basicModal-VER<?php echo $id_pefilp; ?>"
                        style="margin:5px; width:32px; font-size:25px;" title="VER">
                        <i class="ri-eye-fill" style="color:#17E45B"></i>
                    </a>
                <?php }
                if ($editar) { ?>
                    <a type="button" data-bs-toggle="modal" data-bs-target="#basicModal-EDITAR<?php echo $id_pefilp; ?>"
                        style="margin:5px; width:32px; font-size:25px;" title="EDITAR">
                        <i class="ri-ball-pen-fill" style="color:#E5D001;"></i>
                    </a>
                <?php }
                if ($eliminar) { ?>
                    <a type="button" data-bs-toggle="modal" data-bs-target="#smallModal-<?php echo $id_pefilp; ?>"
                        style="margin:5px; width:32px; font-size:25px;" title="Eliminar">
                        <i class="ri-delete-bin-2-line" style="color:#EE0D0D;"></i>
                    </a>
                <?php }
            }
        } else { ?>
            <span style="font-size: 20px;" class="badge bg-primary">
                <i class="bi bi-star me-1"></i> ADMINISTRADOR
            </span>
        <?php }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
$conn = null;
?>



<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Listado de fertilizantes</title>   
  <link rel="stylesheet" type="text/css" href="css_personalizado/permisos_agro.css">
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>   
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script>
  <!-- Bootstrap CSS -->
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script> 

</head> 

    <!-- Codigo traducion footer Table -->
      <style type="text/css">
       tr > .datatable-empty{
        color: white;
        }
      </style>
    <!-- ---------------------------- -->

<body>  


  <!-- (NOTIFICACION) MODAL VALIDACION -->
  <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header text-white text-center" style="background-color:#FFC107;">
          <h5 class="modal-title" id="myModalLabel">
            <i class="bi bi-exclamation-triangle me-1"></i> ALERTA!
          </h5>
        </div>
        <div class="modal-body">
          <p class="fw-bold"><?php echo $_SESSION['validacion_permisos']; ?></p>
          <div class="text-center">
            <i class="af af-hand-wave fs-1"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- (NOTIFICACION) VALIDACION -->
  <?php 
if (isset($_SESSION['validacion_permisos'])) {
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById("myModal"), {
            keyboard: false
        });
        myModal.show();
    });
    </script>';
    $_SESSION['validacion_permisos'] = null;
}  

if (isset($_SESSION['mensaje'])) {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            text: '" . addslashes($_SESSION['mensaje']) . "',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
    });
    </script>";
    unset($_SESSION['mensaje']);
}
?>

            <!------- tabla -------->
            <main id="main" class="main"> 
              <section class="section">
                <nav>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item" style="">Configuración</li>
                    <li class="breadcrumb-item" style="">Ajustes</li>
                    <li class="breadcrumb-item active" style="color:#172871;">Permisos</li>
                  </ol>
                </nav>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">

                        <p style="position: absolute; right:165px; top:130px;"> Buscar... </p>

                        <h5 class="card-title" style="color:black; font-size:40px; margin-left:7%;">Control de Perfiles</h5>
                        <button  type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#largeModal"
                        style="margin-right:82.5%; margin-top:10px; margin-bottom:8px;" title="Agregar"><i class="ri-add-fill"
                        style="color:white;"></i>Agregar &nbsp</button>
                        
                        <table class="table datatable">
                          <thead>
                            <tr>
                              <th scope="col">Nombre Perfil</th>  
                              <th scope="col">Estado</th>     
                              <th scope="col">Programas Asignados</th>  
                              <th scope="col">Sub-Programas Asignados</th>  
                              <th scope="col">Modulos</th>
                              <th scope="col">Privilegios</th>
                              <th scope="col"> Acción </th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php


$conn = cconexion::ConexionBD();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Consulta para obtener perfiles
$sql = "SELECT * FROM perfil";
$stmt = $conn->prepare($sql);
$stmt->execute();

while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Obtener conteo de programas asignados
    $stmt2 = $conn->prepare('SELECT COUNT(*) AS total FROM perfil_programa WHERE "Id_Perfil" = :idPerfil');
    $stmt2->bindParam(':idPerfil', $fila['Id_Perfil'], PDO::PARAM_INT);
    $stmt2->execute();
    $programasAsig = $stmt2->fetch(PDO::FETCH_ASSOC)['total'];

    // Obtener conteo de subprogramas asignados
    $stmt3 = $conn->prepare('SELECT COUNT(*) AS total FROM perfil_subprograma WHERE "Id_Perfil" = :idPerfil');
    $stmt3->bindParam(':idPerfil', $fila['Id_Perfil'], PDO::PARAM_INT);
    $stmt3->execute();
    $subprogramasAsig = $stmt3->fetch(PDO::FETCH_ASSOC)['total'];

    // Obtener conteo de módulos asignados
    $stmt4 = $conn->prepare('SELECT COUNT(*) AS total FROM perfil_modulo WHERE "Id_Perfil" = :idPerfil');
    $stmt4->bindParam(':idPerfil', $fila['Id_Perfil'], PDO::PARAM_INT);
    $stmt4->execute();
    $total_modulo = $stmt4->fetch(PDO::FETCH_ASSOC)['total'];

    // Obtener privilegios
    $privilegios = 0;
    $stmt5 = $conn->prepare('SELECT ver, editar, eliminar, imprimir FROM privilegios WHERE id_perfil = :idPerfil');
    $stmt5->bindParam(':idPerfil', $fila['Id_Perfil'], PDO::PARAM_INT);
    $stmt5->execute();

    while ($priv = $stmt5->fetch(PDO::FETCH_ASSOC)) {
        $privilegios += filter_var($priv['ver'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        $privilegios += filter_var($priv['editar'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        $privilegios += filter_var($priv['eliminar'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        $privilegios += filter_var($priv['imprimir'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    }
    ?>
    <tr>
        <td><?php echo htmlspecialchars($fila['Id_Perfil'] . " - " . $fila['nombre_perfil']); ?></td>
        <td><?php echo htmlspecialchars($fila['estado']); ?></td>
        <td><?php echo $programasAsig; ?>/6</td>
        <td><?php echo $subprogramasAsig; ?>/9</td>
        <td><?php echo $total_modulo; ?>/21</td>
        <td><?php echo $privilegios; ?>/4</td>
        <td>
            <?php 
            if (isset($_SESSION['Id_Perfilp'])) {
                privilegios($fila['Id_Perfil'], $_SESSION['Id_Perfilp'], $fila['nombre_perfil']);
            } else {
                echo "Error: sesión no iniciada";
            }
            ?>
        </td>

                                <!-- modal [ELIMINAR] -->
                                <div class="modal fade" id="smallModal-<?php echo $fila["Id_Perfil"]; ?>" tabindex="-1">
                                  <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                      <div class="modal-header"style="text-align:center; display: inline-block; background-color:#F25050;">
                                        <h5 class="modal-title" style="background-color:#F25050; color:white;">¡ATENCION!</h5>
                                      </div>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                      style="position:absolute; left:91%; top:2px;">
                                    </button>
                                    <div class="modal-body">
                                      ¿Desea Eliminar este Registro?
                                    </div>
                                    <div class="modal-footer">
                                      <a style="top:-1px; left:-60px; position: relative; color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                                      href='deshabilitaciones/deshabilitar_permisosAgro.php?id=<?php echo $fila["Id_Perfil"] ?>'
                                      title="Eliminar">
                                      <span class="btn btn-outline-danger">Eliminar</span>
                                    </a>
                                    <button style="left:px; position: relative;" type="button"
                                    class="btn btn-outline-success" data-bs-dismiss="modal">Cerrar
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- fin modal [ELIMINAR] --> 
                          <!-- modal [EDITAR] --> 
                          <div class="modal fade" id="basicModal-EDITAR<?php echo $fila["Id_Perfil"];?>" tabindex="-1">
                            <div class="modal-dialog modal-lg" style="max-width: 900px;">
                              <div class="modal-content">
                                <form action="actualizar/actualizar_permisosAgro.php" method="POST">
                                  <div class="modal-header" style="background-color: #0d6efd; color: white;">
                                    <h5 class="modal-title text-center w-100"> ACTUALIZAR </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <!-- BODY -->
                                  <div class="modal-body">

                                    <div class="row mb-3" >
                                      <label for="inputText" class="col-sm-2 col-form-label">Nombre Perfil</label>
                                      <div class="col-sm-9">
                                        <input class="form-control" value="<?php echo $fila['nombre_perfil']; ?>" type="text" id="validationCustom01" name="nombre_perfil" required placeholder="Ej: Adiministrador" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                                      </div>
                                    </div>

                                    <div class="row mb-3" id="des">
                                      <label for="inputText" class="col-sm-2 col-form-label">Estado</label>
                                      <div class="col-sm-9">
                                        <select class="form-select" style="cursor: pointer;" name="estado" required onchange="showPriceField(this.value)">
                                          <option value="Activo" >Activo</option>
                                          <option value="Inacivo" >Inactivo</option>
                                        </select>
                                      </div>
                                    </div>



          <!-- --------------- PRIVILEGIOS -------------- -->
          <div class="row mb-3" id="des">
            <label for="inputText" class="col-sm-2 col-form-label">Privilegios</label>
            <div style="padding:1%; margin-left:1%; border: 1px solid black; width: 72.5%; display: inline-block;">
              <label><h8>Todo</h8></label>
              <input style="margin-right: 8%; cursor: pointer;" class="priv2" type="checkbox">
              <label><h8>Ver</h8></label>
              <input style="margin-right: 4%; cursor: pointer;"  class="subpriv2"type="checkbox" name="ver" value="true" <?php if(isset($privlg[0]) ){ echo "checked"; } ?> >
              <label><h8>Editar</h8></label>                
              <input style="margin-right: 4%; cursor: pointer;" class="subpriv2" type="checkbox" name="editar" value="true" <?php if(isset($privlg[1]) ){ echo "checked"; } ?> >
              <label><h8>eliminar</h8></label>                
              <input style="margin-right: 4%; cursor: pointer;" class="subpriv2" type="checkbox" name="eliminar" value="true" <?php if(isset($privlg[2]) ){ echo "checked"; } ?> >
              <label><h8>Imprimir</h8></label>                
              <input style="margin-right:; cursor: pointer;" class="subpriv2" type="checkbox" name="imprimir" value="true" <?php if(isset($privlg[3])){ echo "checked"; } ?> >
            </div>
          </div>


          <!-- FIN BODY -->
          <!-- GRAND CONTENIDO -->
          <div class="row mb-3">
            <h3 for="inputText" class="col-sm-2 col-form-label">
              Programas
            </h3>
            <div class="col-sm-9"> 
              <!-- BRANCH (style) --> 
              <?php include('css_personalizado/permisos_agro.html'); ?>

              <!-- ------ DATO PERSONAL -------->
              <input type="hidden" name="Id_Perfil" value='<?php echo $fila["Id_Perfil"] ?>'>

              <!-- --------- PROGRAMAS -------- -->
              <div style="border-top: 1px solid white;">              
                <input class="programasedit" type="checkbox" 
                name="programaG" value="programaG"  style="font-size: 30px;">
                <h1 style="display: inline-block; margin-bottom: 20px;">PROGRAMAS</h1>
              </div>

              <!-- -------- ANIMALES --------- -->
              <div style="border-top: 1px solid white; width: 70%; margin-left:10%;">
                <input class="animales123" type="checkbox" <?php if( isset($prog[1]) && $prog[1] == "ANIMALES"){ echo "checked"; } ?> name="ANIMALES">
                <h2 style="display: inline-block;">Animales </h2>
              </div>

              <input class="animales1" style="cursor: pointer; margin-left:20%;" type="checkbox" <?php if (isset($subprog[1]) && $subprog[1] == "general_animales"){ echo "checked";}?> name="general_animales">
              <h4 style="display: inline-block;">General Animales</h4><br>

              <input class="animalessub1" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if (isset($modul[1]) && $modul[1] == "registro_animales"){ echo "checked"; }?> name="registro_animales">
              <h5 style="display: inline-block;">Registro Animales</h5><br>
              <input class="animalessub1" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if (isset($modul[2]) && $modul[2] == "reproducciones_animales"){ echo "checked";}?> name="reproducciones_animales">
              <h5 style="display: inline-block;">Reproduccion Animales</h5><br>
              <input class="animalessub1" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if (isset($modul[3]) && $modul[3] == "registro_potreros" ){ echo "checked";}?> name="registro_potreros">
              <h5 style="display: inline-block;">Registro Potreros</h5><br>

              <input class="animales2" style="cursor: pointer; display: inline-block; margin-left: 20%;" type="checkbox" <?php if (isset($subprog[2]) && $subprog[2] == "movimiento_animal"){ echo "checked";}?> name="movimiento_animal">
              <h4 style="display: inline-block;">Movimiento Animal</h4><br>
              <input class="animalessub2" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[4]) && $modul[4] == "actividad_animal")){ echo "checked";}?> name="actividad_animal">
              <h5 style="display: inline-block;">Actividad Animales</h5><br>
              <input class="animalessub2" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[5]) && $modul[5] == "pastoreo")){ echo "checked";}?> name="pastoreo">
              <h5 style="display: inline-block;">Pastoreo</h5><br>
              <input class="animalessub2" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[6]) &&$modul[6] == "insumos_animal")){ echo "checked";}?> name="insumos_animal">
              <h5 style="display: inline-block;">Insumos</h5><br><br>

              <!-- --------- CULTIVOS ---------- -->
              <div style="border-top: 1px solid white; width: 70%; margin-left:3.5%;">
                <input class="cultivos123" style="margin-left:10%;" type="checkbox"  <?php if( isset($prog[2]) && $prog[2] == "CULTIVOS") { echo "checked"; } ?>  name="CULTIVOS" value="">
                <h2 style="display: inline-block;">CULTIVOS</h2>
              </div>
              <input class="cultivos1" style="cursor: pointer; display: inline-block; margin-left: 20%;" type="checkbox" <?php if (isset($subprog[4]) && $subprog[4] == "seguimiento_cultivos"){ echo "checked";}?> name="seguimiento_cultivos">
              <h4 style="display: inline-block;">Seguimiento Cultivos</h4><br>

              <input class="cultivossub1" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[7]) &&$modul[7] == "siembra")){ echo "checked";}?> name="siembra">
              <h5 style="display: inline-block;">Siembra</h5><br>
              <input class="cultivossub1" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[8]) &&$modul[8] == "espacios")){ echo "checked";}?> name="espacios">
              <h5 style="display: inline-block;">Espacios</h5><br>
              <input class="cultivossub1" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[9]) &&$modul[9] == "actividades")){ echo "checked";}?> name="actividades">
              <h5 style="display: inline-block;">Actividades</h5><br>

              <input class="cultivos2" style="cursor: pointer; display: inline-block; margin-left: 20%;" type="checkbox" <?php if (isset($subprog[3]) && $subprog[3] == "general_cultivos"){ echo "checked";}?> name="general_cultivos">
              <h4 style="display: inline-block;">General Cultivos</h4><br>


              <input class="cultivossub2" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[10]) && $modul[10] == "control_fertilizante")){ echo "checked";}?> name="control_fertilizante">
              <h5 style="display: inline-block;">Control Fertilizante</h5><br>
              <input class="cultivossub2" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[11]) && $modul[11] == "control_plagas")){ echo "checked";}?> name="control_plagas">
              <h5 style="display: inline-block;">Control Plagas</h5><br>
              <input class="cultivossub2" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[12]) && $modul[12] == "insumos_cultivo")){ echo "checked";}?> name="insumos_cultivo">
              <h5 style="display: inline-block;">Insumos</h5><br><br>

              <!-- -------------- VENTAS-------------- -->
              <div style="border-top: 1px solid white; width: 70%; margin-left:3.5%;">
                <input class="ventas123" style="margin-left:10%;" type="checkbox" <?php if( isset($prog[3]) && $prog[3] == "VENTA") { echo "checked"; } ?>  name="VENTA" value="">
                <h2 style="display: inline-block;">VENTA</h2>
              </div>
              <input class="ventasxq" style="cursor: pointer; display: inline-block; margin-left: 20%;" type="checkbox" <?php if (isset($subprog[5]) && $subprog[5] == "venta"){ echo "checked";}?> name="venta">
              <h4 style="display: inline-block;">Ventas</h4><br>

              <input class="ventassubxq" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[14]) && $modul[14]=="orden_salida")){ echo "checked";}?> name="orden_salida">
              <h5 style="display: inline-block;">Orden Salida</h5><br>
              <input class="ventassubxq" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[15]) && $modul[15]=="animales_venta")){ echo "checked";}?> name="animales_venta">
              <h5 style="display: inline-block;">Animales Venta</h5><br><br>

              <!-- -------------- FINANZAS ------------- -->
              <div style="border-top: 1px solid white; width: 70%; margin-left:3.5%;">
                <input class="finanzas123" style="margin-left:10%;" type="checkbox" <?php if( isset($prog[4]) && $prog[4] == "FINANZAS") { echo "checked"; } ?>  name="FINANZAS" value="">
                <h2 style="display: inline-block;">FINANZAS</h2>
              </div>
              <input class="finanzas456" style="cursor: pointer; display: inline-block; margin-left: 20%;" type="checkbox" <?php if (isset($subprog[6]) && $subprog[6] == "general_finanzas"){ echo "checked";}?> name="general_finanzas">
              <h4 style="display: inline-block;">General</h4><br>

              <input class="finanzassub456" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[16]) && $modul[16] == "animales")){ echo "checked";}?> name="animales">
              <h5 style="display: inline-block;">Animales</h5><br>
              <input class="finanzassub456" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[17]) && $modul[17] == "cultivo")){ echo "checked";}?> name="cultivo">
              <h5 style="display: inline-block;">Cultivos</h5><br>

              <input class="costos456" style="cursor: pointer; display: inline-block; margin-left: 20%;" type="checkbox" <?php if (isset($subprog[7]) && $subprog[7] == "costos"){ echo "checked";}?> name="costos">
              <h4 style="display: inline-block;">Costos</h4><br>

              <input class="costossub456" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[18]) && $modul[18] == "costo_fijo")){ echo "checked";}?> name="costo_fijo">
              <h5 style="display: inline-block;">Costo Fijo</h5><br>
              <input class="costossub456" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[19]) && $modul[19] == "costo_variable")){ echo "checked";}?> name="costo_variable">
              <h5 style="display: inline-block;">Costo Variable</h5><br><br>

              <!-- ------------ RECURSOS_HUMANOS ------------ -->
              <div style="border-top: 1px solid white; width: 70%; margin-left:3.5%;">
                <input class="empleados123" style="margin-left:10%;" type="checkbox" <?php if( isset($prog[5]) && $prog[5] == "RECURSOS_HUMANOS"){ echo "checked"; } ?>  name="RECURSOS_HUMANOS" value="">
                <h2 style="display: inline-block;">RECURSOS HUMANOS</h2>
              </div>
              <input class="empleadossub123" style="cursor: pointer; display: inline-block; margin-left: 20%;" type="checkbox" <?php if ((isset($subprog[8]) && $subprog[8] == "empleados")){ echo "checked";}?> name="empleados">
              <h4 style="display: inline-block;">Empleados</h4><br><br>

              <!-- --------------- CONFIGURACION -------------- -->
              <div style="border-top: 1px solid white; width: 70%; margin-left:3.5%;">
                <input class="configuracion123" style="margin-left:10%;" type="checkbox" <?php if( isset($prog[6]) && $prog[6] == "CONFIGURACION") { echo "checked"; } ?>  name="CONFIGURACION" value="">
                <h2 style="display: inline-block;">CONFIGURACION</h2>
              </div>
              <input class="ajustes123" style="cursor: pointer; display: inline-block; margin-left: 20%;" type="checkbox" <?php if (isset($subprog[9]) && $subprog[9] == "ajustes"){ echo "checked";}?> name="ajustes">
              <h4 style="display: inline-block;">Ajustes</h4><br>

              <input class="ajustessub123" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if (((isset($modul[20]) && $modul[20] == "usuarios"))){ echo "checked"; }?> name="usuarios">
              <h5 style="display: inline-block;">Usuarios</h5><br>
              <input class="ajustessub123" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if (((isset($modul[21]) && $modul[21] == "permisos"))){ echo "checked"; }?> name="permisos">
              <h5 style="display: inline-block;">Permisos</h5><br>

              <input class="ajustessub123" style="cursor: pointer; display: inline-block; margin-left:30%;" type="checkbox" <?php if (((isset($modul[22]) && $modul[22] == "bitacora"))){ echo "checked"; }?> name="bitacora">
              <h5 style="display: inline-block;">Bitacora</h5><br>
              <br>

            </div>
            <!-- FIN DEL CUADRANTE -->
            <!-- FOOTER -->
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" style="background-color:#008000;">
                Guardar
              </button>
              <button style="position:relative; background-color:grey; color:white;" type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Cerrar
              </button>
            </div>
            <!-- FIN FOOTER -->
          </div>
          <!-- FIN GRAND CONTENIDO -->
        </form>
      </div>
    </div>      
  </div>
  <!-- fin modal [EDITAR] -->  

</tr>
<!-- modal [VER] --> 
<div class="modal fade" id="basicModal-VER<?php echo $fila["Id_Perfil"];?>" tabindex="-1">
  <div class="modal-dialog modal-lg" style="max-width: 900px;">
    <div class="modal-content">
      <form>
        <div class="modal-header" style="background-color: #0d6efd; color: white;">
          <h5 class="modal-title text-center w-100"> VER </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <!-- BODY -->
        <div class="modal-body">

          <div class="row mb-3" style="pointer-events: none;">
            <label for="inputText" class="col-sm-2 col-form-label">Nombre Perfil</label>
            <div class="col-sm-9">
              <input class="form-control" value="<?php echo $fila['nombre_perfil']; ?>" type="text" id="validationCustom01" name="nombre_perfil" required placeholder="Ej: Adiministrador" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
            </div>
          </div>

          <div class="row mb-3" id="des">
            <label for="inputText" class="col-sm-2 col-form-label">Estado</label>
            <div class="col-sm-9" style="pointer-events: none;">
              <select class="form-select" name="estado" required onchange="showPriceField(this.value)">
                <option value="Activo"><?php echo $fila['estado'] ?></option>
              </select>
            </div>
          </div>
        


<!-- FIN BODY -->
<!-- GRAND CONTENIDO -->
<div class="row mb-3">
  <h3 for="inputText" class="col-sm-2 col-form-label">
    Programas
  </h3>
  <div class="col-sm-9" style="pointer-events: none;">  
    <!-- CUADRANTE -->

    <!-- Ramas  -->
    <?php include('css_personalizado/permisos_agro.html'); ?>

    <!-- ------ DATO PERSONAL -------->
    <input type="hidden" name="Id_Perfil" value='<?php echo $fila["Id_Perfil"] ?>'>
    <!-- --------- PROGRAMAS -------- -->
    <div style="border-top: 1px solid white;">              
      <input class="programasedit" type="checkbox" 
      name="programaG" value="programaG" style="font-size: 30px;">
      <h1 style="display: inline-block; margin-bottom: 20px;">PROGRAMAS</h1>
    </div>
    <!-- -------- ANIMALES --------- -->
    <div style="border-top: 1px solid white; width: 70%; margin-left:10%;">
      <input class="animales123" type="checkbox" <?php if( isset($prog[1]) && $prog[1] == "ANIMALES"){ echo "checked"; } ?> name="ANIMALES">
      <h2 style="display: inline-block;">Animales </h2>
    </div>
    <input class="animales1" style="margin-left:20%;" type="checkbox" <?php if (isset($subprog[1]) && $subprog[1] == "general_animales"){ echo "checked";}?> name="general_animales">
    <h4 style="display: inline-block;">General Animales</h4><br>
    <input class="animalessub1" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if (isset($modul[1]) && $modul[1] == "registro_animales"){ echo "checked"; }?> name="registro_animales">
    <h5 style="display: inline-block;">Registro Animales</h5><br>
    <input class="animalessub1" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if (isset($modul[2]) && $modul[2] == "reproducciones_animales"){ echo "checked";}?> name="reproducciones_animales">
    <h5 style="display: inline-block;">Reproduccion Animales</h5><br>
    <input class="animalessub1" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if (isset($modul[3]) && $modul[3] == "registro_potreros" ){ echo "checked";}?> name="registro_potreros">
    <h5 style="display: inline-block;">Registro Potreros</h5><br>
    <input class="animales2" style="display: inline-block; margin-left: 20%;" type="checkbox" <?php if (isset($subprog[2]) && $subprog[2] == "movimiento_animal"){ echo "checked";}?> name="movimiento_animal">
    <h4 style="display: inline-block;">Movimiento Animal</h4><br>
    <input class="animalessub2" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[4]) && $modul[4] == "actividad_animal")){ echo "checked";}?> name="actividad_animal">
    <h5 style="display: inline-block;">Actividad Animales</h5><br>
    <input class="animalessub2" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[5]) && $modul[5] == "pastoreo")){ echo "checked";}?> name="pastoreo">
    <h5 style="display: inline-block;">Pastoreo</h5><br>
    <input class="animalessub2" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[6]) &&$modul[6] == "insumos_animal")){ echo "checked";}?> name="insumos_animal">
    <h5 style="display: inline-block;">Insumos</h5><br><br>
    <!-- --------- CULTIVOS ---------- -->
    <div style="border-top: 1px solid white; width: 70%; margin-left:3.5%;">
      <input class="cultivos123" style="margin-left:10%;" type="checkbox"  <?php if( isset($prog[2]) && $prog[2] == "CULTIVOS") { echo "checked"; } ?>  name="CULTIVOS" value="">
      <h2 style="display: inline-block;">CULTIVOS</h2>
    </div>
    <input class="cultivos1" style="display: inline-block; margin-left: 20%;" type="checkbox" <?php if (isset($subprog[4]) && $subprog[4] == "seguimiento_cultivos"){ echo "checked";}?> name="seguimiento_cultivos">
    <h4 style="display: inline-block;">Seguimiento Cultivos</h4><br>
    <input class="cultivossub1" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[7]) &&$modul[7] == "siembra")){ echo "checked";}?> name="siembra">
    <h5 style="display: inline-block;">Siembra</h5><br>
    <input class="cultivossub1" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[8]) &&$modul[8] == "espacios")){ echo "checked";}?> name="espacios">
    <h5 style="display: inline-block;">Espacios</h5><br>
    <input class="cultivossub1" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[9]) &&$modul[9] == "actividades")){ echo "checked";}?> name="actividades">
    <h5 style="display: inline-block;">Actividades</h5><br>
    <input class="cultivos2" style="display: inline-block; margin-left: 20%;" type="checkbox" <?php if (isset($subprog[3]) && $subprog[3] == "general_cultivos"){ echo "checked";}?> name="general_cultivos">
    <h4 style="display: inline-block;">General Cultivos</h4><br>
    <input class="cultivossub2" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[10]) && $modul[10] == "control_fertilizante")){ echo "checked";}?> name="control_fertilizante">
    <h5 style="display: inline-block;">Control Fertilizante</h5><br>
    <input class="cultivossub2" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[11]) && $modul[11] =="control_plagas")){ echo "checked";}?> name="control_plagas">
    <h5 style="display: inline-block;">Control Plagas</h5><br>
    <input class="cultivossub2" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[12]) && $modul[12] == "insumos_cultivo")){ echo "checked";}?> name="insumos_cultivo">
    <h5 style="display: inline-block;">Insumos</h5><br><br>
    <!-- -------------- VENTAS-------------- -->
    <div style="border-top: 1px solid white; width: 70%; margin-left:3.5%;">
      <input class="ventas123" style="margin-left:10%;" type="checkbox" <?php if( isset($prog[3]) && $prog[3] == "VENTA") { echo "checked"; } ?>  name="VENTA" value="">
      <h2 style="display: inline-block;">VENTA</h2>
    </div>
    <input class="ventasxq" style="display: inline-block; margin-left: 20%;" type="checkbox" <?php if (isset($subprog[5]) && $subprog[5] == "venta"){ echo "checked";}?> name="venta">
    <h4 style="display: inline-block;">Ventas</h4><br>
    <input class="ventassubxq" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[14]) && $modul[14]=="orden_salida")){ echo "checked";}?> name="orden_salida">
    <h5 style="display: inline-block;">Orden Salida</h5><br>
    <input class="ventassubxq" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[15]) && $modul[15]=="animales_venta")){ echo "checked";}?> name="animales_venta">
    <h5 style="display: inline-block;">Animales Venta</h5><br><br>
    <!-- -------------- FINANZAS ------------- -->
    <div style="border-top: 1px solid white; width: 70%; margin-left:3.5%;">
      <input class="finanzas123" style="margin-left:10%;" type="checkbox" <?php if( isset($prog[4]) && $prog[4] == "FINANZAS") { echo "checked"; } ?>  name="FINANZAS" value="">
      <h2 style="display: inline-block;">FINANZAS</h2>
    </div>
    <input class="finanzas456" style="display: inline-block; margin-left: 20%;" type="checkbox" <?php if (isset($subprog[6]) && $subprog[6] == "general_finanzas"){ echo "checked";}?> name="general_finanzas">
    <h4 style="display: inline-block;">General</h4><br>
    <input class="finanzassub456" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[16]) && $modul[16] == "animales")){ echo "checked";}?> name="animales">
    <h5 style="display: inline-block;">Animales</h5><br>
    <input class="finanzassub456" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[17]) && $modul[17] == "cultivo")){ echo "checked";}?> name="cultivo">
    <h5 style="display: inline-block;">Cultivos</h5><br>
    <input class="costos456" style="display: inline-block; margin-left: 20%;" type="checkbox" <?php if (isset($subprog[7]) && $subprog[7] == "costos"){ echo "checked";}?> name="costos">
    <h4 style="display: inline-block;">Costos</h4><br>
    <input class="costossub456" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[18]) && $modul[18] == "costo_fijo")){ echo "checked";}?> name="costo_fijo">
    <h5 style="display: inline-block;">Costo Fijo</h5><br>
    <input class="costossub456" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if ((isset($modul[19]) && $modul[19]== "costo_variable")){ echo "checked";}?> name="costo_variable">
    <h5 style="display: inline-block;">Costo Variable</h5><br><br>
    <!-- ------------ RECURSOS_HUMANOS ------------ -->
    <div style="border-top: 1px solid white; width: 70%; margin-left:3.5%;">
      <input class="empleados123" style="margin-left:10%;" type="checkbox" <?php if( isset($prog[5]) && $prog[5] == "RECURSOS_HUMANOS"){ echo "checked"; } ?>  name="RECURSOS_HUMANOS" value="">
      <h2 style="display: inline-block;">RECURSOS HUMANOS</h2>
    </div>
    <input class="empleadossub123" style="display: inline-block; margin-left: 20%;" type="checkbox" <?php if ((isset($subprog[8]) && $subprog[8] == "empleados")){ echo "checked";}?> name="empleados">
    <h4 style="display: inline-block;">Empleados</h4><br><br>
    <!-- --------------- CONFIGURACION -------------- -->
    <div style="border-top: 1px solid white; width: 70%; margin-left:3.5%;">
      <input class="configuracion123" style="margin-left:10%;" type="checkbox" <?php if( isset($prog[6]) && $prog[6] == "CONFIGURACION") { echo "checked"; } ?>  name="CONFIGURACION" value="">
      <h2 style="display: inline-block;">CONFIGURACION</h2>
    </div>
    <input class="ajustes123" style="display: inline-block; margin-left: 20%;" type="checkbox" <?php if (isset($subprog[9]) && $subprog[9] == "ajustes"){ echo "checked";}?> name="ajustes">
    <h4 style="display: inline-block;">Ajustes</h4><br>
    <input class="ajustessub123" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if (((isset($modul[20]) && $modul[20] == "usuarios"))){ echo "checked"; }?> name="usuarios">
    <h5 style="display: inline-block;">Usuarios</h5><br>
    <input class="ajustessub123" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if (((isset($modul[21]) && $modul[21] == "permisos"))){ echo "checked"; }?> name="permisos">
    <h5 style="display: inline-block;">Permisos</h5><br>
    <input class="ajustessub123" style="display: inline-block; margin-left:30%;" type="checkbox" <?php if (((isset($modul[22]) && $modul[22] == "bitacora"))){ echo "checked"; }?> name="bitacora">
    <h5 style="display: inline-block;">Bitacora</h5><br>
                                              <!-- <input style="display: inline-block; margin-left:30%;" type="checkbox" <?php //if (isset($modul[1])){ //echo "checked";}?> name="general_animales">
                                                <h5 style="display: inline-block;">Ayuda</h5><br> --><br>
                                              </div>                         
                                              <!-- FIN DEL CUADRANTE -->
                                              <!-- FOOTER -->
                                              <div class="modal-footer">
                                                <button style="position:relative; pointer-events:visible;" type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                  Cerrar
                                                </button>
                                              </div>
                                              <!-- FIN FOOTER -->
                                            </div>
                                            <!-- FIN GRAND CONTENIDO -->
                                          </form>
                                        </div>
                                      </div>      
                                    </div> 
                                    <!-- fin modal [VER] --> 
                                  <?php } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </section> 
                                 <!-- modal de registrar-->
                                 <div class="modal fade" id="largeModal" tabindex="-1">
                      <div class="modal-dialog modal-lg" style="max-width: 900px;">
                        <div class="modal-content">
                          <div class="modal-header" style="background-color:#0d6efd; color: white;">
                            <h5 class="modal-title text-center w-100">Registrar Perfil</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form id="formAgregar" method="POST" action="procesar/procesar_permisosAgro.php" style="padding: 0 50px 0 50px;"><br>
                            <input type="hidden" name="Id_Perfil" value="<?php echo $d; ?>">
                            <div class="row mb-3"> 
                              <label for="inputText" class="col-sm-2 col-form-label">Nombre Perfil</label>
                              <div class="col-sm-9">
                                <input class="form-control" type="text" id="validationCustom01" name="nombre_perfil" required placeholder="Ej: Adiministrador" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                              </div>
                            </div>

                            <div class="row mb-3">
                              <label for="inputText" class="col-sm-2 col-form-label">Estado</label>
                              <div class="col-sm-9">
                                <select style="cursor: pointer;" class="form-select" name="estado" required onchange="showPriceField(this.value)">
                                  <option value="">Selecciona una opción</option>
                                  <option value="Activo" >Activo</option>
                                  <option value="Inacivo" >Inactivo</option>
                                </select>
                              </div>
                            </div>

                            <div class="row mb-3" style="margin-top: 2%;">
                              <label for="inputText" class="col-sm-2 col-form-label">Privilegios</label>
                              <div class="col-sm-9" style="border: 1px solid black; padding: 1%; margin-left: 1.5%; width:72.5%;">
                                <label><h5>Todo</h5></label>
                                <input style="cursor: pointer; margin-right: 5%;" id="privgeneral" type="checkbox" name="todo_priv">
                                <label><h5>Ver</h5></label>
                                <input style="cursor: pointer; margin-right: 2%;" id="priv1" type="checkbox" name="ver" value="true">
                                <label><h5>Editar</h5></label>                
                                <input style="cursor: pointer; margin-right: 2%;" id="priv2" type="checkbox" name="editar" value="true">
                                <label><h5>eliminar</h5></label>                
                                <input style="cursor: pointer; margin-right: 2%;" id="priv3" type="checkbox" name="eliminar" value="true">
                                <label><h5>Imprimir</h5></label>                
                                <input style="cursor: pointer; margin-right: 2%;" id="priv4" type="checkbox" name="imprimir" value="true">
                              </div>
                            </div>

                            <div class="row mb-3">
  <h3 class="col-sm-2 col-form-label">Programas</h3>
  <div class="col-sm-9 checkbox_val" style="margin-bottom: 2%;">
    <?php
    try {
        // Conexión a PostgreSQL con PDO
        include("../conexion/conexion.php");
        $conn = cconexion::ConexionBD();
        // Consulta de programas y subprogramas
        $sql = $conn->prepare("SELECT p.nombre AS programa, sp.nombre_subp AS subprograma 
                               FROM sub_programa sp 
                               JOIN programa p ON p.\"Id_Programa\" = sp.\"Id_ProgramaS\"");
        $sql->execute();
        $resultados = $sql->fetchAll(PDO::FETCH_ASSOC);

        // Verificación de datos obtenidos
        if ($resultados) {
            $programa_anterior = "";
            foreach ($resultados as $row) {
                if ($row['programa'] != $programa_anterior) {
                    $programa_anterior = $row['programa'];
                    echo "<div style='border-top: 1px solid white; width: 70%; margin-left:10%;'>
                            <input type='checkbox' id='{$row['programa']}' name='{$row['programa']}' value='{$row['programa']}'>
                            <h3 style='display:inline-block;'> {$row['programa']}</h3>
                          </div>";
                }
                echo "<input id='{$row['subprograma']}' style='cursor: pointer; margin-left:20%;' type='checkbox' name='{$row['subprograma']}' value='{$row['subprograma']}'>
                      <h4 style='display:inline-block;'> {$row['subprograma']}</h4><br>";

                // Consulta de módulos por subprograma
                $sql_modulos = $conn->prepare("SELECT nombre_modulo FROM modulo WHERE \"Id_Subprograma\" = (SELECT \"Id_Subprograma\" FROM sub_programa WHERE nombre_subp = :subprograma)");
                $sql_modulos->bindParam(':subprograma', $row['subprograma']);
                $sql_modulos->execute();
                $modulos = $sql_modulos->fetchAll(PDO::FETCH_ASSOC);

                foreach ($modulos as $modulo) {
                    echo "<input id='{$modulo['nombre_modulo']}' style='cursor: pointer; margin-left:30%;' type='checkbox' name='{$modulo['nombre_modulo']}' value='{$modulo['nombre_modulo']}'>
                          <h5 style='display:inline-block;'> {$modulo['nombre_modulo']}</h5><br>";
                }
            }
        } else {
            echo "<p>No hay programas disponibles.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error en la conexión: " . $e->getMessage() . "</p>";
    }
    ?>
  </div>
</div>

                          </div> 
                          <span class="row mb-3" style="padding-left: 15%; text-align: center; margin-left: 10%;  color: red;" id="span_val"></span>
                          <div class="row mb-3" style="padding-left: 15%; text-align: center; margin-left: 8%;">
                            <div class="col-sm-9" style="">
                              <button style="width: 110px; background-color: grey; color: white;" type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Cerrar
                              </button>
                              <input type="submit" class="btn btn-primary" value="Registrar" style="width: 110px; background-color: green; color: white;">
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                  <!-- fin modal registrar -->
                </main>
                <!------ fin tabla ----->
                <!-- ========= Footer ======== -->
                <?php include_once("footer.php"); ?>
                <!-- ======= Fin Footer ======= -->
              </body>
              </html>
              <!---   JQ  --->
              <script type="text/javascript" src="validacion/permisos_agro.js"></script>
              <script type="text/javascript">
                document.getElementsByClassName('datatable-input')[0].placeholder = "This is my new text";
                $('#PROGRAMAS').click(function(){
                  $('#bitacora').prop('checked', $(this).prop('checked'));
                });
                $('#PROGRAMAS').click(function(){
                  $('#bitacora').prop('checked', $(this).prop('checked'));
                });
                $('#CONFIGURACION').click(function(){
                  $('#bitacora').prop('checked', $(this).prop('checked'));
                });
                $('#ajustes').click(function(){
                  $('#bitacora').prop('checked', $(this).prop('checked'));
                });
              </script>

              <script type="text/javascript">
                const form = document.querySelector('#formAgregar');
                const span = document.querySelector('#span_val');

                form.addEventListener('submit', e => {
                  const cb = document.querySelectorAll("div.checkbox_val input:checked");
                  // console.log(cb.length);
                  if(cb.length < 1){
                    span.innerHTML = "Se Requiere un Minimo de 1 Modulo/Sub-Programa Asignado*";
                    e.preventDefault( );
                  }
                });

                $('#privgeneral').click(function(){
                  $('#priv1').prop('checked', $(this).prop('checked'));
                  $('#priv2').prop('checked', $(this).prop('checked'));
                  $('#priv3').prop('checked', $(this).prop('checked'));
                  $('#priv4').prop('checked', $(this).prop('checked'));
                  $('#priv5').prop('checked', $(this).prop('checked'));
                });
              </script>




<script>
    function registrarjs(){
      var bPreguntar = true;
      window.onbeforeunload = preguntarAntesDeSalir;
        function preguntarAntesDeSalir () {
          var respuesta;
          if ( bPreguntar ) {
            respuesta = confirm("¿Seguro que desea salir, Sin antes mandar Formulario?");
            if ( respuesta ) {
              window.onunload = function () {
                return true;
            }
            } else {
              return false;
            }
          }
        }
      }
</script><!-- Bootstrap CSS -->
