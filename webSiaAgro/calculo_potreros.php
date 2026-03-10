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

<style type="text/css">

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

/*<!-- Codigo traducion footer Table -->*/
tr > .datatable-empty{
  color: white;
}
/*<!-- ---------------------------- -->*/


</style>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>calculo de Potreros</title>  
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script> 
  <link rel="stylesheet"type="text/css" href="css_personalizado/estilo_calculo_potreros.css">
</head> 
<body>
  <!--------------------------------------------------------------------------------------------------------------------->

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
              <h5 class="modal-title text-center w-100">Registro de Potreros</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="procesar/procesar_calculo_potreros.php" style="padding: 0 50px 0 50px;">
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
                <label for="inputText" class="col-sm-3 col-form-label">Área expresada</label>
                <div class="col-sm-9">
                  <select class="form-select" id="area_expresada" name="area_expresada" required>
                    <option value="">Seleccione una opción</option>
                    <option value="HT">HT</option>
                    <option value="m²">m²</option>
                  </select>
                </div>
              </div>
              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Área</label>
                <div class="col-sm-9">
                  <input  oninput="validateAnimalNumber(this)" type="number" class="form-control" id="validationCustom01" name="area" required placeholder="150" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                <label for="Tipo_suelo" class="col-sm-3 col-form-label">Tipo de Suelo</label>
                <div class="col-sm-9">
                  <select class="form-select" id="Tipo_suelo" name="Tipo_suelo" required>
                    <option value="">Selecciona un tipo de suelo</option>
                    <option value="Franco">Franco</option>
                    <option value="Arenoso">Arenoso</option>
                    <option value="Arcilloso">Arcilloso</option>
                    <option value="Pedregoso">Pedregoso</option>
                    <option value="Limoso">Limoso</option>
                    <option value="Salino">Salino</option>
                    <option value="Cenagoso">Cenagoso</option>
                    <option value="Alcalino">Alcalino</option>
                    <!-- Agrega más opciones según los tipos de suelo de potreros en Venezuela -->
                  </select>
                </div>
              </div>
              <div class="row mb-2">
                <label for="Tipo_pasto" class="col-sm-3 col-form-label">Tipo de Pasto</label>
                <div class="col-sm-9">
                  <select class="form-select" id="Tipo_pasto" name="Tipo_pasto" required>
                    <option value="">Selecciona un tipo de pasto</option>
                    <option value="Dactylis">Dactylis</option>
                    <option value="Ryegrass">Ryegrass</option>
                    <option value="Clover">Clover</option>
                    <option value="Cynodon">Cynodon</option>
                    <option value="Festuca">Festuca</option>
                    <option value="Hierba de San Juan">Hierba de San Juan</option>
                    <option value="Buffel">Buffel</option>
                    <!-- Agrega más opciones según los tipos de pasto de potreros en Venezuela -->
                  </select>
                </div>
              </div>


              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Cantidad de días (Secos)</label>
                <div class="col-sm-9">
                  <input oninput="validatediassecos_verdes(this)" class="form-control" type="number" name="Cantidad_dias_secos" required placeholder="Ej: 30" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
              </div>

              <div class="row mb-2">
                <label for="inputText" class="col-sm-3 col-form-label">Cantidad de días (Verdes)</label>
                <div class="col-sm-9">
                  <input oninput="validatediassecos_verdes(this)" class="form-control" type="number" name="Cantidad_dias_verdes" required placeholder="Ej: 50" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
              </div>
              <script>
              function validatediassecos_verdes(input) {
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
              <div class="row mb-2" style="padding-left: 20%;">
                <div class="col-sm-9" style="text-align: center"> 
                  <input type="hidden" name="session_acceso" value="<?php echo $_SESSION['Usuario'] ?>">
                  <input type="hidden" name="session_id" value="<?php echo $_SESSION['Id_Usuario'] ?>">
                  <a class="btn btn-secondary" style="width: 100px;"  onclick="vaciarCampos()">Vaciar</a>
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
              <li class="breadcrumb-item">Animales</li>
              <li class="breadcrumb-item">General</li>
              <li class="breadcrumb-item active">Registro Potreros</li>
            </ol>
          </nav>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <p style="position: absolute; right:165px; top:130px;"> Buscar... </p>
                  <h5 class="card-title" style="color:black; font-size:40px; margin-left:7%;">Listado de Potreros </h5>

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
                      <th scope="col" >Nombre</th>
                      <th scope="col" >Área Expresada</th>
                      <th scope="col" >Área</th>
                      <th scope="col" >Tipo de Suelo</th>
                      <th scope="col" >Cantidad de días  (Secos)</th>
                      <th scope="col" >Cantidad de días  (Verdes)</th>       
                      <th scope="col" >Tipo de Pasto</th>     
                      <th scope="col" >Acción</th>      
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    include_once("conexion/conexion.php");
                    $conn = cconexion::ConexionBD(); // Inicializar la conexión
                    
                    $sql = "SELECT * FROM potreros ORDER BY \"Id_potreros\"";
  
                    $result = $conn->query($sql);
                    
                    // Verificar si se encontraron registros
                    if ($result->rowCount() > 0) {
                      // Variable para contar los registros
                        $contador = 1;
  
                      // Recorrer los registros y mostrar los datos en la tabla
                      while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                          <td><?php echo $fila['Nombre']; ?></td>
                          <td><?php echo $fila['area_expresada']; ?></td>
                          <td><?php echo $fila['area']; ?></td>
                          <td><?php echo $fila['Tipo_suelo']; ?></td>
                          <td><?php echo $fila['Cantidad_dias_secos']; ?></td>
                          <td><?php echo $fila['Cantidad_dias_verdes']; ?></td>
                          <td><?php echo $fila['Tipo_pasto']; ?></td>
                          <td>
                            <div class="btn-group" role="group">
                        
                            
                                <?php if($ver == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                                <!-- Boton-modal [ver] -->
                                <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                                type="button" data-bs-toggle="modal" data-bs-target='#basicModal-VER<?php echo $fila["Id_potreros"]; ?>'
                                title="Ver">
                                <i class="ri-eye-fill" style="color:#17E45B" aria-describedby="tooltip831980"></i>
                              </a>
                              <?php } ?>  <!-- ← CODIGO A COPIAR -->


                              <?php if($editar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                              <!-- Boton-modal [Editar] -->
                              <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                              type="button" data-bs-toggle="modal" data-bs-target='#basicModal-<?php echo $fila["Id_potreros"]; ?>'
                              title="Editar">
                              <i class="ri-ball-pen-fill" style="color:#E5D001;"></i>
                            </a>
                            <?php } ?>  <!-- ← CODIGO A COPIAR -->


                            <?php if($eliminar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                            <!-- Boton-modal [eliminar] -->
                            <a type="button" data-bs-toggle="modal" data-bs-target="#smallModal-<?php echo $fila["Id_potreros"]; ?>"
                              style="color:none;  margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                              title="Eliminar">
                              <i class="ri-delete-bin-2-line" style="color:#EE0D0D;"></i>
                            </a>
                          <?php } ?>

                          <!-- modal [eliminar] -->
                          <div class="modal fade" id="smallModal-<?php echo $fila["Id_potreros"]; ?>" tabindex="-1">
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
                                href='deshabilitaciones/deshabilitar_calculo_potreros.php?id=<?php echo $fila["Id_potreros"] ?>&session_acceso=<?php echo isset($_SESSION["Usuario"]) ? $_SESSION["Usuario"] : ""; ?>&session_id=<?php echo isset($_SESSION["Id_Usuario"]) ? $_SESSION["Id_Usuario"] : ""; ?>'

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
                      <div class="modal fade" id="basicModal-<?php echo $fila["Id_potreros"]; ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg" style="max-width: 900px;">
                          <div class="modal-content">
                            <div class="modal-header" style="background-color: #0d6efd; color: white;">
                              <h5 class="modal-title text-center w-100">Actualizar información</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form method="POST" action="actualizar/actualizar_calculo_potreros.php"> 
                                <div>
                                  <input type="hidden" class="form-control" name="Id_potreros" value='<?php echo $fila["Id_potreros"]; ?>' >
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre</label>
                                  <div class="col-sm-9">
                                    <input type="text" class="form-control" name="Nombre"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" value='<?php echo $fila["Nombre"]; ?>'>
                                  </div>
                                </div>

                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Área expresada</label>
                                  <div class="col-sm-9">
                                    <select class="form-select" id="area_expresada" name="area_expresada" required>
                                      <option <?php echo $fila["area_expresada"]==='HT' ? "selected='selected'":""?>value="HT">HT</option>
                                      <option <?php echo $fila["area_expresada"]==='m²' ? "selected='selected'":""?>value="m²">m²</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Área</label>
                                  <div class="col-sm-9">
                                    <input  oninput="validateAnimalNumber(this)" type="number" class="form-control" name="area"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value='<?php echo $fila["area"]; ?>'>
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
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de Suelo</label>
                                  <div class="col-sm-9">
                                    <select class="form-select" name="Tipo_suelo" required>
                                      <option value="">Selecciona un tipo de suelo</option>
                                      <option value="Franco" <?php echo ($fila["Tipo_suelo"] === 'Franco') ? 'selected' : ''; ?>>Franco</option>
                                      <option value="Arenoso" <?php echo ($fila["Tipo_suelo"] === 'Arenoso') ? 'selected' : ''; ?>>Arenoso</option>
                                      <option value="Arcilloso" <?php echo ($fila["Tipo_suelo"] === 'Arcilloso') ? 'selected' : ''; ?>>Arcilloso</option>
                                      <option value="Pedregoso" <?php echo ($fila["Tipo_suelo"] === 'Pedregoso') ? 'selected' : ''; ?>>Pedregoso</option>
                                      <option value="Limoso" <?php echo ($fila["Tipo_suelo"] === 'Limoso') ? 'selected' : ''; ?>>Limoso</option>
                                      <option value="Salino" <?php echo ($fila["Tipo_suelo"] === 'Salino') ? 'selected' : ''; ?>>Salino</option>
                                      <option value="Cenagoso" <?php echo ($fila["Tipo_suelo"] === 'Cenagoso') ? 'selected' : ''; ?>>Cenagoso</option>
                                      <option value="Alcalino" <?php echo ($fila["Tipo_suelo"] === 'Alcalino') ? 'selected' : ''; ?>>Alcalino</option>
                                      <!-- Agrega más opciones según los tipos de suelo de potreros en Venezuela -->
                                    </select>
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de Pasto</label>
                                  <div class="col-sm-9">
                                    <select class="form-select" name="Tipo_pasto" required>
                                      <option value="">Selecciona un tipo de pasto</option>
                                      <option value="Dactylis" <?php echo ($fila["Tipo_pasto"] === 'Dactylis') ? 'selected' : ''; ?>>Dactylis</option>
                                      <option value="Ryegrass" <?php echo ($fila["Tipo_pasto"] === 'Ryegrass') ? 'selected' : ''; ?>>Ryegrass</option>
                                      <option value="Clover" <?php echo ($fila["Tipo_pasto"] === 'Clover') ? 'selected' : ''; ?>>Clover</option>
                                      <option value="Cynodon" <?php echo ($fila["Tipo_pasto"] === 'Cynodon') ? 'selected' : ''; ?>>Cynodon</option>
                                      <option value="Festuca" <?php echo ($fila["Tipo_pasto"] === 'Festuca') ? 'selected' : ''; ?>>Festuca</option>
                                      <option value="Hierba de San Juan" <?php echo ($fila["Tipo_pasto"] === 'Hierba de San Juan') ? 'selected' : ''; ?>>Hierba de San Juan</option>
                                      <option value="Buffel" <?php echo ($fila["Tipo_pasto"] === 'Buffel') ? 'selected' : ''; ?>>Buffel</option>
                                      <!-- Agrega más opciones según los tipos de pasto de potreros en Venezuela -->
                                    </select>
                                  </div>
                                </div>

                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Cantidad de días/Secos</label>
                                  <div class="col-sm-9">
                                    <input oninput="validatediassecos_verdes(this)" type="number" class="form-control" name="Cantidad_dias_secos"  required placeholder=""   oninput="this.value = this.value.replace(/[^0-9]/g, '')" value='<?php echo $fila["Cantidad_dias_secos"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Cantidad de días/Verdes</label>
                                  <div class="col-sm-9">
                                    <input oninput="validatediassecos_verdes(this)" class="form-control" name="Cantidad_dias_verdes"  required placeholder="" value='<?php echo $fila["Cantidad_dias_verdes"]; ?>' oninput="this.value = this.value.replace(/[^0-9]/g, '')" >
                                  </div>
                                </div>

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

                      <!------- modal de Ver -------->
                      <div class="modal fade" id="basicModal-VER<?php echo $fila["Id_potreros"]; ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg" style="max-width: 900px;">
                          <div class="modal-content">
                            <div class="modal-header" style="background-color: #0d6efd; color: white;">
                              <h5 class="modal-title text-center w-100">Ver información</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form method="POST" action="actualizar/actualizar_calculo_potreros.php"> 
                                <div>
                                  <input style="pointer-events:none;" type="hidden" class="form-control" name="Id_potreros" value='<?php echo $fila["Id_potreros"]; ?>' >
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Nombre</label>
                                  <div class="col-sm-9">
                                    <input style="pointer-events:none;" type="text" class="form-control" name="Nombre"  required placeholder=""  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" value='<?php echo $fila["Nombre"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Área expresada</label>
                                  <div class="col-sm-9">
                                    <select style="pointer-events:none;" class="form-select" id="area_expresada" name="area_expresada" required>
                                      <option <?php echo $fila["area_expresada"]==='HT' ? "selected='selected'":""?>value="HT">HT</option>
                                      <option <?php echo $fila["area_expresada"]==='m²' ? "selected='selected'":""?>value="m²">m²</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Área</label>
                                  <div class="col-sm-9">
                                    <input style="pointer-events:none;" type="number" class="form-control" name="area"  required placeholder="" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value='<?php echo $fila["area"]; ?>'>
                                  </div>
                                </div>
                                
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de Suelo</label>
                                  <div class="col-sm-9">
                                    <select style="pointer-events:none;"class="form-select" name="Tipo_suelo" required>
                                      <option value="">Selecciona un tipo de suelo</option>
                                      <option value="Franco" <?php echo ($fila["Tipo_suelo"] === 'Franco') ? 'selected' : ''; ?>>Franco</option>
                                      <option value="Arenoso" <?php echo ($fila["Tipo_suelo"] === 'Arenoso') ? 'selected' : ''; ?>>Arenoso</option>
                                      <option value="Arcilloso" <?php echo ($fila["Tipo_suelo"] === 'Arcilloso') ? 'selected' : ''; ?>>Arcilloso</option>
                                      <option value="Pedregoso" <?php echo ($fila["Tipo_suelo"] === 'Pedregoso') ? 'selected' : ''; ?>>Pedregoso</option>
                                      <option value="Limoso" <?php echo ($fila["Tipo_suelo"] === 'Limoso') ? 'selected' : ''; ?>>Limoso</option>
                                      <option value="Salino" <?php echo ($fila["Tipo_suelo"] === 'Salino') ? 'selected' : ''; ?>>Salino</option>
                                      <option value="Cenagoso" <?php echo ($fila["Tipo_suelo"] === 'Cenagoso') ? 'selected' : ''; ?>>Cenagoso</option>
                                      <option value="Alcalino" <?php echo ($fila["Tipo_suelo"] === 'Alcalino') ? 'selected' : ''; ?>>Alcalino</option>
                                      <!-- Agrega más opciones según los tipos de suelo de potreros en Venezuela -->
                                    </select>
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de Pasto</label>
                                  <div class="col-sm-9">
                                    <select style="pointer-events:none;" class="form-select" name="Tipo_pasto" required>
                                      <option value="">Selecciona un tipo de pasto</option>
                                      <option value="Dactylis" <?php echo ($fila["Tipo_pasto"] === 'Dactylis') ? 'selected' : ''; ?>>Dactylis</option>
                                      <option value="Ryegrass" <?php echo ($fila["Tipo_pasto"] === 'Ryegrass') ? 'selected' : ''; ?>>Ryegrass</option>
                                      <option value="Clover" <?php echo ($fila["Tipo_pasto"] === 'Clover') ? 'selected' : ''; ?>>Clover</option>
                                      <option value="Cynodon" <?php echo ($fila["Tipo_pasto"] === 'Cynodon') ? 'selected' : ''; ?>>Cynodon</option>
                                      <option value="Festuca" <?php echo ($fila["Tipo_pasto"] === 'Festuca') ? 'selected' : ''; ?>>Festuca</option>
                                      <option value="Hierba de San Juan" <?php echo ($fila["Tipo_pasto"] === 'Hierba de San Juan') ? 'selected' : ''; ?>>Hierba de San Juan</option>
                                      <option value="Buffel" <?php echo ($fila["Tipo_pasto"] === 'Buffel') ? 'selected' : ''; ?>>Buffel</option>
                                      <!-- Agrega más opciones según los tipos de pasto de potreros en Venezuela -->
                                    </select>
                                  </div>
                                </div>


                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Cantidad de días/Secos</label>
                                  <div class="col-sm-9">
                                    <input style="pointer-events:none;" type="number" class="form-control" name="Cantidad_dias_secos"  required placeholder=""   oninput="this.value = this.value.replace(/[^0-9]/g, '')" value='<?php echo $fila["Cantidad_dias_secos"]; ?>'>
                                  </div>
                                </div>
                                <div class="row mb-2">
                                  <label class="col-sm-3 col-form-label" style="color:#21618C;">Cantidad de días/Verdes</label>
                                  <div class="col-sm-9">
                                    <input style="pointer-events:none;" type="number" class="form-control" name="Cantidad_dias_verdes"  required placeholder="" value='<?php echo $fila["Cantidad_dias_verdes"]; ?>' oninput="this.value = this.value.replace(/[^0-9]/g, '')" >
                                  </div>
                                </div>

                                <div class="modal-footer">
                                  <input style="pointer-events:none;" type="hidden" name="session_acceso" value="<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>">
                                  <input style="pointer-events:none;" type="hidden" name="session_id" value="<?php echo isset($_SESSION['Id_Usuario']) ? $_SESSION['Id_Usuario'] : ''; ?>">
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
                // Incrementar el contador en cada iteración
                $contador++; 
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
    document.getElementsByName("Nombre")[0].value = "";
    document.getElementsByName("area_expresada")[0].value = "";
    document.getElementsByName("area")[0].value = "";
    document.getElementsByName("Tipo_suelo")[0].value = "";
    document.getElementsByName("Tipo_pasto")[0].value = "";
    document.getElementsByName("Cantidad_dias_secos")[0].value = "";
    document.getElementsByName("Cantidad_dias_verdes")[0].value = "";
    document.getElementsByName("categoria")[0].value = "";
    document.getElementsByName("peso_animales")[0].value = "";
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

