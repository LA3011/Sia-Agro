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

<?php include_once("header.php")  ?>
<?php include_once("Sidebar.php") ?>

<head>
  <meta charset="utf-8">
  <title>Listado de insumos</title>
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script> 
  <link rel="stylesheet"type="text/css" href="css_personalizado/estilo_datos_veterinarios.css">
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

      <!-- modal de registrar-->
      <div class="modal fade" id="largeModal" tabindex="-1">
        <div class="modal-dialog modal-lg" style="max-width: 950px;">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #0d6efd; color: white;">
              <h5 class="modal-title text-center w-100">Registro de Datos Veterinarios</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="procesar/procesar_datos_veterinarios.php" style="padding: 0 50px 0 50px; " id="p1">
              <br>

              
            <?php
                  include_once("conexion/conexion.php");
                  $conn = cconexion::ConexionBD();
                  $query = "SELECT \"Id_animal\", \"Nombre\" FROM animales";
                  $result = $conn->query($query);
                  $options = '';
                  if ($result->rowCount() > 0) {
                  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                  $idLote = $row['Id_animal'];
                  $nombreLote = $row['Nombre'];
                  $options .= '<option value="' . $idLote . '">' . $nombreLote . '</option>';
                    }
                   } else {
                    $options = '<option value="">No se encontraron lotes</option>';
                   }
                   $conn = null;
                         ?>
              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Animal</label>
                <div class="col-sm-9">
                  <select class="form-select" id="validationCustom01" name="animal" required>
                  <option value="">Seleccione una opción</option>
                  <?php echo $options; ?>
                  </select>
                  </div>
            </div>

            <div class="row mb-2">
              <label for="inputText" class="col-sm-3 col-form-label">Tipo de Tratamiento</label>
              <div class="col-sm-9">
                <input class="form-control" type="text" name="tipo_Tratamiento" required placeholder="Ej: Intramuscular"
                oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
              </div>
            </div>

            <div class="row mb-2">
              <label for="inputText" class="col-sm-3 col-form-label">Nombre del Tratamiento</label>
              <div class="col-sm-9">
                <input class="form-control" type="text" name="name_Tratamiento" required placeholder="Ej: Vacunación"
                oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
              </div>
            </div>      
            

            <div class="row mb-2">
              <label for="inputText" class="col-sm-3 col-form-label">Veterinario</label>
              <div class="col-sm-9">
                <input class="form-control" type="text" name="Veterinario" required placeholder="Ej: Diego"
                oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
              </div>
            </div>

            <div class="row mb-2">
              <label for="inputText" class="col-sm-3 col-form-label">Diagnóstico</label>
              <div class="col-sm-9">
                <input class="form-control" type="text" name="Diagnostico" required placeholder="Ej: Parásitos"
                oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
              </div>
            </div>

            <div class="row mb-2">
              <label for="inputText" class="col-sm-3 col-form-label">Número de días de Tratamiento</label>
              <div class="col-sm-9">
                <input oninput="validateAnimalNumber(this)" class="form-control" type="number" name="Fecha_Tratamiento" required placeholder="Ej: 5"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
              </div>
            </div>
            <script>
    function validateAnimalNumber(input) {
        let maxLength = 2;

        // Limitar la cantidad de caracteres a 3 dígitos
        if (input.value.length > maxLength) {
            input.value = input.value.slice(0, maxLength);
        }

        // Evitar valores negativos y ceros
        if (input.value < 1) {
            input.value = "";
        }
    }
</script>
            <div class="row mb-2">
              <label for="inputText" class="col-sm-3 col-form-label">Precio (Bs)</label>
              <div class="col-sm-9">
                <input oninput="validateprecio(this)" class="form-control" type="number" name="Precio" required placeholder="Ej: 150"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
              </div>
            </div>

            <script>
    function validateprecio(input) {
        let maxLength = 5;

        // Limitar la cantidad de caracteres a 3 dígitos
        if (input.value.length > maxLength) {
            input.value = input.value.slice(0, maxLength);
        }

        // Evitar valores negativos y ceros
        if (input.value < 1) {
            input.value = "";
        }
    }
