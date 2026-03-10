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
  <title>Listado de Movimiento Animal</title>
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script> 
  <link rel="stylesheet"type="text/css" href="css_personalizado/estilo_movimiento_animal.css">
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
              <h5 class="modal-title text-center w-100">Registrar Información</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="procesar/procesar_movimiento_animal.php" style="padding: 0 50px 0 50px;">
              <br>
              <div class="row mb-2">
    <label for="validationCustom01" class="col-sm-3 col-form-label">Fecha</label>
    <div class="col-sm-9">
        <input 
            type="date" 
            class="form-control" 
            id="validationCustom01" 
            name="Fecha" 
            placeholder="Seleccione una fecha" 
            min="2020-01-01" 
            required>
    </div>
</div>




              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">cantidad Personal</label>
                <div class="col-sm-9">
                  <input type="number" oninput="validateAnimalNumber(this)" class="form-control" id="validationCustom01" placeholder="Ej: 15" required
                  name="Cantidad_personal"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
              </div>

              <script>
    function validateAnimalNumber(input) {
        let maxLength = 3;

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
                <label for="inputText" class="col-sm-3 col-form-label">Encargado</label>
                <div class="col-sm-9">
                  <input class="form-control" type="text" name="Encargado" required placeholder="Ej: Diego"  required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                </div>
              </div>

              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Detalle</label>
                <div class="col-sm-9">
                  <input class="form-control" type="text" name="Detalle" required placeholder="Ninguno"  required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                </div>
              </div>

              <?php
                  include_once("conexion/conexion.php");
                  $conn = cconexion::ConexionBD();
                  $query = "SELECT \"Id_potreros\", \"Nombre\" FROM potreros";
                  $result = $conn->query($query);
                  $options = '';
                  if ($result->rowCount() > 0) {
                  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                  $idLote = $row['Id_potreros'];
                  $nombreLote = $row['Nombre'];
                  $options .= '<option value="' . $idLote . '">' . $nombreLote . '</option>';
                    }
                   } else {
                    $options = '<option value="">No se encontraron lotes</option>';
                   }
                   $conn = null;
                         ?>
              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Establo</label>
                <div class="col-sm-9">
                  <select class="form-select" id="Establo" name="Establo" required>
                  <option value="">Seleccione una opción</option>
                  <?php echo $options; ?>
                  </select>
                </div>
              </div>
              <br>
              <div class="row mb-2" style="padding-left: 22%;">
                <div class="col-sm-9" style="text-align: center">
                  <input type="hidden" name="session_acceso" value="<?php echo $_SESSION['Usuario'] ?>">
                  <input type="hidden" name="session_id" value="<?php echo $_SESSION['Id_Usuario'] ?>">
                  <a class="btn btn-secondary" style="width: 100px;" onclick="vaciarCampos()">Vaciar</a>
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
              <li class="breadcrumb-item">Movimiento Animal</li>
              <li class="breadcrumb-item active">Actividad Animal</li>
            </ol>
          </nav>

          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title" style="color:black; font-size:40px; margin-left:7%;">Actividad Animal
                  </h5>

                  <button  type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#largeModal"
                  style="margin-right:82.5%; margin-top:10px; margin-bottom:8px;" title="Agregar"><i class="ri-add-fill"
                  style="color:white;"></i>Agregar &nbsp
                </button>

                <table class="table datatable">
                  <thead>
                    <tr>
                      <th scope="col">fecha</th>
                      <th scope="col">cantidad Personal</th>
                      <th scope="col">Encargado</th>
                      <th scope="col">Detalles</th>
                      <th scope="col">Establo</th>
                      <th scope="col">Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();
