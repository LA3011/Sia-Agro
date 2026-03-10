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
  <title>Listado de conttrol de plagas </title>
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script> 
  <link rel="stylesheet"type="text/css" href="css_personalizado/estilo_control_plagas.css">
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
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 900px;">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #0d6efd; color: white;">
              <h5 class="modal-title" id="registrarActividadModalLabel" style="margin-left:35%;">Registrar Información</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <!-- Formulario para registrar la actividad -->
              <form id="dateForm" method="POST" action="procesar/procesar_control_plagas.php" style="padding: 0 50px 0 50px;">
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-3 col-form-label">Parcela</label>
                  <div class="col-sm-9">
                  <select class="form-select" id="validationCustom01" required name="Parcela" required>
                      <option value="">Seleccione una opción</option>
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
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-3 col-form-label">Dosis (ml)</label>
                  <div class="col-sm-9">
                    <!-- especifica el tamaño del campo -->
                    <input oninput="validateAnimalNumber(this)"  type="number" class="form-control" id="" required name="Dosis"  required placeholder="Ej: 150" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-3 col-form-label">Cantidad</label>
                  <div class="col-sm-9">
                    <!-- especifica el tamaño del campo -->
                    <input oninput="validateAnimalNumber(this)" type="number" class="form-control" id="validationCustom01" required name="Cantidad"  required placeholder="Ej: 150" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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

                <div class="row mb-3">
                  <label for="inputText" class="col-sm-3 col-form-label">Cultivo</label>
                  <div class="col-sm-9">
                  <select class="form-select" id="validationCustom01" name="Tipo_cultivo" required>
                      <option value="">Seleccione una opción</option>
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
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-3 col-form-label">Fitosanitario</label>
                  <div class="col-sm-9">
                    <select class="form-select" id="validationCustom01" name="Fitosanitario" required>
                      <option value="">Seleccione una opción</option>

                      <?php
        include_once("conexion/conexion.php");
        $conn = cconexion::ConexionBD();
        try {
            $tabla = "SELECT * FROM insumos_funguisidas";
            $stmt = $conn->prepare($tabla);
            $stmt->execute();
            while ($valores = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="'. $valores['nombre_funguisida'] . '">' . $valores['nombre_funguisida'] . '</option>';
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
                <div class="row mb-3">
                  <label for="tipo_cultivo" class="col-sm-3 col-form-label">Nivel/plaga</label>
                  <div class="col-sm-9">
                    <select class="form-select" id="validationCustom01" name="Nivel_plaga" required>
                      <option value="">Seleccione una opción</option>
                      <option value="Alta">Alta</option>
                      <option value="Media">Media</option>
                      <option value="Baja">Baja</option>
                    </select>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-3 col-form-label">Fecha Inicial</label>
                  <div class="col-sm-9">
                    <!-- especifica el tamaño del campo -->
                    <input type="date" class="form-control" id="fecha_inicio" required name="Fecha_inicial">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputnumber" class="col-sm-3 col-form-label">Fecha Final</label>
                  <div class="col-sm-9">
                    <!-- especifica el tamaño del campo -->
                    <input min="2020-01-01" type="date" class="form-control" id="fecha_final" required name="Fecha_final">
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
                alert("La fecha final del control no puede ser menor o igual a la fecha de inicio.");
                fechaCosechaInput.value = "";  // Limpiar campo de fecha de cosecha si es inválido
                fechaCosechaInput.focus();     // Focalizar nuevamente en el campo de fecha de cosecha
            }
        });

        // Validación de la fecha de siembra (en caso de que se ingrese una fecha posterior a la cosecha)
        fechaSiembraInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha && fechaSiembra > fechaCosecha) {
                alert("La fecha de inicio del control no puede ser posterior a la final.");
                fechaSiembraInput.value = "";  // Limpiar campo de fecha de siembra si es inválido
                fechaSiembraInput.focus();     // Focalizar nuevamente en el campo de fecha de siembra
            }
        });
    });
