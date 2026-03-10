<?php session_start();            ?>
<?php if(!isset($_SESSION['Aceso'])){
  header("location: index.html");
}?>
<!-- ---- ↓↓ CODIGO A COPIAR ↓↓ ---- -->
<?php
include("conexion/conexion.php");
$conn = cconexion::ConexionBD();

$id_perfil_actual = $_SESSION['Id_Perfilp'];

$query = "SELECT * FROM privilegios WHERE id_perfil = :id_perfil_actual";
$statement = $conn->prepare($query);
$statement->bindValue(':id_perfil_actual', $id_perfil_actual, PDO::PARAM_INT);
$statement->execute();

while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    $ver = $row['ver'];
    $eliminar = $row['eliminar'];
    $editar = $row['editar'];
    $imprimir = $row['imprimir'];
}
?>
<!-- ---- ↑↑ CODIGO A COPIAR ↑↑ ---- -->
 
<?php include_once("header.php")  ?>
<?php include_once("Sidebar.php") ?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Reproducciones</title>
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script>
  <link rel="stylesheet"type="text/css" href="css_personalizado/estilo_reproducciones.css">
</head>
<body>
  <?php
// Verifica si hay un mensaje almacenado en la variable de sesión
  if (isset($_SESSION['mensaje'])) {
    echo "
    <script>
    $(document).ready(function() {
      Swal.fire({
        text: '".$_SESSION['mensaje']."',
        icon: 'success',
        confirmButtonText: 'Aceptar'
        });
        });
        </script>
        ";

      // Elimina el mensaje de la variable de sesión para que no se muestre nuevamente después de la actualización de la página
        unset($_SESSION['mensaje']);
      }
      ?>

      <!-- Codigo traducion footer Table -->
        <style type="text/css">
         tr > .datatable-empty{
          color: white;
          }
        </style>
      <!-- ---------------------------- -->

      <!-- modal de registrar-->
      <div class="modal fade" id="largeModal" tabindex="-1">
        <div class="modal-dialog modal-lg" style="max-width: 900px;">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #0d6efd; color: white;">
              <h5 class="modal-title text-center w-100">Registrar Información </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="procesar/procesar_reproduccion.php" id="idformulario" style="padding: 0 50px 0 50px;">
              <br>

              <?php
              include_once("conexion/conexion.php");
              $conn = cconexion::ConexionBD();
              $query = "SELECT \"Id_animal\",\"Nombre\" FROM animales WHERE \"Sexo\"= 'Hembra' AND  \"Venta\"= 'Venta'  OR \"Venta\"= 'Crianza' ";
              $result = $conn->query($query);
              $options = '';
              if ($result->rowCount() > 0) {
              while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
              $idanimales = $row['Id_animal'];
              $nombreanimales = $row['Nombre'];
             $options .= '<option value="' . $idanimales . '">' . $nombreanimales . '</option>';
                 }
            } else {
             $options = '<option value="">No se encontraron lotes</option>';
            }
            $conn = null;
              ?>
              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Nombre de la Hembra</label>
                <div class="col-sm-9">
                  <select class="form-select" id="Nombre_hembra" name="Nombre_hembra" required>
                  <option value="">Seleccione una opción</option>
                  <?php echo $options; ?>
                  </select>
                </div>
              </div>

              <?php
              include_once("conexion/conexion.php");
              $conn = cconexion::ConexionBD();
              $query = "SELECT \"Id_animal\",\"Nombre\" FROM animales WHERE \"Sexo\"= 'Macho' AND  \"Venta\"= 'Venta'  OR \"Venta\"= 'Crianza'  ";
              $result = $conn->query($query);
              $options = '';
              if ($result->rowCount() > 0) {
              while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
              $idanimales = $row['Id_animal'];
              $nombreanimales = $row['Nombre'];
             $options .= '<option value="' . $idanimales . '">' . $nombreanimales . '</option>';
                 }
            } else {
             $options = '<option value="">No se encontraron lotes</option>';
            }
            $conn = null;
              ?>
              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Nombre del Macho</label>
                <div class="col-sm-9">
                  <select class="form-select" id="Nombre_macho" name="Nombre_macho" required>
                  <option value="">Seleccione una opción</option>
                  <?php echo $options; ?>
                   
                  </select>
                </div>
              </div>
              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Tipo de Reproducción</label>
                <div class="col-sm-9">
                  <select class="form-select" id="Tipo_reproducción" name="Tipo_reproducción" required>
                    <option value="">Seleccione una opción</option>
                    <option value="revision">revisión</option>
                    <option value="Inseminacion">Inseminación</option>
                  </select>
                </div>
              </div>
              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Tipo de Fertilización</label>
                <div class="col-sm-9">
                  <select class="form-select" id="Tipo_ fertilizacion" name="Tipo_fertilizacion" required>
                    <option value="">Seleccione una opción</option>
                    <option value="Artificial">Artificial</option>
                    <option value="Natural">Natural</option>
                    <option value="Transferencia">Transferencia</option>
                  </select>
                </div>
              </div>
              <div class="row mb-2">
    <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha de Revisión</label>
    <div class="col-sm-9">
        <input type="date" class="form-control"  id="Fecha_revision" name="Fecha_revision" value='<?php echo $fila["Fecha_revision"]; ?>' min="2020-01-01" required>
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha Posible de parto</label>
    <div class="col-sm-9">
        <input type="date" class="form-control"  id="Fecha_parto" name="Fecha_parto" value='<?php echo $fila["Fecha_parto"]; ?>' required>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const fechaSiembraInput = document.getElementById("Fecha_revision");
        const fechaCosechaInput = document.getElementById("Fecha_parto");

        // Validación de la fecha de cosecha
        fechaCosechaInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha <= fechaSiembra) {
                alert("La fecha de parto no puede ser menor o igual a la fecha de revicion.");
                fechaCosechaInput.value = "";  // Limpiar campo de fecha de cosecha si es inválido
                fechaCosechaInput.focus();     // Focalizar nuevamente en el campo de fecha de cosecha
            }
        });

        // Validación de la fecha de siembra (en caso de que se ingrese una fecha posterior a la cosecha)
        fechaSiembraInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha && fechaSiembra > fechaCosecha) {
                alert("La fecha de parto no puede ser posterior a la fecha de revicion.");
                fechaSiembraInput.value = "";  // Limpiar campo de fecha de siembra si es inválido
                fechaSiembraInput.focus();     // Focalizar nuevamente en el campo de fecha de siembra
            }
        });
    });
