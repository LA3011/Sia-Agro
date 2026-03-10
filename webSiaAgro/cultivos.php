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
  <link rel="stylesheet"type="text/css" href="css_personalizado/estilo_cultivos.css">
  <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS, Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<style>
/* Estilo base para los enlaces */
.tab-link {
    display: inline-block;
    margin: 0 15px;
    padding: 5px 0;
    font-size: 18px;
    color: #0d6efd; /* Color azul */
    text-decoration: none; /* Quitar subrayado */
    border-bottom: 2px solid transparent; /* Línea invisible por defecto */
    transition: all 0.3s ease; /* Transición suave */
    cursor: pointer;
}

/* Estilo para el enlace activo */
.tab-link.active {
    border-bottom: 2px solid #0d6efd; /* Línea azul debajo del enlace activo */
    font-weight: bold; /* Negrita para el enlace activo */
}

/* Efecto hover */
.tab-link:hover {
    color: #0056b3; /* Azul más oscuro al pasar el cursor */
}
</style>
</head> 


<body>
  


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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #0d6efd; color: white;">
            <h5 class="modal-title text-center w-100">Registrar Cultivo </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <form method="POST" action="procesar/procesar_cultivos.php">

              <!-- Esta sección contiene los campos básicos del cultivo -->
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="tipo_cultiv" class="form-label">Nombre del Cultivo</label>
                  <input type="text" class="form-control" id="tipo_cultivo" name="nombre_cultivo" required placeholder="Maíz"
                  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                </div>

                <div class="col-md-6">
                  <label for="tipo_cultivo" class="form-label">Tipo de Cultivo</label>
                  <select class="form-select" id="tipo_cultivo" name="tipo_cultivo" required>
                    <option value="">Seleccione una opción</option>
                    <option value="Cereal">Cereal</option>
                    <option value="Hortaliza">Hortaliza</option>
                    <option value="Frutal">Frutal</option>
                    <option value="Flor">Flor</option>
                    <option value="Otro">Otro</option>
                  </select>
                </div>
              </div>

              <div class="row mb-3">

              <div class="col-md-6">
    <label for="espacio" class="form-label">Espacio</label>
    <select class="form-select" id="espacio" name="espacio" required>
        <option value="">Seleccione un espacio</option>

        <?php
        include_once("conexion/conexion.php");
        $conn = cconexion::ConexionBD();
        try {
            $tabla = "SELECT * FROM espacios where estado='true' and deshabilitar='false'";
            $stmt = $conn->prepare($tabla);
            $stmt->execute();
            while ($valores = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . $valores['Id_espacios'] . ':' . $valores['nombre_espacio'] . '">' . $valores['nombre_espacio'] . '</option>';
            }
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
        ?>

    </select>
</div>


                <div class="col-md-6">
                  <label for="cosecha_estimada" class="form-label">Cosecha Estimada (Kg)</label>
                  <input oninput="validateAnimalNumber(this)" type="number" class="form-control" id="cosecha_estimada" name="cosecha_estimada" required placeholder="Ej: 150"
                  oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
              </div>

              <div class="row mb-3">
    <div class="col-md-6">
        <label for="fecha_siembra" class="form-label">Fecha de Siembra</label>
        <input min="2020-01-01" type="date" class="form-control" id="fecha_siembra" name="fecha_siembra" required>
    </div>
    <div class="col-md-6">
        <label for="fecha_cosecha" class="form-label">Fecha de Cosecha</label>
        <input min="2020-01-01" type="date" class="form-control" id="fecha_cosecha" name="fecha_cosecha" required>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const fechaSiembraInput = document.getElementById("fecha_siembra");
        const fechaCosechaInput = document.getElementById("fecha_cosecha");

        // Validación de la fecha de cosecha
        fechaCosechaInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha <= fechaSiembra) {
                alert("La fecha de cosecha no puede ser menor o igual a la fecha de siembra.");
                fechaCosechaInput.value = "";  // Limpiar campo de fecha de cosecha si es inválido
                fechaCosechaInput.focus();     // Focalizar nuevamente en el campo de fecha de cosecha
            }
        });

        // Validación de la fecha de siembra (en caso de que se ingrese una fecha posterior a la cosecha)
        fechaSiembraInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha && fechaSiembra > fechaCosecha) {
                alert("La fecha de siembra no puede ser posterior a la fecha de cosecha.");
                fechaSiembraInput.value = "";  // Limpiar campo de fecha de siembra si es inválido
                fechaSiembraInput.focus();     // Focalizar nuevamente en el campo de fecha de siembra
            }
        });
    });
