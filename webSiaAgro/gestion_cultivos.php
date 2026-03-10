<?php session_start(); ?>
<?php if (!isset($_SESSION['Aceso'])) {
  header("location: index.html");
} ?>
<?php include_once("header.php") ?>
<?php include_once("Sidebar.php") ?>

<!DOCTYPE html>
<html>

<head>
  <style>
    body {
      margin: 0;
      padding: 0;
    }

    .container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      margin-top: 100px;
      /* Agrega el  margen superior para mover los divs hacia abajo */
      margin-left: 20%;
      /* Ajusta el margen izquierdo para centrar los divs */
      margin-right: 10%;
      /* Ajusta el margen derecho para centrar los divs */
    }

    .col-lg-3 {
      width: 30%;
      /* Ajusta el ancho de los divs para organizarlos en filas y columnas */
      box-sizing: border-box;
      padding: 10px;
      margin-bottom: 40px;
    }

    .row {
      display: flex;
      justify-content: center;
      /* Cambio en la alineación para centrar los cuadros */
      margin-left: 5%;
      /* Ajusta el margen izquierdo para centrar los divs */
    }

    footer {
      height: 50px;
      margin-top: 60px;
      /* Agrega margen superior al footer */
    }
  </style>
</head>

<body>


  <div class="container">
    <nav style="position:relative; left: -21%;">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">General</li>
        <li class="breadcrumb-item active">Gestion Cultivos</li>
      </ol>
    </nav>
    <h1 style="position:relative; left:40px;">Gestión Cultivos</h1>
    <div class="row">
      <div class="col-lg-3">
        <div class="card">

          <div class="card-body">
            <h5 class="card-title">Siembra</h5>
            <h6 class="card-subtitle mb-2 text-muted">Cantidad </h6>
            <p class="card-text"></p>
            <p class="card-text"><a href="cultivos.php" class="btn btn-primary">Detalles</a></p>
          </div>

        </div><!-- End Card with titles, buttons, and links -->
      </div>
      <div class="col-lg-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Actividades</h5>
            <h6 class="card-subtitle mb-2 text-muted">Cantidad</h6>
            <p class="card-text"></p>
            <p class="card-text"><a href="actividades_cultivos.php" class="btn btn-primary">Detalles</a></p>

          </div>
        </div><!-- End Card with titles, buttons, and links -->
      </div>
      <div class="col-lg-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Insumos fertilizantes</h5>
            <h6 class="card-subtitle mb-2 text-muted">Cantidad</h6>
            <p class="card-text"></p>
            <p class="card-text"><a href="fertilizante.php" class="btn btn-primary">Detalles</a></p>
          </div>
        </div><!-- End Card with titles, buttons, and links -->
      </div>
      <div class="col-lg-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Insumos Agroquimicos</h5>
            <h6 class="card-subtitle mb-2 text-muted">Cantidad</h6>
            <p class="card-text"></p>
            <p class="card-text"><a href="funguisidas.php" class="btn btn-primary">Detalles</a></p>
          </div>
        </div><!-- End Card with titles, buttons, and links -->
      </div>
      <div class="col-lg-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Contol fertilizante</h5>
            <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
            <p class="card-text"></p>
            <p class="card-text"><a href="control_fertilizante.php" class="btn btn-primary">Detalles</a></p>
          </div>
        </div><!-- End Card with titles, buttons, and links -->
      </div>
      <div class="col-lg-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Control agroquimico</h5>
            <h6 class="card-subtitle mb-2 text-muted">Cantidad</h6>
            <p class="card-text"></p>
            <p class="card-text"><a href="control_plagas.php" class="btn btn-primary">Detalles</a></p>
          </div>
        </div><!-- End Card with titles, buttons, and links -->
      </div>
      <div class="col-lg-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">semillas</h5>
            <h6 class="card-subtitle mb-2 text-muted">Cantidad</h6>
            <p class="card-text"></p>
            <p class="card-text"><a href="semillas" class="btn btn-primary">Detalles</a></p>
          </div>
        </div><!-- End Card with titles, buttons, and links -->
      </div>
    </div>
  </div>
</body>
</html>

<?php include_once("footer.php"); ?>

</body>
</html>