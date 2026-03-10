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
  <title>Costos</title>

  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script> 
  <link rel="stylesheet"type="text/css" href="css_personalizado/estilo_costo_fijo.css">
</head>
<body>

    <!-- Codigo traducion footer Table -->
        <style type="text/css">
         tr > .datatable-empty{
          color: white;
          }
        </style>
    <!-- ---------------------------- -->
    
  <!-- mensaje de sesion-->
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

      // Resto del código de tu página costo_fijo.php
      // ...
      ?>
      <!-- modal de registrar-->
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 900px;">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #0d6efd; color: white;">
              <h5 class="modal-title text-center w-100">Registro del Costo Variable  </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
              <form method="POST" action="procesar/procesar_costo_variable.php" style="padding: 0 50px 0 50px;"
              onsubmit="return validarFormulario();">
              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Nombre</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="validationCustom01" required name="Nombre" required
                  placeholder="Ej: Electricidad " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                </div>
              </div>

              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Monto (Bs)</label>
                <div class="col-sm-9">
                  <input oninput="validateprecio(this)" class="form-control" type="number" name="Monto" required placeholder="Ej: 150"
                  oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
              </div>
              <script>
  function validateprecio(input) {
        let maxLength = 5;
        if (input.value.length > maxLength) {
            input.value = input.value.slice(0, maxLength);
        }
        if (input.value < 1) {
            input.value = "";
        }
    }
</script>
              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Observaciones </label>
                <div class="col-sm-9">
                  <input class="form-control" type="text" name="Observaciones" required placeholder="Eje: Consumo"
                  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                </div>
              </div>

              <div class="row mb-2">
                <label for="tipo_cultivo" class="col-sm-3 col-form-label">Prioridad</label>
                <div class="col-sm-9">
                  <select class="form-select" id="Prioridad" name="Prioridad" required>
                    <option value="">Selecciona una opción</option>
                    <option value="Alta">Alta</option>
                    <option value="Media">Media</option>
                    <option value="Baja">Baja</option>
                  </select>
                </div>
              </div>

              <div class="row mb-2" style="padding-left: 25%;">
                <div class="col-sm-9" style="text-align: center">
                  <!-- Campo oculto para enviar la sesión del usuario -->
                  <input type="hidden" name="session_acceso" value="<?php echo $_SESSION['Usuario'] ?>">
                  <input type="hidden" name="session_id" value="<?php echo $_SESSION['Id_Usuario'] ?>">
                  <input type="submit" class="btn btn-primary" value="Guardar"
                  style="width: 100px; background-color: green; color: white;">
                  <a class="btn btn-secondary" style="width: 100px;" onclick="vaciarCampos()">Vaciar</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!------- tabla -------->
    <main id="main" class="main">
      <section class="section">
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Finanzas</li>
            <li class="breadcrumb-item">Costos</li>
            <li class="breadcrumb-item active">Costo Variables</li>
          </ol>
        </nav>
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <p style="position: absolute; right:165px; top:130px;"> Buscar... </p>
                <h5 class="card-title" style="color:black; font-size:40px; margin-left:7%;"> Costos Variables</h5>
                <button  type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal"
                style="margin-right:82.5%; margin-top:10px; margin-bottom:8px;" title="Agregar"><i class="ri-add-fill"
                style="color:white;"></i>Agregar &nbsp</button>
                <table class="table datatable">
                  <thead>
                    <tr>
                      <th scope="col">Nombre </th>
                      <th scope="col">Monto</th>
                      <th scope="col">Observaciones</th>
                      <th scope="col">Prioridad</th>
                      <th scope="col">Fecha/Hora Registro</th>
                      <th scope="col">Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();

$sql = "SELECT * FROM costo_variable ORDER BY \"Id_variable\"";
$result = $conn->query($sql);

