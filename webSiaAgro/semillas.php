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
  <title>Listado de semillas</title>
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script>
  <link rel="stylesheet"type="text/css" href="css_personalizado/estilo_semillas.css"> 
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
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 900px;">

          <div class="modal-content">
            <div class="modal-header" style="background-color: #0d6efd; color: white;">
              <h5 class="modal-title" id="registrarActividadModalLabel" style="margin-left:37%;">Registro Información</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

              <!-- Formulario para registrar la actividad -->
              <form method="POST" action="procesar/procesar_semillas.php" style="padding: 0 50px 0 50px;">
                <div class="row mb-2">
                  <label for="inputText" class="col-sm-3 col-form-label">Nombre de la semilla</label>
                  <div class="col-sm-9">
                    <!-- especifica el tamaño del campo -->
                    <input type="text" class="form-control" id="validationCustom01" required name="nombre"  required placeholder="Ej:  Cariopsis " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                  </div>
                </div>

                <div class="row mb-2">
                  <label for="inputText" class="col-sm-3 col-form-label">Tipo</label>
                  <div class="col-sm-9">
                    <!-- especifica el tamaño del campo -->
                    <input type="text" class="form-control" id="validationCustom01" required name="tipo"  required placeholder="Ej: Semicristalino" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                  </div>
                </div>

                <div class="row mb-2">
                  <label for="inputText" class="col-sm-3 col-form-label">Cantidad Adquirida</label>
                  <div class="col-sm-9">
                    <!-- especifica el tamaño del campo -->
                    <input oninput="validateAnimalNumber(this)" type="number" class="form-control" id="validationCustom01" required name="cantidad_adquirida"  required placeholder="Ej: 100" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                  </div>
                </div>

                <div class="row mb-2">
                  <label for="inputText" class="col-sm-3 col-form-label">Fecha Adquisición</label>
                  <div class="col-sm-9">
                    <!-- especifica el tamaño del campo -->
                    <input min="2020-01-01" type="date" class="form-control" id="fecha_adqusicion" required name="Fecha_adquisicion">
                  </div>
                </div>


                <div class="row mb-2">
                  <label for="inputText" class="col-sm-3 col-form-label">Fecha Vencimiento </label>
                  <div class="col-sm-9">
                    <!-- especifica el tamaño del campo -->
                    <input min="2020-01-01" type="date" class="form-control" id="fecha_vencimiento" required name="Fecha_vencimiento">
                  </div>
                </div>

                <div class="row mb-2">
                  <label for="inputText" class="col-sm-3 col-form-label">Precio Unitario (Bs)</label>
                  <div class="col-sm-9">
                    <!-- especifica el tamaño del campo -->
                    <input oninput="validateprecio(this)" type="number" class="form-control" id="validationCustom01" required name="precio_unitario"  required placeholder="Ej: 100" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                  </div>
                </div>

                <div class="row mb-2">
                  <label for="inputText" class="col-sm-3 col-form-label">Código</label>
                  <div class="col-sm-9">
                    <!-- especifica el tamaño del campo -->
                    <input  oninput="validatecodigo(this)" type="number" class="form-control" id="validationCustom01" required name="codigo"  required placeholder="Ej: " oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                  </div>
                </div>
                <br>


                <script>

