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

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Listado de Dieta animal</title>
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script>
  <link rel="stylesheet"type="text/css" href="css_personalizado/estilo_dieta_animal.css"> 
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
        <div class="modal-dialog modal-lg" style="max-width: 900px;">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #0d6efd; color: white;">
              <h5 class="modal-title text-center w-100">Registro de Dieta</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="procesar/procesar_dieta_animal.php" style="padding: 0 50px 0 50px;" id="p1">
              <br>
              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">fecha Inicio/Dieta</label>
                <div class="col-sm-9">
                  <input min="2020-01-01" type="date" class="form-control" id="fecha_inicio" placeholder="Ej:  " required
                  name="Fecha_inicio_dieta">
                </div>
              </div>

              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">fecha Final/Dieta</label>
                <div class="col-sm-9">
                  <input type="date" class="form-control" id="fecha_final" placeholder="Ej:  " required
                  name="Fecha_final_dieta">
                </div>
              </div>

              <script>
    document.addEventListener("DOMContentLoaded", function () {
        const fechaSiembraInput = document.getElementById("fecha_inicio");
        const fechaCosechaInput = document.getElementById("fecha_final");
        
        // Validación de la fecha de cosecha
        fechaCosechaInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha <= fechaSiembra) {
                alert("La fecha final de la dieta no puede ser menor o igual a la fecha de inicio.");
                fechaCosechaInput.value = "";  // Limpiar campo de fecha de cosecha si es inválido
                fechaCosechaInput.focus();     // Focalizar nuevamente en el campo de fecha de cosecha
            }
        });

        // Validación de la fecha de siembra (en caso de que se ingrese una fecha posterior a la cosecha)
        fechaSiembraInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha && fechaSiembra > fechaCosecha) {
                alert("La fecha de inicio de la dieta no puede ser posterior a la final.");
                fechaSiembraInput.value = "";  // Limpiar campo de fecha de siembra si es inválido
                fechaSiembraInput.focus();     // Focalizar nuevamente en el campo de fecha de siembra
            }
        });
    });
</script>


              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Proteínas</label>
                <div class="col-sm-9">
                  <input class="form-control" type="text" name="Proteinas" required placeholder="PROFIT"
"
                  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                </div>
              </div>

              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Nota</label>
                <div class="col-sm-9">
                  <input class="form-control" type="text" name="Nota" required placeholder="Ninguna"
                  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                </div>
              </div>

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
                  <select class="form-select" id="validationCustom01" name="Animal" required>
                  <option value="">Seleccione una opción</option>
                  <?php echo $options; ?>
                  </select>
                  </div>
            </div>
              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Ingredientes</label>
                <div class="col-sm-9">
                  <input class="form-control" type="text" name="Ingredientes" required placeholder="Suero Leche" required oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                </div>
              </div>
              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Precio (Bs)</label>
                <div class="col-sm-9">
                  <input  oninput="validateprecio(this)" class="form-control" type="number" name="Precio" required placeholder="Ej: 150"
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
              <br>
              <div class="row mb-2" style="padding-left: 20%;">
                <div class="col-sm-9" style="text-align: center">
                  <a class="btn btn-secondary" style="width: 100px;" id="p2">Vaciar</a>
                  <input type="hidden" name="session_acceso" value="<?php echo $_SESSION['Usuario'] ?>">
                  <input type="hidden" name="session_id" value="<?php echo $_SESSION['Id_Usuario'] ?>">
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
              <li class="breadcrumb-item active">Dieta</li>
            </ol>
          </nav>

          <div class="container">
            <div class="row justify-content-center">

              <div class="col-lg-4">
                <div class="card" style="background-color:  #99ff99; color: white; ">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="icon">
                        <i class="fas fa-chart-pie fa-3x"></i>
                      </div>
                      <div class="content ml-3 text-right">
                        <h5 class="card-title">Veterinario</h5>
                        <a style="width: 150px;" href="datos_veterinarios.php" class="btn btn-primary mt-2 float-right">Ver más</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="card" style="background-color: #80F2F4; color: white; height: 133px; border: 1px solid black; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="icon">
                        <i class="fas fa-chart-bar fa-3x"></i>
                      </div>
                      <div class="content ml-3 text-right">
                        <h5 class="card-title">Dieta Animal</h5>
                        <button  type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal"
                        style="margin-right:82.5%; margin-top:10px; margin-bottom:8px; width: 290px;" title="Agregar"><i class="ri-add-fill"
                        style="color:white;"></i>Agregar &nbsp
                      </button>                </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="card" style="background-color: #F4AA46; color: white;">
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
                        <th scope="col">fecha Inicio/Dieta</th>
                        <th scope="col">fecha Final/Dieta</th>
                        <th scope="col">Proteínas</th>
                        <th scope="col">Nota</th>
                        <th scope="col">Animal</th>
                        <th scope="col">Ingrediente</th>
                        <th scope="col">Precio Bs</th>
                        <th scope="col">Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();
