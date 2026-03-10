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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50vh;
        }
        .container {
        }
        .row {
            display: flex;
            justify-content: space-between;
            margin-left: 5%; /* Ajusta el margen izquierdo para centrar los divs */
        }

        .custom-card {
            height: 160px;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>
   <div class="container">
    <h1 style="text-align: center;">General</h1>
     <div class="row">
            <div class="col-lg-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title">Espacios</h5>
                        <p class="card-text">Cantidad</p>
                        <p class="card-text"><a href="Espacios.php" class="btn btn-primary">Detalles</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title">Equipos</h5>
                        <p class="card-text">Cantidad</p>
                        <p class="card-text"><a href="equipos.php" class="btn btn-primary">Detalles</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title">Empleados</h5>
                        <p class="card-text">Cantidad</p>
                        <p class="card-text"><a href="empleados.php" class="btn btn-primary">Detalles</a></p>
          
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php include_once("footer.php"); ?>

</body>
</html>