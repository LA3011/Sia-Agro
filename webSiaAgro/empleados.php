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


<head>
  <script src="js/jquery-3.7.1.min.js"></script>
  <meta charset="utf-8">
  <title>Listado de Empleados</title>
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script>
  <link rel="stylesheet"type="text/css" href="css_personalizado/estilo_empleados.css">
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

          <!-- Este div define el encabezado de la ventana modal -->
          <div class="modal-header" style="background-color: #0d6efd; color: white;">
            <h5 class="modal-title text-center w-100">Registro de Empleados</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <!-- Este formulario se utiliza para recopilar información del usuario -->
          <form class="p1" method="POST" action="procesar/procesar_empleados.php" style="padding: 0 50px 0 50px;"
          onsubmit="return validarFormulario();">
          <br>
          <!-- Este div define los elementos del formulario -->

          <div class="row mb-2">
            <label for="inputText" class="col-sm-3 col-form-label">Nombre</label>
            <div class="col-sm-9">
              <input class="form-control" type="text" name="Nombre" required placeholder="Ej: Diego"
              oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
            </div>
          </div>

          <div class="row mb-2">
            <label for="inputText" class="col-sm-3 col-form-label">Apellido</label>
            <div class="col-sm-9">
              <input class="form-control" type="text" name="apellido" required placeholder="Ej: Flores"
              oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
            </div>
          </div>

          <div class="row mb-2">
            <label for="inputText" class="col-sm-3 col-form-label">Dirección de Vivienda</label>
            <div class="col-sm-9">
              <input class="form-control" type="text" name="direccion_vivienda" required placeholder="Ej: Limon">
            </div>
          </div>

          <div class="row mb-2">
    <label for="inputText" class="col-sm-3 col-form-label">Correo Electrónico</label>
    <div class="col-sm-9">
        <input class="form-control" type="email" id="correo_electronico" equired name="correo_electronico" placeholder="Ej: Diego@gmail.com" oninput="validarCorreo()">
        <div id="mensaje_correo" style="color: red;"></div>
    </div>
</div>

<script>
    function validarCorreo() {
        var correoInput = document.getElementById("correo_electronico");
        var correo = correoInput.value.trim();
        var mensajeCorreo = document.getElementById("mensaje_correo");

        var expresionCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (expresionCorreo.test(correo)) {
            // El correo tiene el formato correcto
            mensajeCorreo.textContent = "";
        } else {
            // El correo no tiene el formato correcto
            mensajeCorreo.textContent = "Por favor, ingresa un correo electrónico válido.";
        }
    }
</script>

<div class="row mb-2">
    <label for="numero_telefonico" class="col-sm-3 col-form-label">Número Telefónico</label>
    <div class="col-sm-9">
        <input class="form-control" type="tel" id="numero_telefonico" name="numero_telefonico" required placeholder="Ej: 041200000" oninput="validarNumero()">
        <div id="mensaje_numero" style="color: red; margin-top: 5px;"></div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#numero_telefonico').on('input', function () {
            var telefono = $(this).val().trim();

            if (telefono.length > 0) {
                $.ajax({
                    url: 'validacion/verificar_empleados.php', // Ruta al archivo PHP
                    type: 'GET',
                    data: { telefono: telefono },
                    dataType: 'json',
                    success: function (response) {
                        if (response.telefono_success === 1) {
                            $('#mensaje_numero').html(response.telefono_message); // Mostrar mensaje si el número ya existe
                        } else {
                            $('#mensaje_numero').html(''); // Limpiar mensaje si el número no existe
                        }
                    },
                    error: function () {
                        $('#mensaje_numero').html('Error al verificar el número telefónico. Intente nuevamente.');
                    }
                });
            } else {
                $('#mensaje_numero').html(''); // Limpiar mensaje si el campo está vacío
            }
        });
    });

    function validarNumero() {
        var numeroInput = document.getElementById("numero_telefonico");
        var numero = numeroInput.value.trim();
        var mensajeNumero = document.getElementById("mensaje_numero");

        // Expresión regular para validar el número telefónico
        var expresionNumero = /^(0412|0416|0414|0424|0426)\d{7}$/;

        if (expresionNumero.test(numero)) {
            // El número tiene el formato correcto
            mensajeNumero.textContent = "";
        } else {
            // El número no tiene el formato correcto
            mensajeNumero.textContent = "El número telefónico debe comenzar con 0412, 0416, 0414, 0424 o 0426 y tener 11 dígitos en total.";
        }
    }