</script>


             
              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Encargado </label>

                <div class="col-sm-9">
                  <input class="form-control" type="text" name="Encargado"  required placeholder="Ej: Diego" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                </div>
              </div>
              <br>
              <div class="row mb-2" style="padding-left: 20%;">
                <div class="col-sm-9" style="text-align: center"> 
                  <!-- Campo oculto para enviar la sesión del usuario -->
                  <input type="hidden" name="session_acceso" value="<?php echo $_SESSION['Usuario'] ?>">
                  <input type="hidden" name="session_id" value="<?php echo $_SESSION['Id_Usuario'] ?>">
                  <a class="btn btn-secondary" style="width: 100px;" onclick="vaciarCampos()">Vaciar</a>
                  <input type="submit" class="btn btn-primary" value="Guardar" style="width: 100px; background-color: green; color: white;">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!------- tabla -------->
      <main id="main" class="main">
        <section class="section">

          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Animales</li>
              <li class="breadcrumb-item" style="">General</li>
              <li class="breadcrumb-item active">Reproducciones Animales</li>
            </ol>
          </nav>

          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <p style="position: absolute; right:165px; top:130px;"> Buscar... </p>
                  <h5 class="card-title" style="color:black; font-size:40px; margin-left:7%;">Reproducción Animal</h5>
                  <!-- ---- ↓↓ CODIGO A COPIAR ↓↓ ---- -->
                  <?php if($ver == "true"){ ?>
                    <button  type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#largeModal"
                    style="margin-right:82.5%; margin-top:10px; margin-bottom:8px;" title="Agregar"><i class="ri-add-fill"
                    style="color:white;"></i>Agregar &nbsp
                  </button>
                  <?php }else{ ?>
                    <div style="margin-right:82.5%; margin-top:24px; margin-bottom:8px; display: inline-block;"> </div>
                  <?php } ?>
                <!-- ---- ↑↑ CODIGO A COPIAR ↑↑ ---- -->

                <table class="table datatable">
                  <br>
                  <thead>
                    <tr>
                      <th scope="col" >Nombre de la Hembra</th>
                      <th scope="col" >Nombre del Macho  </th>
                      <th scope="col" >Tipo de Reproducción</th>       
                      <th scope="col" >Fecha de revisión</th>     
                      <th scope="col" >Fecha Estimada de parto</th>
                      <th scope="col" >Tipo de fertilización</th>      
                      <th scope="col" >Encargado</th> 
                      <th scope="col" >Fecha y Hora de registro</th> 
                      <th scope="col" > Acción </th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();