</script>


              <!-- Esta sección contiene los campos de aspersión -->
              <div class="row mb-3" style="">
                <div class="col-sm-12"
        style="text-align: center; font-weight: bold; font-size: 18px; margin-top: 10px; background-color: #0d6efd; color: white;"> Aspersión
            </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="Fecha_aspercion" class="form-label">Fecha de Aspersión</label>
                <input min="2020-01-01" type="date" class="form-control" id="fecha_aspercion" name="fecha_aspercion" required>
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
                <div class="col-md-6">
                  <label for="nombre_producto" class="form-label">Nombre del Producto</label>
                  <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required placeholder="Fungicida"
                  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" placeholder="Ej: ">
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="dosis" class="form-label">Dosis (ml)</label>
                  <input  oninput="validateAnimalNumber(this)" type="number" class="form-control" id="dosis" name="dosis" required placeholder="Ej: 150"
                  oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Ej:150">
                </div>
                <div class="col-md-6">
                  <label for="tipo_aspercion" class="form-label">Tipo de Aspersión</label>
                  <input type="text" class="form-control" id="tipo_aspercion" name="tipo_aspercion" required
                  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" placeholder="Ej: Móvil">
                </div>
              </div>

              <!-- Esta sección contiene los campos de fertilización -->
              <hr>
              <div class="row mb-3" style="">
                <div class="col-sm-12"
                style="text-align: center; font-weight: bold; font-size: 18px; margin-top: 10px; background-color: #0d6efd; color: white;">
              Fertilización</div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="fecha_fertilizacion" class="form-label">Fecha de Fertilización</label>
                <input min="2020-01-01" type="date" class="form-control" id="fecha_fertilizacion" name="fecha_fertilizacion">
              </div>
              <div class="col-md-6">
                <label for="nombre_fertilizante" class="form-label">Nombre del Fertilizante</label>
                <input  type="text" class="form-control" id="nombre_fertilizante" name="nombre_fertilizante" required placeholder="Ej: MAP" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="dosis_fertilizacion" class="form-label">Dosis de Fertilización (ml)</label>
                <input  oninput="validateAnimalNumber(this)"  type="number" class="form-control" id="dosis_fertilizacion" name="dosis_fertilizacion" required placeholder="Ej: 150"
                 oninput="this.value = this.value.replace(/[^0-9]/g, '')">
              </div>
              <div class="col-md-6">
                <label for="cantidad_fertilizante" class="form-label">Cantidad de Fertilizante</label>
                <input oninput="validateAnimalNumber(this)" type="number" class="form-control" id="cantida_fertilizante" name="cantidad_fertilizante" required placeholder="Ej: 5" 
                 oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Ej:150"

                required>
                </div> </div>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label for="fecha_aplicacion" class="form-label">Fecha de Aplicación de Fertilizante</label>
                    <input min="2020-01-01"type="date" class="form-control" id="fecha_aplicacion" name="fecha_aplicacion" required placeholder=""
                    >
                  </div>

                  <div class="col-md-6">
    <label for="tipo_fertilizante" class="form-label">Tipo de Fertilizante</label>
    <select class="form-select" id="tipo_fertilizante" name="tipo_fertilizante" required>
        <option value="">Selecciona un tipo de fertilizante</option>
        <option value="Orgánicos">Orgánicos</option>
        <option value="Químicos">Químicos</option>
        <option value="Minerales">Minerales</option>
        <option value="Biológicos">Biológicos</option>
        <!-- Agrega más opciones según los tipos de fertilizante que desees incluir -->
    </select>
</div>


                <div class="row mb-3">

                <div class="col-md-6">
    <label for="tipo_riego" class="form-label">Tipo de Riego</label>
    <select class="form-select" id="tipo_riego" name="tipo_riego" required>
        <option value="">Selecciona un tipo de riego</option>
        <option value="Aspersión">Aspersión</option>
        <option value="Goteo">Goteo</option>
        <option value="Surco">Surco</option>
        <option value="Inundación">Inundación</option>
        <option value="Gravedad">Gravedad</option>
        <!-- Agrega más opciones según los tipos de riego del cultivo -->
    </select>
</div>


                  <div class="col-md-6">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <input type="text" class="form-control" id="observaciones" name="observaciones" required placeholder="Ej: Ninguna"
                    oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                  </div>
                </div>
              </div>


              <!-- Botones para enviar o cerrar el modal -->
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
    </div>
    <script>
