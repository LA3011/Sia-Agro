
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
  <link rel="stylesheet"type="text/css" href="css_personalizado/estilo_bitacora.css">
  
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
  </head>

  <style type="text/css">
    #username{
      margin-bottom: 6px;
    }
    .auto > div{
      padding-left: 4px;
      display: block;
      background-color: ;
      color: black;
      border-radius: 6px;
    }
    .auto > div:hover{
      background-color: #DBDEE6;
      cursor: pointer;
      padding-left: 10px;
    }
  </style>

  
  <body>
    <main id="main" class="main">
      <section class="section">
        <nav style="">
          <ol class="breadcrumb">
            <li class="breadcrumb-item" style="">Configuración</li>
            <li class="breadcrumb-item">Ajustes</li>
            <li class="breadcrumb-item active">Bitacora</li>
          </ol>
        </nav>

        <script>
          function redirectTo(url) {
            window.location.href = url;
          }
        </script>

        <div class="container">
          <h1> Bitacora Filtrada</h1>
          <div class="filters">
            <div class="row">
              <div class="col">

                <form action="pdf/formato_pdf_bitacora.php" id="formato_pdf_bitacora" method="post">
                  <input type="hidden" name="ingreso_generado" id="xp1">
                  <input type="hidden" name="ganancia" id="xp2">

                  <label for="startDate" class="form-label">Usuario:</label>
                  <input type="text" id="username" name="usuario" class="form-control">
                  <div id="autocompleteResults" class="auto" style="border: 4px solid #BCD6FF; border-radius: 6px;"></div>
                </div>
                <div class="col">
                  <label for="startDate" class="form-label">Desde:</label>
                  <input type="date" id="startDate"  name="fechaInicio" class="form-control">
                  <div id="autocompleteResults" class="auto" style="border: 4px solid #BCD6FF; border-radius: 6px; margin-top: 6px;"></div>
                </div>
                <div class="col">
                  <label for="endDate" class="form-label">Hasta:</label>
                  <input type="date" id="endDate"  name="fechaFinal" class="form-control">
                  <div id="autocompleteResults" class="auto" style="border: 4px solid #BCD6FF; border-radius: 6px; margin-top: 6px;"></div>
                </form>

              </div>
              <div class="col">
                <div class="d-flex justify-content-between align-items-center">
                  <div>

                    <button onclick="filterSales()" class="btn btn-primary" style="position: absolute; top: 32px;"><i class="fas fa-filter"></i> Filtrar</button>
                  </div>
                  <div>

                    <div id="p10">

                     <!--  <button type="submit" form="formato_pdf_bitacora"  class="btn" style="position: absolute;top: 32px; background-color: rgb(255, 165, 0);"><i class="fas fa-print"></i> 
                        Imprimir
                      </button> -->

                      <button type="submit" form="formato_pdf_bitacora" style="position: absolute; left: 40%; top: 18px;"  class="btn" title="Imprimir">
                        <img src="icon/icon-pdf.jpg" style="height: 50px;" viewBox="0 0 512 512">
                      </button>

                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <table id="salesTable" class="table table-striped" style="text-align: center;">
          <thead>
            <tr>
              <th><i class="bi bi-people-fill"></i> Usuario</th>
              <th><i class="fas fa-calendar-alt"></i> Fecha</th>
              <th><i class="bi bi-clock-fill"></i> Hora</th>
              <th><i class="bi bi-check-square-fill"></i> Accion Realizada</th>
              <th><i class="bi bi-file-earmark-text-fill"></i> Registro</th>
              <th><i class="bi bi-layout-text-window-reverse"></i> Tabla Modificada</th>
            </tr>
          </thead>

          <tbody id="salesData" style="text-align: center;">
            <!-- Aquí se cargarán los datos de bitacoras filtrados -->
          </tbody>
          <tfoot>
            <tr>
              <!-- <td colspan="2">Total:</td> -->
              <td id="totalAmount"></td>
              <td colspan="1"></td>
              <td id="totalGanancia"></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <script>

// Función para realizar la solicitud de autocompletado
function autocomplete() {
  const searchTerm = document.getElementById('username').value;

  // Realizar la solicitud AJAX al archivo autocomplete.php
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function(){
    if (xhr.readyState === 4 && xhr.status === 200) {
      const users = JSON.parse(xhr.responseText);
      displayAutocompleteResults(users);
    }
  };
  xhr.open("GET", "autocomplete.php?term=" + searchTerm, true);
  xhr.send();
}