</script>
<div class="row mb-2">
    <label for="inputText" class="col-sm-3 col-form-label">Fecha Nacimiento</label>
    <div class="col-sm-9">
        <input class="form-control" type="date" id="fecha_nacimiento" name="fecha_nacimiento" required placeholder="" max="2005-01-01" min="1980-01-01" oninput="validarFechaNacimiento()">
        <div id="mensaje_error"></div>
    </div>
</div>

<div class="row mb-2">
    <label for="inputText" class="col-sm-3 col-form-label">Fecha de Ingreso</label>
    <div class="col-sm-9">
        <input type="date" class="form-control" name="fecha_ingreso" id="fecha_ingreso" required>
    </div>
</div>

<script>
function validarFechaNacimiento() {
    var inputFecha = document.getElementById("fecha_nacimiento").value;
    var fechaNacimiento = new Date(inputFecha);
    var hoy = new Date();
    var edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
    var m = hoy.getMonth() - fechaNacimiento.getMonth();
    if (m < 0 || (m === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
        edad--;
    }

    var mensajeError = document.getElementById("mensaje_error");
    if (edad < 18 || edad >= 70) {
        mensajeError.innerHTML = "La fecha de nacimiento debe estar entre 18 y 70 años.";
        mensajeError.style.color = "red";
    } else {
        mensajeError.innerHTML = "";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const fechaNacimientoInput = document.getElementById("fecha_nacimiento");
    const fechaIngresoInput = document.getElementById("fecha_ingreso");
    
    // Validación de la fecha de ingreso con respecto a la fecha de nacimiento
    fechaIngresoInput.addEventListener("change", function () {
        const fechaNacimiento = new Date(fechaNacimientoInput.value);
        const fechaIngreso = new Date(fechaIngresoInput.value);

        // Si la fecha de ingreso es igual a la fecha de nacimiento o anterior
        if (fechaIngreso <= fechaNacimiento) {
            alert("La fecha de ingreso no puede ser igual o menor a la fecha de nacimiento. Verifique las fechas.");
            fechaIngresoInput.value = "";  // Limpiar campo de fecha de ingreso si es inválido
            fechaIngresoInput.focus();     // Focalizar nuevamente en el campo de fecha de ingreso
        }
    });

    // Validación de la fecha de nacimiento con respecto a la fecha de ingreso
    fechaNacimientoInput.addEventListener("change", function () {
        const fechaNacimiento = new Date(fechaNacimientoInput.value);
        const fechaIngreso = new Date(fechaIngresoInput.value);

        // Si la fecha de nacimiento es igual o posterior a la fecha de ingreso
        if (fechaNacimiento >= fechaIngreso) {
            alert("La fecha de nacimiento no puede ser igual o posterior a la fecha de ingreso. Verifique las fechas.");
            fechaNacimientoInput.value = "";  // Limpiar campo de fecha de nacimiento si es inválido
            fechaNacimientoInput.focus();     // Focalizar nuevamente en el campo de fecha de nacimiento
        }
    });
});

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

function validateAnimalNumber(input) {
        let maxLength = 8;

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
    <label for="inputText" class="col-sm-3 col-form-label">Rif</label>
    <div class="col-sm-9">
        <input id="rif" class="form-control" type="number" name="rif" required placeholder="Ej:123456789"
               oninput="this.value = this.value.replace(/[^0-9]/g, '')">
        <div id="rif-message" style="color: red; margin-top: 5px;"></div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#rif').on('input', function () {
            var rif = $(this).val().trim();

            if (rif.length > 0) {
                $.ajax({
                    url: 'validacion/verificar_empleados.php', // Ruta al archivo PHP
                    type: 'GET',
                    data: { rif: rif },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success === 1) {
                            $('#rif-message').html(response.message); // Mostrar mensaje si el RIF ya existe
                        } else {
                            $('#rif-message').html(''); // Limpiar mensaje si el RIF no existe
                        }
                    },
                    error: function () {
                        $('#rif-message').html('Error al verificar el RIF. Intente nuevamente.');
                    }
                });
            } else {
                $('#rif-message').html(''); // Limpiar mensaje si el campo está vacío
            }
        });
    });
