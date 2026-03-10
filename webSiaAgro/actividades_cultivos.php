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
  <title> Acyividades de los Cultivos</title>
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script> 
  <link rel="stylesheet"type="text/css" href="css_personalizado/estilo_actividades_cultivos.css">
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
        <div class="modal-dialog modal-lg" style="max-width: 950px; padding: 20px;">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #0d6efd; color: white;">
              <h5 class="modal-title text-center w-100">Registro de Actividad</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="procesar/procesar_actividades_cultivos.php" id="dateForm" style="padding: 22px;">
              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Nombre de la actividad</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="nombre_actividad" name="nombre_actividad" required placeholder="Ej: Desmalezar"
                   oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" >
                </div>
              </div>

              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Detalles de la actividad</label>
                <div class="col-sm-9">
                  <input type="text" placeholder="Ej: Nueva Plaga" class="form-control" id="detalle_actividad" name="detalle_actividad" required >
                </div>
              </div>

              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Fecha de inicio</label>
                <div class="col-sm-9">
                  <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                </div>
              </div>

              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Fecha Final</label>
                <div class="col-sm-9">
                  <input type="date" class="form-control" id="fecha_final" name="fecha_final" required>
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
                alert("La fecha final de la actividad no puede ser menor o igual a la fecha de inicio.");
                fechaCosechaInput.value = "";  // Limpiar campo de fecha de cosecha si es inválido
                fechaCosechaInput.focus();     // Focalizar nuevamente en el campo de fecha de cosecha
            }
        });

        // Validación de la fecha de siembra (en caso de que se ingrese una fecha posterior a la cosecha)
        fechaSiembraInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha && fechaSiembra > fechaCosecha) {
                alert("La fecha de inicio de la actividad no puede ser posterior a la final.");
                fechaSiembraInput.value = "";  // Limpiar campo de fecha de siembra si es inválido
                fechaSiembraInput.focus();     // Focalizar nuevamente en el campo de fecha de siembra
            }
        });
    });