// Función para mostrar los resultados del autocompletado
function displayAutocompleteResults(users) {
  const autocompleteResults = document.getElementById('autocompleteResults');
  
  // Vaciar los resultados anteriores
  autocompleteResults.innerHTML = '';

  // Mostrar los usuarios coincidentes en el elemento de resultados
  users.forEach(user => {
    const result = document.createElement('div');
    result.textContent = user;

    // Agregar un controlador de eventos para seleccionar el usuario al hacer clic en él
    result.addEventListener('click', function() {
      document.getElementById('username').value = user;
      autocompleteResults.innerHTML = '';
    });

    autocompleteResults.appendChild(result);
  });
}

// Agregar un controlador de eventos para el evento "input" en el campo de usuario
document.getElementById('username').addEventListener('input', autocomplete);


// Función para filtrar por rango de fechas y usuario
// Función para filtrar por rango de fechas y usuario
function filterSales() {
const startDate = document.getElementById('startDate').value;
const endDate = document.getElementById('endDate').value;
const username = document.getElementById('username').value.trim(); // Obtener el valor del campo de usuario y eliminar espacios en blanco

// Validar si el campo de usuario está vacío
if (username === '') {
  alert('Por favor, ingrese un usuario válido.');
  return; // Salir de la función si el campo de usuario está vacío
}

// Realizar la solicitud AJAX al archivo data_bitacora.php
const xhr = new XMLHttpRequest();
xhr.onreadystatechange = function(){
  if (xhr.readyState === 4 && xhr.status === 200) {
    const salesData = JSON.parse(xhr.responseText);
    // Filtrar los datos según las fechas y el usuario seleccionados
    const filteredSales = salesData.filter(sale => {
      const saleDate = new Date(sale.Fecha); 
      const saleUsername = sale.Usuario.toLowerCase();
      return saleDate >= new Date(startDate) && saleDate <= new Date(endDate) && saleUsername.includes(username.toLowerCase());
    });
    // Mostrar los datos filtrados en la tabla
    displaySales(filteredSales);
  }
};
xhr.open("GET", "data_bitacora.php", true);
xhr.send();
}

// Llamar a la función para mostrar los datos de bitácoras solo si se proporciona un usuario
document.getElementById('filterButton').addEventListener('click', filterSales);


function displaySales(sales) {
  const salesTable = document.getElementById('salesData');
  const totalAmount = document.getElementById('totalAmount');

  // Vaciar la tabla y el total
  salesTable.innerHTML = '';
  totalAmount.textContent = '';

  // Verificar si hay datos a mostrar
  if (sales.length === 0) {
    // Mostrar un mensaje de que no hay usuraio
    const row = document.createElement('tr');
    const messageCell = document.createElement('td');
    messageCell.setAttribute('colspan', '20');
    messageCell.textContent = 'No se encontro en el rango de fechas y usuario especificado';
    document.getElementById('p10').style.display = 'none';
    row.appendChild(messageCell);
    salesTable.appendChild(row);
    return;
  } else {


  document.getElementById('p10').style.display = 'block';



}

  // Recorrer y agregar filas a la tabla
  sales.forEach(sale => {
    // Verificar si  tiene una fecha válida
    if (!sale.Fecha) {
      return;
    }

    const row = document.createElement('tr');
    const dateCell = document.createElement('td');
    const productCell = document.createElement('td');
    const hourCell = document.createElement('td'); // Agregar celda para la hora
    const actionCell = document.createElement('td');
    const registerCell = document.createElement('td');
    const modifiedTableCell = document.createElement('td');
    const userCell = document.createElement('td');

    // Formatear la fecha y la hora
    const saleDate = new Date(sale.Fecha);
    const formattedDate = saleDate.toLocaleDateString();
    const formattedHour = sale.Hora;

    dateCell.textContent = formattedDate;
    hourCell.textContent = formattedHour;
    actionCell.textContent = sale.Accion;
    registerCell.textContent = sale.Numero_Registro;
    modifiedTableCell.textContent = sale.Tabla_Modificada;
    userCell.textContent = sale.Usuario;

    row.appendChild(userCell);
    row.appendChild(dateCell);
    row.appendChild(hourCell);
    row.appendChild(actionCell);
    row.appendChild(registerCell);
    row.appendChild(modifiedTableCell);
    salesTable.appendChild(row);
  });
}

// Llamar a la función para mostrar los datos de bitácoras
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