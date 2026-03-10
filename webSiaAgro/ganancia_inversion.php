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
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <head>
    <title></title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
    <style>
      /* Estilos generales de las tablas */

      /* Estilos generales de las tarjetas */

      /* Estilos específicos de los contenedores */
      body {
        background-color: #f8f9fa;
      }

      .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
      }

      h1 {
        margin-bottom: 30px;
        color: #007bff;
      }

      .filters {
        margin-bottom: 20px;
      }

      .table {
        margin-bottom: 40px;
      }

      .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
      }

      .btn-primary:hover {
        background-color: #0069d9;
        border-color: #0062cc;
      }

      .fa {
        margin-right: 5px;
      }
    </style>
  </head>
  <body>

    <main id="main" class="main">
      <section class="section">

        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Finanzas</li>
            <li class="breadcrumb-item">General</li>
            <li class="breadcrumb-item active">Animales - Ventas</li>
          </ol>
        </nav>

        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4">
              <div class="card" style="background-color:  #2cde6c; color: white;">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div class="icon">
                      <i class="fas fa-chart-bar fa-3x"></i>
                    </div>
                    <div class="content ml-3">
                      <h5 class="card-title">Ventas</h5>
                      <button onclick="redirectTo('ganancia_inversion.php')" class="btn btn-primary">Ver más</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="card" style="background-color: #46f0f3; color: white;">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div class="icon">
                      <i class="fas fa-chart-pie fa-3x"></i>
                    </div>
                    <div class="content ml-3">
                      <h5 class="card-title">Ganancia</h5>
                      <button onclick="redirectTo('ganancias.php')" class="btn btn-primary">Ver más</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="card" style="background-color: #58c1d9; color: white;">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div class="icon">
                      <i class="fas fa-chart-pie fa-3x"></i>
                    </div>
                    <div class="content ml-3">
                      <h5 class="card-title">Inversión</h5>
                      <button onclick="redirectTo('inversion.php')" class="btn btn-primary">Ver más</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <script>
          function redirectTo(url) {
            window.location.href = url;
          }
        </script>

        <div class="container">
          <h1><i class="fas fa-chart-line"></i> Tabla de Ventas</h1>
          <div class="filters">
            <div class="row">
              <div class="col">


                <form action="pdf/formato_pdf_gananciaInver.php" id="formato_pdf_gananciaInver" method="post">
                  <input type="hidden" name="ingreso_generado" id="xp1">
                  <input type="hidden" name="ganancia" id="xp2">
                  <label for="startDate" class="form-label">Desde:</label>
                  <input type="date" id="startDate" name="fechaInicio" class="form-control">
                </div>
                <div class="col">
                  <label for="endDate" class="form-label">Hasta:</label>
                  <input type="date" id="endDate"  name="fechaFinal" class="form-control">
                </form>

              </div>
              <div class="col" style="position: relative; top:30px;">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <button onclick="filterSales()" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
                  </div>
                  <div>

                    <div id="p10">

                      <!-- <button type="submit" form="formato_pdf_gananciaInver"  class="btn" style="background-color: rgb(255, 165, 0);"><i class="fas fa-print"></i> 
                        Imprimir
                      </button> -->
                      <button type="submit" form="formato_pdf_gananciaInver" style="position: absolute; left: 75%; top: -10px;"  class="btn" title="Imprimir">                 
                      <img src="icon/icon-pdf.jpg" style="height: 50px;" viewBox="0 0 512 512">
                      </button>

                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <div class="card bg-info text-white rounded">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="icon">
                    <i class="fas fa-chart-bar fa-3x"></i>
                  </div>
                  <div class="content ml-3">
                    <h5 class="card-title">Ingreso generado</h5>
                    <p class="card-text text-black font-weight-bold" id="total2Amount"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="card bg-warning text-white rounded">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="icon">
                    <i class="fas fa-chart-pie fa-3x"></i>
                  </div>
                  <div class="content ml-3">
                    <h5 class="card-title">Ganancia Obtenida</h5>
                    <p class="card-text text-black font-weight-bold" id="total3Amount"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <table id="salesTable" class="table table-striped">
          <thead>
            <tr>
              <th><i class="fas fa-calendar-alt"></i> Fecha</th>
              <th><i class="fas fa-cube"></i> Tipo de usuario</th>
              <th><i class="fas fa-dollar-sign"></i> Cantidad de animales</th>
              <th><i class="fas fa-money-bill"></i> Ingreso generado</th>
              <th><i class="fas fa-chart-line"></i> Ganancia obtenida</th>
            </tr>
          </thead>

          <tbody id="salesData">
            <!-- Aquí se cargarán los datos de ventas filtrados -->
          </tbody>
          <tfoot>
            <tr>
              <td colspan="2">Total:</td>
              <td id="totalAmount"></td>
              <td colspan="1"></td>
              <td id="totalGanancia"></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <script>


    // Función para filtrar las ventas por rango de fechas
    function filterSales() {
      const startDate = document.getElementById('startDate').value;
      const endDate = document.getElementById('endDate').value;
      // Realizar la solicitud AJAX al archivo data.php
      const xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function(){
        if (xhr.readyState === 4 && xhr.status === 200) {
          const salesData = JSON.parse(xhr.responseText);
          // Filtrar los datos de ventas según las fechas seleccionadas
          const filteredSales = salesData.filter(sale => {
            const saleDate = new Date(sale.fecha); 
            return saleDate >= new Date(startDate) && saleDate <= new Date(endDate);
          });
          // Mostrar los datos filtrados en la tabla
          displaySales(filteredSales);
        }
      };
      xhr.open("GET", "data.php", true);
      xhr.send();
    }



    function displaySales(sales) {
      const salesTable = document.getElementById('salesData');
      const totalAmount = document.getElementById('totalAmount');

  // Vaciar la tabla y el total
  salesTable.innerHTML = '';
  totalAmount.textContent = '';

  // Verificar si hay ventas para mostrar
  if (sales.length === 0) {
    // Mostrar un mensaje de que no hay ventas
    const row = document.createElement('tr');
    const messageCell = document.createElement('td');
    messageCell.setAttribute('colspan', '20');
    messageCell.textContent = 'No se encontraron ventas en el rango de fechas especificado';
    document.getElementById('p10').style.display = 'none';
    row.appendChild(messageCell);
    salesTable.appendChild(row);
    return;

  }else{

// ------------ VALIDACION IMPRIMIR ------------


    document.getElementById('p10').style.display = 'block';

  }

  // Recorrer las ventas y agregar filas a la tabla
  sales.forEach(sale => {
    // Verificar si la venta tiene una fecha válida
    if (!sale.fecha) {
      return;
    }

    const row = document.createElement('tr');
    const dateCell = document.createElement('td');
    const productCell = document.createElement('td');
    const amountCell = document.createElement('td');
    const amount2 = document.createElement('td');
    const amountCe3 = document.createElement('td');



    /* forma original: */
    // dateCell.textContent = sale.fecha;
    
    /* forma adulterada: */
    fecha_formt = sale.fecha;
    fecha_formt = fecha_formt.split('-').reverse().join('-');
    dateCell.textContent = fecha_formt;
    /* ------------------ */

    productCell.textContent = sale.tipoPublico;
    amountCell.textContent = sale.cantidad_animales;
    amountCe3.textContent = new Intl.NumberFormat("de-DE").format(sale.precio);
    amount2.textContent = new Intl.NumberFormat("de-DE").format(sale.ganancia);
    
    
    

    row.appendChild(dateCell); //Si presenta error ---> poner form original => 
    row.appendChild(productCell);
    row.appendChild(amountCell);
    row.appendChild(amountCe3);
    row.appendChild(amount2);
    salesTable.appendChild(row);
  });

  // Calcular y mostrar el total de ventas
  const total = sales.reduce((accumulator, sale) => accumulator + parseInt(sale.cantidad_animales), 0);
  totalAmount.textContent = "Total: " + total.toLocaleString();

  const total2 = sales.reduce((accumulator, sale) => accumulator + parseInt(sale.precio), 0);
  total2Amount.textContent = "Total: " + total2.toLocaleString();

  const total3 = sales.reduce((accumulator, sale) => accumulator + parseInt(sale.ganancia), 0);
  total3Amount.textContent = "Total: " + total3.toLocaleString();

// mandar Form ---> print
$('#xp1').val(total2);
$('#xp2').val(total3);

}
// ...
    // Resto del código JavaScript...

    // Llamar a la función para mostrar los datos de ventas
    filterSales();
  </script> 

</section>

</main><!-- End #main -->

<!-- ======= Footer ======= -->
<?php include("footer.php");
?>
<script src="assets/js/main.js"></script>

</body>

</html>