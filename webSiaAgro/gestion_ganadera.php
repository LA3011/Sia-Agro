<?php session_start(); ?>
<?php if (!isset($_SESSION['Aceso'])) {
  header ("location: index.html"); 
} 
      include_once("header.php") ?>
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
      /* Agrega margen superior para mover los divs hacia abajo */
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

    footer {
      height: 50px;
      margin-top: 60px;
      /* Agrega margen superior al footer */
    }
  </style>
  
</head>
<body>
  <div class="container" style="position:relative; left:120px;">
    <nav style="position:relative; left: -30%;">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">General</li>
        <li class="breadcrumb-item active">Gestion Gandera</li>
      </ol>
    </nav>

    <h1 style="position:relative; left:-65px;">Gestión Ganadera</h1>
    <div class="row">
      <div class="col-lg-3">
        <div class="card">

          <div class="card-body" style="">
            <h5 class="card-title" style="">Animales</h5>
            <h6 class="card-subtitle mb-2 text-muted">Cantidad </h6>
            <p class="card-text"></p>
            <p class="card-text"><a href="animales.php" class="btn btn-primary">Detalles</a></p>

          </div>
        </div><!-- End Card with titles, buttons, and links -->
      </div>
      <div class="col-lg-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Datos Veterinarios</h5>
            <h6 class="card-subtitle mb-2 text-muted">Cantidad</h6>
            <p class="card-text"></p>
            <p class="card-text"><a href="datos_veterinarios.php" class="btn btn-primary">Detalles</a></p>

          </div>
        </div><!-- End Card with titles, buttons, and links -->
      </div>
      <div class="col-lg-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Dieta Animal</h5>
            <h6 class="card-subtitle mb-2 text-muted">Cantidad</h6>
            <p class="card-text"></p>
            <p class="card-text"><a href="dieta_animal.php" class="btn btn-primary">Detalles</a></p>
          </div>
        </div><!-- End Card with titles, buttons, and links -->
      </div>
      <div class="col-lg-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Reproducciones</h5>
            <h6 class="card-subtitle mb-2 text-muted">Cantidad</h6>
            <p class="card-text"></p>
            <p class="card-text"><a href="reproducciones.php" class="btn btn-primary">Detalles</a></p>
          </div>
        </div><!-- End Card with titles, buttons, and links -->
      </div>
      <div class="col-lg-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Animales en produccion de leche</h5>
            <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
            <p class="card-text"></p>
            <p class="card-text"><a href="animales_produccion_leche.php" class="btn btn-primary">Detalles</a></p>
          </div>
        </div><!-- End Card with titles, buttons, and links -->
      </div>
      <div class="col-lg-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Animales en produccion de Carne</h5>
            <h6 class="card-subtitle mb-2 text-muted">Cantidad</h6>
            <p class="card-text"></p>
            <p class="card-text"><a href="animales_produccion_carne.php" class="btn btn-primary">Detalles</a></p>
          </div>
        </div><!-- End Card with titles, buttons, and links -->
      </div>
      <div class="col-lg-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Animales en venta</h5>
            <h6 class="card-subtitle mb-2 text-muted">Cantidad</h6>
            <p class="card-text"></p>
            <p class="card-text"><a href="Animales_venta.php" class="btn btn-primary">Detalles</a></p>
          </div>
        </div><!-- End Card with titles, buttons, and links -->
      </div>
      <div class="col-lg-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Animales vendidos</h5>
            <h6 class="card-subtitle mb-2 text-muted">Cantidad</h6>
            <p class="card-text"></p>
            <p class="card-text"><a href="#" class="btn btn-primary">Detalles</a></p>
          </div>
        </div><!-- End Card with titles, buttons, and links -->
      </div>
      <div class="col-lg-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Animales en crianza</h5>
            <h6 class="card-subtitle mb-2 text-muted">Cantidad</h6>
            <p class="card-text"></p>
            <p class="card-text"><a href="#" class="btn btn-primary">Detalles</a></p>
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