</script>



          <div class="row mb-2">
            <label for="inputText" class="col-sm-3 col-form-label">Cargo</label>
            <div class="col-sm-9">
              <input class="form-control" type="text" name="cargo" required placeholder="Ej: Administrador"
              oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
            </div>
          </div>

          <div class="row mb-2">
            <label for="inputText" class="col-sm-3 col-form-label" id="sueldo">Sueldo (Bs)</label>
            <div class="col-sm-9">
              <input oninput="validateprecio(this)" class="form-control" type="number" name="sueldo" required placeholder="Ej: 150"
              oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div>
          </div>
          <br>
          <div class="row mb-2" style="padding-left: 25%;">
            <div class="col-sm-9" style="text-align: center">
            <a class="btn btn-secondary" style="width: 100px;" onclick="vaciarCampos()">Vaciar</a>
              <!-- Este botón se utiliza para enviar el formulario -->
              <input type="hidden" name="session_acceso" value="<?php echo $_SESSION['Usuario'] ?>">
              <input type="hidden" name="session_id" value="<?php echo $_SESSION['Id_Usuario'] ?>">
              <input type="submit" class="btn btn-succcess" value="Registrar"
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
          <li class="breadcrumb-item">Recursos Humanos</li>
          <li class="breadcrumb-item active">Empleados</li>
        </ol>
      </nav>

      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <p style="position: absolute; right:165px; top:130px;"> Buscar... </p>
              <h5 class="card-title" style="color:black; font-size:40px; margin-left:7%;">Listado de Empleados</h5>
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#largeModal"
              style="margin-right:82.5%; margin-top:10px; margin-bottom:8px;" title="Agregar"><i class="ri-add-fill"
              style="color:white;"></i>Agregar &nbsp</button>

              <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido</th>
                    <th scope="col">Fecha/Nacimiento</th>
                    <th scope="col">telefónico</th>
                    <th scope="col">Rif</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Fecha/Ingreso</th>
                    <th scope="col">Cargo</th>
                    <th scope="col">Sueldo</th>
                    <th scope="col">Registro</th>
                    <th scope="col">Acción</th>
                  </tr>
                </thead>
                <tbody>
                <?php
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();

$sql = "SELECT * FROM empleados ORDER BY \"Id_empleados\"";
$result = $conn->query($sql);