function mostrarTabla(tablaId, link) {
    // Ocultar todas las tablas
    const tablas = document.querySelectorAll('.tabla-contenedor');
    tablas.forEach(tabla => {
        tabla.style.display = 'none';
    });

    // Mostrar la tabla seleccionada
    const tablaSeleccionada = document.getElementById(tablaId);
    if (tablaSeleccionada) {
        tablaSeleccionada.style.display = 'block';
    }

    // Quitar la clase "active" de todos los enlaces
    const links = document.querySelectorAll('.tab-link');
    links.forEach(link => {
        link.classList.remove('active');
    });

    // Agregar la clase "active" al enlace seleccionado
    link.classList.add('active');
}
document.addEventListener("DOMContentLoaded", function () {
    // Seleccionar todas las casillas de verificación
    const checkboxes = document.querySelectorAll(".cosecha-realizada");

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            const idCultivo = this.getAttribute("data-id");

            if (this.checked) {
                // Confirmar si se realizó la cosecha
                Swal.fire({
                    title: '¿Confirmar?',
                    text: "¿Se realizó la cosecha de esta siembra?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Enviar el ID del cultivo al archivo PHP para procesarlo
                        procesarCosecha(idCultivo);
                    } else {
                        // Si se cancela, desmarcar la casilla
                        this.checked = false;
                    }
                });
            }
        });
    });

    function procesarCosecha(idCultivo) {
        $.ajax({
            url: 'procesar/procesar_cosecha.php', // Ruta al archivo PHP para procesar la cosecha
            type: 'POST',
            data: { id: idCultivo },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cosecha registrada',
                        text: response.message,
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        location.reload(); // Recargar la página para actualizar la tabla
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
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al procesar la cosecha.',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    }
});
</script>
    <!------- tabla -------->
    <main id="main" class="main">
      <section class="section">

        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a>Cultivo</a></li>
            <li class="breadcrumb-item"><a>Seguimiento</a></li>
            <li class="breadcrumb-item active">Siembra</li>
          </ol>
        </nav>

        <div class="row">
          <div class="col-lg-30">
            <div class="card">
              <div class="card-body">
                <p style="position: absolute; right:165px; top:200px;"> Buscar... </p>
                <div class="container mt-4">
                  
    <!-- Botones para alternar entre tablas -->
    <div class="text-center mb-3">
        <button class="btn btn-primary" onclick="mostrarTabla('tablaSiembras')">Siembras Realizadas</button>
        <button class="btn btn-secondary" onclick="mostrarTabla('tablaOtra')">Siembras Cosechadas</button>
    </div>
    
    <div id="tablaOtra" class="tabla-contenedor" style="display: none;">
    <h5 class="card-title text-center" style="color:black; font-size:40px;">Siembras Cosechadas</h5>
    <table class="table datatable">
        <thead>
            <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Tipo</th>
                <th scope="col">Fecha Siembra</th>
                <th scope="col">Espacio Sembrado</th>
                <th scope="col">Observaciones</th>
                <th scope="col">Fecha Registro</th>
                <th scope="col">Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include_once("conexion/conexion.php");
            $conn = cconexion::ConexionBD();

            // Consulta para obtener cultivos con estado = TRUE
            $sql = "SELECT * FROM cultivos WHERE estado = TRUE ORDER BY \"ID\"";
            $result = $conn->query($sql);

            if ($result->rowCount() > 0) {
                while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['tipo']; ?></td>
                        <td><?php echo date("d/m/Y", strtotime($fila['fecha_siembra'])); ?></td>
                      
                        <td><?php echo $fila['espacio']; ?></td>
                        <td><?php echo $fila['observaciones']; ?></td>
                        <td><?php echo $fila['fecha_registro']; ?></td>
                        <td>
    <div class="btn-group" role="group">
        <?php
        // Verificar si existe un detalle de cosecha para este cultivo
        $detalleSql = "SELECT * FROM detalle_cosecha WHERE id_cosecha = :id_cosecha";
        $detalleStmt = $conn->prepare($detalleSql);
        $detalleStmt->bindParam(':id_cosecha', $fila['ID'], PDO::PARAM_INT);
        $detalleStmt->execute();

        if ($detalleStmt->rowCount() > 0) {
            // Si existe un detalle de cosecha, mostrar el botón de Modificar
            $detalle = $detalleStmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarDetalleModal-<?php echo $fila['ID']; ?>" title="Modificar Detalle">
                <i class="bi bi-pencil-square"></i> <!-- Ícono de modificar -->
            </button>

            <!-- Modal para editar detalle -->
            <div class="modal fade" id="editarDetalleModal-<?php echo $fila['ID']; ?>" tabindex="-1" aria-labelledby="editarDetalleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #ffc107; color: black;">
                            <h5 class="modal-title" id="editarDetalleModalLabel">
                                <i class="bi bi-pencil-square"></i> Editar Detalle de Cosecha
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="actualizar/actualizar_detalle_cosecha.php">
                                <input type="hidden" name="id_detalle" value="<?php echo $detalle['id']; ?>">
                                <input type="hidden" name="id_cultivo" value="<?php echo $fila['ID']; ?>">

                                <!-- Cantidad Cosechada -->
                                <div class="mb-3">
                                    <label for="cantidad_cosechada-<?php echo $fila['ID']; ?>" class="form-label">
                                        <i class="bi bi-box-seam"></i> Cantidad Cosechada (Kg)
                                    </label>
                                    <input oninput="validateAnimalNumber(this)" type="number" class="form-control" id="cantidad_cosechada-<?php echo $fila['ID']; ?>" name="cantidad_cosechada" value="<?php echo $detalle['cantidad_cosechada']; ?>" required>
                                </div>

                               <!-- Fecha de Cosecha -->
<div class="mb-3">
    <label for="fecha_cosecha-<?php echo $fila['ID']; ?>" class="form-label">
        <i class="bi bi-calendar-event"></i> Fecha de Cosecha
    </label>
    <input 
        type="date" 
        class="form-control fecha-cosecha" 
        id="fecha_cosecha-<?php echo $fila['ID']; ?>" 
        name="fecha_cosecha" 
        value="<?php echo $detalle['fecha_cosecha']; ?>" 
        data-fecha-siembra="<?php echo $fila['fecha_siembra']; ?>" 
        required>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Seleccionar todos los campos de fecha de cosecha
        const fechaCosechaInputs = document.querySelectorAll(".fecha-cosecha");

        fechaCosechaInputs.forEach(input => {
            input.addEventListener("change", function () {
                // Obtener la fecha de siembra desde el atributo data-fecha-siembra
                const fechaSiembra = new Date(this.getAttribute("data-fecha-siembra"));
                const fechaCosecha = new Date(this.value);

                // Validar que la fecha de cosecha no sea menor a la fecha de siembra
                if (fechaCosecha < fechaSiembra) {
                    alert("La fecha de cosecha no puede ser menor a la fecha de siembra. Por favor, ingrese una fecha válida.");
                    this.value = ""; // Limpiar el campo de fecha de cosecha si es inválido
                    this.focus(); // Focalizar nuevamente en el campo de fecha de cosecha
                }
            });
        });
    });
</script>

<div class="mb-3">
    <label for="observaciones-<?php echo $fila['ID']; ?>" class="form-label">
        <i class="bi bi-chat-left-text"></i> Observaciones
    </label>
    <input 
        type="text" 
        class="form-control" 
        id="observaciones-<?php echo $fila['ID']; ?>" 
        name="observaciones" 
        value="<?php echo htmlspecialchars($fila['observaciones'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
        placeholder="Ej: Observaciones del cultivo">
</div>

                                <!-- Botones -->
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle"></i> Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-save"></i> Guardar Cambios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            // Si no existe un detalle de cosecha, mostrar el botón de Agregar
            ?>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detalleModal-<?php echo $fila['ID']; ?>" title="Agregar Detalle">
                <i class="bi bi-plus-circle"></i> <!-- Ícono de agregar -->
            </button>
            <?php
        }
        ?>

        <!-- Botón para imprimir -->
      <!-- Botón para imprimir -->