</script>

            <div class="row mb-2" style="padding-left: 20%;">
              <div class="col-sm-9" style="text-align: center">
               <!-- Campo oculto para enviar la sesión del usuario -->
               <input type="hidden" name="session_acceso" value="<?php echo $_SESSION['Usuario'] ?>">
               <input type="hidden" name="session_id" value="<?php echo $_SESSION['Id_Usuario'] ?>">
               <a class="btn btn-secondary" style="width: 100px;" id="p2">Vaciar</a>
               <input type="submit" class="btn btn-primary" value="Guardar"
               style="width: 100px; background-color: green; color: white;">
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
          <a href="insumos_animal.php" class="breadcrumb-item active">Insumos</a>
          <li class="breadcrumb-item active">Veterinario</li>
        </ol>
      </nav>

      <div class="container">
        <div class="row justify-content-center">

          <div class="col-lg-4">
            <div class="card" style="background-color:  #99ff99; color: white; height: 133px; border: 1px solid black; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="icon">
                    <i class="fas fa-chart-pie fa-3x"></i>
                  </div>
                  <div class="content ml-3 text-right">
                    <h5 class="card-title">Veterinario</h5>
                    <button  type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal"
                    style="margin-right:82.5%; margin-top:10px; margin-bottom:8px; width: 290px;" title="Agregar"><i class="ri-add-fill"
                    style="color:white;"></i>Agregar &nbsp
                  </button>                </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="card" style="background-color: #80F2F4; color: white;">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="icon">
                    <i class="fas fa-chart-pie fa-3x"></i>
                  </div>
                  <div class="content ml-3 text-right">
                    <h5 class="card-title">Dieta Animal</h5>
                    <a  style="width: 150px;"href="dieta_animal.php" class="btn btn-primary mt-2 float-right">Ver más</a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="card" style="background-color: #F3B665; color: white;">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="icon">
                    <i class="fas fa-chart-pie fa-3x"></i>
                  </div>
                  <div class="content ml-3 text-right">
                    <h5 class="card-title">Comida Ani.</h5>
                    <a href="comida_animal.php"  style="width: 150px;"href="#" class="btn btn-primary mt-2 float-right">Ver más</a>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">Animal</th>
                    <th scope="col">Tipo/Tratamiento</th>
                    <th scope="col">Nombre/Tratamiento</th>
                    <th scope="col">Veterinario</th>
                    <th scope="col">Diagnóstico</th>
                    <th scope="col">Día/Tratamiento</th>
                    <th scope="col">Precio Unitario Bs</th>
                    <th scope="col">Acción </th>
                  </tr>
                </thead>
                <tbody>
                <?php
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();
$sql = "SELECT * FROM datos_veterinarios ORDER BY \"Id_Veterinario\" ";
$result = $conn->query($sql);
if ($result->rowCount() > 0) {
    $contador = 1;
    while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {
      $id_animal = $fila['id_animal'];
      $sql_id_animal = "SELECT \"Nombre\" FROM animales WHERE \"Id_animal\" = :id_animal";
      $stmt_id_animal = $conn->prepare($sql_id_animal);
      $stmt_id_animal->bindParam(':id_animal', $id_animal);
      $stmt_id_animal->execute();
      $nombre_animal = $stmt_id_animal->fetchColumn();

      ?>
                      <tr>
                      <td><?php echo $nombre_animal; ?></td>
                        <td>
                          <?php echo $fila['Tipo_Tratamiento']; ?>
                        </td>
                        <td>
                          <?php echo $fila['Nombre_tratamiento']; ?>
                        </td>
                        
                        <td>
                          <?php echo $fila['Veterinario']; ?>
                        </td>
                        <td>
                          <?php echo $fila['Diagnostico']; ?>
                        </td>
                        <td>
                          <?php echo $fila['Dias_tratamiento']; ?>
                        </td>
                        <td>
                          <?php echo $fila['Precio'] . "Bs"; ?>
                        </td>
                        
                        <td>
                          <div class="btn-group" role="group">

                              <?php if($ver == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                              <!-- Boton-modal [ver] -->
                              <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                              type="button" data-bs-toggle="modal" data-bs-target='#basicModal-VER<?php echo $fila["Id_Veterinario"]; ?>'
                              title="Editar">
                              <i class="ri-eye-fill" style="color:#17E45B"></i>
                            </a>
                            <?php } ?>  <!-- ← CODIGO A COPIAR -->

                            
                            <?php if($editar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                            <!-- Boton-modal [Editar] -->
                            <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                            type="button" data-bs-toggle="modal" data-bs-target='#basicModal-<?php echo $fila["Id_Veterinario"]; ?>'
                            title="Editar">
                            <i class="ri-ball-pen-fill" style="color:#E5D001;"></i>
                          </a>
                          <?php } ?>  <!-- ← CODIGO A COPIAR -->


                          <?php if($eliminar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                          <!-- Boton-modal [eliminar] -->
                          <a type="button" data-bs-toggle="modal" data-bs-target="#smallModal-<?php echo $fila["Id_Veterinario"]; ?>"
                            style="color:none;  margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                            title="Eliminar">
                            <i class="ri-delete-bin-2-line" style="color:#EE0D0D;"></i>
                          </a>
                        <?php } ?>

                        <!-- modal [eliminar] -->
                        <div class="modal fade" id="smallModal-<?php echo $fila["Id_Veterinario"]; ?>" tabindex="-1">
                          <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                              <div class="modal-header"
                              style="text-align:center; display: inline-block; background-color:#F25050;">
                              <h5 class="modal-title" style="background-color:#F25050; color:white;">¡ATENCION!</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="position:absolute; left:91%; top:2px;"></button>
                            <div class="modal-body">
                              ¿Desea Eliminar este Registro?
                            </div>
                            <div class="modal-footer">
                              <a style="top:-1px; left:-60px; position: relative; color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                              href='deshabilitaciones/deshabilitar_datos_veterinarios.php?id=<?php echo $fila["Id_Veterinario"] ?>&session_acceso=<?php echo isset($_SESSION["Usuario"]) ? $_SESSION["Usuario"] : ""; ?>&session_id=<?php echo isset($_SESSION["Id_Usuario"]) ? $_SESSION["Id_Usuario"] : ""; ?>'
                              title="Eliminar">
                              <span class="btn btn-outline-danger">Eliminar</span>
                            </a>
                            <button style="left:px; position: relative;" type="button"
                            class="btn btn-outline-success" data-bs-dismiss="modal">Cerrar</button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!------- modal de ver -------->
                    <div class="modal fade" id="basicModal-VER<?php echo $fila["Id_Veterinario"]; ?>" tabindex="-1">
                      <div class="modal-dialog modal-lg" style="max-width: 950px;">
                        <div class="modal-content">
                          <div class="modal-header" style="background-color: #0d6efd; color: white;">
                            <h5 class="modal-title text-center w-100">Ver información</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <form method="POST" action="actualizar/actualizar_datos_veterinarios.php">
                              <div>
                                <input style="pointer-events: none;" type="hidden" class="form-control" name="Id_Veterinario"
                                value='<?php echo $fila["Id_Veterinario"]; ?>'>
                              </div>

                              <div class="row mb-2">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Animal</label>
                                 <div class="col-sm-9">
                                 <?php
                                        $id_animal = $fila["id_animal"];
                                    $sql_animal = "SELECT \"Nombre\" FROM \"animales\" WHERE \"Id_animal\" = :id_animal";
                                   $stmt = $conn->prepare($sql_animal);
                                   $stmt->bindParam(':id_animal', $id_animal);
                                     $stmt->execute();
                                     $nombre_animal = $stmt->fetchColumn();
                                     $sql_animales = "SELECT * FROM \"animales\"";
                                     $result_potreros = $conn->query($sql_animales);
                                     echo '<select style="pointer-events: none;" class="form-select" id="animal" name="Animal" required>';
                                     while ($valores_potrero = $result_potreros->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($valores_potrero['Nombre'] == $nombre_animal) ? 'selected' : '';
                                   echo '<option value="' . $valores_potrero['Id_animal'] . '" ' . $selected . '>' . $valores_potrero['Nombre'] . '</option>';
                                      }
                                 echo '</select>';
                                   ?>
                                      </div>
                                  </div>

                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de
                                Tratamiento</label>
                                <div class="col-sm-9">
                                  <input style="pointer-events: none;" type="text" class="form-control" name="Tipo_Tratamiento" required
                                  placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                  value='<?php echo $fila["Tipo_Tratamiento"]; ?>'>
                                </div>
                              </div>

                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre del
                                tratamiento</label>
                                <div class="col-sm-9">
                                  <input style="pointer-events: none;" type="text" class="form-control" name="Nombre_tratamiento" required
                                  placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                  value='<?php echo $fila["Nombre_tratamiento"]; ?>'>
                                </div>
                              </div>

                              
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Veterinario</label>
                                <div class="col-sm-9">
                                  <input style="pointer-events: none;" type="text" class="form-control" name="Veterinario" required
                                  placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                  value='<?php echo $fila["Veterinario"]; ?>'>
                                </div>
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Diagnóstico</label>
                                <div class="col-sm-9">
                                  <input style="pointer-events: none;" type="text" class="form-control" name="Diagnostico" required
                                  placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                  value='<?php echo $fila["Diagnostico"]; ?>'>
                                </div>
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Días de
                                tratamiento</label>
                                <div class="col-sm-9">
                                  <input style="pointer-events: none;" type="text" class="form-control" name="Dias_tratamiento" required
                                  placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                  value='<?php echo $fila["Dias_tratamiento"]; ?>'>
                                </div>
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Precio (Bs)</label>
                                <div class="col-sm-9">
                                  <input oninput="validateprecio(this)" style="pointer-events: none;" type="text" class="form-control" name="Precio" required placeholder=""
                                  oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                  value="<?php echo $fila['Precio']; ?>" readonly>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <input type="hidden" name="session_acceso" value="<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>">
                                <input type="hidden" name="session_id" value="<?php echo isset($_SESSION['Id_Usuario']) ? $_SESSION['Id_Usuario'] : ''; ?>">
                                <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!------- modal de actualizar -------->

                  <div class="modal fade" id="basicModal-<?php echo $fila["Id_Veterinario"]; ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg" style="max-width: 950px;">
                      <div class="modal-content">
                        <div class="modal-header" style="background-color: #0d6efd; color: white;">
                          <h5 class="modal-title text-center w-100">Actualizar Datos Veterinarios</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"
                          aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <form method="POST" action="actualizar/actualizar_datos_veterinarios.php">
                            <div>
                              <input type="hidden" class="form-control" name="Id_Veterinario"
                              value='<?php echo $fila["Id_Veterinario"]; ?>'>
                            </div>

                            <div class="row mb-2">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Animal</label>
                                 <div class="col-sm-9">
                                 <?php
                                        $id_animal = $fila["id_animal"];
                                    $sql_animal = "SELECT \"Nombre\" FROM \"animales\" WHERE \"Id_animal\" = :id_animal";
                                   $stmt = $conn->prepare($sql_animal);
                                   $stmt->bindParam(':id_animal', $id_animal);
                                     $stmt->execute();
                                     $nombre_animal = $stmt->fetchColumn();
                                     $sql_animales = "SELECT * FROM \"animales\"";
                                     $result_potreros = $conn->query($sql_animales);
                                     echo '<select class="form-select" id="animal" name="Animal" required>';
                                     while ($valores_potrero = $result_potreros->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($valores_potrero['Nombre'] == $nombre_animal) ? 'selected' : '';
                                   echo '<option value="' . $valores_potrero['Id_animal'] . '" ' . $selected . '>' . $valores_potrero['Nombre'] . '</option>';
                                      }
                                 echo '</select>';
                                   ?>
                                      </div>
                                  </div>
                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de
                              Tratamiento</label>
                              <div class="col-sm-9">
                                <input type="text" class="form-control" name="Tipo_Tratamiento" required
                                placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                value='<?php echo $fila["Tipo_Tratamiento"]; ?>'>
                              </div>
                            </div>

                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre del
                              tratamiento</label>
                              <div class="col-sm-9">
                                <input type="text" class="form-control" name="Nombre_tratamiento" required
                                placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                value='<?php echo $fila["Nombre_tratamiento"]; ?>'>
                              </div>
                            </div>

                            
                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Veterinario</label>
                              <div class="col-sm-9">
                                <input type="text" class="form-control" name="Veterinario" required
                                placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                value='<?php echo $fila["Veterinario"]; ?>'>
                              </div>
                            </div>
                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Diagnóstico</label>
                              <div class="col-sm-9">
                                <input type="text" class="form-control" name="Diagnostico" required
                                placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                value='<?php echo $fila["Diagnostico"]; ?>'>
                              </div>
                            </div>
                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Días de
                              tratamiento</label>
                              <div class="col-sm-9">
                                <input oninput="validateAnimalNumber(this)" type="text" class="form-control" name="Dias_tratamiento" required
                                placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                value='<?php echo $fila["Dias_tratamiento"]; ?>'>
                              </div>
                            </div>
                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Precio (Bs)</label>
                              <div class="col-sm-9">
                                <input oninput="validateprecio(this)" type="text" class="form-control" name="Precio" required placeholder=""
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                value="<?php echo $fila['Precio']; ?>" readonly>
                                <small class="text-muted">Este campo no se puede editar</small>
                              </div>
                            </div>
                            
                            <div class="modal-footer">
                              <input type="hidden" name="session_acceso" value="<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>">
                              <input type="hidden" name="session_id" value="<?php echo isset($_SESSION['Id_Usuario']) ? $_SESSION['Id_Usuario'] : ''; ?>">

                              <button type="submit" class="btn btn-success" name="actualizar">Actualizar</button>
                              <button type="button" class="btn btn-secondary"
                              data-bs-dismiss="modal">Cancelar</button>
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
                      $contador++; 
                    }
                  }
                  ?>
                </tbody>
              </table>
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

<script type="text/javascript">
  $(document).on('click','#p2',function(){
    $('#p1').trigger('reset');    
  });
</script>


<script>
  function vaciarCampos() {
    document.getElementsByName("animal")[0].value = "";
    document.getElementsByName("Estatus")[0].value = "";
    document.getElementsByName("tipo_Tratamiento")[0].value = "";
    document.getElementsByName("name_Tratamiento")[0].value = "";
    document.getElementsByName("Enfermedad")[0].value = "";
    document.getElementsByName("Encargado")[0].value = "";
    document.getElementsByName("Veterinario")[0].value = "";
    document.getElementsByName("Diagnostico")[0].value = "";
    document.getElementsByName("Fecha_Tratamiento")[0].value = "";
    document.getElementsByName("Precio")[0].value = "";
    document.getElementsByName("Peso")[0].value = "";
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