$sql = "SELECT * FROM reproduccion ORDER BY \"Id_reproduccion\"";
$result = $conn->query($sql);

if ($result->rowCount() > 0) {
    $contador = 1;
    while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {
        $id_hembra = $fila['Nombre_hembra'];
        $sql_hembra = "SELECT \"Nombre\" FROM animales WHERE \"Id_animal\" = :id_hembra";
        $stmt_hembra = $conn->prepare($sql_hembra);
        $stmt_hembra->bindParam(':id_hembra', $id_hembra);
        $stmt_hembra->execute();
        $nombre_hembra = $stmt_hembra->fetchColumn();
        $id_macho = $fila['Nombre_macho'];
        $sql_macho = "SELECT \"Nombre\" FROM animales WHERE \"Id_animal\" = :id_macho";
        $stmt_macho = $conn->prepare($sql_macho);
        $stmt_macho->bindParam(':id_macho', $id_macho);
        $stmt_macho->execute();
        $nombre_macho = $stmt_macho->fetchColumn();
        ?>
        <tr>
            <td><?php echo $nombre_hembra; ?></td>
            <td><?php echo $nombre_macho; ?></td>
            <td><?php echo $fila['tipo_reproducción']; ?></td>
            <td><?php echo date("d/m/Y", strtotime($fila['Fecha_parto'])); ?></td>
            <td><?php echo date("d/m/Y", strtotime($fila['Fecha_hora_registro'])); ?></td>
            <td><?php echo $fila['Tipo_fertilizacion']; ?></td>
            <td><?php echo $fila['encargado']; ?></td>
            <td><?php echo $fila['Fecha_hora_registro']; ?></td>
                          <td>
                            <div class="btn-group" role="group">

                              <?php if($ver == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                              <!-- Boton-modal [ver] -->
                              <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                              type="button" data-bs-toggle="modal" data-bs-target='#basicModal-VER<?php echo $fila["Id_reproduccion"]; ?>'
                              title="Ver">
                              <i class="ri-eye-fill" style="color:#17E45B" aria-describedby="tooltip831980"></i>
                            </a>
                            <?php } ?>  <!-- ← CODIGO A COPIAR -->


                            <?php if($editar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                            <!-- Boton-modal [Editar] -->
                            <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                            type="button" data-bs-toggle="modal" data-bs-target='#basicModal-<?php echo $fila["Id_reproduccion"]; ?>'
                            title="Editar">
                            <i class="ri-ball-pen-fill" style="color:#E5D001;"></i>
                          </a>
                          <?php } ?>  <!-- ← CODIGO A COPIAR -->


                          <?php if($eliminar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                          <!-- Boton-modal [eliminar] -->
                          <a type="button" data-bs-toggle="modal" data-bs-target="#smallModal-<?php echo $fila["Id_reproduccion"]; ?>"
                            style="color:none;  margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                            title="Eliminar">
                            <i class="ri-delete-bin-2-line" style="color:#EE0D0D;"></i>
                          </a>
                        <?php } ?>

                        <!-- modal [eliminar] -->
                        <div class="modal fade" id="smallModal-<?php echo $fila["Id_reproduccion"]; ?>" tabindex="-1">
                          <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                              <div class="modal-header"
                              style="text-align:center; display: inline-block; background-color:#F25050;">
                              <h5 class="modal-title" style="background-color:#F25050; color:white;">¡ATENCIÓN!</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="position:absolute; left:91%; top:2px;"></button>
                            <div class="modal-body">
                              ¿Desea Eliminar este Registro?
                            </div>
                            <div class="modal-footer">
                              <a style="top:-1px; left:-60px; position: relative; color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                              href='deshabilitaciones/deshabilitar_reproducciones.php?id=<?php echo $fila["Id_reproduccion"] ?>&session_acceso=<?php echo isset($_SESSION["Usuario"]) ? $_SESSION["Usuario"] : ""; ?>&session_id=<?php echo isset($_SESSION["Id_Usuario"]) ? $_SESSION["Id_Usuario"] : ""; ?>'
                              title="Eliminar">
                              <span class="btn btn-outline-danger">Eliminar</span>
                            </a>
                            <button style="left:px; position: relative;" type="button"
                            class="btn btn-outline-success" data-bs-dismiss="modal">Cerrar</button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!------- modal de VER -------->
                    <div class="modal fade" id="basicModal-VER<?php echo $fila["Id_reproduccion"]; ?>" tabindex="-1">
                      <div class="modal-dialog modal-lg" style="max-width: 900px;">
                        <div class="modal-content">
                          <div class="modal-header" style="background-color: #0d6efd; color: white;">
                            <h5 class="modal-title text-center w-100">Ver Información</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                          </div>
                          <div class="modal-body">

                            <form method="POST" action="actualizar/actualizar_reproducciones.php">
                              <div>
                                <input style="pointer-events:none;" type="hidden" class="form-control" name="id_reproduccion" value='<?php echo $fila["Id_reproduccion"]; ?>' >
                              </div>

                              <div class="row mb-2">
               <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre de la Hembra</label>
              <div class="col-sm-9">
        <select style="pointer-events:none;" class="form-select" id="Nombre_hembra" name="Nombre_hembra" required>
            <?php

            $sql_hembras = "SELECT * FROM animales WHERE \"Sexo\" = 'Hembra'";
            $result_hembras = $conn->query($sql_hembras);
            while ($valores_hembra = $result_hembras->fetch(PDO::FETCH_ASSOC)) {
                if ($valores_hembra['Nombre'] == $espacio_actual) {
                    echo '<option value="' . $valores_hembra['Id_animales'] . '" selected>' . $valores_hembra['Nombre'] . '</option>';
                } else {
                    echo '<option value="' . $valores_hembra['Id_animales'] . '">' . $valores_hembra['Nombre'] . '</option>';
                }
            }
            ?>
        </select>
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre del macho</label>
    <div class="col-sm-9">
        <select style="pointer-events:none;" class="form-select" id="Nombre_macho" name="Nombre_macho" required>
            <?php
            $sql_machos = "SELECT * FROM animales WHERE \"Sexo\" = 'Macho'";
            $result_machos = $conn->query($sql_machos);
            while ($valores_macho = $result_machos->fetch(PDO::FETCH_ASSOC)) {
                if ($valores_macho['Nombre'] == $espacio_actual) {
                    echo '<option value="' . $valores_macho['Id_animales'] . '" selected>' . $valores_macho['Nombre'] . '</option>';
                } else {
                    echo '<option value="' . $valores_macho['Id_animales'] . '">' . $valores_macho['Nombre'] . '</option>';
                }
            }
            ?>
        </select>
    </div>
</div>

                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de reproducción</label>
                                <div class="col-sm-9">
                                  <select style="pointer-events:none;" class="form-select" id="Tipo_reproducción" name="Tipo_reproducción" required>
                                    <option <?php echo $fila["tipo_reproducción"]==='revision' ? "selected='selected'":""?> value="revision">revisión</option>
                                    <option <?php echo $fila["tipo_reproducción"]==='Inseminacion' ? "selected='selected'":""?> value="Inseminacion">Inseminación</option>
                                  </select>
                                </div>
                              </div>

                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">fecha de revisión</label>
                                <div class="col-sm-9">
                                  <input style="pointer-events:none;" type="date" class="form-control" name="Fecha_revision" value='<?php echo $fila["Fecha_revision"]; ?>'>
                                </div>
                              </div>

                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha Posible de parto</label>
                                <div class="col-sm-9">
                                  <input style="pointer-events:none;" type="date" class="form-control" name="Fecha_parto" value='<?php echo $fila["Fecha_parto"]; ?>'>
                                </div>
                              </div>

                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de fertilización</label>
                                <div class="col-sm-9">
                                  <select style="pointer-events:none;" class="form-select" id="Tipo_fertilizacion" name="Tipo_fertilizacion" required>
                                    <option <?php echo $fila["Tipo_fertilizacion"]==='Artificial' ? "selected='selected'":""?> value="Artificial">Artificial</option>
                                    <option <?php echo $fila["Tipo_fertilizacion"]==='Natural' ? "selected='selected'":""?> value="Natural">Natural</option>
                                    <option <?php echo $fila["Tipo_fertilizacion"]==='Transferencia' ? "selected='selected'":""?> value="Transferencia">Transferencia</option>
                                  </select>
                                </div>
                              </div>

                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Encargado</label>
                                <div class="col-sm-9">
                                  <input style="pointer-events:none;" type="text" class="form-control" name="Encargado" required placeholder="" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" value='<?php echo $fila["encargado"]; ?>'>
                                </div>
                              </div>

                              <br>

                              <div class="d-grid gap-2 col-6 mx-auto">

                                <input type="hidden" name="session_acceso" value="<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>">
                                <input type="hidden" name="session_id" value="<?php echo isset($_SESSION['Id_Usuario']) ? $_SESSION['Id_Usuario'] : ''; ?>">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!------- modal de actualizar -------->
                    <div class="modal fade" id="basicModal-<?php echo $fila["Id_reproduccion"]; ?>" tabindex="-1">
                      <div class="modal-dialog modal-lg" style="max-width: 900px;">
                        <div class="modal-content">
                          <div class="modal-header" style="background-color: #0d6efd; color: white;">
                            <h5 class="modal-title text-center w-100">Actualizar Información</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                          </div>
                          <div class="modal-body">

                            <form method="POST" action="actualizar/actualizar_reproducciones.php">
                              <div>
                                <input type="hidden" class="form-control" name="id_reproduccion" value='<?php echo $fila["Id_reproduccion"]; ?>' >
                              </div>

                              <div class="row mb-2">
    <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre de la Hembra</label>
    <div class="col-sm-9">
        <select class="form-select" id="Nombre_hembra" name="Nombre_hembra" required>
            <?php

            $sql_hembras = "SELECT * FROM animales WHERE \"Sexo\" = 'Hembra'";
            $result_hembras = $conn->query($sql_hembras);
            while ($valores_hembra = $result_hembras->fetch(PDO::FETCH_ASSOC)) {
                $selected = ($valores_hembra['Id_animal'] == $espacio_actual) ? 'selected' : '';
                echo '<option value="' . $valores_hembra['Id_animal'] . '" ' . $selected . '>' . $valores_hembra['Nombre'] . '</option>';
            }
            ?>
        </select>
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre del macho</label>
    <div class="col-sm-9">
        <select class="form-select" id="Nombre_macho" name="Nombre_macho" required>
            <?php
            $sql_machos = "SELECT * FROM animales WHERE \"Sexo\" = 'Macho'";
            $result_machos = $conn->query($sql_machos);
            while ($valores_macho = $result_machos->fetch(PDO::FETCH_ASSOC)) {
                $selected = ($valores_macho['Id_animal'] == $espacio_actual) ? 'selected' : '';
                echo '<option value="' . $valores_macho['Id_animal'] . '" ' . $selected . '>' . $valores_macho['Nombre'] . '</option>';
            }
            ?>
        </select>
    </div>
</div>

                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de reproducción</label>
                                <div class="col-sm-9">
                                  <select class="form-select" id="Tipo_reproducción" name="Tipo_reproducción" required>
                                    <option <?php echo $fila["tipo_reproducción"]==='revision' ? "selected='selected'":""?> value="revision">revisión</option>
                                    <option <?php echo $fila["tipo_reproducción"]==='Inseminacion' ? "selected='selected'":""?> value="Inseminacion">Inseminación</option>
                                  </select>
                                </div>
                              </div>

                              <div class="row mb-2">
    <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha de Revisión</label>
    <div class="col-sm-9">
        <input type="date" class="form-control" name="Fecha_revision" id="Fecharevision_act" value='<?php echo $fila["Fecha_revision"]; ?>' min="2020-01-01" required>
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha Posible de parto</label>
    <div class="col-sm-9">
        <input type="date" class="form-control" name="Fecha_parto" id="Fechaparto_act" value='<?php echo $fila["Fecha_parto"]; ?>' required>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const fechaSiembraInput = document.getElementById("Fecharevision_act");
        const fechaCosechaInput = document.getElementById("Fechaparto_act");

        // Validación de la fecha de cosecha
        fechaCosechaInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha <= fechaSiembra) {
                alert("La fecha de parto no puede ser menor o igual a la fecha de revision.");
                fechaCosechaInput.value = "";  // Limpiar campo de fecha de cosecha si es inválido
                fechaCosechaInput.focus();     // Focalizar nuevamente en el campo de fecha de cosecha
            }
        });

        // Validación de la fecha de siembra (en caso de que se ingrese una fecha posterior a la cosecha)
        fechaSiembraInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha && fechaSiembra > fechaCosecha) {
                alert("La fecha de revicion no puede ser posterior a la fecha de parto.");
                fechaSiembraInput.value = "";  // Limpiar campo de fecha de siembra si es inválido
                fechaSiembraInput.focus();     // Focalizar nuevamente en el campo de fecha de siembra
            }
        });
    });