</script>


                <div class="row mb-3">
                  <label for="inputnumber" class="col-sm-3 col-form-label">Encargado</label>
                  <div class="col-sm-9">
                    <!-- especifica el tamaño del campo -->
                    <input min="2020-01-01" type="text" class="form-control" id="validationCustom01" required name="Encargado"  required placeholder="Ej: Diego" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputnumber" class="col-sm-3 col-form-label">Maquinaría</label>
                  <div class="col-sm-9">
                    <!-- especifica el tamaño del campo -->
                    <input type="text" class="form-control" id="validationCustom01"  required name="Maquinaria"  required placeholder="Ej: Bombas de Pistón" >
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputnumber" class="col-sm-3 col-form-label">Nota</label>
                  <div class="col-sm-9">
                    <!-- especifica el tamaño del campo -->
                    <input type="text" class="form-control" id="validationCustom01" required name="Nota"  required placeholder="Ninguna">
                  </div>
                </div>
                <div class="row mb-3" style="padding-left: 15%;">
                  <div class="col-sm-10" style="text-align: center">
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
      </div>

      <!------- tabla -------->
      <main id="main" class="main">
        <section class="section">

          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Cultivo</li>
              <li class="breadcrumb-item">General</li>
              <li class="breadcrumb-item active">Control Plagas</li>
            </ol>
          </nav>

          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <p style="position: absolute; right:165px; top:130px;"> Buscar... </p>
                  <h5 class="card-title" style="color:black; font-size:40px; margin-left:7%;">Control de Plagas</h5>
                  <button  type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal"
                  style="margin-right:82.5%; margin-top:10px; margin-bottom:8px;" title="Agregar"><i class="ri-add-fill"
                  style="color:white;"></i>Agregar &nbsp</button>

                  <table class="table datatable">
                    <thead>
                      <tr>
                        <th scope="col">Parcela</th>
                        <th scope="col">Dosis</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Tipo cultivo</th>
                        <th scope="col">Fitosanitario</th>
                        <th scope="col">Nivel/plaga</th>
                        <th scope="col">Fecha inicial</th>
                        <th scope="col">Fecha Final</th>
                        <th scope="col">Encargado</th>
                        <th scope="col">Maquinaría</th>
                        <th scope="col">Nota</th>
                        <th scope="col">Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();

$sql = "SELECT * FROM control_plagas WHERE estado = 'activo' ORDER BY \"Id_plagas\" ASC";

$result = $conn->query($sql);