<button type="button" class="btn btn-success" onclick="imprimirCultivo(<?php echo $fila['ID']; ?>)" title="Imprimir">
    <i class="bi bi-printer"></i> <!-- Ícono de imprimir -->
</button>

<script>
    function imprimirCultivo(idCultivo) {
        // Redirigir al archivo formato_pdf_cultivos.php con el ID del cultivo como parámetro
        window.open(`pdf/formato_pdf_cultivos.php?id=${idCultivo}`, '_blank');
    }
</script>

<a 
    type="button" 
    data-bs-toggle="modal" 
    data-bs-target="#smallModal-<?php echo $fila["ID"]; ?>" 
    style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;" 
    title="Eliminar">
    <i class="ri-delete-bin-2-line" style="color:#EE0D0D;"></i>
</a>
  
    </div>
</td>
                    </tr>
                    <div class="modal fade" id="smallModal-<?php echo $fila["ID"]; ?>" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center; display: inline-block; background-color:#F25050;">
                <h5 class="modal-title" style="background-color:#F25050; color:white;">¡ATENCIÓN!</h5>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position:absolute; left:91%; top:2px;"></button>
            <div class="modal-body">
                ¿Desea eliminar este registro?
            </div>
            <div class="modal-footer">
                <a 
                    style="top:-1px; left:-60px; position: relative; color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;" 
                    href='deshabilitaciones/deshabilitar_cultivos.php?id=<?php echo $fila["ID"]; ?>&session_acceso=<?php echo isset($_SESSION["Usuario"]) ? $_SESSION["Usuario"] : ""; ?>&session_id=<?php echo isset($_SESSION["Id_Usuario"]) ? $_SESSION["Id_Usuario"] : ""; ?>' 
                    title="Eliminar">
                    <span class="btn btn-outline-danger">Eliminar</span>
                </a>
                <button style="left:px; position: relative;" type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
                    <div class="modal fade" id="detalleModal-<?php echo $fila['ID']; ?>" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Cambiado a modal-lg para hacerlo más amplio -->
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0d6efd; color: white;">
                <h5 class="modal-title" id="detalleModalLabel">
                    <i class="bi bi-pencil-square"></i> Agregar Detalle de Cosecha
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="procesar/procesar_detalle_cosecha.php">
                    <input type="hidden" name="id_cultivo" value="<?php echo $fila['ID']; ?>">

                    <!-- Cantidad Cosechada -->
                    <div class="mb-3">
                        <label for="cantidad_cosechada" class="form-label">
                            <i class="bi bi-box-seam"></i> Cantidad Cosechada (Kg)
                        </label>
                        <input oninput="validateAnimalNumber(this)" type="number" class="form-control" id="cantidad_cosechada" name="cantidad_cosechada" required placeholder="Ej: 500">
                    </div>

                    <!-- Fecha de Cosecha -->
                    <div class="mb-3">
    <label for="fecha_cosecha-<?php echo $fila['ID']; ?>" class="form-label">
        <i class="bi bi-calendar-event"></i> Fecha de Cosecha
    </label>
    <input 
        min="2020-01-01" 
        type="date" 
        class="form-control fecha-cosecha" 
        id="fecha_cosecha-<?php echo $fila['ID']; ?>" 
        name="fecha_cosecha" 
        data-fecha-siembra="<?php echo $fila['fecha_siembra']; ?>" 
        required>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Seleccionar todos los campos de fecha de cosecha
        const fechaCosechaInputs = document.querySelectorAll(".fecha-cosecha");

        fechaCosechaInputs.forEach(input => {
            input.addEventListener("change", function () {
                // Obtener la fecha de siembra desde el atributo data-fecha-siembra
                const fechaSiembra = new Date(this.getAttribute("data-fecha-siembra"));
                const fechaCosecha = new Date(this.value);

                // Validar que la fecha de cosecha no sea menor o igual a la fecha de siembra
                if (fechaCosecha <= fechaSiembra) {
                    alert("La fecha de cosecha no puede ser menor o igual a la fecha de siembra. Por favor verifique la fecha ");
                    this.value = ""; // Limpiar el campo de fecha de cosecha si es inválido
                    this.focus(); // Focalizar nuevamente en el campo de fecha de cosecha
                }
            });
        });
    });
