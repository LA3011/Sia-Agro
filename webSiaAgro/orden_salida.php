<?php session_start(); 

if (isset($_SESSION['ref']) == false) {
  $_SESSION['ref'] = "null";
  header("location: tabla_orden_salida.php");
}
if ($_SESSION['ref'] === "print") {
  $_SESSION['ref'] = "reset";
  header("location: tabla_orden_salida.php");
} 
  // VALIDAR INSERSION
$_SESSION['pdf'] = true; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facturación</title>
  <script src="js/popper.min.js"></script>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery-3.7.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="js/bootstrap.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
    }

    .container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
    }

    h1, h3 {
      text-align: center;
      color: #333;
    }

    .form-group label {
      font-weight: bold;
    }

    .form-control {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      box-sizing: border-box;
      font-size: 14px;
    }

    .btn {
      margin-top: 10px;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      font-size: 14px;
      color: #fff;
      background-color: #007bff;
      cursor: pointer;
    }

    .btn-primary {
      background-color: #007bff;
    }

    .btn-primary:hover {
      background-color: #0056b3;
    }

    .btn-success {
      background-color: #28a745;
    }

    .btn-success:hover {
      background-color: #1e7e34;
    }

    .btn-danger {
      background-color: #dc3545;
    }

    .btn-danger:hover {
      background-color: #a71d2a;
    }

    .table {
      margin-top: 20px;
      width: 100%;
      border-collapse: collapse;
    }

    .table th, .table td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #ccc;
    }

    .table th {
      background-color: #f5f5f5;
      font-weight: bold;
    }

    .table td:last-child {
      text-align: center;
    }


  </style>
</head>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Orden de salida </title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Dashboard - SB Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Dashboard - SB Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center" style="background-color: #1c2833; ">
  <div class="d-flex align-items-center justify-content-between">
    <a href="inicio.php" class="logo d-flex align-items-center">
      <!-- <img src="./imagen/imagen4.jpg" alt="La Hacienda los Tucupidos"> -->
      <a href="index.html">
        <img src="imagen/logo11.png" style="width: 200px; position: absolute; top: 10px; left: 10px;">
      </a>
    </a>
    <i class="bi bi-list toggle-sidebar-btn" style="color:white;"></i>
  </div><!-- End Logo -->
  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle " href="#">
          <i class="bi bi-search"></i>
        </a>
        <li class="nav-item dropdown">
          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <style>
              .x1{border-radius:3px; border:.1px solid grey; margin-right:10px; padding:10px;} .x1:hover{ border:.2px solid blue; background-color:rgb(127 128 128 5);}
            </style>
            <!--  <a class="" href="#">Login&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>  -->
          </a>  
        </li>
        <div class="search-bar" style="position:relative; top:-5px;">
          <div class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
              <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" style="position:relative; top:9px;" />
              <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
          </div>

        </div>

        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="./imagen/perfil.png" alt="Profile" class="rounded-circle">
            <span class="fas fa-user fa-fw d-none d-md-block dropdown-toggle ps-2" style="color:white; font-size: 1.5em;"></span>
          </a><!-- End Profile Iamge Icon -->
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <!-- nombre de usuario -->
              <h6 >U.P.T.A</h6>
              <!-- tipo de usuario -->
              <span>admin</span>
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="salir.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Salir</span>
              </a>
            </li>
          </ul>
        </li><!-- End Profile Nav -->
      </ul>
    </header><!-- End Header -->
    <body>
      <div class="container" style="padding:3% 7%; padding-right:7%; margin-top:6%; margin-bottom:4%;">
        <h1>Orden de salida Animal</h1>
        <hr>
        <div>
          <div>
            <form method="POST" action="procesar/procesar_factura1.php" style="display:block;">

              <div class="row">

                <div class="form-group">
                  <label for="fecha">Fecha:</label>
                  <?php
                  $fecha = date("Y-m-d");
                  ?>
                  <input style="padding-left:20px; width:15%;" type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $fecha; ?>" readonly required>
                </div>
              </div>

              <div class="" style=" display:inline-block; width:50%; margin-right:23.5%; height:90px;  margin-bottom: 0;">
                <div class="form-group" style="display:block;  margin:0px; height:90px;">
                  <label for="cliente">Cliente:</label>
                  <input style=" margin-bottom:20px; margin:0;" type="text" class="form-control" id="cliente" name="cliente"required placeholder="Eje: Carlos" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" >
                </div>
              </div>
              <div class="row" style=" display:inline-block; height:90px; margin:10px;  margin-bottom: 0;">
    <div class="form-group" style="display:block;  margin:0px; height:90px;">
        <label for="despachador">Despachador</label>
        <select style="height:44px;" class="form-control" id="despachador" name="despachador" required>
            <option value="">Seleccione una opcion</option>
            <?php
            include_once("conexion/conexion.php");

            $conn = cconexion::ConexionBD();
            try {
                $tabla = "SELECT \"Id_empleados\", nombre FROM empleados";
                $stmt = $conn->prepare($tabla);
                $stmt->execute();
                $valores = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($valores as $row) {
                    echo '<option value="' . htmlspecialchars($row['Id_empleados']) . '">' . htmlspecialchars($row['nombre']) . '</option>';
                }
            } catch (PDOException $e) {
                echo "Error al ejecutar la consulta: " . $e->getMessage();
            }
            $conn = null;
            ?>
        </select>
    </div>
