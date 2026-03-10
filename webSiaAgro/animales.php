
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
  <title>Listado de animales</title>     
  <link rel="stylesheet"type="text/css" href="css_personalizado/estilo_animales.css">
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script> 
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
            <div class="modal-header" style="background-color:#0d6efd; color: white;">
              <h5 class="modal-title text-center w-100">Registro Animal</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> 
            <form method="POST" action="procesar/procesar_animales.php" enctype="multipart/form-data" style="padding: 0 50px 0 50px;">
              <br>

              <div class="row mb-3">
                <label for="inputText" class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-9">
                  <input class="form-control" type="text" id="validationCustom01" name="nombre_animales" required placeholder="Ej: Lola" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                </div>
              </div>

              <div class="row mb-3">
    <label for="inputText" class="col-sm-2 col-form-label">N° del Animal</label>
    <div class="col-sm-9">
        <input type="number" class="form-control" id="num_animales" 
               name="num_animales" required placeholder="Ej: 10"
              oninput="validateAnimalNumber(this)">
    </div>
</div>

<script>
    function validateAnimalNumber(input) {
        let maxLength = 4;

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

              <script>
                ¨use strict¨

                const test = document.querySelector(".test")

                test.addEventListener("onChange",(e => {
                  console.log(e.target)
                }))
              </script>

              <div class="row mb-3">
                <label for="inputText" class="col-sm-2 col-form-label">Ganadería</label>
                <div class="col-sm-9">
                  <select class="form-select" id="ganaderia_animales" name="ganaderia_animales" required>
                    <option value="">Seleccione una opción</option>
                    <option value="Carne">Bovino Carne</option>
                    <option value="Leche">Bovino Leche</option>
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <label for="inputText" class="col-sm-2 col-form-label">Raza</label>
                <div class="col-sm-9">
                  <select class="form-select" id="raza_animales" name="raza_animales" required>
                    <option value="">Seleccione una opción</option>
                <?php
            include_once("conexion/conexion.php");
            $conn = cconexion::ConexionBD();
            $query = "SELECT raza FROM raza_animales";
            $result = $conn->query($query);
            if ($result->rowCount() > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $nombreraza = $row['raza'];
                    echo '<option value="' . $nombreraza . '">' . $nombreraza . '</option>';
                }
            } else {
                echo '<option value="">No se encontraron lotes</option>';
            }
            $conn = null;
            ?>
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <label for="inputText" class="col-sm-2 col-form-label">Estatus</label>
                <div class="col-sm-9">
                  <select class="form-select" id="venta_animales" name="venta_animales" required onchange="showPriceField(this.value)">
                    <option value="">Seleccione una opción</option>
                    <option value="Venta">Venta</option>
                    <option value="Crianza">Crianza</option>
                  </select>
                </div>
              </div>

              <?php
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();
$query = "SELECT id_lotes, nombre FROM lotes";
$result = $conn->query($query);
$options = '';
if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $idLote = $row['id_lotes'];
        $nombreLote = $row['nombre'];
        $options .= '<option value="' . $idLote . '">' . $nombreLote . '</option>';
    }
} else {
    $options = '<option value="">No se encontraron lotes</option>';
}
$conn = null;
?>
<div class="row mb-3">
    <label for="inputText" class="col-sm-2 col-form-label">Lote</label>
    <div class="col-sm-9">
        <select class="form-select" id="lote_animales" name="lote_animales" required>
            <option value="">Seleccione una opción</option>
            <?php echo $options; ?>
        </select>
    </div>
</div>
        
            <div class="row mb-3">
    <label for="inputText" class="col-sm-2 col-form-label">Sexo</label>
    <div class="col-sm-9">
        <select class="form-select" id="Sexo" name="Sexo" required>
            <option value="">Seleccione una opción</option>
            <option value="Hembra">Hembra</option>
            <option value="Macho">Macho</option>
        </select>
    </div>
</div>