if ($result->rowCount() > 0) {
    $contador = 1;
    while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                          <tr>

                            <td>
                              <?php echo $fila['Parcela']; ?>
                            </td>
                            <td>
                              <?php echo $fila['Dosis'] . " ml"; ?>
                            </td>
                            <td>
                              <?php echo $fila['Cantidad']; ?>
                            </td>
                            <td>
                              <?php echo $fila['tipo_cultivo']; ?>
                            </td>
                            <td>
                              <?php echo $fila['fitosanitario']; ?>
                            </td>
                            <td>
                              <?php echo $fila['Nivel_plaga']; ?>
                            </td>
                            <td>
                              <?php echo $fila['Fecha_inicial']; ?>
                            </td>
                            <td>
                              <?php echo $fila['Fecha_final']; ?>
                            </td>
                            <td>
                              <?php echo $fila['Encargado']; ?>
                            </td>
                            <td>
                              <?php echo $fila['maquinaria']; ?>
                            </td>
                            <td>
                              <?php echo $fila['Nota']; ?>
                            </td>
                            <td>
                              <div class="btn-group" role="group">
                                <!-- ---- ↑↑ CODIGO A COPIAR ↑↑ ---- -->
                                <div class="btn-group" role="group">

                                  <?php if($ver == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                                  <!-- Boton-modal [ver] -->
                                  <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                                  type="button" data-bs-toggle="modal" data-bs-target='#basicModal-VER<?php echo $fila["Id_plagas"]; ?>'
                                  title="Editar">
                                  <i class="ri-eye-fill" style="color:#17E45B"></i>
                                </a>
                                <?php } ?>  <!-- ← CODIGO A COPIAR -->

                                <?php if($editar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                                <!-- Boton-modal [Editar] -->
                                <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                                type="button" data-bs-toggle="modal" data-bs-target='#basicModal-<?php echo $fila["Id_plagas"]; ?>'
                                title="Editar">
                                <i class="ri-ball-pen-fill" style="color:#E5D001;"></i>
                              </a>
                              <?php } ?>  <!-- ← CODIGO A COPIAR -->

                              <?php if($eliminar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                              <!-- Boton-modal [eliminar] -->
                              <a type="button" data-bs-toggle="modal" data-bs-target="#smallModal-<?php echo $fila["Id_plagas"]; ?>"
                                style="color:none;  margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                                title="Eliminar">
                                <i class="ri-delete-bin-2-line" style="color:#EE0D0D;"></i>
                              </a>
                            <?php } ?>

                            <!-- modal [eliminar] -->
                            <div class="modal fade" id="smallModal-<?php echo $fila["Id_plagas"]; ?>" tabindex="-1">
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
                                  href='deshabilitaciones/deshabilitar_control_plagas.php?id=<?php echo $fila["Id_plagas"] ?>&session_acceso=<?php echo isset($_SESSION["Usuario"]) ? $_SESSION["Usuario"] : ""; ?>&session_id=<?php echo isset($_SESSION["Id_Usuario"]) ? $_SESSION["Id_Usuario"] : ""; ?>'
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
                        <div class="modal fade" id="basicModal-VER<?php echo $fila["Id_plagas"]; ?>" tabindex="-1">
                          <!-- Este div define la estructura y el tamaño de la ventana modal -->
                          <div class="modal-dialog modal-lg" style="max-width: 900px;">
                            <!-- Este div contiene el contenido de la ventana modal -->
                            <div class="modal-content">
                              <!-- Este div define el encabezado de la ventana modal y contiene el título "Editar Usuario" y un botón para cerrar la ventana -->
                              <div class="modal-header" style="background-color: #0d6efd; color: white;">
                                <h5 class="modal-title" id="registrarActividadModalLabel" style="margin-left: 40%;">Ver información
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                              </div>
                              <!-- Este div contiene el cuerpo de la ventana modal -->
                              <div class="modal-body">
                                <!-- Este formulario se utiliza para enviar los datos del formulario a la página "editar.php" utilizando el método "POST" -->
                                <form method="POST" action="actualizar/actualizar_control_plagas.php">
                                  <div>
                                    <input  style="pointer-events: none;"type="hidden" class="form-control" name="Id_plagas"
                                    value='<?php echo $fila["Id_plagas"]; ?>'>
                                  </div>
                                  <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Parcela</label>
                                    <div class="col-sm-9">
                                    <input style="pointer-events: none;" type="text" class="form-control" id="Parcela" name="Parcela"   required placeholder=" "
                                oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                value='<?php echo $fila["Parcela"]; ?>'>
                                    </div>
                                  </div>
                                  <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Dosis (ml)</label>
                                    <div class="col-sm-9">
                                      <input  style="pointer-events: none;" type="number" class="form-control" name="Dosis"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                      value='<?php echo $fila["Dosis"]; ?>'>
                                    </div>
                                  </div>
                                  <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Cantidad</label>
                                    <div class="col-sm-9">
                                      <input  style="pointer-events: none;" type="number" class="form-control" name="Cantidad"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                      value='<?php echo $fila["Cantidad"]; ?>'>
                                    </div>
                                  </div>
                                  <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de
                                    cultivo</label>
                                    <div class="col-sm-9">
                                    <input style="pointer-events: none;" type="text" class="form-control" id="Tipo_cultivo" name="Tipo_cultivo"   required placeholder=" "
                                oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                value='<?php echo $fila["Tipo_cultivo"]; ?>'>
                                    </div>
                                  </div>
                                  <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Fitosanitario</label>
                                    <div class="col-sm-9">

                                    <input style="pointer-events: none;" type="text" class="form-control" id="Fitosanitario" name="Fitosanitario"   required placeholder=" "
                                oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                value='<?php echo $fila["Fitosanitario"]; ?>'>


                      
                                    </div>
                                  </div>
                                  <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Nivel/plaga</label>
                                    <div class="col-sm-9">
                                      <select  style="pointer-events: none;" class="form-select" id="Nivel_plaga" name="Nivel_plaga" required>
                                        <option <?php echo $fila["Nivel_plaga"] === 'Alta' ? "selected='selected'" : "" ?>value="Alta">Alta</option>
                                        <option <?php echo $fila["Nivel_plaga"] === 'Media' ? "selected='selected'" : "" ?>value="Media">Media</option>
                                        <option <?php echo $fila["Nivel_plaga"] === 'Baja' ? "selected='selected'" : "" ?>value="Baja">Baja</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha inicial</label>
                                    <div class="col-sm-9">
                                      <input min="2020-01-01" style="pointer-events: none;" type="date" class="form-control" name="Fecha_inicial"
                                      value='<?php echo $fila["Fecha_inicial"]; ?>'>
                                    </div>
                                  </div>
                                  <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha Final</label>
                                    <div class="col-sm-9">
                                      <input  min="2020-01-01" style="pointer-events: none;" type="date" class="form-control" name="Fecha_final"
                                      value='<?php echo $fila["Fecha_final"]; ?>'>
                                    </div>
                                  </div>
                                  <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Encargado</label>
                                    <div class="col-sm-9">
                                      <input  style="pointer-events: none;" type="text" class="form-control" name="Encargado"  required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                      value='<?php echo $fila["Encargado"]; ?>'>
                                    </div>
                                  </div>
                                  <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Maquinaría</label>
                                    <div class="col-sm-9">
                                      <input  style="pointer-events: none;" type="text" class="form-control" name="Maquinaria"  required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                      value='<?php echo $fila["Maquinaria"]; ?>'>
                                    </div>
                                  </div>
                                  <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label" style="color:#21618C;">Nota</label>
                                    <div class="col-sm-9">
                                      <input  style="pointer-events: none;" type="text" class="form-control" name="Nota"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                      value='<?php echo $fila["Nota"]; ?>'>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <input  style="pointer-events: none;" type="hidden" name="session_acceso" value="<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>">
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
                      <div class="modal fade" id="basicModal-<?php echo $fila["Id_plagas"]; ?>" tabindex="-1">
                        <!-- Este div define la estructura y el tamaño de la ventana modal -->
                        <div class="modal-dialog modal-lg" style="max-width: 900px;">
                          <!-- Este div contiene el contenido de la ventana modal -->
                          <div class="modal-content">
                            <!-- Este div define el encabezado de la ventana modal y contiene el título "Editar Usuario" y un botón para cerrar la ventana -->
                            <div class="modal-header" style="background-color: #0d6efd; color: white;">
                              <h5 class="modal-title" id="registrarActividadModalLabel" style="margin-left:35%">Actualizar información
                              </h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"
                              aria-label="Close"></button>
                            </div>
                            <!-- Este div contiene el cuerpo de la ventana modal -->
                            <div class="modal-body">
                              <!-- Este formulario se utiliza para enviar los datos del formulario a la página "editar.php" utilizando el método "POST" -->
                              <form method="POST" action="actualizar/actualizar_control_plagas.php">
                                <div>
                                  <input type="hidden" class="form-control" name="Id_plagas"
                                  value='<?php echo $fila["Id_plagas"]; ?>'>
                                </div>
                                <div class="row mb-3">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Parcela</label>
                                  <div class="col-sm-9">
                                  <select class="form-select" id="Parcela" name="Parcela" required>
                                  <option value="<?php echo $fila['Parcela']; ?>">
                                <?php echo $fila['Parcela']; ?>
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
                    if ($valores['nombre_espacio'] != $fila['espacio']) {
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
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Dosis (ml)</label>
                                  <div class="col-sm-9">
                                    <input oninput="validateAnimalNumber(this)" type="number" class="form-control" name="Dosis"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    value='<?php echo $fila["Dosis"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-3">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Cantidad</label>
                                  <div class="col-sm-9">
                                    <input oninput="validateAnimalNumber(this)" type="number" class="form-control" name="Cantidad"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    value='<?php echo $fila["Cantidad"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-3">
    <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de cultivo</label>
    <div class="col-sm-9">
        <select class="form-select" id="tipo_cultivo" name="tipo_cultivo" required>
            <option value="<?php echo htmlspecialchars($fila['tipo_cultivo']); ?>">
                <?php echo htmlspecialchars($fila['tipo_cultivo']); ?>
            </option>
            <?php
            try {
                // Crear una conexión PDO
                include("../conexion/conexion.php");
                $conn = cconexion::ConexionBD();
                // Consulta para obtener los cultivos
                $query = "SELECT nombre FROM cultivos";
                $stmt = $conn->prepare($query);
                $stmt->execute();

                // Obtener los resultados y mostrarlos como opciones
                while ($valores = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($valores['nombre'] != $fila['tipo_cultivo']) {
                        echo '<option value="' . htmlspecialchars($valores['nombre']) . '">' . htmlspecialchars($valores['nombre']) . '</option>';
                    }
                }
            } catch (PDOException $e) {
                echo '<option value="">Error al cargar cultivos: ' . htmlspecialchars($e->getMessage()) . '</option>';
            }
            ?>
        </select>
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label" style="color:#21618C;">Fitosanitario</label>
    <div class="col-sm-9">
        <select class="form-select" id="Fitosanitario" name="Fitosanitario" required>
            <option value="<?php echo htmlspecialchars($fila['fitosanitario']); ?>">
                <?php echo htmlspecialchars($fila['fitosanitario']); ?>
            </option>
            <?php
            try {
                // Crear una conexión PDO
                include("../conexion/conexion.php");
                $conn = cconexion::ConexionBD();
                // Consulta para obtener los insumos fungicidas
                $query = "SELECT nombre_funguisida FROM insumos_funguisidas";
                $stmt = $conn->prepare($query);
                $stmt->execute();

                // Obtener los resultados y mostrarlos como opciones
                while ($valores = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($valores['nombre_funguisida'] != $fila['fitosanitario']) {
                        echo '<option value="' . htmlspecialchars($valores['nombre_funguisida']) . '">' . htmlspecialchars($valores['nombre_funguisida']) . '</option>';
                    }
                }
            } catch (PDOException $e) {
                echo '<option value="">Error al cargar fitosanitarios: ' . htmlspecialchars($e->getMessage()) . '</option>';
            }
            ?>
        </select>
    </div>
</div>

                                <div class="row mb-3">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Nivel/plaga</label>
                                  <div class="col-sm-9">
                                    <select class="form-select" id="Nivel_plaga" name="Nivel_plaga" required>
                                      <option <?php echo $fila["Nivel_plaga"] === 'Alta' ? "selected='selected'" : "" ?>value="Alta">Alta</option>
                                      <option <?php echo $fila["Nivel_plaga"] === 'Media' ? "selected='selected'" : "" ?>value="Media">Media</option>
                                      <option <?php echo $fila["Nivel_plaga"] === 'Baja' ? "selected='selected'" : "" ?>value="Baja">Baja</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="row mb-3">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha inicial</label>
                                  <div class="col-sm-9">
                                    <input id="fechainicio_act"type="date" class="form-control" name="Fecha_inicial"
                                    value='<?php echo $fila["Fecha_inicial"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-3">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha Final</label>
                                  <div class="col-sm-9">
                                    <input id="fechafinal_act" type="date" class="form-control" name="Fecha_final"
                                    value='<?php echo $fila["Fecha_final"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-3">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Encargado</label>
                                  <div class="col-sm-9">
                                    <input  type="text" class="form-control" name="Encargado"  required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                    value='<?php echo $fila["Encargado"]; ?>'>
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
                alert("La fecha final del control no puede ser menor o igual a la fecha de inicio.");
                fechaCosechaInput.value = "";  // Limpiar campo de fecha de cosecha si es inválido
                fechaCosechaInput.focus();     // Focalizar nuevamente en el campo de fecha de cosecha
            }
        });

        // Validación de la fecha de siembra (en caso de que se ingrese una fecha posterior a la cosecha)
        fechaSiembraInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha && fechaSiembra > fechaCosecha) {
                alert("La fecha de inicio del control no puede ser posterior a la final.");
                fechaSiembraInput.value = "";  // Limpiar campo de fecha de siembra si es inválido
                fechaSiembraInput.focus();     // Focalizar nuevamente en el campo de fecha de siembra
            }
        });
    });