$sql = "SELECT * FROM actividad_animal ORDER BY \"Id_Actividad\" ";
$result = $conn->query($sql);
if ($result->rowCount() > 0) {
    $contador = 1;
    while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {
      $id_establo = $fila['Establo'];
      $sql_establo = "SELECT \"Nombre\" FROM potreros WHERE \"Id_potreros\" = :id_establo";
      $stmt_establo = $conn->prepare($sql_establo);
      $stmt_establo->bindParam(':id_establo', $id_establo);
      $stmt_establo->execute();
      $nombre_establo = $stmt_establo->fetchColumn();

      ?>
                        <tr>
                          <td>
                            <?php echo date("d/m/Y", strtotime($fila["Fecha"])); ?>
                          </td>
                          <td>
                            <?php echo $fila['Cantidad_personal']; ?>
                          </td>
                          <td>
                            <?php echo $fila['Encargado']; ?>
                          </td>
                          <td>
                            <?php echo $fila['Detalle']; ?>
                          </td>
                          <td><?php echo $nombre_establo; ?></td>
                          <td>
                            <div class="btn-group" role="group">
                              <?php if($ver == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                              <!-- Boton-modal [ver] -->
                              <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                              type="button" data-bs-toggle="modal" data-bs-target='#basicModal-VER<?php echo $fila["Id_Actividad"]; ?>'
                              title="Ver">
                              <i class="ri-eye-fill" style="color:#17E45B"></i>
                              <?php } ?>  <!-- ← CODIGO A COPIAR -->


                              <?php if($editar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                              <!-- Boton-modal [Editar] -->
                              <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                              type="button" data-bs-toggle="modal" data-bs-target='#basicModal-<?php echo $fila["Id_Actividad"]; ?>'
                              title="Editar">
                              <i class="ri-ball-pen-fill" style="color:#E5D001;"></i>
                            </a>
                            <?php } ?>  <!-- ← CODIGO A COPIAR -->


                            <?php if($eliminar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                            <!-- Boton-modal [eliminar] -->
                            <a type="button" data-bs-toggle="modal" data-bs-target='#smallModal-<?php echo $fila["Id_Actividad"]; ?>'
                              style="color:none;  margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                              title="Eliminar">
                              <i class="ri-delete-bin-2-line" style="color:#EE0D0D;"></i>
                            </a>
                          <?php } ?>


                          <!-- modal [eliminar] -->
                          <div class="modal fade" id='smallModal-<?php echo $fila["Id_Actividad"]; ?>' abindex="-1">
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
                                href='deshabilitaciones/deshabilitar_movimiento_animal.php?id=<?php echo $fila["Id_Actividad"] ?>&session_acceso=<?php echo isset($_SESSION["Usuario"]) ? $_SESSION["Usuario"] : ""; ?>&session_id=<?php echo isset($_SESSION["Id_Usuario"]) ? $_SESSION["Id_Usuario"] : ""; ?>'
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
                      <div class="modal fade" id='basicModal-VER<?php echo $fila["Id_Actividad"]; ?>' tabindex="-1">
                        <div class="modal-dialog modal-lg" style="max-width: 900px;">
                          <div class="modal-content">
                            <div class="modal-header" style="background-color: #0d6efd; color: white;">
                              <h5 class="modal-title text-center w-100">Ver información</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"
                              aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                              <form method="POST" action="actualizar/actualizar_movimiento_animal.php">
                                <div>
                                  <input style="pointer-events: none;" type="hidden" class="form-control" name="Id_Actividad"
                                  value='<?php echo $fila["Id_Actividad"]; ?>'>
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">fecha</label>
                                  <div class="col-sm-9">
                                    <input style="pointer-events: none;" type="date" class="form-control" name="Fecha"
                                    value='<?php echo $fila["Fecha"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Cantidad
                                  personal</label>
                                  <div class="col-sm-9">
                                    <input min="2020-01-01" style="pointer-events: none;" type="number" class="form-control" name="Cantidad_personal"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    value='<?php echo $fila["Cantidad_personal"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Encargado</label>
                                  <div class="col-sm-9">
                                    <input style="pointer-events: none;" type="text" class="form-control" name="Encargado"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                    value='<?php echo $fila["Encargado"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Detalle</label>
                                  <div class="col-sm-9">
                                    <input style="pointer-events: none;" type="text" class="form-control" name="Detalle"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                    value='<?php echo $fila["Detalle"]; ?>'>
                                  </div>
                                </div>


                               
                               <div class="row mb-2">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Establo</label>
                                 <div class="col-sm-9">
                                     <?php
                                        $id_establo = $fila["Establo"];
                                    $sql_establo = "SELECT \"Nombre\" FROM \"potreros\" WHERE \"Id_potreros\" = :id_establo";
                                   $stmt = $conn->prepare($sql_establo);
                                   $stmt->bindParam(':id_establo', $id_establo);
                                     $stmt->execute();
                                     $nombre_establo = $stmt->fetchColumn();
                                     $sql_potreros = "SELECT * FROM \"potreros\"";
                                     $result_potreros = $conn->query($sql_potreros);
                                     echo '<select style="pointer-events: none;" class="form-select" id="Establo" name="Establo" required>';
                                     while ($valores_potrero = $result_potreros->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($valores_potrero['Nombre'] == $nombre_establo) ? 'selected' : '';
                                   echo '<option value="' . $valores_potrero['Id_potreros'] . '" ' . $selected . '>' . $valores_potrero['Nombre'] . '</option>';
                                      }
                                 echo '</select>';
                                   ?>
                                      </div>
                                  </div>


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

                      <!------- modal de actualizar -------->
                      <div class="modal fade" id='basicModal-<?php echo $fila["Id_Actividad"]; ?>' tabindex="-1">
                        <div class="modal-dialog modal-lg" style="max-width: 900px;">
                          <div class="modal-content">
                            <div class="modal-header" style="background-color: #0d6efd; color: white;">
                              <h5 class="modal-title text-center w-100">Actualizar información</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"
                              aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                              <form method="POST" action="actualizar/actualizar_movimiento_animal.php">
                                <div>
                                  <input type="hidden" class="form-control" name="Id_Actividad"
                                  value='<?php echo $fila["Id_Actividad"]; ?>'>
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">fecha</label>
                                  <div class="col-sm-9">
                                    <input min="2020-01-01" type="date" class="form-control" name="Fecha" id="validationCustom01"   min="2020-01-01"
                                    value='<?php echo $fila["Fecha"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Cantidad
                                  personal</label>
                                  <div class="col-sm-9">
                                    <input oninput="validateAnimalNumber(this)" type="number" class="form-control" name="Cantidad_personal"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    value='<?php echo $fila["Cantidad_personal"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Encargado</label>
                                  <div class="col-sm-9">
                                    <input type="text" class="form-control" name="Encargado"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                    value='<?php echo $fila["Encargado"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Detalle</label>
                                  <div class="col-sm-9">
                                    <input type="text" class="form-control" name="Detalle"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                    value='<?php echo $fila["Detalle"]; ?>'>
                                  </div>
                                </div>

                                 <div class="row mb-2">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Establo</label>
                                 <div class="col-sm-9">
                                     <?php
                                        $id_establo = $fila["Establo"];
                                    $sql_establo = "SELECT \"Nombre\" FROM \"potreros\" WHERE \"Id_potreros\" = :id_establo";
                                   $stmt = $conn->prepare($sql_establo);
                                   $stmt->bindParam(':id_establo', $id_establo);
                                     $stmt->execute();
                                     $nombre_establo = $stmt->fetchColumn();
                                     $sql_potreros = "SELECT * FROM \"potreros\"";
                                     $result_potreros = $conn->query($sql_potreros);
                                     echo '<select class="form-select" id="Establo" name="Establo" required>';
                                     while ($valores_potrero = $result_potreros->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($valores_potrero['Nombre'] == $nombre_establo) ? 'selected' : '';
                                   echo '<option value="' . $valores_potrero['Id_potreros'] . '" ' . $selected . '>' . $valores_potrero['Nombre'] . '</option>';
                                      }
                                 echo '</select>';
                                   ?>
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
                      $contador++; // Incrementar el contador en cada iteración
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
<script>
    // Bloquear fechas futuras estableciendo el atributo max dinámicamente
    const fechaInput = document.getElementById('validationCustom01');
    const hoy = new Date().toISOString().split("T")[0]; // Obtener la fecha actual en formato YYYY-MM-DD
    fechaInput.setAttribute('max', hoy);
</script>
<script>
  function vaciarCampos() {
    document.getElementsByName("Fecha")[0].value = "";
    document.getElementsByName("Cantidad_personal")[0].value = "";
    document.getElementsByName("Encargado")[0].value = "";
    document.getElementsByName("Detalle")[0].value = "";
    document.getElementsByName("Establo")[0].value = "";
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