</script>

                    <!-- Observaciones -->
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">
                            <i class="bi bi-chat-left-text"></i> Observaciones
                        </label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3" placeholder="Ej: Cosecha exitosa, sin problemas."></textarea>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>
    <!-- Contenedor de la tabla de Siembras -->
    <div id="tablaSiembras" class="tabla-contenedor">
      
        <h5 class="card-title text-center" style="color:black; font-size:40px;">Siembras Realizadas</h5>
        <button  type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal"
                style="margin-right:82.5%; margin-top:10px; margin-bottom:8px;" title="Agregar"><i class="ri-add-fill"
                style="color:white;"></i>Agregar &nbsp</button>
        <table class="table datatable">
                  <thead>
                  <tr>
                      <th scope="col">Nombre</th>
                      <th scope="col">Tipo</th>
                      <th scope="col">Fecha Siembra</th>
                      <th scope="col">Fecha cosecha</th>
                      <th scope="col">Tipo Riego</th>
                      <th scope="col">Espacio Sembrado</th>
                      <th scope="col">Fecha fertilización</th>
                      <th scope="col">Cantidad fertilizante</th>
                      <th scope="col">Fecha Aspersión</th>
                      <th scope="col">Observaciones</th>
                      <th scope="col">Fecha registro</th>
                      <th scope="col">Cosecha Realizada</th>
                      <th scope="col">Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();