$sql = "SELECT * FROM dieta_animal ORDER BY \"Id_Dieta\" ";
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
                            <td>
                              <?php echo date("d/m/Y", strtotime($fila["Fecha_inicio_dieta"])); ?>
                            </td>
                            <td>
                              <?php echo date("d/m/Y", strtotime($fila['Fecha_final_dieta'])); ?>
                            </td>
                            <td>
                              <?php echo $fila['Proteinas']; ?>
                            </td>
                            <td>
                              <?php echo $fila['Nota']; ?>
                            </td>
                            <td><?php echo $nombre_animal; ?></td>
                            <td>
                              <?php echo $fila['Ingredientes']; ?>
                            </td>

                            <td>
                              <?php echo $fila['Precio']; ?>
                            </td>

                            <td>
                              <div class="btn-group" role="group">

                                  <?php if($ver == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                                  <!-- Boton-modal [ver] -->
                                  <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                                  type="button" data-bs-toggle="modal" data-bs-target='#basicModal-VER<?php echo $fila["Id_Dieta"]; ?>'
                                  title="Editar">
                                  <i class="ri-eye-fill" style="color:#17E45B"></i>
                                </a>
                                <?php } ?>  <!-- ← CODIGO A COPIAR -->


                                <?php if($editar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                                <!-- Boton-modal [Editar] -->
                                <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                                type="button" data-bs-toggle="modal" data-bs-target='#basicModal-<?php echo $fila["Id_Dieta"]; ?>'
                                title="Editar">
                                <i class="ri-ball-pen-fill" style="color:#E5D001;"></i>
                              </a>
                              <?php } ?>  <!-- ← CODIGO A COPIAR -->


                              <?php if($eliminar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                              <!-- Boton-modal [eliminar] -->
                              <a type="button" data-bs-toggle="modal" data-bs-target="#smallModal-<?php echo $fila["Id_Dieta"]; ?>"
                                style="color:none;  margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                                title="Eliminar">
                                <i class="ri-delete-bin-2-line" style="color:#EE0D0D;"></i>
                              </a>
                            <?php } ?>





                            <!-- modal [eliminar] -->
                            <div class="modal fade" id="smallModal-<?php echo $fila["Id_Dieta"]; ?>" tabindex="-1">
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
                                  href='deshabilitaciones/deshabilitar_dieta_animal.php?id=<?php echo $fila["Id_Dieta"] ?>&session_acceso=<?php echo isset($_SESSION["Usuario"]) ? $_SESSION["Usuario"] : ""; ?>&session_id=<?php echo isset($_SESSION["Id_Usuario"]) ? $_SESSION["Id_Usuario"] : ""; ?>'
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
                        <div class="modal fade" id="basicModal-VER<?php echo $fila["Id_Dieta"]; ?>" role="dialog"
                          aria-labelledby="basicModal">
                          <div class="modal-dialog modal-lg" style="max-width: 900px;">
                            <div class="modal-content">
                              <div class="modal-header" style="background-color: #0d6efd; color: white;">
                                <h5 class="modal-title text-center w-100">Ver información</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <form method="POST" action="actualizar/actualizar_dieta_animal.php">
                                  <div>
                                    <input style="pointer-events: none;" type="hidden" class="form-control" name="Id_Dieta"
                                    value='<?php echo $fila["Id_Dieta"]; ?>'>
                                  </div>
                                  <div class="row mb-2">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha/inicio
                                    dieta</label>
                                    <div class="col-sm-9">
                                      <input style="pointer-events: none;" type="date" class="form-control" name="Fecha_inicio_dieta"
                                      value='<?php echo $fila["Fecha_inicio_dieta"]; ?>'>
                                    </div>
                                  </div>
                                  <div class="row mb-2">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha/Final
                                    dieta</label>
                                    <div class="col-sm-9">
                                      <input style="pointer-events: none;" type="date" class="form-control" name="Fecha_final_dieta"
                                      value='<?php echo $fila["Fecha_final_dieta"]; ?>'>
                                    </div>
                                  </div>
                                  <div class="row mb-2">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Proteínas</label>
                                    <div class="col-sm-9">
                                      <input style="pointer-events: none;" type="text" class="form-control" name="Proteinas" required placeholder=" "
                                      oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                      value='<?php echo $fila["Proteinas"]; ?>'>
                                    </div>
                                  </div>

                                  <div class="row mb-2">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Nota</label>
                                    <div class="col-sm-9">
                                      <input style="pointer-events: none;" type="text" class="form-control" name="Nota" required placeholder=" "
                                      oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                      value='<?php echo $fila["Nota"]; ?>'>
                                    </div>
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
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Ingredientes</label>
                                    <div class="col-sm-9">
                                      <input style="pointer-events: none;" type="text" class="form-control" name="Ingredientes" required
                                      placeholder=" " 
                                      value='<?php echo $fila["Ingredientes"]; ?>' oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                                    </div>
                                  </div>

                                  <div class="row mb-2">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Precio (Bs)</label>
                                    <div class="col-sm-9">
                                      <input style="pointer-events: none;" type="text" class="form-control" name="Precio" required placeholder=""
                                      oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                      value="<?php echo $fila['Precio']; ?>" readonly>
                                    </div>
                                  </div>
                                  <br>
                                  <div class="modal-footer">
                                    <input style="pointer-events: none;" type="hidden" name="session_acceso" value="<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>">
                                    <input style="pointer-events: none;" type="hidden" name="session_id" value="<?php echo isset($_SESSION['Id_Usuario']) ? $_SESSION['Id_Usuario'] : ''; ?>">

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
                      <div class="modal fade" id="basicModal-<?php echo $fila["Id_Dieta"]; ?>" role="dialog"
                        aria-labelledby="basicModal">
                        <div class="modal-dialog modal-lg" style="max-width: 900px;">
                          <div class="modal-content">
                            <div class="modal-header" style="background-color: #0d6efd; color: white;">
                              <h5 class="modal-title text-center w-100">Actualizar información</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"
                              aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form method="POST" action="actualizar/actualizar_dieta_animal.php">
                                <div>
                                  <input type="hidden" class="form-control" name="Id_Dieta"
                                  value='<?php echo $fila["Id_Dieta"]; ?>'>
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha/inicio
                                  dieta</label>
                                  <div class="col-sm-9">
                                    <input id="fecheiniciodieta_act" type="date" class="form-control" name="Fecha_inicio_dieta"
                                    value='<?php echo $fila["Fecha_inicio_dieta"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha/Final
                                  dieta</label>
                                  <div class="col-sm-9">
                                    <input id="fechafinaldieta_act" type="date" class="form-control" name="Fecha_final_dieta"
                                    value='<?php echo $fila["Fecha_final_dieta"]; ?>'>
                                  </div>
                                </div>

                                <script>
    document.addEventListener("DOMContentLoaded", function () {
        const fechaSiembraInput = document.getElementById("fecheiniciodieta_act");
        const fechaCosechaInput = document.getElementById("fechafinaldieta_act");
        
        // Validación de la fecha de cosecha
        fechaCosechaInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha <= fechaSiembra) {
                alert("La fecha final de la dieta no puede ser menor o igual a la fecha de inicio.");
                fechaCosechaInput.value = "";  // Limpiar campo de fecha de cosecha si es inválido
                fechaCosechaInput.focus();     // Focalizar nuevamente en el campo de fecha de cosecha
            }
        });

        // Validación de la fecha de siembra (en caso de que se ingrese una fecha posterior a la cosecha)
        fechaSiembraInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha && fechaSiembra > fechaCosecha) {
                alert("La fecha de inicio de la dieta no puede ser posterior a la final.");
                fechaSiembraInput.value = "";  // Limpiar campo de fecha de siembra si es inválido
                fechaSiembraInput.focus();     // Focalizar nuevamente en el campo de fecha de siembra
            }
        });
    });