<div class="row mb-3">
    <label for="inputText" class="col-sm-2 col-form-label">Categoría</label>
    <div class="col-sm-9">
        <select class="form-select" id="categoria" name="categoria" required>
            <option value="">Seleccione una opción</option>
        </select>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sexoSelect = document.getElementById("Sexo");
        const categoriaSelect = document.getElementById("categoria");

        const categorias = {
            Hembra: [
                { value: "Vacas Paridas", text: "Vacas Paridas" },
                { value: "Vacas Horras", text: "Vacas Horras" },
                { value: "Hembras de Vientre", text: "Hembras de Vientre" },
                { value: "Hembra de Levante", text: "Hembra de Levante" },
                { value: "Crías Hembras", text: "Crías Hembras" }
            ],
            Macho: [
                { value: "Toros", text: "Toros" },
                { value: "Toretes", text: "Toretes" },
                { value: "Macho de Seba", text: "Macho de Seba" },
                { value: "Macho de Levante", text: "Macho de Levante" },
                { value: "Cría Macho", text: "Crías Machos" }
            ]
        };

        sexoSelect.addEventListener("change", function () {
            categoriaSelect.innerHTML = '<option value="">Seleccione una opción</option>'; // Reiniciar opciones
            
            const selectedSexo = this.value;
            if (selectedSexo && categorias[selectedSexo]) {
                categorias[selectedSexo].forEach(categoria => {
                    let option = document.createElement("option");
                    option.value = categoria.value;
                    option.textContent = categoria.text;
                    categoriaSelect.appendChild(option);
                });
            }
        });
    });
</script>


<div class="row mb-3">
    <label for="inputText" class="col-sm-2 col-form-label">Peso (Kg)</label>
    <div class="col-sm-9">
        <input class="form-control" type="number" name="peso_animales" required 
               placeholder="Ej: 150" id="peso_animales"
               oninput="validateInput(this)" min="1" max="99999">
    </div>
</div>

<script>
    function validateInput(input) {
        let maxLength = 3;

        // Convertir valor a string y verificar longitud
        if (input.value.length > maxLength) {
            input.value = input.value.slice(0, maxLength); // Limitar a 5 caracteres
        }

        // Evitar valores negativos y ceros
        if (input.value < 1) {
            input.value = "";
        }
    }
</script>


              <div class="row mb-3">
        <label for="imagen_animales" class="col-sm-2 col-form-label">Imagen</label>
        <div class="col-sm-9">
            <input type="file" class="form-control" id="imagen_animales" name="imagen_animales" accept="image/jpeg, image/png" onchange="previewImage(event)">
            <img id="preview" src="" alt="Vista previa de la imagen" style="max-width: 300px; margin-top: 10px; display: none;">
            <!-- Agregar un elemento para mostrar mensajes de error -->
            <div id="error_message" style="color: red; display: none;">Error: La imagen no es válida.</div>
        </div>
    </div>

    <script>