</div>




              <div class="col-md-4" style=" padding:0; display:inline-block; margin-bottom: 0; height:90px; margin-right:2%;">
                <div class="form-group" style="display:block;  margin:0px; height:90px;">
                  <label for="serie">Serie:</label>
                  <input type="text" class="form-control" id="serie" name="serie" onchange='comprobarInfoEquipo(this);' required placeholder="Eje: F001">
                </div>
              </div>

              <div class="col-md-6" style=" padding:0; position: relative; display:inline-block; margin-bottom: 0; height:90px;">
                <div class="form-group" style="display:block;  margin:0px; height:90px;">
                  <label for="numero">Número:</label>
                  <input style="width:125%;" type="text" class="form-control" id="numero" name="numero" onchange="comprobarInfoEquipo(this);" required placeholder="Eje: 000001">
                </div>
              </div>
            </div>

            <div class="col-md-6" style=" padding:0; height:90px;">
              <div class="form-group" style="display:block;  margin:0px;height:90px;">
                <label for="tipoPublico">Tipo de público:</label>
                <select style="height:50px;" class="form-control" id="tipoPublico" name="tipoPublico" required>
                  <option selected>Público general</option>
                </select>
              </div>

            </div>

            <!-- Tabla de factura -->
            <div class="form-group">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAnimales"><i class="bi bi-cart-fill"></i> Agregar Animal</button>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Raza</th>
                  <th>Peso</th>
                  <th>Lote</th>
                  <th>Imagen</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="tablaAnimales">
                <input type="hidden" id="animalesSeleccionados" name="animalesSeleccionados" value="">
                <script>
  // Capturar el formulario
  var formulario = document.querySelector('form');

  // Agregar evento de envío del formulario
  formulario.addEventListener('submit', function(event) {
    // Validar si no se ha agregado ningún animal
    if (!hayAnimalesSeleccionados()) {
      event.preventDefault(); // Evitar el envío del formulario
      alert('Debe agregar al menos 1 animal');
    } else {
      // Capturar los datos de los animales seleccionados
      var animalesSeleccionados = obtenerAnimalesSeleccionados();

      // Asignar los datos al campo oculto
      document.getElementById('animalesSeleccionados').value = JSON.stringify(animalesSeleccionados);
    }
  });

  // Función para verificar si hay animales seleccionados
  function hayAnimalesSeleccionados() {
    var filas = document.querySelectorAll('#tablaAnimales tr');
    return filas.length > 0;
  }

  // Función para obtener los animales seleccionados
  function obtenerAnimalesSeleccionados() {
    var animales = [];
    var filas = document.querySelectorAll('#tablaAnimales tr');

    // Recorrer las filas de la tabla
    for (var i = 0; i < filas.length; i++) {
      var fila = filas[i];

      // Obtener los valores de cada columna en la fila
      var nombre = fila.cells[0].textContent;
      var raza = fila.cells[1].textContent;
      var peso = fila.cells[2].textContent;
      var lote = fila.cells[3].textContent;
      var imagen = fila.cells[4].textContent;

      // Crear un objeto con los datos del animal
      var animal = {
        nombre: nombre,
        raza: raza,
        peso: peso,
        lote: lote,
        imagen: imagen
      };

      // Agregar el animal al array
      animales.push(animal);
    }

    return animales;
  }
</script>
<!-- se agregarán los animales seleccionados -->
</tbody>
</table>
<div style="display:block; width:170px; margin:0;  height:43px;">
  <input type="hidden" name="session_acceso" value="<?php echo $_SESSION['Usuario'] ?>">
  <input type="hidden" name="session_id" value="<?php echo $_SESSION['Id_Usuario'] ?>">
  <button style="display:block; width: 165px; height:42px; margin: 0;" type="submit" class="btn btn-success" id="ocl" onclick="ocultando()"><i class="bi bi-check-circle"></i> Generar Orden</button>
</div>
            <!-- <script>
                function ocultando(){
                  document.getElementById("ocl").style.display = "none";
                }
              </script> -->
            </form>


            <a style="position:relative; left:170px; bottom: 52.5px; height:42px;" href="tabla_orden_salida.php" class="btn btn-danger"><i class="bi bi-arrow-left-circle"></i> Atras</a>
          </div>

          <!-- Modal de selección de animales -->
          <?php
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();
if (!$conn) {
    die("Error de conexión: No se pudo conectar a la base de datos.");
}

try {
    // Consulta para obtener los datos de los animales
    $sql = 'SELECT "Nombre", "Raza", "Peso", "Lote", "Imagen" FROM animales WHERE "Venta" = :venta';
    $stmt = $conn->prepare($sql);
    $stmt->execute(['venta' => 'Venta']);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al ejecutar la consulta: " . $e->getMessage();
    exit;
}
?>