</script>



                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Proteínas</label>
                                  <div class="col-sm-9">
                                    <input type="text" class="form-control" name="Proteinas" required placeholder=" "
                                    oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                    value='<?php echo $fila["Proteinas"]; ?>'>
                                  </div>
                                </div>

                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Nota</label>
                                  <div class="col-sm-9">
                                    <input type="text" class="form-control" name="Nota" required placeholder=" "
                                    oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                    value='<?php echo $fila["Nota"]; ?>'>
                                  </div>
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
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Ingredientes</label>
                                  <div class="col-sm-9">
                                    <input type="text" class="form-control" name="Ingredientes" required
                                    placeholder=" " 
                                    value='<?php echo $fila["Ingredientes"]; ?>'oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                                  </div>
                                </div>

                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Precio (Bs)</label>
                                  <div class="col-sm-9">
                                    <input type="text" class="form-control" name="Precio" required placeholder=""
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    value="<?php echo $fila['Precio']; ?>" readonly>
                                    <small class="text-muted">Este campo no se puede editar</small>
                                  </div>
                                </div>
                                <br>
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
                      $contador++; // Incrementar el contador en cada iteración
                    }
                  }

                
                  ?>
                </tbody>
              </table>

            
              <!-- <span style="display:inline-block; position:relative; top:-20px;">N° de registros <?php echo $contador-1; ?></span> -->
              

              <!-- End Table with stripped rows -->
            </div>
          </div>
        </div>
      </div>
    </section>

  </main>
  <?php include_once("footer.php"); ?>

</body>

</html>

<script>
  function vaciarCampos() {
    document.getElementsByName("Fecha_inicio_dieta")[0].value = "";
    document.getElementsByName("Fecha_final_dieta")[0].value = "";
    document.getElementsByName("Proteinas")[0].value = "";
    document.getElementsByName("Veterinario")[0].value = "";
    document.getElementsByName("Nota")[0].value = "";
    document.getElementsByName("Establo")[0].value = "";
    document.getElementsByName("Animal")[0].value = "";
    document.getElementsByName("Precio")[0].value = "";
    document.getElementsByName("Ingredientes")[0].value = "";
  }
</script>

<script type="text/javascript">
  $(document).on('click','#p2',function(){
    $('#p1').trigger('reset');    
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
</script>