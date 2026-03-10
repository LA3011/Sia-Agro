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
  <title>Listado de comida animmal </title>
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script> 
  <link rel="stylesheet"type="text/css" href="css_personalizado/estilo_comida_animal.css">
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
              <h5 class="modal-title text-center w-100">Registro</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="procesar/procesar_comida_animal.php" style="padding: 0 50px 0 50px;" id="p1">
              <br>
              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Tipo de Comida</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="validationCustom01" placeholder="Ej: Heno" required
                  name="tipo_comida" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" >
                </div>
              </div>

              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Cantidad en kilos</label>
                <div class="col-sm-9">
                  <input oninput="validateAnimalNumber(this)"  type="number" class="form-control" id="validationCustom01" placeholder="Ej: 100" required
                  name="cantidad" oninput="this.value = this.value.replace(/[^0-9]/g, '')" >
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
                <label for="inputText" class="col-sm-3 col-form-label">Precio unitario Bs</label>
                <div class="col-sm-9">
                  <input oninput="validateprecio(this)" class="form-control" type="number" name="precio"placeholder="Ej: 25" required oninput="this.value = this.value.replace(/[^0-9]/g, '')" >
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

              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Notas</label>
                <div class="col-sm-9">
                  <input class="form-control" type="text" name="notas" required placeholder="Ninguna" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                </div>
              </div>

              <br>
              <div class="row mb-2" style="padding-left: 20%;">
                <div class="col-sm-9" style="text-align: center">
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
              <li class="breadcrumb-item active">Comida</li>
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
                <div class="card" style="background-color: #80F2F4; color: white; height: 133px; ">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="icon">
                        <i class="fas fa-chart-bar fa-3x"></i>
                      </div>
                      <div class="content ml-3 text-right">
                        <h5 class="card-title">Dieta Animal</h5>
                        <a style="width: 150px;" href="dieta_animal.php" class="btn btn-primary mt-2 float-right">Ver más</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="card" style="background-color: #F4AA46; color: white; border: 1px solid black; height: 134px; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="icon">
                        <i class="fas fa-chart-pie fa-3x"></i>
                      </div>
                      <div class="content ml-3 text-right">
                        <h5 class="card-title">Comida Ani.</h5>
                        <button  type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal"
                        style="margin-right:82.5%; margin-top:10px; margin-bottom:8px; width: 290px;" title="Agregar"><i class="ri-add-fill"
                        style="color:white;"></i>Agregar &nbsp
                      </button>                </div>
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
                       <th scope="col">Tipo de comida</th>
                       <th scope="col" >Cantidad en kilos</th>   
                       <th scope="col" >Precio Unitario Bs</th>
                       <th scope="col" >Notas</th>
                       <th scope="col" >Fecha de registro</th>    
                       <th scope="col" >Acción</th> 
                     </tr>
                   </thead>
                   <tbody>
                   <?php
            include_once("conexion/conexion.php");
                $conn = cconexion::ConexionBD();
                   $sql = "SELECT * FROM comida_animal ORDER BY \"id_comida\" ";
                         $result = $conn->query($sql);
                   if ($result->rowCount() > 0) {
                  $contador = 1;
                     while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                         <td><?php echo $fila["tipo_comida"];?></td>
                         <td><?php echo $fila['cantidad_kilos']; ?></td>
                         <td><?php echo $fila['precio_unitario']; ?></td>
                         <td><?php echo $fila['notas']; ?></td>
                         <td><?php echo $fila['Fecha_hora_registro']; ?></td>
                         <td>
                          <div class="btn-group" role="group">
                          

                            <?php if($ver == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                              <!-- Boton-modal [ver] -->
                              <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                              type="button" data-bs-toggle="modal" data-bs-target='#basicModal-VER<?php echo $fila["id_comida"]; ?>'
                              title="Editar">
                              <i class="ri-eye-fill" style="color:#17E45B"></i>
                              </a>
                            <?php } ?>  <!-- ← CODIGO A COPIAR -->


                            <?php if($editar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                              <!-- Boton-modal [Editar] -->
                              <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                              type="button" data-bs-toggle="modal" data-bs-target='#basicModal-<?php echo $fila["id_comida"]; ?>'
                              title="Editar">
                              <i class="ri-ball-pen-fill" style="color:#E5D001;"></i>
                              </a>
                            <?php } ?>  <!-- ← CODIGO A COPIAR -->


                            <?php if($eliminar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                              <!-- Boton-modal [eliminar] -->
                              <a type="button" data-bs-toggle="modal" data-bs-target="#smallModal-<?php echo $fila["id_comida"]; ?>"
                                style="color:none;  margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                                title="Eliminar">
                                <i class="ri-delete-bin-2-line" style="color:#EE0D0D;"></i>
                              </a>
                            <?php } ?>


                        <!-- modal [eliminar] -->
                        <div class="modal fade" id="smallModal-<?php echo $fila["id_comida"]; ?>" tabindex="-1">
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
                              href='deshabilitaciones/deshabilitar_comida_animal.php?id=<?php echo $fila["id_comida"] ?>&session_acceso=<?php echo isset($_SESSION["Usuario"]) ? $_SESSION["Usuario"] : ""; ?>&session_id=<?php echo isset($_SESSION["Id_Usuario"]) ? $_SESSION["Id_Usuario"] : ""; ?>'
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
                    <div class="modal fade" id="basicModal-VER<?php echo $fila["id_comida"]; ?>" role="dialog"
                      aria-labelledby="basicModal">
                      <div class="modal-dialog modal-lg" style="max-width: 900px;">
                        <div class="modal-content">
                          <div class="modal-header" style="background-color: #0d6efd; color: white;">
                            <h5 class="modal-title text-center w-100">Ver información</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <form method="POST" action="actualizar/actualizar_comida_animal.php">
                              <div>
                                <input style="pointer-events: none;" type="hidden" class="form-control" name="id_comida"
                                value='<?php echo $fila["id_comida"]; ?>'>
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de comida</label>
                                <div class="col-sm-9">
                                  <input style="pointer-events: none;" type="text" class="form-control" name="tipo_comida"
                                  value='<?php echo $fila["tipo_comida"]; ?>'oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                                </div>
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Cantidad en kilos</label>
                                <div class="col-sm-9">
                                  <input style="pointer-events: none;" type="number" class="form-control" name="cantidad"
                                  value='<?php echo $fila["cantidad_kilos"]; ?>'readonly >
<!--                                   <small class="text-muted">Este campo no se puede editar</small>
-->                                </div>
</div>
<div class="row mb-2">
  <label class="col-sm-3 col-form-label" style="color:#21618C;">Precio Unitario Bs</label>
  <div class="col-sm-9">
    <input style="pointer-events: none;" type="number" class="form-control" name="precio" required placeholder=" "

    value='<?php echo $fila["precio_unitario"]; ?>'readonly>
    <!-- <small class="text-muted">Este campo no se puede editar</small> -->
  </div>
</div>
<div class="row mb-2">
  <label class="col-sm-3 col-form-label" style="color:#21618C;">Notas</label>
  <div class="col-sm-9">
    <input style="pointer-events: none;" type="text" class="form-control" name="notas" required
    placeholder=" " 
    value='<?php echo $fila["notas"]; ?>'>
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
<div class="modal fade" id="basicModal-<?php echo $fila["id_comida"]; ?>" role="dialog"
  aria-labelledby="basicModal">
  <div class="modal-dialog modal-lg" style="max-width: 900px;">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #0d6efd; color: white;">
        <h5 class="modal-title text-center w-100">Actualizar información</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"
        aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="actualizar/actualizar_comida_animal.php">
          <div>
            <input type="hidden" class="form-control" name="id_comida"
            value='<?php echo $fila["id_comida"]; ?>'>
          </div>
          <div class="row mb-2">
            <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de comida</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="tipo_comida"
              value='<?php echo $fila["tipo_comida"]; ?>'oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-sm-3 col-form-label" style="color:#21618C;">Cantidad en kilos</label>
            <div class="col-sm-9">
              <input oninput="validateAnimalNumber(this)" type="number" class="form-control" name="cantidad"
              value='<?php echo $fila["cantidad_kilos"]; ?>'readonly >
              <small class="text-muted">Este campo no se puede editar</small>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-sm-3 col-form-label" style="color:#21618C;">Precio Unitario Bs</label>
            <div class="col-sm-9">
              <input  oninput="validateprecio(this)"type="number" class="form-control" name="precio" required placeholder=" "

              value='<?php echo $fila["precio_unitario"]; ?>'readonly>
              <small class="text-muted">Este campo no se puede editar</small>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-sm-3 col-form-label" style="color:#21618C;">Notas</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="notas" required
              placeholder=" " 
              value='<?php echo $fila["notas"]; ?>'oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
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

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
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