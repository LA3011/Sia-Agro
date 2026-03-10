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
    <link rel="stylesheet" type="text/css" href="estilos_inverCult.css">
    <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  </head>

  <body>

    <main id="main" class="main">
      <section class="section">

        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Finanzas</li>
            <li class="breadcrumb-item">General</li>
            <li class="breadcrumb-item active">Cultivos - Inversión/Ganancias</li>
          </ol>
        </nav>

        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4">
              <div class="card" style="background-color: #ffbdd6; color: white;">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div class="icon">
                      <i class="fas fa-chart-bar fa-3x"></i>
                    </div>
                    <div class="content ml-3 text-right">
                      <h5 class="card-title">Inversión</h5>
                     
                    </div>
                  </div>
                </div>
              </div>
            </div>
            </div>
          </div>
        </div>

        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <div class="custom-container" style="--container-color: transparet;">
                <canvas id="doughnutChart" style="max-height: 200px;"></canvas>
              </div>
            </div>
            <div class="col-md-6">
              <div class="custom-container" style="--container-color: #e6ffe6;  border: 1px solid #ced4da;">
                <h2><i class="fas fa-chart-pie fa-3x" style="vertical-align: middle;"></i>Inversión Realizada</h2>
                <?php include 'estimacion_ganancias.php'; ?>
                <p style="font-size: 37px;"><?php echo "" . number_format($inversion_cultivos, 2, ",", "."); ?></p>
              </div>
            </div>
          </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
          document.addEventListener("DOMContentLoaded", () => {
            const chartData = {
              labels: ['Fertilizante', 'Funguisida', 'Semillas', 'Equipos'],
              datasets: [{
                label: 'My First Dataset',
                data: [
                <?php echo $totalFertilizante; ?>,
                <?php echo $totalFunguisida; ?>,
                <?php echo $totalSemillas; ?>,
                <?php echo $totalEquipos; ?>
                ],
                backgroundColor: ['rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)', 'rgb(75, 192, 192)'],
                hoverOffset: 4
              }]
            };

            const doughnutChart = new Chart(document.getElementById('doughnutChart'), {
              type: 'doughnut',
              data: chartData,
              options: {
                plugins: {
                  tooltip: {
                    callbacks: {
                      label: (context) => {
                        const dataset = chartData.datasets[context.datasetIndex];
                        const value = dataset.data[context.dataIndex];
                        const label = chartData.labels[context.dataIndex];
                        const total = dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(2);
                        return `${label}: ${percentage}%`;
                      }
                    }
                  }
                }
              }
            });
          });
        </script>
        <style>
          .custom-container {
            background-color: var(--container-color, #f8f9fa);
            border-radius: 10px;
            padding: 5px;
            text-align: center;
            margin-bottom: 5px;
            color: black;
          }
        </style>
        <script>
          function redirectTo(url) {
            window.location.href = url;
          }
        </script>

        <div class="container">
          <h1><i class="fas fa-chart-line"></i> Tabla de inversiones en insumos cultivos</h1>
          <div class="filters">
            <div class="row">
              <div class="col">



                <form action="pdf/formato_pdf_inversion_cultivo.php" id="formato_pdf_inversion_cultivo" method="post">
                  <input type="hidden" name="inv" value="<?php echo $inversion_cultivos;?> ">
                  <input type="hidden" name="total_inv" id="p250">
                  <input type="hidden" name="ingreso_poc" id="p350">
                  <input type="hidden" name="promedio" id="p300">
                  <label for="startDate" class="form-label">Desde:</label>
                  <input type="date" id="startDate" name="fechaInicio" class="form-control">
                </div>
                <div class="col">
                  <label for="endDate" class="form-label">Hasta:</label>
                  <input type="date" id="endDate" name="fechaFinal" class="form-control">

                </form>
              </div>

              <div class="col" style="margin-bottom:5%;">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <!-- <button onclick="filterSales()" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button> -->
                    <button id="p150" onclick="filterSales()" class="btn btn-primary" style="position:relative; top:32px;"><i class="fas fa-filter"></i>  Filtrar
                    </button>
                  </div>
                  <div id="p10" >
                   <!--  <button type="submit" form="formato_pdf_inversion_cultivo"  class="btn" style="background-color: rgb(255, 165, 0); position: absolute; left: 40%; top:30px;">
                      <i class="fas fa-print"></i>Imprimir
                    </button> -->
                    <button type="submit" form="formato_pdf_inversion_cultivo" style="position: absolute; left: 40%; top: 18px;"  class="btn" title="Imprimir">
                      <img src="icon/icon-pdf.jpg" style="height: 50px;" viewBox="0 0 512 512">¹
                    </button>
                  </div>
                </div>
              </div>



            </div>
          </div>
          <div class="row justify-content-center" style="">
            <div class="col-lg-3">
              <div class="card bg-info text-white rounded">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div class="icon">
                      <i class="fas fa-chart-bar fa-3x"></i>
                    </div>
                    <div class="content ml-3">
                      <h5 class="card-title">Inversión en %</h5>
                      <p class="card-text text-black font-weight-bold" id="total2Amount"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-3">
              <div class="card bg-warning text-white rounded">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div class="icon">
                      <i class="fas fa-chart-pie fa-3x"></i>
                    </div>
                    <div class="content ml-3">
                      <h5 class="card-title">Total de Inversión</h5>
                      <p class="card-text text-black font-weight-bold" id="total4Amount"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-3">
              <div class="card" style="background-color: #46f0f3; color: white;">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div class="icon">
                      <i class="fas fa-chart-line fa-3x"></i>
                    </div>
                    <div class="content ml-3">
                      <h5 class="card-title">Promedio</h5>
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
                <th><i class="fas fa-money-bill"></i>Inversión Fertilizante</th>
                <th><i class="fas fa-chart-line"></i>Inversión Semillas</th>
                <th><i class="fas fa-chart-line"></i>Inversión Fungicida</th>
                <th><i class="fas fa-chart-line"></i>Inversión Equipos</th>
                <th><i class="fas fa-chart-line"></i>Total invertido</th>
              </tr>
            </thead>
            <tbody id="salesData">
              <!-- Aquí se cargarán los datos de ventas filtrados -->
            </tbody>
            <tfoot>
              <tr>
                <td colspan="5"></td>
                <td id="totalAmount"></td>
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
      xhr.onreadystatechange = function () {
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
      xhr.open("GET", "data_cultivos.php", true);
      xhr.send();
    }


    function displaySales(sales) {
      const salesTable = document.getElementById('salesData');
      const totalAmount = document.getElementById('totalAmount');
      const total2Amount = document.getElementById('total2Amount');
      const total3Amount = document.getElementById('total3Amount');
      const total4Amount = document.getElementById('total4Amount');
      const total5Amount = document.getElementById('total4Amount');
  // Vaciar la tabla y los totales
  salesTable.innerHTML = '';
  totalAmount.textContent = '';
  total2Amount.textContent = '';
  total3Amount.textContent = '';
  total4Amount.textContent = '';
  total5Amount.textContent = '';
  // Verificar si hay ventas para mostrar
  if (sales.length === 0) {
    // Mostrar un mensaje de que no hay ventas
    const row = document.createElement('tr');
    const messageCell = document.createElement('td');
    messageCell.setAttribute('colspan', '20');
    messageCell.textContent = 'No se encontraron ventas en el rango de fechas especificado';

     //bottom ---> print
     document.getElementById('p10').style.display = 'none';

     row.appendChild(messageCell);
     salesTable.appendChild(row);
     return;

   }else{

    document.getElementById('p10').style.display = 'block';

// --------------------------------------------------- ?>


  }

  let totalCantidadAnimales = 0;
  let totalGanancia = 0;

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
    const amountCe3 = document.createElement('td');
    const totalCell = document.createElement('td');
    const total9 = document.createElement('td');

    dateCell.textContent = sale.fecha;
    productCell.textContent = new Intl.NumberFormat("de-DE").format(sale.fertilizante);
    amountCell.textContent = new Intl.NumberFormat("de-DE").format(sale.funguisida);
    amountCe3.textContent = new Intl.NumberFormat("de-DE").format(sale.semillas);
    total9.textContent = new Intl.NumberFormat("de-DE").format(sale.equipos);

    let totalAmount = parseFloat(sale.fertilizante) + parseFloat(sale.funguisida) + parseFloat(sale.semillas)+parseFloat(sale.equipos);

    totalCell.textContent = new Intl.NumberFormat("de-DE").format(totalAmount);;

    row.appendChild(dateCell);
    row.appendChild(productCell);
    row.appendChild(amountCe3);
    row.appendChild(amountCell);
    row.appendChild(total9);
    row.appendChild(totalCell);
    salesTable.appendChild(row);

    const rowCount = salesTable.rows.length;
    const total = sales.reduce((accumulator, sale) => accumulator + parseInt(sale.fertilizante), 0);
    const total1 = sales.reduce((accumulator, sale) => accumulator + parseInt(sale.funguisida), 0);
    const total2 = sales.reduce((accumulator, sale) => accumulator + parseInt(sale.semillas), 0);
    const total4 = sales.reduce((accumulator, sale) => accumulator + parseInt(sale.equipos), 0);

    totaAmount25 = total + total1 + total2+total4;


  // mandar Form ---> print
  $('#p250').val(totaAmount25);
  $('#p300').val(totaAmount25/rowCount);


  total4Amount.textContent = "Total: " + totaAmount25.toLocaleString();
  const totalConPorcentaje = totaAmount25 * 0.2;

  // mandar Form ---> print
  $('#p350').val(totalConPorcentaje);


  total2Amount.textContent = "Total: " + totalConPorcentaje.toLocaleString();
  totaAmount26=totaAmount25 /rowCount;

  var p1x1 = parseInt(totalConPorcentaje);
  var p2x2 = parseInt(rowCount);
  var toll = (p1x1/p2x2);

  total3Amount.textContent = "Total : " + totaAmount26.toLocaleString();
});


}
// ...
    // Resto del código JavaScript...

    // Llamar a la función para mostrar los datos de ventas
    filterSales();
  </script>  

</section>
</section>

</main><!-- End #main -->

<!-- ======= Footer ======= -->
<?php include("footer.php");
?>
<script src="assets/js/main.js"></script>

</body>

</html>