<!-- Modal de selección de animales -->
<div class="modal fade" id="modalAnimales" tabindex="-1" role="dialog" aria-labelledby="modalAnimalesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: green; color: white;">
                <h5 class="modal-title text-center w-100">Seleccionar Animal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="text-align:center">Nombre</th>
                            <th style="text-align:center">Raza</th>
                            <th style="text-align:center">Peso</th>
                            <th style="text-align:center">Lote</th>
                            <th style="text-align:center">Imagen</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tablaModalAnimales">
                        <?php
                        foreach ($result as $row) {
                            $nombre = htmlspecialchars($row["Nombre"]);
                            $raza = htmlspecialchars($row["Raza"]);
                            $peso = htmlspecialchars($row["Peso"]);
                            $lote = htmlspecialchars($row["Lote"]);
                            
                            // Leer el recurso de la imagen y convertirlo en una cadena
                            $imagen = '';
                            if (!is_null($row["Imagen"])) {
                                $imagen = 'data:image/jpeg;base64,' . base64_encode(stream_get_contents($row["Imagen"]));
                            }

                            echo '<tr>';
                            echo '<td style="text-align:center;">' . $nombre . '</td>';
                            echo '<td style="text-align:center;">' . $raza . '</td>';
                            echo '<td style="text-align:center;">' . $peso . '</td>';
                            echo '<td style="text-align:center;">' . $lote . '</td>';
                            if ($imagen) {
                                echo '<td style="text-align:center;"><img src="' . $imagen . '" alt="Imagen del animal" width="80px"></td>';
                            } else {
                                echo '<td style="text-align:center;">Sin imagen</td>';
                            }
                            echo '<td style="text-align:center;"><button type="button" class="btn" style="margin-top:0; background-color: #28A745;" onclick="agregarAnimalSeleccionado(this)"><span style="color: white; font-size:20px;">+</span></button></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php
// Cerrar la conexión
$conn = null;
?>



          <script>
// Función para agregar un animal seleccionado a la tabla de la factura
function agregarAnimalSeleccionado(btn) {
  var fila = $(btn).closest('tr');
  var nombre = fila.find('td:eq(0)').text();
  var raza = fila.find('td:eq(1)').text();
  var peso = fila.find('td:eq(2)').text();
  var lote = fila.find('td:eq(3)').text();
  var imagen = fila.find('td:eq(4) img').attr('src');

  // Agregar el animal seleccionado a la tabla de la factura
  var filaFactura = '<tr>' +
  '<td>' + nombre + '</td>' +
  '<td>' + raza + '</td>' +
  '<td>' + peso + '</td>' +
  '<td>' + lote + '</td>' +
  '<td><img src="' + imagen + '" alt="Imagen del animal" width="50"></td>' +
  '<td><button style="margin-top:0;" type="button" class="btn btn-danger btn-eliminar-animal" onclick="eliminarAnimalSeleccionado(this)">Eliminar</button></td>' +
  '</tr>';

  $('#tablaAnimales').append(filaFactura);

  // Eliminar la fila del animal seleccionado de la tabla del modal
  fila.hide(); // Ocultar la fila en lugar de eliminarla

  // Restaurar el estilo del botón en la tabla del modal
  var botonModal = fila.find('td:eq(5) button');
  botonModal.removeClass('btn-primary').addClass('btn btn-warning btn-agregar-animal');
  botonModal.html('<span style="color: white;">+</span>');
}

// Función para eliminar un animal seleccionado de la tabla de la factura
function eliminarAnimalSeleccionado(btn) {
  var fila = $(btn).closest('tr');
  var nombre = fila.find('td:eq(0)').text();
  var raza = fila.find('td:eq(1)').text();
  var peso = fila.find('td:eq(2)').text();
  var lote = fila.find('td:eq(3)').text();
  var imagen = fila.find('td:eq(4) img').attr('src');

  // Agregar el animal eliminado nuevamente a la tabla del modal
  var filaModal = '<tr>' +
  '<td style="text-align:center">' + nombre + '</td>' +
  '<td style="text-align:center">' + raza + '</td>' +
  '<td style="text-align:center">' + peso + '</td>' +
  '<td style="text-align:center">' + lote + '</td>' +
  '<td style="text-align:center"><img src="' + imagen + '" alt="Imagen del animal" style="width:80px"></td>' +
  '<td style="text-align:center"><button type="button" class="btn btn-primary btn-agregar-animal" style="background-color: #28a745; margin-top:0;" onclick="agregarAnimalSeleccionado(this)"><span style="color: white; font-size:20px;">+</span></button></td>' +
  '</tr>';

  $('#tablaModalAnimales').append(filaModal);

  // Eliminar la fila del animal seleccionado de la tabla de la factura
  fila.remove();
}

$(document).ready(function() {
  // Evento para abrir el modal de selección de animales
  $('#modalAnimales').on('show.bs.modal', function() {

  });
});
</script>

