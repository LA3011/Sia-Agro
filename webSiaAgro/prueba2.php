
<?php session_start(); ?>
<?php if (!isset($_SESSION['Aceso'])) {
  header("location: index.html");
} ?>
<?php include_once("header.php") ?>
<?php include_once("Sidebar.php") ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - NiceAdmin Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .card-title { font-weight: bold; color: #343a40; }
        .breadcrumb-item a { text-decoration: none; color: #007bff; }
        .table thead { background-color: #007bff; color: white; }
        .btn { border-radius: 10px; }
    </style>
  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link hrefy="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">


  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: May 30 2023 with Bootstrap v5.3.0
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>


  <main id="main" class="main">
  <?php
// Conexión a la base de datos
include_once("conexion/conexion.php");

try {
    $conn = cconexion::ConexionBD(); // Obtener conexión PDO

    // Consulta segura
    $Psql = "SELECT precio FROM precios LIMIT 1";
    $stmt = $conn->prepare($Psql);
    $stmt->execute();
    
    // Obtener el valor de precio si hay registros
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);
    $precio = $fila ? $fila["precio"] : ""; // Si no hay resultados, asigna un valor vacío

    // Formatear el precio
    $precio_formateado = number_format($precio, 2, ',', '.'); // 2 decimales, coma para decimales y punto para miles

} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}
?>
<div class="container mt-4">
    <div class="pagetitle">
        <h1 class="text-primary"><i class="bi bi-cart"></i> Venta</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Animales en venta</a></li>
                <li class="breadcrumb-item active">Raza animal</li>
            </ol>
        </nav>
    </div>

    <div class="row g-2"> 
    <div class="col-sm-6 col-md-4 col-lg-3">
    <div class="card p-2 text-center">
        <h6 class="card-title"><i class="bi bi-cash-stack"></i> Venta</h6>
        <h6 class="card-price text-white">Bfs <?php echo $precio_formateado; ?></h6>
        <a href="ganancia_inversion.php" class="btn btn-primary btn-sm w-100">Detalles</a>
    </div>
</div>
<div class="col-sm-6 col-md-4 col-lg-3">
    <div class="card p-2 text-center">
        <h6 class="card-title"><i class="bi bi-piggy-bank"></i> Animal de inversión</h6>
        <h6 class="card-price text-white ">Bfs <?php echo $precio_formateado; ?></h6>
        <a href="inversion.php" class="btn btn-primary btn-sm w-100">Detalles</a>
    </div>
</div>
<div class="col-sm-6 col-md-4 col-lg-3">
    <div class="card p-2 text-center">
        <h6 class="card-title"><i class="bi bi-clipboard-plus"></i> Raza Animal</h6>
        <h6 class="card-price text-white">Bfs <?php echo $precio_formateado; ?></h6>
        <button data-bs-toggle="modal" data-bs-target="#modalAgregarRaza" class="btn btn-success btn-sm w-100">Agregar</button>
    </div>
</div>

        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card p-2 text-center">
                <h6 class="card-title"><i class="bi bi-clipboard-plus"></i> Precio del kilo de animal</h6>
                <h6 class="card-price">Bfs <?php echo $precio_formateado; ?></h6>
                <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#editPriceModal">Editar</button>
            </div>
        </div>
    </div>
</div>



       





      <!-- Top Selling -->
      <div class="card mt-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-list-check"></i> Raza Animal</h5>
    </div>
    <div class="card-body">
        <table id="tablaRazas" class="table table-hover" style="border-collapse: collapse; border: none;">
            <thead>
                <tr style="border-bottom: 2px solid #dee2e6;">
                    <th>Imagen</th>
                    <th>Raza</th>
                    <th>Vendidas</th>
                    <th>En venta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                include_once("conexion/conexion.php");
                try {
                    $conn = cconexion::ConexionBD();
                    $Rsql = "SELECT id_raza, raza, vendidas, COALESCE(venta, 0) AS venta, encode(imagen_raza, 'base64') as imagen_base64 FROM raza_animales";
                    $stmt = $conn->prepare($Rsql);
                    $stmt->execute();
                    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (count($resultados) > 0) {
                        foreach ($resultados as $fila) {
                            $id_raza = htmlspecialchars($fila["id_raza"]);
                            $imagenBase64 = $fila["imagen_base64"];
                            $raza = htmlspecialchars($fila["raza"]);
                            $vendidas = htmlspecialchars($fila["vendidas"]);
                            $en_venta = isset($fila["venta"]) ? htmlspecialchars($fila["venta"]) : "No disponible";
                            echo "<tr style='border: none;'>
                                <td><img src='data:image/jpeg;base64,$imagenBase64' width='50'></td>
                                <td>$raza</td>
                                <td>$vendidas</td>
                                <td>$en_venta</td>
                                <td>
                                    <button class='btn btn-warning btn-sm btnActualizar' data-id='$id_raza' data-raza='$raza' data-imagen='data:image/jpeg;base64,$imagenBase64'>
                                        <i class='bi bi-pencil-fill'></i>
                                    </button>
                                    <button class='btn btn-danger btn-sm btnEliminar' data-id='$id_raza'>
                                        <i class='bi bi-trash-fill'></i>
                                    </button>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No se encontraron registros</td></tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='5'>Error: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Incluir DataTables y jQuery -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Script para inicializar DataTables -->
<script>
    $(document).ready(function () {
        $('#tablaRazas').DataTable({
            "paging": true,      // Habilitar paginación
            "searching": true,   // Habilitar filtro de búsqueda
            "ordering": true,    // Habilitar ordenación de columnas
            "lengthMenu": [5, 10, 25, 50], // Opciones de cantidad de registros por página
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ registros en total)",
                "search": "Buscar:",
                "paginate": {
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });

        // Eliminar las líneas de separación en la tabla
        $("#tablaRazas").css("border-collapse", "collapse");
        $("#tablaRazas tbody tr").css("border", "none");
    });
</script>


<!-- Modal para actualizar raza -->
<div class="modal fade" id="modalActualizarRaza" tabindex="-1" aria-labelledby="modalActualizarRazaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title text-black" id="modalActualizarRazaLabel">Actualizar Raza Animal</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formActualizarRaza">
          <input type="hidden" id="idRaza" name="id_raza"> <!-- Campo oculto para la ID -->
          <div class="mb-3">
            <label for="nombreRazaActualizar" class="form-label">Nombre de la Raza</label>
            <input type="text" class="form-control" id="nombreRazaActualizar" name="nombre_raza" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Imagen Actual</label><br>
            <img id="imagenActual" src="" alt="Imagen de la raza" width="100">
          </div>
          <div class="mb-3">
            <label for="imagenRazaActualizar" class="form-label">Nueva Imagen (Opcional)</label>
            <input type="file" class="form-control" id="imagenRazaActualizar" name="imagen_raza" accept="image/*">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-warning" id="actualizarRazaBtn">Actualizar</button>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Abre el modal y carga los datos de la raza seleccionada
    document.querySelectorAll(".btnActualizar").forEach(button => {
        button.addEventListener("click", function() {
            let id = this.getAttribute("data-id");
            let raza = this.getAttribute("data-raza");
            let imagen = this.getAttribute("data-imagen");

            document.getElementById("idRaza").value = id;
            document.getElementById("nombreRazaActualizar").value = raza;
            document.getElementById("imagenActual").src = imagen;

            let modal = new bootstrap.Modal(document.getElementById("modalActualizarRaza"));
            modal.show();
        });
    });

    // Petición para actualizar raza
    document.getElementById("actualizarRazaBtn").addEventListener("click", function() {
        let form = document.getElementById("formActualizarRaza");
        let formData = new FormData(form);

        fetch("actualizar_raza.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("✅ Raza actualizada correctamente!");
                location.reload(); // Recargar la página
            } else {
                alert("❌ Error al actualizar: " + data.message);
            }
        })
        .catch(error => {
            console.error("❌ Error en la petición:", error);
        });
    });

    // Petición para eliminar raza
    document.querySelectorAll(".btnEliminar").forEach(button => {
        button.addEventListener("click", function() {
            let id = this.getAttribute("data-id");
            if (confirm("¿Seguro que quieres eliminar esta raza?")) {
                fetch("eliminar_raza.php", {
                    method: "POST",
                    body: JSON.stringify({ id_raza: id }),
                    headers: { "Content-Type": "application/json" }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("✅ Raza eliminada correctamente!");
                        location.reload(); // Recargar la página
                    } else {
                        alert("❌ Error al eliminar: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("❌ Error en la petición:", error);
                });
            }
        });
    });
});
</script>

        <div class="modal fade" id="modalAgregarRaza" tabindex="-1" aria-labelledby="modalAgregarRazaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="modalAgregarRazaLabel">Agregar Raza Animal</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formAgregarRaza">
          <div class="mb-3">
            <label for="nombreRaza" class="form-label">Nombre de la Raza</label>
            <input type="text" class="form-control" id="nombreRaza" name="nombre_raza" placeholder="Ingrese el nombre de la raza" required>
          </div>
          <div class="mb-3">
            <label for="imagenRaza" class="form-label">Imagen de la Raza</label>
            <input type="file" class="form-control" id="imagenRaza" name="imagen_raza" accept="image/*" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="guardarRazaBtn">Guardar</button>
      </div>
    </div>
  </div>
</div>
<script>
document.getElementById("guardarRazaBtn").addEventListener("click", function() {
    let form = document.getElementById("formAgregarRaza");
    let formData = new FormData(form);
    
    fetch("guardar_raza.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("✅ Raza guardada con éxito!");

            // Ocultar modal
            let modal = bootstrap.Modal.getInstance(document.getElementById("modalAgregarRaza"));
            modal.hide();

            // Esperar a que el modal termine de cerrarse antes de limpiar los campos
            setTimeout(() => {
                form.reset(); // Limpia el formulario
            }, 500); // Pequeño retraso para evitar errores visuales
        } else {
            alert("❌ Error al guardar: " + data.message);
        }
    })
    .catch(error => {
        console.error("❌ Error en la petición:", error);
    });
});
</script>