</script>

                                <div class="row mb-3">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Maquinaría</label>
                                  <div class="col-sm-9">
                                    <input type="text" class="form-control" name="Maquinaria"  required placeholder=" " oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                    value='<?php echo $fila["maquinaria"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-3">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Nota</label>
                                  <div class="col-sm-9">
                                    <input type="text" class="form-control" name="Nota"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                    value='<?php echo $fila["Nota"]; ?>'>
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
    top: 162px;
  }

  .datatable-dropdown>label {
    visibility: hidden;
  }

  .datatable-selector {
    visibility: visible;
  }
</style>


              <!-- <span style="display:inline-block; position:relative; top:-20px;">N° de registros
                <?php  $contador ; ?>
              </span> -->



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
    document.getElementsByName("Parcela")[0].value = "";
    document.getElementsByName("Dosis")[0].value = "";
    document.getElementsByName("Cantidad")[0].value = "";
    document.getElementsByName("Tipo_cultivo")[0].value = "";
    document.getElementsByName("Fitosanitario")[0].value = "";
    document.getElementsByName("Nivel_plaga")[0].value = "";
    document.getElementsByName("Fecha_inicial")[0].value = "";
    document.getElementsByName("Fecha_final")[0].value = "";
    document.getElementsByName("Encargado")[0].value = "";
    document.getElementsByName("Maquinaria")[0].value = "";
    document.getElementsByName("Nota")[0].value = "";
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




    document.getElementById("dateForm").addEventListener("submit", function (event) {
      const validationCustom011 = document.getElementById("validationCustom011").value;
      const validationCustom012 = document.getElementById("validationCustom012").value;
      if (new Date(validationCustom011) >= new Date(validationCustom012)) {
        event.preventDefault(); // Previene el envío del formulario
      }
      console.log(document.getElementById("validationCustom011"))
    });

    document.getElementById("validationCustom011").addEventListener("change", function () {
      const validationCustom011 = this.value;
      const minfecha_final = new Date(validationCustom011);
      minfecha_final.setDate(minfecha_final.getDate() + 1);
      document.getElementById("validationCustom012").setAttribute("min", minfecha_final.toISOString().split("T")[0]);

    });
</script>