function previewImage(event) {
    var input = event.target;
    var reader = new FileReader();
    reader.onload = function(){
        var preview = document.getElementById('preview');
        preview.src = reader.result;
        preview.style.display = 'block'; 
    }
    reader.readAsDataURL(input.files[0]);
}
</script>

              <div class="row mb-3" style="padding-left: 20%;">
                <div class="col-sm-9" style="text-align: center">
                 <!-- Campo oculto para enviar la sesión del usuario -->
                 <input type="hidden" name="session_acceso" value="<?php echo $_SESSION['Usuario'] ?>">
                 <input type="hidden" name="session_id" value="<?php echo $_SESSION['Id_Usuario'] ?>">
                 <input type="submit" class="btn btn-primary"  value="Registrar" style="width: 100px; background-color: green; color: white;">
                 <a class="btn btn-secondary" style="width: 100px;" onclick="vaciarCampos()">Vaciar</a>
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
            <li class="breadcrumb-item" style="">Animales</li>
            <li class="breadcrumb-item" style="">General</li>
            <li class="breadcrumb-item active" style="color:#172871;">Registro de Animales</li>
          </ol>
        </nav>
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <p style="position: absolute; right:165px; top:130px;"> Buscar... </p>
                <h5 class="card-title" style="color:black; font-size:40px; margin-left:7%;">Listado de Animales </h5>


                <!-- ---- ↓↓ CODIGO A COPIAR ↓↓ ---- -->
                <?php if("true" == "true"){ ?>
                  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#largeModal"
                  style="margin-right:82.5%; margin-top:10px; margin-bottom:8px;" title="Agregar"><i class="ri-add-fill"
                  style="color:white;"></i>Agregar &nbsp
                  </button>
                <?php }else{ ?>
                  <div style="margin-right:82.5%; margin-top:24px; margin-bottom:8px; display: inline-block;"> </div>
                <?php } ?>
                <!-- ---- ↑↑ CODIGO A COPIAR ↑↑ ---- -->


                <table class="table datatable">
                  <thead>
                    <tr>
                      <th scope="col" >N° de Animales</th>
                      <th scope="col" >Ganadería</th>   
                      <th scope="col" >Nombre</th>
                      <th scope="col" >Raza</th>     
                      <th scope="col" >Lote</th>       
                      <th scope="col" >Peso </th>      
                      <th scope="col" >Sexo</th> 
                      <th scope="col" >categoría</th> 
                      <th scope="col" >Estatus</th>
                      <th scope="col" >Fecha y Hora de registro</th>
                      <th scope="col" >Acción </th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  include_once("conexion/conexion.php");
                  $conn = cconexion::ConexionBD(); // Inicializar la conexión
                  
                  $sql = "SELECT * FROM animales ORDER BY \"Id_animal\"";

                  $result = $conn->query($sql);
                  
                  // Verificar si se encontraron registros
                  if ($result->rowCount() > 0) {
                    // Variable para contar los registros
                      $contador = 1;

                    // Recorrer los registros y mostrar los datos en la tabla
                    while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                          <td><?php echo $fila["N_animal"];                  ?></td>
                          <td><?php echo $fila['Ganaderia']; ?></td>
                          <td><?php echo $fila['Nombre']; ?></td>
                          <td><?php echo $fila['Raza']; ?></td>
                          <td><?php echo $fila['Lote']; ?></td>
                          <td><?php echo $fila['Peso'];echo"Kg"; ?></td>
                          <td><?php echo $fila['Sexo']; ?></td>
                          <td><?php echo $fila['Categoria']; ?></td>
                          <td><?php echo $fila['Venta']; ?></td>
                          <td><?php echo $fila['Fecha_hora_registro']; ?></td>
                          <td>
                          <div class="btn-group" role="group">

                <?php if($ver == "true") { ?> <!-- ← CODIGO A COPIAR -->

               <?php if ($fila["Venta"]== 'Vendido'): ?> <!-- ← NO A COPIAR -->
                <a style="color:none;  margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
               type="button" data-bs-toggle="modal"
              data-bs-target='#basicModal-VER<?php echo $fila["Id_animal"]; ?>' title="Ver mas">
               <i class="ri-eye-fill" style="color:#17E45B"></i>
                  </a> <?php endif; ?>  <!-- ← NO A COPIAR -->
                   <?php } ?> <!-- ← CODIGO A COPIAR -->


                 <?php if($editar == "true") { ?>  <!-- ← CODIGO A PEGAR -->
                <?php if ($fila["Venta"] !== 'Vendido'): ?> <!-- ← NO A COPIAR -->
               <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
               type="button" data-bs-toggle="modal" data-bs-target='#basicModal-EDITAR<?php echo $fila["Id_animal"]; ?>'
              title="Editar">
              <i class="ri-ball-pen-fill" style="color:#E5D001;"></i>
             </a>
                </a> <?php endif; ?> <!-- ← NO A COPIAR -->
                <?php } ?>  <!-- ← CODIGO A PEGAR -->
                <?php if($eliminar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                        <a type="button" data-bs-toggle="modal" data-bs-target="#smallModal-<?php echo $fila["Id_animal"]; ?>"
                         style="color:none;  margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                         title="Eliminar">
                         <i class="ri-delete-bin-2-line" style="color:#EE0D0D;"></i>
                       </a>
                       <?php } ?>  <!-- ← CODIGO A COPIAR -->

                

                       <!-- modal [eliminar] -->
                       <div class="modal fade" id="smallModal-<?php echo $fila["Id_animal"]; ?>" tabindex="-1">
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
                            href='deshabilitaciones/deshabilitar_animales.php?id=<?php echo $fila["Id_animal"] ?> &session_acceso=<?php echo isset($_SESSION["Usuario"]) ? $_SESSION["Usuario"] : ""; ?>&session_id=<?php echo isset($_SESSION["Id_Usuario"]) ? $_SESSION["Id_Usuario"] : ""; ?>'
                            title="Eliminar">
                            <span class="btn btn-outline-danger">Eliminar</span>
                          </a>
                          <button style="left:px; position: relative;" type="button"
                          class="btn btn-outline-success" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                      </div>
                    </div>
                  </div>

              <!------- modal de actualizar -------->
              <div class="modal fade" id="basicModal-EDITAR<?php echo $fila["Id_animal"]; ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg" style="max-width: 900px;">
                      <div class="modal-content">
                        <div class="modal-header" style="background-color: #0d6efd; color: white;">
                            <h5 class="modal-title text-center w-100">Actualizar Información</h5>
                           
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <form method="POST" action="actualizar/actualizar_animales.php" enctype="multipart/form-data" >
                              <div>
                                <input style="cursor:pointer" type="hidden" class="form-control" name="Id_Animal" value='<?php echo $fila["Id_animal"]; ?>' >
                              </div>

                              <div class="row mb-3">
                             <label class="col-sm-2 col-form-label" style="color:#21618C;">Imagen</label>
                              <div class="col-sm-9">
                              <?php if (!empty($fila["Imagen"])): ?>
                              <p>Imagen guardada:</p>
                              <?php
                              $imagen_mostrar = base64_encode(stream_get_contents($fila["Imagen"]));
                              $tipo_imagen = 'image/jpeg'; // Cambiar según el tipo de imagen que estás guardando en la base de datos
                               echo '<img src="data:'.$tipo_imagen.';base64,'.$imagen_mostrar.'" alt="Imagen guardada" width="200">';
                              ?>
                             <?php endif; ?>
                            <input style="cursor:pointer" type="file" class="form-control" name="imagen_animales" accept="image/jpeg, image/png" <?php echo $fila["Venta"] === 'Vendido' ? 'disabled' : ''; ?> >
                            </div>
                          </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="color:#21618C;">Nombre</label>
                                <div class="col-sm-9">
                                  <input style="cursor:pointer" type="text" class="form-control" name="nombre_animales" required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"  value='<?php echo $fila["Nombre"]; ?>'
                                  <?php echo $fila["Venta"] === 'Vendido' ? 'disabled' : ''; ?>>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="color:#21618C;">N° del Animal</label>
                                <div class="col-sm-9">
                                  <input min="1" max="999999" style="cursor:pointer" type="text" class="form-control" name="num_animales" required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value='<?php echo $fila["N_animal"]; ?>'
                                  <?php echo $fila["Venta"] === 'Vendido' ? 'disabled' : ''; ?>>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="color:#21618C;"> Ganadería</label>
                                <div class="col-sm-9">
                                  <select style="cursor:pointer" class="form-select" id="ganaderia_animales" name="ganaderia_animales" required <?php echo $fila["Venta"] === 'Vendido' ? 'disabled' : ''; ?>>
                                    <option <?php echo $fila["Ganaderia"]==='Leche' ? "selected='selected'":""?>value="Leche" >Bovino Leche</option>
                                    <option <?php echo $fila["Ganaderia"]==='Carne' ? "selected='selected'":""?>value="Carne">Bovino Carne</option>

                                  </select>

                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="color:#21618C;">Estatus</label>
                                <div class="col-sm-9">
                                  <select style="cursor:pointer" class="form-select" id="venta_animales" name="venta_animales" required <?php echo $fila["Venta"] === 'Vendido' ? 'disabled' : ''; ?>>
                                    <option <?php echo $fila["Venta"] === 'Venta' ? 'selected="selected"' : ''; ?> value="Venta">Venta</option>
                                    <option <?php echo $fila["Venta"] === 'Crianza' ? 'selected="selected"' : ''; ?> value="Crianza">Crianza</option>
                                  </select>
                                </div>
                              </div>
                              <div class="row mb-3">
  <label class="col-sm-2 col-form-label" style="color:#21618C;">Lote</label>
  <div class="col-sm-9">
    <select style="cursor:pointer" class="form-select" id="lote_animales" name="lote_animales" required <?php echo $fila["Venta"] === 'Vendido' ? 'disabled' : ''; ?>>
      <?php
   
      $tabla = "SELECT * FROM lotes";
      $sql = $conn->query($tabla);
      while ($valores = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($valores['nombre'] == $espacio_actual) {
          echo '<option value="'.$valores['id_lotes'].'" selected>'.$valores['nombre'].'</option>';
        } else {
          echo '<option value="'.$valores['id_lotes'].'">'.$valores['nombre'].'</option>';
        }
      }
      ?>
    </select>
  </div>
</div>
                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="color:#21618C;">Peso</label>
                                <div class="col-sm-9">
                                  <input style="cursor:pointer" type="text" class="form-control" name="peso_animales"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value='<?php echo $fila["Peso"]; ?>'
                                  <?php echo $fila["Venta"] === 'Vendido' ? 'disabled' : ''; ?>>
                                </div>
                              </div>

                              

                              <div class="row mb-3">
    <label class="col-sm-2 col-form-label" style="color:#21618C;">Sexo</label>
    <div class="col-sm-9">
        <select class="form-select" id="Sexo-<?php echo $fila["Id_animal"]; ?>" name="Sexo" required>
            <option value="">Seleccione una opción</option>
            <option value="Hembra" <?php echo $fila["Sexo"] === 'Hembra' ? "selected='selected'" : ""; ?>>Hembra</option>
            <option value="Macho" <?php echo $fila["Sexo"] === 'Macho' ? "selected='selected'" : ""; ?>>Macho</option>
        </select>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-2 col-form-label" style="color:#21618C;">Categoría</label>
    <div class="col-sm-9">
        <select class="form-select" id="Categoria-<?php echo $fila["Id_animal"]; ?>" name="Categoria" required>
            <option value="">Seleccione una opción</option>
            <!-- Las opciones se generarán dinámicamente con JavaScript -->
        </select>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sexoSelect = document.getElementById("Sexo-<?php echo $fila["Id_animal"]; ?>");
        const categoriaSelect = document.getElementById("Categoria-<?php echo $fila["Id_animal"]; ?>");

        const categorias = {
            Hembra: [
                { value: "Vacas Paridas", text: "Vacas Paridas" },
                { value: "Vacas Horras", text: "Vacas Horras" },
                { value: "Hembras de Vientre", text: "Hembras de Vientre" },
                { value: "Hembra de Levante", text: "Hembra de Levante" },
                { value: "Crías Hembras", text: "Crías Hembras" }
            ],
            Macho: [
                { value: "Toros", text: "Toros" },
                { value: "Toretes", text: "Toretes" },
                { value: "Macho de Seba", text: "Macho de Seba" },
                { value: "Macho de Levante", text: "Macho de Levante" },
                { value: "Cría Macho", text: "Cría Macho" }
            ]
        };

        // Función para cargar las categorías según el sexo seleccionado
        function cargarCategorias(sexoSeleccionado) {
            categoriaSelect.innerHTML = '<option value="">Seleccione una opción</option>'; // Reiniciar opciones

            if (sexoSeleccionado && categorias[sexoSeleccionado]) {
                categorias[sexoSeleccionado].forEach(categoria => {
                    let option = document.createElement("option");
                    option.value = categoria.value;
                    option.textContent = categoria.text;

                    // Seleccionar la categoría guardada en la base de datos
                    if (categoria.value === "<?php echo $fila["Categoria"]; ?>") {
                        option.selected = true;
                    }

                    categoriaSelect.appendChild(option);
                });
            }
        }

        // Cargar las categorías al cargar la página
        cargarCategorias(sexoSelect.value);

        // Cambiar las categorías dinámicamente al cambiar el sexo
        sexoSelect.addEventListener("change", function () {
            cargarCategorias(this.value);
        });
    });