if ($result->rowCount() > 0) {
    $contador = 1;
    while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                      <tr>
                        <th scope="row">
                          <?php echo $contador; ?>
                        </th>
                        <td>
                          <?php echo $fila["nombre"]; ?>
                        </td>
                        <td>
                          <?php echo $fila["apellido"]; ?>
                        </td>
                        <td>
                          <?php echo date("d/m/Y", strtotime($fila["Fecha_nacimiento"])); ?>
                        </td>
                        <td>
                          <?php echo $fila["numero_telefonico"]; ?>
                        </td>
                        <td>
                          <?php echo $fila["rif"]; ?>
                        </td>
                        
                        <td>
                          <?php echo $fila["correo"]; ?>
                        </td>
                        <td>
                          <?php echo date("d/m/Y", strtotime($fila["Fecha_ingreso"])); ?>
                        </td>
                        <td>
                          <?php echo $fila["cargo"]; ?>
                        </td>
                        <td>
                          <?php echo $fila["sueldo"] . "Bs"; ?>
                        </td>
                        <td>
                          <?php echo $fila["fecha_registro"]; ?>
                        </td>
                        <td>
                          
                            <div class="btn-group" role="group">

                              <?php if($ver == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                              <!-- Boton-modal [ver] -->
                              <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                              type="button" data-bs-toggle="modal"
                              data-bs-target='#basicModal-VER<?php echo $fila["Id_empleados"]; ?>' title="Editar">
                              <i class="ri-eye-fill" style="color:#17E45B"></i>
                            </a>
                            <?php } ?>  <!-- ← CODIGO A COPIAR -->


                            <?php if($editar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                            <!-- Boton-modal [Editar] -->
                            <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                            type="button" data-bs-toggle="modal"
                            data-bs-target='#basicModal-<?php echo $fila["Id_empleados"]; ?>' title="Editar">
                            <i class="ri-ball-pen-fill" style="color:#E5D001;"></i>
                          </a>
                          <?php } ?>  <!-- ← CODIGO A COPIAR -->


                          <?php if($eliminar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                          <!-- Boton-modal [eliminar] -->
                          <a type="button" data-bs-toggle="modal" data-bs-target="#smallModal-<?php echo $fila["Id_empleados"]; ?>"
                            style="color:none;  margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                            title="Eliminar">
                            <i class="ri-delete-bin-2-line" style="color:#EE0D0D;"></i>
                          </a>
                        <?php } ?>




                        <!-- modal [eliminar] -->
                        <div class="modal fade" id="smallModal-<?php echo $fila["Id_empleados"]; ?>" tabindex="-1">
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
                              href='deshabilitaciones/deshabilitar_empleados.php?id=<?php echo $fila["Id_empleados"] ?>&session_acceso=<?php echo isset($_SESSION["Usuario"]) ? $_SESSION["Usuario"] : ""; ?>&session_id=<?php echo isset($_SESSION["Id_Usuario"]) ? $_SESSION["Id_Usuario"] : ""; ?>'
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
                    <div class="modal fade" id="basicModal-VER<?php echo $fila["Id_empleados"]; ?>" tabindex="-1">

                      <!-- Este div define la estructura y el tamaño de la ventana modal -->
                      <div class="modal-dialog modal-lg" style="max-width: 900px;">

                        <!-- Este div contiene el contenido de la ventana modal -->
                        <div class="modal-content">

                          <!-- Este div define el encabezado de la ventana modal y contiene el título "Editar Usuario" y un botón para cerrar la ventana -->
                          <div class="modal-header" style="background-color: #0d6efd; color: white;">
                            <h5 class="modal-title text-center w-100">Ver información</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                          </div>

                          <!-- Este div contiene el cuerpo de la ventana modal -->
                          <div class="modal-body">

                            <!-- Este formulario se utiliza para enviar los datos del formulario a la página "editar.php" utilizando el método "POST" -->
                            <form method='POST' action="actualizar/actualizar_empleados.php"
                            onsubmit="return validarFormulario();">
                            <div>
                              <input style="pointer-events: none;" type="hidden" class="form-control" name="id_empleados"
                              value='<?php echo $fila["Id_empleados"]; ?>'>
                            </div>

                            <!-- Este div contiene un input y una etiqueta para el campo "Nombre" -->
                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre</label>
                              <div class="col-sm-9">
                                <input style="pointer-events: none;" type="text" class="form-control" name="nombre"
                                value='<?php echo $fila["nombre"]; ?>' required placeholder=" "
                                oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                              </div>
                            </div>

                            <!-- Este div contiene un input y una etiqueta para el campo "Apellido" -->
                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Apellido</label>
                              <div class="col-sm-9">
                                <input style="pointer-events: none;" type="text" class="form-control" name="apellido"
                                value='<?php echo $fila["apellido"]; ?>' required placeholder=" "
                                oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                              </div>
                            </div>

                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Dirección de
                              vivienda</label>
                              <div class="col-sm-9">
                                <input style="pointer-events: none;" type="text" class="form-control" name="direccion_vivienda"
                                value='<?php echo $fila["direccion_vivienda"]; ?>'>
                              </div>
                            </div>

                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Número
                              Telefónico</label>
                              <div class="col-sm-9">
                                <input style="pointer-events: none;" type="number" class="form-control" name="numero"
                                value='<?php echo $fila["numero_telefonico"]; ?>' required placeholder=""
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                              </div>
                            </div>

                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Rif</label>
                              <div class="col-sm-9">
                                <input oninput="validateAnimalNumber(this)" style="pointer-events: none;" type="number" class="form-control" name="rif"
                                value='<?php echo $fila["rif"]; ?>' required placeholder=""
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                              </div>
                            </div>

                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Correo </label>
                              <div class="col-sm-9">
                                <input style="pointer-events: none;" type="text" class="form-control" name="correo"
                                value='<?php echo $fila["correo"]; ?>'>
                              </div>
                            </div>

                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Cargo </label>
                              <div class="col-sm-9">
                                <input style="pointer-events: none;" type="text" class="form-control" name="cargo"
                                value='<?php echo $fila["cargo"]; ?>' required placeholder=" "
                                oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                              </div>
                            </div>

                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;"> Sueldo (Bs)</label>
                              <div class="col-sm-9">
                                <input  style="pointer-events: none;" type="number" class="form-control" name="sueldo"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                value='<?php echo $fila["sueldo"]; ?>'>
                              </div>
                            </div>

                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha de
                              Nacimiento</label>
                              <div class="col-sm-9">
                                <input  max="2005-01-01" min="1980-01-01" style="pointer-events: none;" type="date" class="form-control" name="fecha_nacimiento"
                                value='<?php echo $fila["Fecha_nacimiento"]; ?>' >
                              </div>
                            </div>


                            <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha de
                              ingreso</label>
                              <div class="col-sm-9">
                                <input style="pointer-events: none;" type="date" class="form-control" name="fecha_ingreso"
                                value='<?php echo $fila["Fecha_ingreso"]; ?>'>
                              </div>
                            </div>


                            <!-- Este div contiene un botón para actualizar los datos del usuario mediante el archivo editar.php -->

                            <div class="modal-footer">
                              <input style="pointer-events: none;" type="hidden" name="session_acceso" value="<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>">
                              <input type="hidden" name="session_id" value="<?php echo isset($_SESSION['Id_Usuario']) ? $_SESSION['Id_Usuario'] : ''; ?>">

                              <button type="button" class="btn btn-secondary"
                              data-bs-dismiss="modal">Cancelar</button>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>



            <!------- modal de actualizar -------->
            <div class="modal fade" id="basicModal-<?php echo $fila["Id_empleados"]; ?>" tabindex="-1">

              <!-- Este div define la estructura y el tamaño de la ventana modal -->
              <div class="modal-dialog modal-lg" style="max-width: 900px;">

                <!-- Este div contiene el contenido de la ventana modal -->
                <div class="modal-content">

                  <!-- Este div define el encabezado de la ventana modal y contiene el título "Editar Usuario" y un botón para cerrar la ventana -->
                  <div class="modal-header" style="background-color: #0d6efd; color: white;">
                    <h5 class="modal-title text-center w-100">Actualizar información</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                  </div>

                  <!-- Este div contiene el cuerpo de la ventana modal -->
                  <div class="modal-body">

                    <!-- Este formulario se utiliza para enviar los datos del formulario a la página "editar.php" utilizando el método "POST" -->
                    <form method='POST' action="actualizar/actualizar_empleados.php"
                    onsubmit="return validarFormulario();">
                    <div>
                      <input type="hidden" class="form-control" name="id_empleados" value='<?php echo $fila["Id_empleados"]; ?>'>
                    </div>

                    <!-- Este div contiene un input y una etiqueta para el campo "Nombre" -->
                    <div class="row mb-2">
                      <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" name="nombre"
                        value='<?php echo $fila["nombre"]; ?>' required placeholder=" "
                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                      </div>
                    </div>

                    <!-- Este div contiene un input y una etiqueta para el campo "Apellido" -->
                    <div class="row mb-2">
                      <label class="col-sm-3 col-form-label" style="color:#21618C;">Apellido</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" name="apellido"
                        value='<?php echo $fila["apellido"]; ?>' required placeholder=" "
                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                      </div>
                    </div>

                    <div class="row mb-2">
  <label class="col-sm-3 col-form-label" style="color:#21618C;">Dirección de vivienda</label>
  <div class="col-sm-9">
    <input type="text" class="form-control" id="direccion_vivienda" name="direccion_vivienda" value='<?php echo $fila["direccion_vivienda"]; ?>' required>
    <div id="mensaje_direccion_vivienda" style="color: red;"></div>
  </div>
</div>

<script>
  document.getElementById('direccion_vivienda').addEventListener('input', validarDireccionVivienda);

  function validarDireccionVivienda() {
    var direccionInput = document.getElementById('direccion_vivienda');
    var direccion = direccionInput.value.trim();
    var mensajeDireccion = document.getElementById('mensaje_direccion_vivienda');

    var expresionCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (expresionCorreo.test(direccion) || direccion === '') {
      mensajeDireccion.textContent = "";
    } else {
      mensajeDireccion.textContent = "Ingrese una dirección de correo válida.";
    }
  }
</script>


                    <div class="row mb-2">
                      <label class="col-sm-3 col-form-label" style="color:#21618C;">Número
                      Telefónico</label>
                      <div class="col-sm-9">
                        <input type="number" class="form-control" name="numero"
                        value='<?php echo $fila["numero_telefonico"]; ?>' required placeholder=""
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                      </div>
                    </div>

                    <div class="row mb-2">
                      <label class="col-sm-3 col-form-label" style="color:#21618C;">Rif</label>
                      <div class="col-sm-9">
                        <input oninput="validateAnimalNumber(this)" type="number" class="form-control" name="rif"
                        value='<?php echo $fila["rif"]; ?>' required placeholder=""
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                      </div>
                    </div>

                    <div class="row mb-2">
                      <label class="col-sm-3 col-form-label" style="color:#21618C;">Correo </label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" name="correo"
                        value='<?php echo $fila["correo"]; ?>'>
                      </div>
                    </div>

                    <div class="row mb-2">
                      <label class="col-sm-3 col-form-label" style="color:#21618C;">Cargo </label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" name="cargo"
                        value='<?php echo $fila["cargo"]; ?>' required placeholder=" "
                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                      </div>
                    </div>

                    <div class="row mb-2">
                              <label class="col-sm-3 col-form-label" style="color:#21618C;"> Sueldo (Bs)</label>
                              <div class="col-sm-9">
                                <input oninput="validateprecio(this)"  type="number" class="form-control" name="sueldo"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                value='<?php echo $fila["sueldo"]; ?>'>
                              </div>
                            </div>

                            <div class="row mb-2">
    <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha de Nacimiento</label>
    <div class="col-sm-9">
        <input id="fechanacimiento_act" max="2005-01-01" min="1980-01-01" type="date" class="form-control" name="fecha_nacimiento"
               value='<?php echo $fila["Fecha_nacimiento"]; ?>'>
        <small id="errorNacimiento" class="text-danger"></small>
    </div>
</div>

<div class="row mb-2">
    <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha de ingreso</label>
    <div class="col-sm-9">
        <input id="fechaingreso_act" type="date" class="form-control" name="fecha_ingreso"
               value='<?php echo $fila["Fecha_ingreso"]; ?>'>
        <small id="errorIngreso" class="text-danger"></small>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const fechaNacimientoInput = document.getElementById("fechanacimiento_act");
    const fechaIngresoInput = document.getElementById("fechaingreso_act");
    const errorNacimiento = document.getElementById("errorNacimiento");
    const errorIngreso = document.getElementById("errorIngreso");

    function validarFechaNacimiento() {
        const inputFecha = fechaNacimientoInput.value;
        if (!inputFecha) return;

        const fechaNacimiento = new Date(inputFecha);
        const hoy = new Date();
        let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
        const m = hoy.getMonth() - fechaNacimiento.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
            edad--;
        }

        if (edad < 18 || edad >= 70) {
            errorNacimiento.textContent = "La edad debe estar entre 18 y 70 años.";
            fechaNacimientoInput.value = "";
        } else {
            errorNacimiento.textContent = "";
        }
    }

    function validarFechasRelacionadas() {
        const fechaNacimiento = new Date(fechaNacimientoInput.value);
        const fechaIngreso = new Date(fechaIngresoInput.value);

        if (!fechaNacimientoInput.value || !fechaIngresoInput.value) return;

        if (fechaIngreso <= fechaNacimiento) {
            errorIngreso.textContent = "La fecha de ingreso no puede ser menor o igual a la fecha de nacimiento.";
            fechaIngresoInput.value = "";
        } else {
            errorIngreso.textContent = "";
        }
    }

    fechaNacimientoInput.addEventListener("change", function () {
        validarFechaNacimiento();
        validarFechasRelacionadas();
    });

    fechaIngresoInput.addEventListener("change", validarFechasRelacionadas);
});
</script>

                    <!-- Este div contiene un botón para actualizar los datos del usuario mediante el archivo editar.php -->

                    <div class="modal-footer">
                      <input type="hidden" name="session_acceso" value="<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>">
                      <input type="hidden" name="session_id" value="<?php echo isset($_SESSION['Id_Usuario']) ? $_SESSION['Id_Usuario'] : ''; ?>">
                      <button type="submit" class="btn btn-success" name="actualizar">Actualizar</button>
                      <button type="button" class="btn btn-secondary"
                      data-bs-dismiss="modal">Cancelar</button>
                    </div>
                  </div>
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
    $('.p1').trigger('reset');
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