</script>
              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Espacio utilizado</label>
                <div class="col-sm-9">
                  <select class="form-select" id="espacio_usado" name="espacio_usado" required>
                    <option value="">Seleccione un espacio</option>
                    <?php
        include_once("conexion/conexion.php");
        $conn = cconexion::ConexionBD();
        try {
            $tabla = "SELECT * FROM espacios";
            $stmt = $conn->prepare($tabla);
            $stmt->execute();
            while ($valores = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="'. $valores['nombre_espacio'] . '">' . $valores['nombre_espacio'] . '</option>';
            }
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
        ?>
                  </select>
                </div>
              </div>

              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Tipo de cultivo</label>
                <div class="col-sm-9">
                  <select class="form-select" id="tipo_cultivo" name="tipo_cultivo" required>
                    <option value="">Seleccione un Cultivo</option>
                    <?php
        include_once("conexion/conexion.php");
        $conn = cconexion::ConexionBD();
        try {
            $tabla = "SELECT * FROM cultivos";
            $stmt = $conn->prepare($tabla);
            $stmt->execute();
            while ($valores = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="'. $valores['nombre'] . '">' . $valores['nombre'] . '</option>';
            }
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
        ?>
                 </select>
               </div>
             </div>

             <div class="row mb-2">
              <label for="inputText" class="col-sm-3 col-form-label">Responsable</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="responsable" placeholder="Ej: Diego" name="responsable" required  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" >
              </div>
            </div>
            <div class="row mb-2">
    <label for="cantidad_trabajadores" class="col-sm-3 col-form-label">Cantidad trabajadores</label>
    <div class="col-sm-9">
        <input oninput="validateAnimalNumber(this)" placeholder="Ej: 15" type="number" class="form-control" id="cantidad_trabajadores" name="cantidad_trabajadores" required>
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
            <div class="modal-footer">
              <input type="hidden" name="session_acceso" value="<?php echo $_SESSION['Usuario'] ?>">
              <input type="hidden" name="session_id" value="<?php echo $_SESSION['Id_Usuario'] ?>">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-success">Guardar</button>
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
            <li class="breadcrumb-item">Cultivo</li>
            <li class="breadcrumb-item">Seguimiento</li>
            <li class="breadcrumb-item active">Actividades</li>
          </ol>
        </nav>
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <p style="position: absolute; right:165px; top:130px;"> Buscar... </p>
                <h5 class="card-title" style="color:black; font-size:40px; margin-left:7%;">Actividades
                Realizadas</h5>
                <button  type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#largeModal"
                style="margin-right:82.5%; margin-top:10px; margin-bottom:8px;" title="Agregar"><i class="ri-add-fill"
                style="color:white;"></i>Agregar &nbsp</button>
                <table class="table datatable">
                  <thead>
                    <tr>

                      <th scope="col">Nombre de la actividad</th>
                      <th scope="col">Detalles</th>
                      <th scope="col">Fecha de inicio</th>
                      <th scope="col">Fecha final</th>
                      <th scope="col">Espacio usado</th>
                      <th scope="col">Tipo de cultivo</th>
                      <th scope="col">Elaborada</th>
                      <th scope="col">Cantidad de trabajadores</th>
                      <th scope="col">Fecha de registro</th>
                      <th scope="col">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();

$sql = "SELECT * FROM actividades where activo= true ORDER BY \"Id_actividades\"";
$result = $conn->query($sql);

if ($result->rowCount() > 0) {
    $contador = 1;
    while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>

                          <td>
                            <?php echo $fila['nombre_actividad']; ?>
                          </td>
                          <td>
                            <?php echo $fila['detalle_actividad']; ?>
                          </td>
                         
                          <td>
                            <?php echo $fila['fechainicio']; ?>
                          </td>
                          <td>
                            <?php echo $fila['fechafinal']; ?>
                          </td>
                          <td>
                            <?php echo $fila['espacio_usado']; ?>
                          </td>
                          <td>
                            <?php echo $fila['tipo_cultivo']; ?>
                          </td>
                          <td>
                            <?php echo $fila['elaborada']; ?>
                          </td>
                          <td>
                            <?php echo $fila['cantidad_trabajadores']; ?>
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
                                type="button" data-bs-toggle="modal" data-bs-target='#basicModal-VER<?php echo $fila["Id_actividades"]; ?>'
                                title="Editar">
                                <i class="ri-eye-fill" style="color:#17E45B"></i>
                              </a>
                              <?php } ?>  <!-- ← CODIGO A COPIAR -->


                              <?php if($editar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                              <!-- Boton-modal [Editar] -->
                              <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                              type="button" data-bs-toggle="modal" data-bs-target='#basicModal-<?php echo $fila["Id_actividades"]; ?>'
                              title="Editar">
                              <i class="ri-ball-pen-fill" style="color:#E5D001;"></i>
                            </a>
                            <?php } ?>  <!-- ← CODIGO A COPIAR -->


                            <?php if($eliminar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                            <!-- Boton-modal [eliminar] -->
                            <a type="button" data-bs-toggle="modal" data-bs-target="#smallModal-<?php echo $fila["Id_actividades"]; ?>"
                              style="color:none;  margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                              title="Eliminar">
                              <i class="ri-delete-bin-2-line" style="color:#EE0D0D;"></i>
                            </a>
                          <?php } ?>

                          <!-- modal [eliminar] -->
                          <div class="modal fade" id="smallModal-<?php echo $fila["Id_actividades"]; ?>" tabindex="-1">
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
                                href='deshabilitaciones/deshabilitar_actividades_cultivos.php?id=<?php echo $fila["Id_actividades"] ?>&session_acceso=<?php echo isset($_SESSION["Usuario"]) ? $_SESSION["Usuario"] : ""; ?>&session_id=<?php echo isset($_SESSION["Id_Usuario"]) ? $_SESSION["Id_Usuario"] : ""; ?>'
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
                      <div class="modal fade" id="basicModal-VER<?php echo $fila["Id_actividades"]; ?>" tabindex="-1">
                        <!-- Este div define la estructura y el tamaño de la ventana modal -->
                        <div class="modal-dialog modal-lg" style="max-width: 950px;">
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
                              <form method="POST" id='actualizar' action="actualizar/actualizar_actividades_cultivos.php">
                                <div>
                                  <input style="pointer-events: none;" type="hidden" class="form-control" name="id_actividades"
                                  value='<?php echo $fila["Id_actividades"]; ?>'>
                                </div>
                                <div class="row mb-3">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre de la
                                  actividad</label>
                                  <div class="col-sm-8">
                                    <input style="pointer-events: none;" type="text" class="form-control" name="nombre_actividad"  required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                    value='<?php echo $fila["nombre_actividad"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-3">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Encargado de la
                                  actividad</label>
                                  <div class="col-sm-8">
                                    <input style="pointer-events: none;" type="text" class="form-control" name="elabora"  required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                    value='<?php echo $fila["elaborada"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-3">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Detalle </label>
                                  <div class="col-sm-8">
                                    <input style="pointer-events: none;" type="text" class="form-control" name="detalle_actividad"  required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                    value='<?php echo $fila["detalle_actividad"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-3">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Espacio usado</label>
                                  <div class="col-sm-8">
                                    <input style="pointer-events: none;" type="text" class="form-control" id="espacio_usado" name="espacio_usado"   required placeholder=" "
                                oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                value='<?php echo $fila["espacio_usado"]; ?>'>
                                  </div>
                                </div>

                                <div class="row mb-3">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha de
                                  inicio</label>
                                  <div class="col-sm-8">
                                    <input style="pointer-events: none;" type="date" class="form-control fechaI" name="fechainicio"
                                    value='<?php echo $fila["fechainicio"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-3">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha final</label>
                                  <div class="col-sm-8">
                                    <input style="pointer-events: none;" type="date" class="form-control fechaF" name="fechafinal"
                                    value='<?php echo $fila["fechafinal"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-3">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de
                                  cultivo</label>
                                  <div class="col-sm-8">
                                  <input style="pointer-events: none;" type="text" class="form-control" id="tipo_cultivo" name="tipo_cultivo"   required placeholder=" "
                                oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                value='<?php echo $fila["tipo_cultivo"]; ?>'>
                                  </div>
                                </div>

                                <div class="row mb-3">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Cantidad de
                                  trabajadores</label>
                                  <div class="col-sm-8">
                                    <input style="pointer-events: none;" type="number" class="form-control" name="cantidad_trabajadores"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    value='<?php echo $fila["cantidad_trabajadores"]; ?>'>
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
                    </div>



                    <!------- modal de actualizar -------->
                    <div class="modal fade" id="basicModal-<?php echo $fila["Id_actividades"]; ?>" tabindex="-1">
                      <!-- Este div define la estructura y el tamaño de la ventana modal -->
                      <div class="modal-dialog modal-lg" style="max-width: 950px;">
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
                            <form method="POST" action="actualizar/actualizar_actividades_cultivos.php">
                              <div>
                                <input type="hidden" class="form-control" name="id_actividades"
                                value='<?php echo $fila["Id_actividades"]; ?>'>
                              </div>
                              <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre de la
                                actividad</label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control" name="nombre_actividad"  required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                  value='<?php echo $fila["nombre_actividad"]; ?>'>
                                </div>
                              </div>
                              <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Encargado de la
                                actividad</label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control" name="elabora"  required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                  value='<?php echo $fila["elaborada"]; ?>'>
                                </div>
                              </div>
                              <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Detalle </label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control" name="detalle_actividad"  required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                  value='<?php echo $fila["detalle_actividad"]; ?>'>
                                </div>
                              </div>
                              <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Espacio usado</label>
                                <div class="col-sm-8">
                                <select class="form-select" id="espacio_usado" name="espacio_usado" required>
                                  <option value="<?php echo $fila['espacio_usado']; ?>">
                                <?php echo $fila['espacio_usado']; ?>
                                </option>
                                    <?php
                                        try {
                // Crear una conexión PDO
                include("../conexion/conexion.php");
                $conn = cconexion::ConexionBD();
                // Consulta para obtener los espacios
                $query = "SELECT nombre_espacio FROM espacios";
                $stmt = $conn->prepare($query);
                $stmt->execute();

                // Obtener los resultados y mostrarlos como opciones
                while ($valores = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($valores['nombre_espacio'] != $fila['espacio_usado']) {
                        echo '<option value="' . $valores['nombre_espacio'] . '">' . $valores['nombre_espacio'] . '</option>';
                    }
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>
        </select>
                                </div>
                              </div>
                              <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha de
                                inicio</label>
                                <div class="col-sm-8">
                                  <input type="date" class="form-control" id="fechainicio_act" name="fechainicio"
                                  value='<?php echo $fila["fechainicio"]; ?>'>
                                </div>
                              </div>
                              <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha final</label>
                                <div class="col-sm-8">
                                  <input type="date" class="form-control" id="fechafinal_act" name="fechafinal"
                                  value='<?php echo $fila["fechafinal"]; ?>'>
                                </div>
                              </div>

                              <script>
    document.addEventListener("DOMContentLoaded", function () {
        const fechaSiembraInput = document.getElementById("fechainicio_act");
        const fechaCosechaInput = document.getElementById("fechafinal_act");
        
        // Validación de la fecha de cosecha
        fechaCosechaInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha <= fechaSiembra) {
                alert("La fecha final de la actividad no puede ser menor o igual a la fecha de inicio.");
                fechaCosechaInput.value = "";  // Limpiar campo de fecha de cosecha si es inválido
                fechaCosechaInput.focus();     // Focalizar nuevamente en el campo de fecha de cosecha
            }
        });

        // Validación de la fecha de siembra (en caso de que se ingrese una fecha posterior a la cosecha)
        fechaSiembraInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha && fechaSiembra > fechaCosecha) {
                alert("La fecha de inicio de la actividad no puede ser posterior a la final.");
                fechaSiembraInput.value = "";  // Limpiar campo de fecha de siembra si es inválido
                fechaSiembraInput.focus();     // Focalizar nuevamente en el campo de fecha de siembra
            }
        });
    });