</script>




                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de fertilización</label>
                                <div class="col-sm-9">
                                  <select class="form-select" id="Tipo_fertilizacion" name="Tipo_fertilizacion" required>
                                    <option <?php echo $fila["Tipo_fertilizacion"]==='Artificial' ? "selected='selected'":""?> value="Artificial">Artificial</option>
                                    <option <?php echo $fila["Tipo_fertilizacion"]==='Natural' ? "selected='selected'":""?> value="Natural">Natural</option>
                                    <option <?php echo $fila["Tipo_fertilizacion"]==='Transferencia' ? "selected='selected'":""?> value="Transferencia">Transferencia</option>
                                  </select>
                                </div>
                              </div>

                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Encargado</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" name="Encargado" required placeholder="" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" value='<?php echo $fila["encargado"]; ?>'>
                                </div>
                              </div>

                              <br>

                              <div class="d-grid gap-2 col-6 mx-auto">

                                <input type="hidden" name="session_acceso" value="<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>">
                                <input type="hidden" name="session_id" value="<?php echo isset($_SESSION['Id_Usuario']) ? $_SESSION['Id_Usuario'] : ''; ?>">
                                <button type="submit" class="btn btn-success" name="actualizar">Actualizar</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <?php
                      $contador++; // Incrementar el contador en cada iteración
                    }
                  }

                  // Cerrar la conexión
                 
                  ?>
                </tbody>
              </table>

              <style>
                /*    CODIGO DE TRADUCCION   */
                .datatable-info {
                  color: whitesmoke;
                  font-size: 0;
                }
                .col-lg-12 {
                  margin-bottom: 5%;
                }
                .datatable-input::placeholder {
                 visibility: hidden;
                 content: "Referenciar";
               }
               .datatable-dropdown::after {
                content: "Entradas por página";
                position: absolute;
                left: 90px;
                top: 162px;
              }
              .datatable-dropdown>label {
                visibility: hidden;
              }

              .datatable-selector {
                visibility: visible;
              }
            </style>
            <!-- End Table with stripped rows -->
          </div>
        </div>
      </div>
    </div>
  </section>
</main><!-- End #main -->
<!-- ======= Footer ======= -->
<?php include_once("footer.php"); ?>
</body>
</html>

<script>
  function vaciarCampos() {
    $("#idformulario")[0].reset();
  }
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
</script> 