$sql = "SELECT * FROM cultivos WHERE estado = FALSE ORDER BY \"ID\"";

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
                    <?php echo date("d/m/Y", strtotime($fila['fecha_siembra'])); ?>
                </td>
                <td>
                    <?php echo date("d/m/Y", strtotime($fila['fecha_cosecha'])); ?>
                </td>
                <td>
                    <?php echo $fila['tipo_riego']; ?>
                </td>
                <td>
                    <?php echo $fila['espacio']; ?>
                </td>
                <td>
                    <?php echo date("d/m/Y", strtotime($fila['fecha_fertilizacion'])); ?>
                </td>
                <td>
                    <?php echo $fila['cantidad_fertilizante']; ?>
                </td>
                <td>
                    <?php echo date("d/m/Y", strtotime($fila['fecha_aspercion'])); ?>
                </td>
                <td>
                    <?php echo $fila['observaciones']; ?>
                </td>
                <td>
                    <?php echo $fila['fecha_registro']; ?>
                </td>
                <td style="text-align: center;">
                            <input type="checkbox" class="cosecha-realizada" data-id="<?php echo $fila['ID']; ?>" style="width: 25px; height: 25px;" />
                        </td>
                          <td>
                            <div class="btn-group" role="group">

                            <?php if($ver == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                              <!-- Boton-modal [ver] -->
                              <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                              type="button" data-bs-toggle="modal" data-bs-target='#basicModal-VER<?php echo $fila["ID"]; ?>'
                              title="Ver">
                              <i class="ri-eye-fill" style="color:#17E45B"></i>
                            </a>
                            <?php } ?>  <!-- ← CODIGO A COPIAR -->


                            <?php if($editar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                            <!-- Boton-modal [Editar] -->
                            <a style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                            type="button" data-bs-toggle="modal" data-bs-target='#basicModal-<?php echo $fila["ID"]; ?>'
                            title="Editar">
                            <i class="ri-ball-pen-fill" style="color:#E5D001;"></i>
                          </a>
                          <?php } ?>  <!-- ← CODIGO A COPIAR -->


                          <?php if($eliminar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                          <!-- Boton-modal [eliminar] -->
                          <a type="button" data-bs-toggle="modal" data-bs-target="#smallModal-<?php echo $fila["ID"]; ?>"
                            style="color:none;  margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                            title="Eliminar">
                            <i class="ri-delete-bin-2-line" style="color:#EE0D0D;"></i>
                          </a>
                          <?php } ?>  <!-- ← CODIGO A COPIAR -->


                        <!-- modal [eliminar] -->
                        <div class="modal fade" id="smallModal-<?php echo $fila["ID"]; ?>" tabindex="-1">
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
                                href='deshabilitaciones/deshabilitar_cultivos.php?id=<?php echo $fila["ID"] ?>&session_acceso=<?php echo isset($_SESSION["Usuario"]) ? $_SESSION["Usuario"] : ""; ?>&session_id=<?php echo isset($_SESSION["Id_Usuario"]) ? $_SESSION["Id_Usuario"] : ""; ?>'

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
                      <div class="modal fade" id="basicModal-<?php echo $fila["ID"]; ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg" style="max-width: 900px;">
                          <div class="modal-content">
                            <div class="modal-header"
                            style="background-color: #0d6efd; color: #fff; text-align: center;">
                            <h5 class="modal-title" style=" text-align: center; margin-left:40%;">Actualizar información</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <form method="POST" action="actualizar/actualizar_cultivos.php">
                              <div>
                                <input type="hidden" class="form-control" name="id_cultivo"
                                value='<?php echo $fila["ID"]; ?>'>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="color:#21618C;">Nombre</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" name="nombre" required placeholder=" "
                                  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                  value='<?php echo $fila["nombre"]; ?>'>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="color:#21618C;">Tipo de Cultivo</label>
                                <div class="col-sm-10">
                                  <select class="form-select" id="tipo" name="tipo" required>
                                    <option <?php echo $fila["tipo"] === 'Cereal' ? "selected='selected'" : "" ?>value="Cereal">Cereal</option>
                                    <option <?php echo $fila["tipo"] === 'Hortaliza' ? "selected='selected'" : "" ?>value="Hortaliza">Hortaliza</option>
                                    <option <?php echo $fila["tipo"] === 'Frutal' ? "selected='selected'" : "" ?>value="Frutal">Frutal</option>
                                    <option <?php echo $fila["tipo"] === '>Flor' ? "selected='selected'" : "" ?>value="Flor">Flor</option>
                                    <option <?php echo $fila["tipo"] === 'Otro' ? "selected='selected'" : "" ?>value="Otro">Otro</option>
                                  </select>
                                </div>
                              </div>
 

                              <div class="row mb-3">
    <label class="col-sm-2 col-form-label" style="color:#21618C;">Espacio</label>
    <div class="col-sm-10">
        <select class="form-select" id="espacio" name="espacio" required>
            <option value="<?php echo $fila['espacio']; ?>">
                <?php echo $fila['espacio']; ?>
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
                                <label class="col-sm-2 col-form-label" style="color:#21618C;">Fecha de
                                siembra</label>
                                <div class="col-sm-10">
                                  <input  min="2020-01-01"  type="date"  id="fecha_siembra_act" class="form-control" name="fecha_siembra"
                                  value='<?php echo $fila["fecha_siembra"]; ?>'>
                                </div>
                              </div>
                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="color:#21618C;">Fecha de
                                cosecha</label>
                                <div class="col-sm-10">
                                  <input  min="2020-01-01"  type="date" id="fecha_cosecha_act" class="form-control" name="fecha_cosecha"
                                  value='<?php echo $fila["fecha_cosecha"]; ?>'>
                                </div>
                              </div> 

                              <script>
    document.addEventListener("DOMContentLoaded", function () {
        const fechaSiembraInput = document.getElementById("fecha_siembra_act");
        const fechaCosechaInput = document.getElementById("fecha_cosecha_act");

        // Validación de la fecha de cosecha
        fechaCosechaInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha <= fechaSiembra) {
                alert("La fecha de cosecha no puede ser menor o igual a la fecha de siembra.");
                fechaCosechaInput.value = "";  // Limpiar campo de fecha de cosecha si es inválido
                fechaCosechaInput.focus();     // Focalizar nuevamente en el campo de fecha de cosecha
            }
        });

        // Validación de la fecha de siembra (en caso de que se ingrese una fecha posterior a la cosecha)
        fechaSiembraInput.addEventListener("change", function () {
            const fechaSiembra = new Date(fechaSiembraInput.value);
            const fechaCosecha = new Date(fechaCosechaInput.value);

            if (fechaCosecha && fechaSiembra > fechaCosecha) {
                alert("La fecha de siembra no puede ser posterior a la fecha de cosecha.");
                fechaSiembraInput.value = "";  // Limpiar campo de fecha de siembra si es inválido
                fechaSiembraInput.focus();     // Focalizar nuevamente en el campo de fecha de siembra
            }
        });
    });