</script>
                              <div class="modal-footer">
                                <?php if ($fila["Venta"] !== 'Vendido'): ?>

                                  <input type="hidden" name="session_acceso" value="<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>">
                                  <input type="hidden" name="session_id" value="<?php echo isset($_SESSION['Id_Usuario']) ? $_SESSION['Id_Usuario'] : ''; ?>">

                                  <button type="submit" class="btn btn-success" name="actualizar">Actualizar</button>
                                <?php endif; ?>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                              </div>

                            </form>

                          </div>

                        </div>
                      </div>
                    </div>
                    
                    
 <!------- modal de ver -------->
                    <div class="modal fade" id="basicModal-VER<?php echo $fila["Id_animal"]; ?>" tabindex="-1">
                      <div class="modal-dialog modal-lg" style="max-width: 900px;">
                        <div class="modal-content">
                          <div class="modal-header" style="background-color: #0d6efd; color: white;">
                            <?php if ($fila["Venta"] !== 'Vendido'): ?>
                              <h5 class="modal-title text-center w-100">Ver Información</h5>
                              <?php else: ?>
                                <h5 class="modal-title text-center w-100">Ver Información</h5>
                              <?php endif; ?>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form method="POST" action="actualizar_animales.php" enctype="multipart/form-data" >
                                <div>
                                  <input type="hidden" class="form-control" name="Id_animal" value='<?php echo $fila["Id_Animal"]; ?>' >
                                </div>

                                 <div class="row mb-3">
                             <label class="col-sm-2 col-form-label" style="color:#21618C;">Imagen</label>
                              <div class="col-sm-9">
                              <?php if (!empty($fila["Imagen"])): ?>
                              <p>Imagen guardada:</p>
                              <?php
                              $imagen_mostrar = base64_encode(stream_get_contents($fila["Imagen"]));
                              $tipo_imagen = 'image/jpeg'; // Cambiar según el tipo de imagen que estás guardando en la base de datos
                               echo '<img src="data:'.$tipo_imagen.';base64,'.$imagen_mostrar.'" alt="Imagen guardada" width="200">';
                              ?>
                             <?php endif; ?>
                            <input style="cursor:pointer" type="file" class="form-control" name="imagen_animales" accept="image/jpeg, image/png" <?php echo $fila["Venta"] === 'Vendido' ? 'disabled' : ''; ?> >
                            </div>
                          </div>


                                <div class="row mb-3">
                                  <label class="col-sm-2 col-form-label" style="color:#21618C;">Nombre</label>
                                  <div class="col-sm-9">
                                    <input type="text" class="form-control" name="nombre_animales" required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"  value='<?php echo $fila["Nombre"]; ?>'
                                    <?php echo $fila["Venta"] === 'Vendido' ? 'disabled' : ''; ?>>
                                  </div>
                                </div>

                                <div class="row mb-3">
                                  <label class="col-sm-2 col-form-label" style="color:#21618C;">N° del Animal</label>
                                  <div class="col-sm-9">
                                    <input type="text" class="form-control" name="num_animales" required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value='<?php echo $fila["N_animal"]; ?>'
                                    <?php echo $fila["Venta"] === 'Vendido' ? 'disabled' : ''; ?>>
                                  </div>
                                </div>

                                <div class="row mb-3">
                                  <label class="col-sm-2 col-form-label" style="color:#21618C;"> Ganadería</label>
                                  <div class="col-sm-9">
                                    <select class="form-select" id="ganaderia_animales" name="ganaderia_animales" required <?php echo $fila["Venta"] === 'Vendido' ? 'disabled' : ''; ?>>
                                      <option <?php echo $fila["Ganaderia"]==='Leche' ? "selected='selected'":""?>value="Leche" >Bovino Leche</option>
                                      <option <?php echo $fila["Ganaderia"]==='Carne' ? "selected='selected'":""?>value="Carne">Bovino Carne</option>

                                    </select>

                                  </div>
                                </div>

                                <div class="row mb-3">
                                  <label class="col-sm-2 col-form-label" style="color:#21618C;">Estatus</label>
                                  <div class="col-sm-9">
                                    <select class="form-select" id="venta_animales" name="venta_animales" required <?php echo $fila["Venta"] === 'Vendido' ? 'disabled' : ''; ?>>
                                      <option <?php echo $fila["Venta"] === 'Venta' ? 'selected="selected"' : ''; ?> value="Venta">Venta</option>
                                      <option <?php echo $fila["Venta"] === 'Crianza' ? 'selected="selected"' : ''; ?> value="Crianza">Crianza</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="row mb-3">
                                  <label class="col-sm-2 col-form-label" style="color:#21618C;">Lote</label>
                                  <div class="col-sm-9">
                                    <select class="form-select" id="lote_animales" name="lote_animales" required <?php echo $fila["Venta"] === 'Vendido' ? 'disabled' : ''; ?>>
                                    <?php
                              
                                $tabla = "SELECT * FROM lotes";
                                $sql = $conn->query($tabla);
                                while ($valores = $sql->fetch(PDO::FETCH_ASSOC)) {
                                if ($valores['nombre'] == $espacio_actual) {
                                echo '<option value="'.$valores['nombre'].'" selected>'.$valores['nombre'].'</option>';
                               } else {
                               echo '<option value="'.$valores['nombre'].'">'.$valores['nombre'].'</option>';
                                  }
                              }
                               ?>
                                    </select>
                                  </div>
                                </div>
                                <div class="row mb-3">
                                  <label class="col-sm-2 col-form-label" style="color:#21618C;">Peso</label>
                                  <div class="col-sm-9">
                                    <input type="text" class="form-control" name="peso_animales"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value='<?php echo $fila["Peso"]; ?>'
                                    <?php echo $fila["Venta"] === 'Vendido' ? 'disabled' : ''; ?>>
                                  </div>
                                </div>

                                

                                <div class="row mb-3">
                                  <label class="col-sm-2 col-form-label" style="color:#21618C;">Sexo</label>
                                  <div class="col-sm-9">
                                    <select class="form-select" id="Sexo" name="Sexo"<?php echo $fila["Venta"] === 'Vendido' ? 'disabled' : ''; ?>>
                                      <option value="">Selecciona una opción</option>
                                      <option value="Hembra" <?php echo $fila["Sexo"] === 'Hembra' ? "selected='selected'" : "" ?>>Hembra</option>
                                      <option value="Macho" <?php echo $fila["Sexo"] === 'Macho' ? "selected='selected'" : "" ?>>Macho</option>
                                    </select>
                                  </div>
                                </div>

                                <div class="row mb-3">
                                  <label class="col-sm-2 col-form-label" style="color:#21618C;">Categoría</label>
                                  <div class="col-sm-9">
                                    <?php
                                    $categoriaSeleccionada = $fila["Categoria"];
                                    $categoriasHembra = array("Vacas Paridas", "Vacas Horras", "Hembras de Vientre", "Hembra de Levante", "Crías Hembras");
                                    $categoriasMacho = array("Toros", "Toretes", "Macho de Seba", "Macho de Levante", "Cría Macho");
                                    ?>
                                    <select class="form-select" id="Categoria" name="Categoria" required <?php echo $fila["Venta"] === 'Vendido' ? 'disabled' : ''; ?>>
                                      <option value="">Selecciona una opción</option>
                                      <?php
                                      foreach ($categoriasHembra as $categoria) {
                                        $selected = ($categoria === $categoriaSeleccionada) ? "selected='selected'" : "";
                                        echo "<option value='$categoria' $selected>$categoria</option>";
                                      }

                                      foreach ($categoriasMacho as $categoria) {
                                        $selected = ($categoria === $categoriaSeleccionada) ? "selected='selected'" : "";
                                        echo "<option value='$categoria' $selected>$categoria</option>";
                                      }
                                      ?>
                                    </select>
                                  </div>
                                </div>

                              <div class="modal-footer">
                                <?php if ($fila["Venta"] !== 'Vendido'): ?>

                                  <input type="hidden" name="session_acceso" value="<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>">
                                  <input type="hidden" name="session_id" value="<?php echo isset($_SESSION['Id_Usuario']) ? $_SESSION['Id_Usuario'] : ''; ?>">

                                  <button type="submit" class="btn btn-success" name="actualizar">Actualizar</button>
                                <?php endif; ?>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                              </div>

                            </form>

                          </div>

                        </div>
                      </div>
                    </div>

                    


                  <?php
        $contador++; // Incrementar el contador en cada iteración
      }
    } else {
      echo "No se encontraron registros.";
  }

    ?>
  </tbody>
</table>
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



<!-- script inhabilitar retroceder -->  
<!-- <script type="text/javascript">
    window.history.forward(1); //Esto es para cuando le pulse albotón de Atrás
    window.history.back(1); //Esto para cuando le pulse al botónde Adelante
</script>  -->
  <!-- script confirmacion de salida -->
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
