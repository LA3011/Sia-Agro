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
  <title>Listado de Espacios</title>       
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script> 



</head> 
<body>
</head>

<script>
// Obtener el modal
var modal = document.getElementById("myModal");

// Mostrar el modal
modal.style.display = "block";

// Agregar un evento al formulario para validar y enviar los datos
document.getElementById("haciendaForm").addEventListener("submit", function(event) {
    var largo = document.getElementById("largo").value;
    var ancho = document.getElementById("ancho").value;
    
    // Validar que ambos campos estén llenos
    if (largo === "" || ancho === "") {
        alert("Por favor llene todos los campos.");
        event.preventDefault(); // Evitar que se envíe el formulario
    } else {
        // Aquí puedes realizar cualquier acción que desees con los datos ingresados
        console.log("Largo:", largo, "Ancho:", ancho);
        modal.style.display = "none"; // Cerrar el modal si los campos están llenos
    }
});
</script>

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
      <div class="modal fade" id="largeModal">
      <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #0d6efd; color: white;">
              <h5 class="modal-title text-center w-100">Registrar Espacio</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            
            <form method="POST" action="procesar/procesar_espacios.php" style="padding: 0 50px 0 50px;">
              <br>


              <div class="row mb-2">
    <label for="tipo_riego" class="col-sm-3 col-form-label">Espacio</label>
    <div class="col-sm-9">
        <select class="form-select" id="espacio" name="espacio" required>
            <option value="">Seleccione un espacio</option>
            <?php
            include_once("conexion/conexion.php");
            $conn = cconexion::ConexionBD();
            try {
                $tabla = "SELECT * FROM poligono where estado = 'activo'";
                $stmt = $conn->prepare($tabla);
                $stmt->execute();
                while ($valores = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Concatenar el ID y el nombre en el valor
                    echo '<option value="' . $valores['id'] . '-' . $valores['nombre'] . '">' . $valores['nombre'] . '</option>';
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            $conn = null;
            ?>
        </select>
    </div>
</div>





              <div class="row mb-2">
                <label for="tipo_cultivo" class="col-sm-3 col-form-label">Estatus</label>
                <div class="col-sm-9">
                  <select class="form-select" id="estatus" name="estatus" required>
                    <option value="">Selecciona una opción</option>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                  </select>
                </div>
              </div>

              
              <div class="row mb-2">
               <label for="tipo_riego" class="col-sm-3 col-form-label">Tipo de Riego</label>
               <div class="col-sm-9">
                <select class="form-select" id="tipo_cultivo" name="tipo_riego" required>
                  <option value="">Seleccione una opción</option>
                  <option value="Goteo">Goteo</option>
                  <option value="Aspercion">Aspersión</option>
                  <option value="Inundacion">Inundación</option>
                  <option value="Manual">Manual</option>
                  <option value="Otro">Otro</option>
                </select>
              </div>
            </div>
            <div class="row mb-2">
              <label for="inputText" class="col-sm-3 col-form-label">Historial de Uso</label>
              <div class="col-sm-9">
                <input class="form-control" type="text" name="historial_uso" required placeholder="Ej: Cultivo de maíz" required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
              </div>
            </div>

            <div class="row mb-2">
              <label for="inputText" class="col-sm-3 col-form-label">Recursos Hídricos</label>
              <div class="col-sm-9">
                <input class="form-control" type="text" name="recursos_hidricos" required placeholder="Ej: Pozo de agua"  required placeholder=" "oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
              </div>
            </div>

            <div class="row mb-2">
              <label for="inputText" class="col-sm-3 col-form-label">Observaciones</label>
              <div class="col-sm-9">
                <input class="form-control" type="text" name="observaciones" required placeholder="Ej: Necesita mantenimiento" required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
              </div>
            </div>

            <br>
            <div class="row mb-2" style="padding-left: 20%;">
              <div class="col-sm-9" style="text-align: center"> 
                <input type="hidden" name="session_acceso" value="<?php echo $_SESSION['Usuario'] ?>">
                <input type="hidden" name="session_id" value="<?php echo $_SESSION['Id_Usuario'] ?>">
                <a  class="btn btn-secondary" style="width: 100px;" onclick="vaciarCampos()" >Vaciar</a>
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
            <li class="breadcrumb-item"><a>Cultivo</a></li>
            <li class="breadcrumb-item"><a>Seguimiento</a></li>
            <li class="breadcrumb-item active">Espacios</li>
          </ol>
        </nav>

        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <p style="position: absolute; right:165px; top:130px;"> Buscar... </p>
                <div class="text-center">
                  <h5 class="card-title" style="color:black; font-size:40px;">Listado de Espacios</h5>
                </div>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#largeModal"
                style="margin-right:82.5%; margin-top:10px; margin-bottom:8px;" title="Agregar"><i class="ri-add-fill"
                style="color:white;"></i>Agregar &nbsp</button>
                <table class="table datatable">
                  <thead>
                    <tr>
                      <th scope="col" >Nombre</th>
                      <th scope="col" >estatus</th>   
                      <th scope="col" >Recursos Hídricos</th>     
                      <th scope="col" >Observaciones</th>       
                      <th scope="col" >tipo de riego</th> 
                      <th scope="col" >Historia de uso</th> 
                      <th scope="col" >Acción  </th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();

$sql = "SELECT * FROM espacios where deshabilitar='false' ORDER BY \"Id_espacios\"";

$result = $conn->query($sql);

if ($result->rowCount() > 0) {
    $contador = 1;
    while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                          <td><?php  echo $fila["nombre_espacio"];                  ?></td>
                          <td><?php echo $fila['estatus']; ?></td>
                          <td><?php echo $fila['recursos_hidricos']; ?></td>
                          <td><?php echo $fila['observaciones']; ?></td>
                          <td><?php echo $fila['tipo_riego']; ?></td>
                          <td><?php echo $fila['historial_uso']; ?></td>
                          <td>
                            <div class="btn-group" role="group">

                              <?php if($ver == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                              <!-- Boton-modal [ver] -->
                              <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                              type="button" data-bs-toggle="modal" data-bs-target='#basicModal-VER<?php echo $fila["Id_espacios"]; ?>'
                              title="Ver">
                              <i class="ri-eye-fill" style="color:#17E45B"></i>
                            </a>
                            <?php } ?>  <!-- ← CODIGO A COPIAR -->


                            <?php if($editar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                            <!-- Boton-modal [Editar] -->
                            <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                            type="button" data-bs-toggle="modal" data-bs-target='#basicModal-<?php echo $fila["Id_espacios"]; ?>'
                            title="Editar">
                            <i class="ri-ball-pen-fill" style="color:#E5D001;"></i>
                          </a>
                          <?php } ?>  <!-- ← CODIGO A COPIAR -->


                          <?php if($eliminar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                          <!-- Boton-modal [eliminar] -->
                          <a type="button" href="javascript:void(0);" 
                          onclick="deleteEspacio(<?php echo $fila['Id_espacios']; ?>)" data-bs-toggle="modal" data-bs-target="#smallModal-<?php echo $fila["Id_espacios"]; ?>"
                            style="color:none;  margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                            title="Eliminar">
                            <i class="ri-delete-bin-2-line" style="color:#EE0D0D;"></i>
                          </a>
                        <?php } ?>

                        <!-- modal [eliminar] -->
                    
<script>
                    function deleteEspacio(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción eliminará el espacio seleccionado.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Realizar la solicitud AJAX
            $.ajax({
                url: 'deshabilitaciones/deshabilitar_Espacios.php', // Ruta al archivo PHP
                type: 'GET',
                data: { id: id }, // Enviar el ID del espacio
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: response.message,
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            // Recargar la tabla o la página
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            confirmButtonText: 'Aceptar'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al intentar eliminar el espacio.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }
    });
}</script>
                    <!------- modal de ver -------->
                    <div class="modal fade"id="basicModal-VER<?php echo $fila["Id_espacios"]; ?>" role="dialog" aria-labelledby="basicModal">
                      <div class="modal-dialog modal-lg" style="max-width: 900px;">
                        <div class="modal-content" style="">
                          <div class="modal-header" style="background-color:#0d6efd; color: white; padding-left: 22%;">
                            <h5 class="modal-title mx-auto" id="registrarActividadModalLabel" >Ver información</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <form method="POST" action=".php">
                              <div>
                                <input style="pointer-events: none;" type="hidden" class="form-control" name="id_espacio" value='<?php echo $fila["Id_espacios"]; ?>' >
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre</label>
                                <div class="col-sm-9">
                                  <input style="pointer-events: none;" type="text" class="form-control" name="nombre_espacio"  value='<?php echo $fila["nombre_espacio"]; ?>'>
                                </div>
                              </div>

                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;"> Estatus</label>
                                <div class="col-sm-9">
                                  <select style="pointer-events: none;" class="form-select" id="estatus" name="estatus" required>
                                    <option <?php echo $fila["estatus"]==='Activo'   ? "selected='selected'":""?>value="Activo" >Activo</option>
                                    <option <?php echo $fila["estatus"]==='Inactivo' ? "selected='selected'":""?>value="Inactivo">Inactivo</option>
                                  </select>
                                </div>
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Recursos Hídricos</label>
                                <div class="col-sm-9">
                                  <input style="pointer-events: none;" type="text" class="form-control" name="recursos_hidricos"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" value='<?php echo $fila["recursos_hidricos"]; ?>'>
                                </div>
                              </div>

                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de riego</label>
                                <div class="col-sm-9">
                                  <select style="pointer-events: none;" class="form-select" id="tipo_riego" name="tipo_riego" required>
                                    <option <?php echo $fila["tipo_riego"]==='Goteo' ? "selected='selected'":""?>value="Goteo">Goteo</option>
                                    <option <?php echo $fila["tipo_riego"]==='Aspercion' ? "selected='selected'":""?>value="Aspercion">Aspersión</option>
                                    <option <?php echo $fila["tipo_riego"]==='Inundacion' ? "selected='selected'":""?>value="Inundacion">Inundación</option>
                                    <option <?php echo $fila["tipo_riego"]==='Manual' ? "selected='selected'":""?>value="Manual">Manual</option>        
                                    <option <?php echo $fila["tipo_riego"]==='Otro' ? "selected='selected'":""?>value="Flor">Otro</option>        
                                  </select>

                                </div>
                              </div>
                           
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Historial de uso</label>
                                <div class="col-sm-9">
                                  <input style="pointer-events: none;" type="text" class="form-control" name="historial_uso" required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" value='<?php echo $fila["historial_uso"]; ?>'>
                                </div>
                              </div>

                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Observaciones</label>
                                <div class="col-sm-9">
                                  <input style="pointer-events: none;" type="text" class="form-control" name="observaciones"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" value='<?php echo $fila["observaciones"]; ?>'>
                                </div>
                              </div>

                              <br>
                              <div class="row mb-3">
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>

                  <!------- modal de actualizar -------->
                  <div class="modal fade"id="basicModal-<?php echo $fila["Id_espacios"];?>" role="dialog" aria-labelledby="basicModal">
                    <div class="modal-dialog modal-lg" style="max-width: 1000px;">
                      <div class="modal-content">
                        <div class="modal-header" style="background-color:#0d6efd; color: white;">
                          <h5 class="modal-title mx-auto" id="registrarActividadModalLabel" style="padding-left: 20%;">Actualizar información</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <form method="POST" action="actualizar/actualizar_espacios.php">
                            <div>
                              <input type="hidden" class="form-control" name="id_espacio" value='<?php echo $fila["Id_espacios"]; ?>' >
                            </div>
                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre</label>
                              <div class="col-sm-9">
                                <input  style="pointer-events: none;" type="text" class="form-control" name="nombre_espacio"  value='<?php echo $fila["nombre_espacio"]; ?>'>
                              </div>
                            </div>

                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;"> Estatus</label>
                              <div class="col-sm-9">
                                <select class="form-select" id="estatus" name="estatus" required>
                                  <option <?php echo $fila["estatus"]==='Activo'   ? "selected='selected'":""?>value="Activo" >Activo</option>
                                  <option <?php echo $fila["estatus"]==='Inactivo' ? "selected='selected'":""?>value="Inactivo">Inactivo</option>
                                </select>
                              </div>
                            </div>
                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Recursos Hídricos</label>
                              <div class="col-sm-9">
                                <input type="text" class="form-control" name="recursos_hidricos"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" value='<?php echo $fila["recursos_hidricos"]; ?>'>
                              </div>
                            </div>

                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de riego</label>
                              <div class="col-sm-9">
                                <select class="form-select" id="tipo_riego" name="tipo_riego" required>
                                  <option <?php echo $fila["tipo_riego"]==='Goteo' ? "selected='selected'":""?>value="Goteo">Goteo</option>
                                  <option <?php echo $fila["tipo_riego"]==='Aspercion' ? "selected='selected'":""?>value="Aspercion">Aspersión</option>
                                  <option <?php echo $fila["tipo_riego"]==='Inundacion' ? "selected='selected'":""?>value="Inundacion">Inundación</option>
                                  <option <?php echo $fila["tipo_riego"]==='Manual' ? "selected='selected'":""?>value="Manual">Manual</option>        
                                  <option <?php echo $fila["tipo_riego"]==='Otro' ? "selected='selected'":""?>value="Flor">Otro</option>        
                                </select>

                              </div>
                            </div>
                           
                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Historial de uso</label>
                              <div class="col-sm-9">
                                <input type="text" class="form-control" name="historial_uso" required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" value='<?php echo $fila["historial_uso"]; ?>'>
                              </div>
                            </div>

                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Observaciones</label>
                              <div class="col-sm-9">
                                <input type="text" class="form-control" name="observaciones"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" value='<?php echo $fila["observaciones"]; ?>'>
                              </div>
                            </div>


                            <br>
                            <div class="row mb-3">
                            </div>
                            <div class="modal-footer">
                              <button type="submit" class="btn btn-success" name="actualizar">Actualizar</button>
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
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
</main>
<?php include_once("footer.php"); ?>
</body>
</html>

<script>
  function vaciarCampos() {
    document.getElementsByName("nombre_espacio")[0].value = "";
    document.getElementsByName("estatus")[0].value = "";
    document.getElementsByName("tipo_riego")[0].value = "";
    document.getElementsByName("area_expresada")[0].value = "";
    document.getElementsByName("area")[0].value = "";
    document.getElementsByName("historial_uso")[0].value = "";
    document.getElementsByName("recursos_hidricos")[0].value = "";
    document.getElementsByName("observaciones")[0].value = "";
  }
</script>