</script>


                              <div class="row mb-3">
    <label class="col-sm-2 col-form-label" style="color:#21618C;">Tipo de riego</label>
    <div class="col-sm-10">
        <select class="form-select" name="tipo_riego" required>
            <option value="">Selecciona un tipo de riego</option>
            <option value="Aspersión" <?php echo ($fila["tipo_riego"] === 'Aspersión') ? 'selected' : ''; ?>>Aspersión</option>
            <option value="Goteo" <?php echo ($fila["tipo_riego"] === 'Goteo') ? 'selected' : ''; ?>>Goteo</option>
            <option value="Surco" <?php echo ($fila["tipo_riego"] === 'Surco') ? 'selected' : ''; ?>>Surco</option>
            <option value="Inundación" <?php echo ($fila["tipo_riego"] === 'Inundación') ? 'selected' : ''; ?>>Inundación</option>
            <option value="Gravedad" <?php echo ($fila["tipo_riego"] === 'Gravedad') ? 'selected' : ''; ?>>Gravedad</option>
            <!-- Agrega más opciones según los tipos de riego -->
        </select>
    </div>
</div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="color:#21618C;">Fecha de
                                Aspersión</label>
                                <div class="col-sm-10">
                                  <input  min="2020-01-01"  type="date" class="form-control" name="fecha_aspercion"
                                  value='<?php echo $fila["fecha_aspercion"]; ?>'>
                                </div>
                              </div>
                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="color:#21618C;">Fecha de
                                fertilizante</label>
                                <div class="col-sm-10">
                                  <input  min="2020-01-01"  type="date" class="form-control" name="fecha_fertilizacion"
                                  value='<?php echo $fila["fecha_fertilizacion"]; ?>'>
                                </div>
                              </div>
                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="color:#21618C;">Cantidad de
                                fertilizante</label>
                                <div class="col-sm-10">
                                  <input  oninput="validateAnimalNumber(this)" type="number" class="form-control" name="cantidad_fertilizante" required placeholder=""
                                  oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                  value='<?php echo $fila["cantidad_fertilizante"]; ?>'>
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
                                <label class="col-sm-2 col-form-label" style="color:#21618C;">Observaciones</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" name="observaciones" required placeholder=" "
                                  oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                  value='<?php echo $fila["observaciones"]; ?>'>
                                </div>
                              </div>

                              <div class="row mb-3">
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


              <!------- modal de ver -------->
              <div class="modal fade" id="basicModal-VER<?php echo $fila["ID"]; ?>" tabindex="-1" >
                      <div class="modal-dialog modal-lg" style="max-width: 900px;">
                        <div class="modal-content">
                        <div class="modal-header" style="background-color: #0d6efd; color: white;">
                            <h5 class="modal-title text-center w-100">Ver información</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"
                          aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <form method="POST" action="actualizar/actualizar_cultivos.php">
                            <div>
                              <input style="pointer-events: none;" type="hidden" class="form-control" name="id_cultivo"
                              value='<?php echo $fila["ID"]; ?>'>
                            </div>

                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" style="color:#21618C;">Nombre</label>
                              <div class="col-sm-10">
                                <input style="pointer-events: none;" type="text" class="form-control" name="nombre" required placeholder=" "
                                oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                value='<?php echo $fila["nombre"]; ?>'>
                              </div>
                            </div>
                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" style="color:#21618C;">Tipo de Cultivo</label>
                              <div class="col-sm-10">
                                <select style="pointer-events: none;" class="form-select" id="tipo" name="tipo" required style="pointer-events: none;">
                                  <option <?php echo $fila["tipo"] === 'Cereal' ? "selected='selected'" : "" ?>value="Cereal">Cereal</option>
                                  <option <?php echo $fila["tipo"] === 'Hortaliza' ? "selected='selected'" : "" ?>value="Hortaliza">Hortaliza</option>
                                  <option <?php echo $fila["tipo"] === 'Frutal' ? "selected='selected'" : "" ?>value="Frutal">Frutal</option>
                                  <option <?php echo $fila["tipo"] === '>Flor' ? "selected='selected'" : "" ?>value="Flor">Flor</option>
                                  <option <?php echo $fila["tipo"] === 'Otro' ? "selected='selected'" : "" ?>value="Otro">Otro</option>
                                </select>
                              </div>
                            </div>
                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" style="color:#21618C;">Espacio</label>
                              <div class="col-sm-10">
                                <input style="pointer-events: none;" type="text" class="form-control" name="espacio"  required placeholder=" "
                                oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                value='<?php echo $fila["espacio"]; ?>'>
                              </div>
                            </div>
                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" style="color:#21618C;">Fecha de
                              siembra</label>
                              <div class="col-sm-10">
                                <input style="pointer-events: none;" type="date" class="form-control" name="fecha_siembra"
                                value='<?php echo $fila["fecha_siembra"]; ?>'>
                              </div>
                            </div>
                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" style="color:#21618C;">Fecha de
                              cosecha</label>
                              <div class="col-sm-10">
                                <input style="pointer-events: none;" type="date" class="form-control" name="fecha_cosecha"
                                value='<?php echo $fila["fecha_cosecha"]; ?>'>
                              </div>
                            </div>
                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" style="color:#21618C;">Tipo de riego</label>
                              <div class="col-sm-10">
                                <input style="pointer-events: none;" type="text" class="form-control" name="tipo_riego" required placeholder=" "
                                oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                value='<?php echo $fila["tipo_riego"]; ?>'>
                              </div>
                            </div>
                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" style="color:#21618C;">Fecha de
                              Aspersión</label>
                              <div class="col-sm-10">
                                <input style="pointer-events: none;" type="date" class="form-control" name="fecha_aspercion"
                                value='<?php echo $fila["fecha_aspercion"]; ?>'>
                              </div>
                            </div>
                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" style="color:#21618C;">Fecha de
                              fertilizante</label>
                              <div class="col-sm-10">
                                <input style="pointer-events: none;" type="date" class="form-control" name="fecha_fertilizacion"
                                value='<?php echo $fila["fecha_fertilizacion"]; ?>'>
                              </div>
                            </div>
                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" style="color:#21618C;">Cantidad de
                              fertilizante</label>
                              <div class="col-sm-10">
                                <input style="pointer-events: none;" type="number" class="form-control" name="cantidad_fertilizante" required placeholder=""
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                value='<?php echo $fila["cantidad_fertilizante"]; ?>'>
                              </div>
                            </div>
                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" style="color:#21618C;">Observaciones</label>
                              <div class="col-sm-10">
                                <input style="pointer-events: none;" type="text" class="form-control" name="observaciones" required placeholder=" "
                                oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                                value='<?php echo $fila["observaciones"]; ?>'>
                              </div>
                            </div>
                            <div class="row mb-3">
                            </div>
                            <div class="modal-footer">
                              <input type="hidden" name="session_acceso" value="<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>">
                              <input type="hidden" name="session_id" value="<?php echo isset($_SESSION['Id_Usuario']) ? $_SESSION['Id_Usuario'] : ''; ?>">
                              <button type="button" class="btn btn-secondary"
                              data-bs-dismiss="modal">Cancelar</button>
                            </div>
                          </form>
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