<!-- Modal para editar el precio -->
<!-- Modal para editar el precio -->
<!-- Modal para editar el precio -->
<!-- Modal para editar el precio -->
<!-- Modal para editar el precio -->
<!-- Modal para editar el precio -->

<div class="modal fade" id="editPriceModal" tabindex="-1" aria-labelledby="editPriceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="editPriceModalLabel"> Precio</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="priceInput" class="form-label">Nuevo Precio</label>
          <div class="input-group">
            <span class="input-group-text">$</span>
            <input type="text" class="form-control" id="priceInput" placeholder="Ingrese el nuevo precio" value="<?php echo $precio; ?>">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="guardarPrecio()">Guardar</button>
      </div>
    </div>
  </div>
</div>
<script>
  function guardarPrecio() {
    const nuevoPrecio = document.getElementById("priceInput").value;

    // Crear un formulario oculto para enviar los datos
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "actualizar_precio.php";

    // Agregar un campo oculto para el nuevo precio
    const precioInput = document.createElement("input");
    precioInput.type = "hidden";
    precioInput.name = "nuevo_precio";
    precioInput.value = nuevoPrecio;
    form.appendChild(precioInput);

    // Agregar el formulario al cuerpo del documento y enviarlo
    document.body.appendChild(form);
    form.submit();
  }
</script><!-- End Recent Activity -->

    
        </div><!-- End Right side columns -->

      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once("footer.php"); ?>
</body>

</html>