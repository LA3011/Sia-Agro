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
  <title>Listado de Usuarios</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script> 
  <link rel="stylesheet"type="text/css" href="css_personalizado/estilo_usuarios.css">
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
        <div class="modal-dialog modal-lg" style="max-width: 800px;">
          <div class="modal-content">

            <!-- Este div define el encabezado de la ventana modal -->
            <div class="modal-header" style="background-color: #0d6efd; color: white;">
              <h5 class="modal-title text-center w-100">Registrar Usuario</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
 
            <!-- Este formulario se utiliza para recopilar información del usuario -->
            <form method="POST" action="procesar/procesar_usuario.php" id="vaciarCampos" style="padding: 15px 50px 0 50px;">

              <!-- Este div define los elementos del formulario --> 
              <div class="row mb-3">
                <label for="inputText" class="col-sm-2 col-form-label">Nombre Usuario</label>
                <div class="col-sm-10">
                  <input oninput="validateprecio(this)" type="text" class="form-control" id="validationCustom01" placeholder="Ej: Servet123UPTA" required name="Usuario">
                  <div id="usernameError" style="color: red;"></div>
                </div>
              </div>
              <script>
  function validateprecio(input) {
        let maxLength = 8;
        if (input.value.length > maxLength) {
            input.value = input.value.slice(0, maxLength);
        }
        if (input.value < 1) {
            input.value = "";
        }
    }
</script>
              <script>
                $(document).ready(function() {
                  $('#validationCustom01').on('input', function() {
                    var username = $(this).val();

                    $.ajax({
                      url: 'verificar_usuario.php',
                      type: 'POST',
                      data: { username: username },
                      success: function(response) {
                        if (response === 'existe') {
              // El nombre de usuario existe
              $('#validationCustom01').removeClass('is-valid').addClass('is-invalid');
              $('#usernameError').html('El usuario ya existe. <i class="fas fa-times"></i>');
            } else {
              // El nombre de usuario no existe
              $('#validationCustom01').removeClass('is-invalid').addClass('is-valid');
              $('#usernameError').html('');
            }
          }
        });
                  });
                });
              </script>


<div class="row mb-3">
    <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
    <div class="col-sm-10">
        <input class="form-control" type="text" name="nombre" id="nombre" required 
            placeholder="Ej: Diego" oninput="validateName(this)" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,}" title="Ingrese un nombre válido">
        <small class="text-danger" id="errorNombre"></small>
    </div>
</div>

<div class="row mb-3">
    <label for="apellido" class="col-sm-2 col-form-label">Apellido</label>
    <div class="col-sm-10">
        <input class="form-control" type="text" name="apellido" id="apellido" required 
            placeholder="Ej: Flores" oninput="validateName(this)" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,}" title="Ingrese un apellido válido">
        <small class="text-danger" id="errorApellido"></small>
    </div>
</div>

<script>
    function validateName(input) {
        var value = input.value.trim();
        var errorElement = document.getElementById("error" + input.name.charAt(0).toUpperCase() + input.name.slice(1));

        // Eliminar caracteres que no sean letras ni espacios
        input.value = value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '');

        // Verificar que tenga al menos dos letras y al menos una vocal
        var tieneVocal = /[AEIOUaeiouÁÉÍÓÚáéíóú]/.test(value);
        if (value.length < 2 || !tieneVocal) {
            errorElement.innerText = "Ingrese un nombre válido sin números y con al menos una vocal";
            input.setCustomValidity("Nombre no válido");
        } else {
            errorElement.innerText = "";
            input.setCustomValidity("");
        }
    }
</script>




              <div class="row mb-3">
    <label for="floatingPassword" class="col-sm-2 col-form-label">Clave</label>
    <div class="col-sm-10 position-relative">
        <input oninput="validateclave(this)" class="form-control" type="password" name="clave" required id="floatingPassword"
        placeholder="Contraseña">
        <span class="position-absolute top-50 end-0 translate-middle-y me-3" onclick="togglePassword()" style="cursor: pointer;">
            <i id="eyeIcon" class="fas fa-eye"></i>
        </span>
    </div>
</div>
<script>
  function validateclave(input) {
        let maxLength = 14;
        if (input.value.length > maxLength) {
            input.value = input.value.slice(0, maxLength);
        }
        if (input.value < 1) {
            input.value = "";
        }
    }