</script>

                              <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de
                                cultivo</label>
                                <div class="col-sm-8">
                                <select class="form-select" id="tipo_cultivo" name="tipo_cultivo" required>
                                    <option value="<?php echo $fila['tipo_cultivo']; ?>">
                                <?php echo $fila['tipo_cultivo']; ?>
                                </option>
                                <?php
                                        try {
                // Crear una conexión PDO
                include("../conexion/conexion.php");
                $conn = cconexion::ConexionBD();
                // Consulta para obtener los espacios
                $query = "SELECT nombre FROM cultivos";
                $stmt = $conn->prepare($query);
                $stmt->execute();

                // Obtener los resultados y mostrarlos como opciones
                while ($valores = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($valores['nombre'] != $fila['tipo_cultivo']) {
                        echo '<option value="' . $valores['nombre'] . '">' . $valores['nombre'] . '</option>';
                    }
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>


                                    </select>
                                </div>
                              </div>
                              <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Cantidad de
                                trabajadores</label>
                                <div class="col-sm-8">
                                  <input oninput="validateAnimalNumber(this)" type="number" class="form-control" name="cantidad_trabajadores"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                  value='<?php echo $fila["cantidad_trabajadores"]; ?>'>
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

                  // Cerrar la conexión
                
                  ?>
                </tbody>
              </table>

              <style>
                .datatable-info {
                  color: whitesmoke;
                  font-size: 0;
                }

                .col-lg-12 {
                  margin-bottom: 5%;
                }

                .datatable-input::placeholder {
                  visibility: hidden;
                  content: "Referenciar";
                }

                .datatable-dropdown::after {
                  content: "Entradas por página";
                  position: absolute;
                  left: 90px;
                  top: 210px;
                }

                .datatable-dropdown>label {
                  visibility: hidden;
                }

                .datatable-selector {
                  visibility: visible;
                }
                /*    CODIGO DE TRADUCCION   */
                .datatable-info {
                  color: whitesmoke;
                  font-size: 0;
                }
                .col-lg-12 {
                  margin-bottom: 5%;
                }
                .datatable-input::placeholder {
                 visibility: hidden;
                 content: "Referenciar";
               }
               .datatable-dropdown::after {
                content: "Entradas por página";
                position: absolute;
                left: 90px;
                top: 162px;
              }
              .datatable-dropdown>label {
                visibility: hidden;
              }

              .datatable-selector {
                visibility: visible;
              }
              /*---------------------------*/
            </style>
            <!-- <span style="display:inline-block; position:relative; top:-20px;">N° de registros
              <?php echo $contador - 1; ?>
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