if ($result->rowCount() > 0) {
    $contador = 1;
    while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>

                          <td>
                            <?php echo $fila['Nombre']; ?>
                          </td>
                          <td>
                            <?php echo $fila['Monto'] . "Bs"; ?>
                          </td>
                          <td>
                            <?php echo $fila['Observaciones']; ?>
                          </td>
                          <td>
                            <?php echo $fila['Prioridad']; ?>
                          </td>
                          <td>
                            <?php echo $fila['Fecha_hora_registro']; ?>
                          </td>
                          <td>
                            <div class="btn-group" role="group">

                              <?php if($ver == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                              <!-- Boton-modal [ver] -->
                              <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                              type="button" data-bs-toggle="modal" data-bs-target='#basicModal-VER<?php echo $fila["Id_variable"]; ?>'
                              title="Editar">
                              <i class="ri-eye-fill" style="color:#17E45B"></i>
                            </a>
                            <?php } ?>  <!-- ← CODIGO A COPIAR -->


                            <?php if($editar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                            <!-- Boton-modal [Editar] -->
                            <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                            type="button" data-bs-toggle="modal" data-bs-target='#basicModal-<?php echo $fila["Id_variable"]; ?>'
                            title="Editar">
                            <i class="ri-ball-pen-fill" style="color:#E5D001;"></i>
                          </a>
                          <?php } ?>  <!-- ← CODIGO A COPIAR -->


                          <?php if($eliminar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                          <!-- Boton-modal [eliminar] -->
                          <a type="button" data-bs-toggle="modal" data-bs-target="#smallModal-<?php echo $fila["Id_variable"]; ?>"
                            style="color:none;  margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                            title="Eliminar">
                            <i class="ri-delete-bin-2-line" style="color:#EE0D0D;"></i>
                          </a>
                        <?php } ?>

                        <!-- modal [eliminar] -->
                        <div class="modal fade" id="smallModal-<?php echo $fila["Id_variable"]; ?>" tabindex="-1">
                          <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                              <div class="modal-header" style="text-align:center; display: inline-block; background-color:#F25050;">
                                <h5 class="modal-title" style="background-color:#F25050; color:white;">¡ATENCION!</h5>
                              </div>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position:absolute; left:91%; top:2px;"></button>
                              <div class="modal-body">
                                ¿Desea Eliminar este Registro?
                              </div>
                              <div class="modal-footer">
                                <a style="top:-1px; left:-60px; position: relative; color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                                href='deshabilitaciones/deshabilitar_costo_variable.php?id=<?php echo $fila["Id_variable"] ?>&session_acceso=<?php echo isset($_SESSION["Usuario"]) ? $_SESSION["Usuario"] : ""; ?>&session_id=<?php echo isset($_SESSION["Id_Usuario"]) ? $_SESSION["Id_Usuario"] : ""; ?>'
                                title="Eliminar">
                                <span class="btn btn-outline-danger">Eliminar</span>
                              </a>
                              <button style="left:px; position: relative;" type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!------- modal de ver -------->
                      <div class="modal fade" id="basicModal-VER<?php echo $fila["Id_variable"]; ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg" style="max-width: 900px;">
                          <div class="modal-content">
                            <div class="modal-header" style="background-color: #0d6efd; color: white;">
                              <h5 class="modal-title text-center w-100">Ver información </h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"
                              aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form method="POST" action="actualizar/actualizar_costo_variable.php"
                              onsubmit="return validarFormulario();">
                              <div>
                                <input style="pointer-events: none;" type="hidden" class="form-control" name="Id_variable"
                                value='<?php echo $fila["Id_variable"]; ?>'>
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre</label>
                                <div class="col-sm-9">
                                  <input style="pointer-events: none;" type="text" class="form-control" name="Nombre"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                  value='<?php echo $fila["Nombre"]; ?>'>
                                </div>
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Monto (Bs)</label>
                                <div class="col-sm-9">
                                  <input oninput="validateprecio(this)" style="pointer-events: none;" type="number" class="form-control" name="Monto"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                  value='<?php echo $fila["Monto"]; ?>'>
                                </div>
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Observaciones</label>
                                <div class="col-sm-9">
                                  <input style="pointer-events: none;" type="text" class="form-control" name="Observaciones"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                  value='<?php echo $fila["Observaciones"]; ?>'>
                                </div>
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Prioridad</label>
                                <div class="col-sm-9">
                                  <select style="pointer-events: none;" class="form-select" id="Prioridad" name="Prioridad" required>
                                    <option <?php echo $fila["Prioridad"] === 'Alta' ? "selected='selected'" : "" ?>value="Alta">Alta</option>
                                    <option <?php echo $fila["Prioridad"] === 'Media' ? "selected='selected'" : "" ?>value="Media">Media</option>
                                    <option <?php echo $fila["Prioridad"] === 'Baja' ? "selected='selected'" : "" ?>value="Baja">Baja</option>
                                  </select>
                                </div>
                              </div>
                              <div class="row mb-2">
                              </div>
                              <div class="modal-footer">
                                <input style="pointer-events: none;" type="hidden" name="session_acceso" value="<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>">
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
                  <div class="modal fade" id="basicModal-<?php echo $fila["Id_variable"]; ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg" style="max-width: 900px;">
                      <div class="modal-content">
                        <div class="modal-header" style="background-color: #0d6efd; color: white;">
                          <h5 class="modal-title text-center w-100">Actualizar información </h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"
                          aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <form method="POST" action="actualizar/actualizar_costo_variable.php"
                          onsubmit="return validarFormulario();">
                          <div>
                            <input type="hidden" class="form-control" name="Id_variable"
                            value='<?php echo $fila["Id_variable"]; ?>'>
                          </div>
                          <div class="row mb-2">
                            <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre</label>
                            <div class="col-sm-9">
                              <input type="text" class="form-control" name="Nombre"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                              value='<?php echo $fila["Nombre"]; ?>'>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <label class="col-sm-3 col-form-label" style="color:#21618C;">Monto (Bs)</label>
                            <div class="col-sm-9">
                              <input oninput="validateprecio(this)" type="number" class="form-control" name="Monto"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                              value='<?php echo $fila["Monto"]; ?>'>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <label class="col-sm-3 col-form-label" style="color:#21618C;">Observaciones</label>
                            <div class="col-sm-9">
                              <input type="text" class="form-control" name="Observaciones"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                              value='<?php echo $fila["Observaciones"]; ?>'>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <label class="col-sm-3 col-form-label" style="color:#21618C;">Prioridad</label>
                            <div class="col-sm-9">
                              <select class="form-select" id="Prioridad" name="Prioridad" required>
                                <option <?php echo $fila["Prioridad"] === 'Alta' ? "selected='selected'" : "" ?>value="Alta">Alta</option>
                                <option <?php echo $fila["Prioridad"] === 'Media' ? "selected='selected'" : "" ?>value="Media">Media</option>
                                <option <?php echo $fila["Prioridad"] === 'Baja' ? "selected='selected'" : "" ?>value="Baja">Baja</option>
                              </select>
                            </div>
                          </div>
                          <div class="row mb-2">
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
                      $contador++; // Incrementar el contador en cada iteración
                    }
                  }
                  ?>
                </tbody>
              </table>
              <!-- <span style="display:inline-block; position:relative; top:-20px;">N° de registros
                <?php $contador; ?>
              </span> -->
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
    document.getElementsByName("Nombre")[0].value = "";
    document.getElementsByName("Monto")[0].value = "";
    document.getElementsByName("Observaciones")[0].value = "";
    document.getElementsByName("Prioridad")[0].value = "";
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