</script>
<script>
    function togglePassword() {
        var passwordField = document.getElementById("floatingPassword");
        var eyeIcon = document.getElementById("eyeIcon");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }
</script>

<!-- Asegúrate de incluir FontAwesome para los iconos -->


              <div class="row mb-3">
                <label for="nivel_usuario" class="col-sm-2 col-form-label">Nivel de Usuario</label>
                <div class="col-sm-10">
                  <select style="cursor: pointer;" class="form-select" name="perfilx" id="nivel_usuario" required>
                    <option value="">Seleccione una opción</option>
                    <?php
            include_once("conexion/conexion.php");
            $conn = cconexion::ConexionBD();
            $query = "SELECT nombre_perfil FROM perfil";
            $result = $conn->query($query);
            if ($result->rowCount() > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $nombreraza = $row['nombre_perfil'];
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


              <!-- Este div define la sección de preguntas de seguridad -->
              <div class="row mb-3" style="">
                <div class="col-sm-12"
                style="text-align: center; font-weight: bold; font-size: 18px; margin-top: 10px; background-color:#0d6efd; color: white;">
              Preguntas de Seguridad</div>
            </div>

            <div class="row mb-3">
              <label for="inputText" class="col-sm-2 col-form-label">Lugar de Nacimiento?</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" name="mascota" required placeholder="Ej: Limon"  required oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
              </div>
            </div>

            <div class="row mb-3">
              <label for="inputText" class="col-sm-2 col-form-label">Comida Favorita?</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" name="comida" required placeholder="Ej: Pabellón criollo"  required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
              </div>
            </div>

            <div class="row mb-3">
              <label for="inputText" class="col-sm-2 col-form-label">Pelicula Favorita?</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" name="pelicula" required placeholder="Ej: Joker">
              </div>
            </div>

            <div class="row mb-3" style="padding-left: 20%;">
              <div class="col-sm-10" style="text-align: center">
                <!-- Este enlace se utiliza para vaciar el formulario -->
                <input type="hidden" name="session_acceso" value="<?php echo $_SESSION['Usuario'] ?>">
                <input type="hidden" name="session_id" value="<?php echo $_SESSION['Id_Usuario'] ?>">
                <a class="btn btn-secondary" style="width: 100px;" onclick="vaciarCampos()">Vaciar</a>
                <!-- Este botón se utiliza para enviar el formulario -->
                <input type="submit" class="btn btn-primary" value="Registrar"
                style="width: 100px; background-color: green; color: white;">
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
    <!-- MODAL VALIDACION -->
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
    <!-- VALIDACION -->
    <?php if(isset($_SESSION['validacion_permisos'])){ 
      echo '
      <script>
      document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById("myModal"), {
          keyboard: false
          });
          myModal.show();
          });
          </script>
          ';
          $_SESSION['validacion_permisos'] = null; }  ?> 
          <!---------------------------->

          <!------- tabla -------->
          <main id="main" class="main">
            <section class="section">

              <nav style="">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item" style="">Configuración</li>
                  <li class="breadcrumb-item">Ajustes</li>
                  <li class="breadcrumb-item active">Usuarios</li>
                </ol>
              </nav>

              <div class="row">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <p style="position: absolute; right:165px; top:130px;"> Buscar... </p>
                      <h5 class="card-title" style="color:black; font-size:40px; margin-left:7%;">Listado de Usuarios</h5>
                      <button  type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#largeModal"
                      style="margin-right:82.5%; margin-top:10px; margin-bottom:8px;" title="Agregar"><i class="ri-add-fill"
                      style="color:white;"></i>Agregar &nbsp</button>

                      <table class="table datatable">
                        <thead>
                          <tr>
                            <th>Usuario </th>
                            <th> Nombre </th>
                            <th>Apellido </th>
                            <th>Tipo Usuario</th>
                            <th>Estado</th>
                            <th>fecha/hora Registro </th>
                            <th>- Accion -</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
                  include_once("conexion/conexion.php");
                  $conn = cconexion::ConexionBD(); // Inicializar la conexión
                  
                  $sql = "SELECT * FROM usuarios ORDER BY \"Id_Usuario\"";

                  $result = $conn->query($sql);
                  
                  // Verificar si se encontraron registros
                  if ($result->rowCount() > 0) {
                    // Variable para contar los registros
                      $contador = 1;

                    // Recorrer los registros y mostrar los datos en la tabla
                    while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {   ?>
                              <tr>
                                <td>
                                  <?php echo $fila["Usuario"]; ?>
                                </td>
                                <td>
                                  <?php echo $fila["Nombre"]; ?>
                                </td>
                                <td>
                                  <?php echo $fila["Apellido"]; ?>
                                </td>
                                <td>
                                  <?php echo $fila["Id_Perfilp"] . " - " . $fila["tipo_usuario"]; ?>
                                </td>
                                <td>
                                  <?php
                                  if ($fila["Habilitado"] == '1') {
                                    echo "Activo";
                                  } else {
                                    echo "Inhabilitado";
                                  } ?>
                                </td>
                                <td>
                                  <?php echo $fila["Fecha"]; ?>
                                </td>
                                <td>

                                  <!-- ---- ↓↓ CODIGO A COPIAR ↓↓ ---- -->
                                 
                                  <!-- ---- ↑↑ CODIGO A COPIAR ↑↑ ---- -->
                                  <div class="btn-group" role="group">
                                <?php if( ($fila["Id_Usuario"] == 1) or ($fila["Id_Usuario"] == $_SESSION['Id_Usuario']) ){ // NADA (SUPERUSUARIO)
                                }else{ ?>

                                  <?php if($ver == "true") { ?>  
                                    <!-- Boton-modal [ver] -->
                                    <a type="button" data-bs-toggle="modal" data-bs-target="#basicModal-VER<?php echo $fila['Id_Usuario']; ?>" style="color:none;  margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"title="Ver">
                                      <i class="ri-eye-fill" style="color:#17E45B" aria-describedby="tooltip831980"></i>
                                    </a>
                                  <?php } ?>      

                                  <?php if($editar == "true") { ?>  
                                    <!-- Boton-modal [Editar] -->
                                    <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                                    type="button" data-bs-toggle="modal"
                                    data-bs-target='#basicModal-<?php echo $fila["Id_Usuario"]; ?>' title="Editar">
                                    <i class="ri-ball-pen-fill" style="color:#E5D001;"></i>
                                  <?php } ?>  
                                </a>

                              <?php } ?> 

                              <?php 
                              if($fila["Id_Usuario"] == 1) { 
                                ?>                    <span style="font-size: 20px;" class="badge bg-primary"><i class="bi bi-star me-1"></i>Super Usuario</span><?php
                              }elseif(($fila["Id_Usuario"] == $_SESSION['Id_Usuario'])){
                                ?>                    <span style="font-size: 20px;" class="badge bg-dark"><i class="bi bi-info-circle me-1"></i>Usuario en Uso </span><?php 
                              }elseif($fila["Habilitado"] == '1') {
                                // Si el usuario está habilitado, se muestra un botón de "Inhabilitar" con un enlace a la página "deshabilitar1.php"
                               if($eliminar == "true") {
                                echo "<a class='btn btn-outline-danger btn-sm mx-1' href='deshabilitaciones/deshabilitar_usuario.php?id=" . $fila["Id_Usuario"] . "' style='display:flex; justify-content: center; align-items: center;' title=". $fila["Id_Usuario"] .">inhabilitar</a>";
                              }
                            } else {
                              // Si el usuario no está habilitado, se muestra un botón de "Habilitar" con un enlace a la página "habilitar1.php"
                              if($eliminar == "true") {
                                echo "<a class='btn btn-outline-success btn btn-sm mx-1' href='deshabilitaciones/habilitar_usuario.php?id=" . $fila["Id_Usuario"] . "'>  Habilitar  </a>";
                              }
                            }
                            ?>
                            
                              </td>
                            <!-- modal [eliminar] -->
                            <div class="modal fade" id="smallModal-<?php echo $fila['Id_Usuario']; ?>" tabindex="-1">
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
                                    href='deshabilitaciones/deshabilitar_usuario.php?id=<?php echo $fila['Id_Usuario'] ?>&session_acceso=<?php echo isset($_SESSION["Usuario"]) ? $_SESSION["Usuario"] : ""; ?>&session_id=<?php echo isset($_SESSION["Id_Usuario"]) ? $_SESSION["Id_Usuario"] : ""; ?>'>

                                    <span class="btn btn-outline-danger">Eliminar</span>
                                  </a>
                                  <button style="left:px; position: relative;" type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Cerrar</button>                                
                                </div>
                              </div>
                            </div>
                          </div>
                          <!------- modal de actualizar -------->
                          <div class="modal fade" id="basicModal-<?php echo $fila["Id_Usuario"]; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg" style="max-width: 600px;">
                              <div class="modal-content">
                                <div class="modal-header"
                                style="background-color:#0d6efd; color: #fff; text-align: center;">
                                <h5 class="modal-title" tyle=" text-align: center;" style="margin-left: 37%;">Editar Usuario</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <form method='POST' action="actualizar/actualizar_usuario.php">
                                  <div>
                                    <input type="hidden" class="form-control" name="id_usuario"
                                    value='<?php echo $fila["Id_Usuario"]; ?>'>
                                  </div>
                                  <!-- Este div contiene un input y una etiqueta para el campo "Usuario" -->
                                  <div class="row mb-3" style="margin:0 40px 0 10px;">
    <label class="col-sm-2 col-form-label" style="color:#21618C;">Usuario</label>
    <div class="col-sm-10">
        <input oninput="validateUsuario(this)" type="text" class="form-control" name="usuario" 
            value='<?php echo $fila["Usuario"] ?>'>
        <small class="text-danger" id="errorUsuario"></small>
    </div>
</div>

<!-- Campo Nombre -->
<div class="row mb-3" style="margin:0 40px 0 10px;">
    <label class="col-sm-2 col-form-label" style="color:#21618C;">Nombre</label>
    <div class="col-sm-10">
        <input type="text" oninput="validatenombre_act(this)" class="form-control" name="nombre" required  
            value='<?php echo $fila["Nombre"]; ?>'>
        <small class="text-danger" id="errorNombre"></small>
    </div>
</div>

<script>
    function validatenombre_act(input) {
        var value = input.value.trim();
        var errorElement = document.getElementById("errorNombre");

        // Eliminar caracteres no permitidos (solo letras y espacios)
        input.value = value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '');

        // Verificar que tenga al menos dos letras y contenga una vocal
        var tieneVocal = /[AEIOUaeiouÁÉÍÓÚáéíóú]/.test(value);
        if (value.length < 2 || !tieneVocal) {
            errorElement.innerText = "Ingrese un nombre válido sin números y con al menos una vocal";
            input.setCustomValidity("Nombre no válido");
        } else {
            errorElement.innerText = "";
            input.setCustomValidity("");
        }
    }

    function validateUsuario(input) {
        var value = input.value.trim();
        var errorElement = document.getElementById("errorUsuario");

        // Solo permitir letras, números y guiones bajos
        input.value = value.replace(/[^A-Za-z0-9_]/g, '');

        // Validar longitud mínima de usuario
        if (value.length < 4) {
            errorElement.innerText = "El usuario debe tener al menos 4 caracteres.";
            input.setCustomValidity("Usuario inválido");
        } else {
            errorElement.innerText = "";
            input.setCustomValidity("");
        }
    }
</script>

                                  <!-- Este div contiene un input y una etiqueta para el campo "Apellido" -->
                                  <div class="row mb-3" style="margin:0 40px 0 10px;">
                                    <label class="col-sm-2 col-form-label" style="color:#21618C;">Apellido</label>
                                    <div class="col-sm-10">
                                      <input type="text" oninput="validatenombre_act(this)" class="form-control" name="Apellido"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                      value='<?php echo $fila["Apellido"]; ?>'>
                                    </div>
                                  </div>

                                  <div class="row mb-3" style="margin:0 40px 0 10px;">
    <label class="col-sm-2 col-form-label" style="color:#21618C;">Perfil</label>
    <div class="col-sm-10">
        <select style="cursor: pointer;" class="form-select" name="Id_Perfilp" id="nivel_usuario" required>
            <option value="<?php echo $fila['Id_Perfilp']; ?>">
                <?php echo $fila["Id_Perfilp"] . ' - ' . $fila["tipo_usuario"]; ?>
            </option>
            <?php
            try {
                $sql = "SELECT * FROM perfil";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($fila["Id_Perfilp"] != $rows["Id_Perfil"] && $rows["Id_Perfil"] != 1) { ?>
                        <option value="<?php echo $rows["Id_Perfil"]; ?>">
                            <?php echo $rows["Id_Perfil"] . ' - ' . $rows['nombre_perfil']; ?>
                        </option>
                    <?php }
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>
        </select>
    </div>
</div>



                                  <!-- Este div contiene un botón para actualizar los datos del usuario mediante el archivo editar.php -->
                                  <div class="row mb-3" style="padding-left:15%;">
                                    <div class="col-sm-10" style="text-align:center">

                                      <input type="hidden" name="session_acceso" value="<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>">
                                      <input type="hidden" name="session_id" value="<?php echo isset($_SESSION['Id_Usuario']) ? $_SESSION['Id_Usuario'] : ''; ?>">
                                      <button type="submit" class="btn btn-primary" name="actualizar">Actualizar</button>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!------- modal de ver -------->
                        <div class="modal fade" id="basicModal-VER<?php echo $fila["Id_Usuario"]; ?>" tabindex="-1">
                          <div class="modal-dialog modal-lg" style="max-width: 600px;">
                            <div class="modal-content">
                              <div class="modal-header"
                              style="background-color:#0d6efd; color: #fff; text-align: center;">
                              <h5 class="modal-title mx-auto" tyle=" text-align: center;">Ver Usuario</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"
                              aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form method='POST' action=".php">
                                <div>
                                  <input style="pointer-events: none;" type="hidden" class="form-control" name="id_usuario"
                                  value='<?php echo $fila["Id_Usuario"]; ?>'>
                                </div>
                                <!-- Este div contiene un input y una etiqueta para el campo "Usuario" -->
                                <div class="row mb-3" style="margin:0 40px 0 10px;">
                                  <label class="col-sm-2 col-form-label" style="color:#21618C;">Usuario</label>
                                  <div class="col-sm-10">
                                    <input style="pointer-events: none;" type="text" class="form-control" name="usuario"
                                    value='<?php echo $fila["Usuario"] ?>'>
                                  </div>
                                </div>

                                <!-- Este div contiene un input y una etiqueta para el campo "Nombre" -->
                                <div class="row mb-3" style="margin:0 40px 0 10px;">
                                  <label class="col-sm-2 col-form-label" style="color:#21618C;">Nombre</label>
                                  <div class="col-sm-10">
                                    <input style="pointer-events: none;" type="text" class="form-control" name="nombre"  required  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                    value='<?php echo $fila["Nombre"]; ?>'>
                                  </div>
                                </div>

                                <!-- Este div contiene un input y una etiqueta para el campo "Apellido" -->
                                <div class="row mb-3" style="margin:0 40px 0 10px;">
                                  <label class="col-sm-2 col-form-label" style="color:#21618C;">Apellido</label>
                                  <div class="col-sm-10">
                                    <input style="pointer-events: none;" type="text" class="form-control" name="Apellido"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                    value='<?php echo $fila["Apellido"]; ?>'>
                                  </div>
                                </div>

                                <div class="row mb-3" style="margin:0 40px 0 10px;">
                                  <label class="col-sm-2 col-form-label" style="color:#21618C;">Perfil</label>
                                  <div class="col-sm-10">
                                    <select style="pointer-events: none;" class="form-select" name="Id_Perfilp" id="nivel_usuario" required>
                                      <option><?php echo $fila["Id_Perfilp"] . ' - ' . $fila["tipo_usuario"] ;?></option>
                                   
                                    </select>
                                  </div>
                                </div>


                                <!-- Este div contiene un botón para actualizar los datos del usuario mediante el archivo editar.php -->
                                <div class="row mb-3" style="padding-left:15%;">
                                  <div class="col-sm-10" style="text-align:center">

                                    <input style="pointer-events: none;" type="hidden" name="session_acceso" value="<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>">
                                    <input type="hidden" name="session_id" value="<?php echo isset($_SESSION['Id_Usuario']) ? $_SESSION['Id_Usuario'] : ''; ?>">
                                    <button style="position:relative; pointer-events:visible;" type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                      Cerrar
                                    </button>
                                  </div>
                                </div>
                              </form>
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

              <style>
                .datatable-info{
                  color:whitesmoke;
                  font-size:0;
                }
                .col-lg-12{
                  margin-bottom:5%;
                }
                .datatable-input::placeholder{
                  visibility: hidden;
                  content: "Referenciar";
                }

                .datatable-dropdown::after{
                  content: "Entradas por página";
                  position: absolute;
                  left:90px;
                  top:162px;
                }

                .datatable-dropdown > label{
                  visibility: hidden;
                }

                .datatable-selector{
                  visibility: visible;
                }

              </style>
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
   $("#vaciarCampos")[0].reset();
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