document.addEventListener("DOMContentLoaded", function () {
        const fechaSiembraInput = document.getElementById("fecha_adqusicion");
        const fechaCosechaInput = document.getElementById("fecha_vencimiento");
        
        // Validación de la fecha de cosecha
        fechaCosechaInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha <= fechaSiembra) {
                alert("La fecha de vencimiento del producto  no puede ser menor o igual a la fecha de compra, verifique la fecha.");
                fechaCosechaInput.value = "";  // Limpiar campo de fecha de cosecha si es inválido
                fechaCosechaInput.focus();     // Focalizar nuevamente en el campo de fecha de cosecha
            }
        });

        // Validación de la fecha de siembra (en caso de que se ingrese una fecha posterior a la cosecha)
        fechaSiembraInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha && fechaSiembra > fechaCosecha) {
                alert("La fecha de Compra del producto no puede ser posterior a la fecha de vencimiento, verifique la fecha de compra.");
                fechaSiembraInput.value = "";  // Limpiar campo de fecha de siembra si es inválido
                fechaSiembraInput.focus();     // Focalizar nuevamente en el campo de fecha de siembra
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
    function validatecodigo(input) {
        let maxLength = 7;

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

                <div class="row mb-2" style="padding-left: 15%;">
                  <div class="col-sm-10" style="text-align: center">
                    <!-- Este enlace se utiliza para vaciar el formulario -->
                    <input type="hidden" name="session_acceso" value="<?php echo $_SESSION['Usuario'] ?>">
                    <input type="hidden" name="session_id" value="<?php echo $_SESSION['Id_Usuario'] ?>">
                    <a class="btn btn-secondary" style="width: 100px;" onclick="vaciarCampos()">Vaciar</a>
                    <!-- Este botón se utiliza para enviar el formulario -->
                    <input type="submit" class="btn btn-primary" value="Guardar"
                    style="width: 100px; background-color: green; color: white;">
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
              <li class="breadcrumb-item">Cultivo</li>
              <a href="insumos.php" class="breadcrumb-item">Insumos</a>
              <li class="breadcrumb-item active">Semillas</li>
            </ol>
          </nav>

          <div class="container">
            <div class="row justify-content-center">

              <div class="col-lg-3"> 
                <div class="card" style="background-color:  #99ff99; color: white; height: 132px">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="icon">
                        <i class="fas fa-chart-bar fa-3x"></i>
                      </div>
                      <div class="content ml-3 text-right">
                        <h5 class="card-title">Agroquímicos</h5>
                        <a style="width: 150px;" href="funguisidas.php" class="btn btn-primary mt-2 float-right">Ver más</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="card" style="background-color: #80F2F4; color: white;">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="icon">
                        <i class="fas fa-chart-pie fa-3x"></i>
                      </div>
                      <div class="content ml-3 text-right">
                        <h5 class="card-title">Equipos</h5>
                        <a style="width: 150px;" href="equipos.php" class="btn btn-primary mt-2 float-right">Ver más</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="card" style="background-color: #F4AA46; color: white; border:1px solid black; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="icon">
                        <i class="fas fa-chart-pie fa-3x"></i>
                      </div>
                      <div class="content ml-3 text-right">
                        <h5 class="card-title">Semillas</h5>
                        <button  type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"
                        style="width: 200px;" title="Agregar"><i class="ri-add-fill"
                        style="color:white;"></i>Agregar &nbsp
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-3">
              <div class="card" style="background-color: #6584F3; color: white; height: 125px ;">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div class="icon">
                      <i class="fas fa-chart-pie fa-3x"></i>
                    </div>
                    <div class="content ml-3 text-right">
                      <h5 class="card-title">Fertilizante</h5>
                      <a style="width: 150px;" href="fertilizante.php" class="btn btn-primary mt-2 float-right">Ver más</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Tipo</th>
                <th scope="col">Código</th>
                <th scope="col">cantidad Adquirida</th>
                <th scope="col">Precio Unitario Bs</th>
                <th scope="col">Fecha Adquisición</th>
                <th scope="col">Fecha Vencimiento</th>
                <th scope="col">Fecha/hora Registro</th>
                <th scope="col">Acción</th>
              </tr>
            </thead>
            <tbody>
            <?php
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();

$sql = "SELECT * FROM semillas WHERE activo = TRUE ORDER BY id_semillas ASC";


$result = $conn->query($sql);

if ($result->rowCount() > 0) {
    $contador = 1;
    while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                  <tr>
                    <td>
                      <?php echo $fila['nombre']; ?>
                    </td>
                    <td>
                      <?php echo $fila['tipo']; ?>
                    </td>
                    <td>
                      <?php echo $fila['codigo']; ?>
                    </td>
                    <td>
                      <?php echo $fila['cantidad_adquirida']; ?>
                    </td>
                    <td>
                      <?php echo $fila['precio_unitario']; ?>
                    </td>
                    <td>
                      <?php echo $fila['fecha_adquisicion']; ?>
                    </td>
                    <td>
                      <?php echo $fila['fecha_vencimiento']; ?>
                    </td>
                    <td>
                      <?php echo $fila['fecha']; ?>
                    </td>
                    <td>
                      <div class="btn-group" role="group">
                        <!-- ---- ↑↑ CODIGO A COPIAR ↑↑ ---- -->

                        <div class="btn-group" role="group">

                          <?php if($ver == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                          <!-- Boton-modal [ver] -->
                          <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                          type="button" data-bs-toggle="modal" data-bs-target='#basicModal-VER<?php echo $fila["id_semillas"]; ?>'
                          title="Editar">
                          <i class="ri-eye-fill" style="color:#17E45B"></i>
                        </a>
                        <?php } ?>  <!-- ← CODIGO A COPIAR -->


                        <?php if($editar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                        <!-- Boton-modal [Editar] -->
                        <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                        type="button" data-bs-toggle="modal" data-bs-target='#basicModal-<?php echo $fila["id_semillas"]; ?>'
                        title="Editar">
                        <i class="ri-ball-pen-fill" style="color:#E5D001;"></i>
                      </a>
                      <?php } ?>  <!-- ← CODIGO A COPIAR -->


                      <?php if($eliminar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                      <!-- Boton-modal [eliminar] -->
                      <a type="button" data-bs-toggle="modal" data-bs-target="#smallModal-<?php echo $fila["id_semillas"]; ?>"
                        style="color:none;  margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                        title="Eliminar">
                        <i class="ri-delete-bin-2-line" style="color:#EE0D0D;"></i>
                      </a>
                    <?php } ?>



                    <!-- modal [eliminar] -->
                    <div class="modal fade" id="smallModal-<?php echo $fila["id_semillas"]; ?>" tabindex="-1">
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
                          href='deshabilitaciones/deshabilitar_semillas.php?id=<?php echo $fila["id_semillas"] ?>&session_acceso=<?php echo isset($_SESSION["Usuario"]) ? $_SESSION["Usuario"] : ""; ?>&session_id=<?php echo isset($_SESSION["Id_Usuario"]) ? $_SESSION["Id_Usuario"] : ""; ?>'

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
                <div class="modal fade" id="basicModal-VER<?php echo $fila["id_semillas"]; ?>" tabindex="-1">

                  <!-- Este div define la estructura y el tamaño de la ventana modal -->
                  <div class="modal-dialog modal-lg" style="max-width: 900px;">

                    <!-- Este div contiene el contenido de la ventana modal -->
                    <div class="modal-content">
                      <!-- Este div define el encabezado de la ventana modal y contiene el título "Editar Usuario" y un botón para cerrar la ventana -->
                      <div class="modal-header" style="background-color: #0d6efd; color: white;">
                        <h5 class="modal-title" id="registrarActividadModalLabel" style="margin-left:37%;">Ver información</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>

                      <!-- Este div contiene el cuerpo de la ventana modal -->
                      <div class="modal-body">

                        <!-- Este formulario se utiliza para enviar los datos del formulario a la página "editar.php" utilizando el método "POST" -->
                        <form method="POST" action="actualizar/actualizar_semillas.php">
                          <div>
                            <input style="pointer-events: none;" type="hidden" class="form-control" name="Id_semillas" value='<?php echo $fila["id_semillas"]; ?>'>
                          </div>
                          <div class="row mb-2">
                            <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre de la Semilla</label>
                            <div class="col-sm-9">
                              <input style="pointer-events: none;" type="text" class="form-control" name="nombre"  required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" value='<?php echo $fila["nombre"]; ?>'>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de semilla </label>
                            <div class="col-sm-9">
                              <input style="pointer-events: none;" type="text" class="form-control" name="tipo"  required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" value='<?php echo $fila["tipo"]; ?>'>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <label class="col-sm-3 col-form-label" style="color:#21618C;">Cantidad de unidades</label>
                            <div class="col-sm-9">
                              <input style="pointer-events: none;" type="text" class="form-control" name="cantidad_adquirida"  
                              value='<?php echo $fila["cantidad_adquirida"]; ?>'readonly>
                              <!-- <small class="text-muted">Este campo no se puede editar</small> -->
                            </div>
                          </div>
                          <div class="row mb-2">
                            <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha de Adquisición</label>
                            <div class="col-sm-9">
                              <input style="pointer-events: none;" type="date" class="form-control" name="Fecha_adquisicion" value='<?php echo $fila["fecha_adquisicion"]; ?>'>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha de vencimiento</label>
                            <div class="col-sm-9">
                              <input style="pointer-events: none;" type="date" class="form-control" name="Fecha_vencimiento" value='<?php echo $fila["fecha_vencimiento"]; ?>'>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <label class="col-sm-3 col-form-label" style="color:#21618C;">Código</label>
                            <div class="col-sm-9">
                              <input style="pointer-events: none;" type="text" class="form-control" name="codigo"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value='<?php echo $fila["codigo"]; ?>'>
                            </div>
                          </div>

                          <div class="row mb-2">
                            <label class="col-sm-3 col-form-label" style="color:#21618C;">precio unitario Bs</label>
                            <div class="col-sm-9">
                              <input style="pointer-events: none;" type="text" class="form-control" name="precio_unitario" 
                              value='<?php echo $fila["precio_unitario"]; ?>'readonly>
                              <!-- <small class="text-muted">Este campo no se puede editar</small> -->
                            </div>
                          </div>
                          <div class="modal-footer">
                            <input style="pointer-events: none;" type="hidden" name="session_acceso" value="<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>">
                            <input style="pointer-events: none;" type="hidden" name="session_id" value="<?php echo isset($_SESSION['Id_Usuario']) ? $_SESSION['Id_Usuario'] : ''; ?>">


                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <!------- modal de actualizar -------->
              <div class="modal fade" id="basicModal-<?php echo $fila["id_semillas"]; ?>" tabindex="-1">

                <!-- Este div define la estructura y el tamaño de la ventana modal -->
                <div class="modal-dialog modal-lg" style="max-width: 900px;">

                  <!-- Este div contiene el contenido de la ventana modal -->
                  <div class="modal-content">
                    <!-- Este div define el encabezado de la ventana modal y contiene el título "Editar Usuario" y un botón para cerrar la ventana -->
                    <div class="modal-header" style="background-color: #0d6efd; color: white;">
                      <h5 class="modal-title" id="registrarActividadModalLabel" style="margin-left: 37%;">Actualizar información</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Este div contiene el cuerpo de la ventana modal -->
                    <div class="modal-body">

                      <!-- Este formulario se utiliza para enviar los datos del formulario a la página "editar.php" utilizando el método "POST" -->
                      <form method="POST" action="actualizar/actualizar_semillas.php">
                        <div>
                          <input type="hidden" class="form-control" name="Id_semillas" value='<?php echo $fila["id_semillas"]; ?>'>
                        </div>
                        <div class="row mb-2">
                          <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre de la Semilla</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="nombre"  required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" value='<?php echo $fila["nombre"]; ?>'>
                          </div>
                        </div>
                        <div class="row mb-2">
                          <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de semilla </label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="tipo"  required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" value='<?php echo $fila["tipo"]; ?>'>
                          </div>
                        </div>
                        <div class="row mb-2">
                          <label class="col-sm-3 col-form-label" style="color:#21618C;">Cantidad de unidades</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="cantidad_adquirida"  
                            value='<?php echo $fila["cantidad_adquirida"]; ?>'readonly>
                            <small class="text-muted">Este campo no se puede editar</small>
                          </div>
                        </div>
                        <div class="row mb-2">
                          <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha de Adquisición</label>
                          <div class="col-sm-9">
                            <input id="fechaadqusicion_act" min="2020-01-01" type="date" class="form-control" name="Fecha_adquisicion" value='<?php echo $fila["fecha_adquisicion"]; ?>'>
                          </div>
                        </div>
                        <div class="row mb-2">
                          <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha de vencimiento</label>
                          <div class="col-sm-9">
                            <input id="fechavencimiento_act" min="2020-01-01" type="date" class="form-control" name="Fecha_vencimiento" value='<?php echo $fila["fecha_vencimiento"]; ?>'>
                          </div>
                        </div>
                        <div class="row mb-2">
                          <label class="col-sm-3 col-form-label" style="color:#21618C;">Código</label>
                          <div class="col-sm-9">
                            <input oninput="validatecodigo(this)" type="text" class="form-control" name="codigo"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value='<?php echo $fila["codigo"]; ?>'>
                          </div>
                        </div>

                        <div class="row mb-2">
                          <label class="col-sm-3 col-form-label" style="color:#21618C;">precio unitario </label>
                          <div class="col-sm-9">
                            <input  type="text" class="form-control" name="precio_unitario" 
                            value='<?php echo $fila["precio_unitario"]; ?>'readonly>
                            <small class="text-muted">Este campo no se puede editar</small>
                          </div>
                        </div>
<script>
  
document.addEventListener("DOMContentLoaded", function () {
        const fechaSiembraInput = document.getElementById("fechaadqusicion_act");
        const fechaCosechaInput = document.getElementById("fechavencimiento_act");
        
        // Validación de la fecha de cosecha
        fechaCosechaInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha <= fechaSiembra) {
                alert("La fecha de vencimiento del producto  no puede ser menor o igual a la fecha de compra, verifique la fecha.");
                fechaCosechaInput.value = "";  // Limpiar campo de fecha de cosecha si es inválido
                fechaCosechaInput.focus();     // Focalizar nuevamente en el campo de fecha de cosecha
            }
        });

        // Validación de la fecha de siembra (en caso de que se ingrese una fecha posterior a la cosecha)
        fechaSiembraInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha && fechaSiembra > fechaCosecha) {
                alert("La fecha de Compra del producto no puede ser posterior a la fecha de vencimiento, verifique la fecha de compra.");
                fechaSiembraInput.value = "";  // Limpiar campo de fecha de siembra si es inválido
                fechaSiembraInput.focus();     // Focalizar nuevamente en el campo de fecha de siembra
            }
        });
    });    
</script>

                        <div class="modal-footer">
                          <input type="hidden" name="session_acceso" value="<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>">
                          <input type="hidden" name="session_id" value="<?php echo isset($_SESSION['Id_Usuario']) ? $_SESSION['Id_Usuario'] : ''; ?>">

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
                          $contador++; // Incrementar el contador en cada iteración
                        }
                      }

                  // Cerrar la conexión
                      ?>
                    </tbody>
                  </table>



                  <!-- End Table with stripped rows -->
                </section>
              </main><!-- End #main -->

              <!-- ======= Footer ======= -->
              <?php include_once("footer.php"); ?>

            </body>

            </html>

            <script>
              function vaciarCampos() {
                document.getElementsByName("nombre")[0].value = "";
                document.getElementsByName("tipo")[0].value = "";
                document.getElementsByName("cantidad_adquirida")[0].value = "";
                document.getElementsByName("Fecha_adquisicion")[0].value = "";
                document.getElementsByName("Fecha_vencimiento")[0].value = "";
                document.getElementsByName("precio_unitario")[0].value = "";
                document.getElementsByName("codigo")[0].value = "";
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