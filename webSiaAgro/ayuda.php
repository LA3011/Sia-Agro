<?php session_start();            ?>
<?php
if(!isset($_SESSION['Aceso'])){
  header("location: index.html");
}
?>
<?php include_once("header.php")  ?>
<?php include_once("Sidebar.php") ?>
<?php
$title1 = 'Realizacion de centro Ayuda <i class="bi bi-skip-end-circle"></i>';
$descripcion1 = "Lorem ipsum dolor sit amet.";

?>
<!DOCTYPE html>
<html>
<head>
  <title>Ayuda</title>
  <link rel="stylesheet" type="text/css" href="css_personalizado/ayuda.css">
</head>
<main id="main" class="main">
  <section class="section">
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item" style="">Configuracion  /  </li>
        <a href="ayuda.php"><li class="breadcrumb-item active" style="color:#172871;">Ayuda</li></a>
      </ol>
    </nav>
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body" style="text-align: center;">
          <h2 class="card-title" style="color:black; font-size:45px; margin-left:-2%;">
            Centro de Ayuda  <i class="bi bi-question-circle"></i>
          </h2>
          <hr style="margin-bottom: 3%; width:79%; margin-left: 10%;">
          <div class="show-check">

<!--             <a href="ayuda-seccion.php?seccion=1" style="color: black;">
              <div class="show1" onclick="">
                <div class="show-descrip" style="width: 89%;">
                  <h2>
                    <strong>Videos de Ayuda <i class="bi bi-play-circle"></i></strong>
                  </h2>
                </div>
              </div>
            </a> -->
            
            <a href="ayuda-seccion.php?seccion=2" style="color:black;">
              <div class="show1" onclick="contenedor4()">
                <div class="show-descrip" style="width: 89%;">
                  <h2>
                    <strong>Perfil <i class="ri-admin-line"></i></strong>
                  </h2>
                </div>
                <br>
              </div>
            </a>

            <a href="ayuda-seccion.php?seccion=3" style="color: black;">
              <div class="show1" onclick="">
                <div class="show-descrip" style="width: 89%;">
                  <h2>
                    <strong>Usuario <i class="ri-group-fill"></i></strong>
                  </h2>
                </div>
              </div>
            </a>

            <a href="ayuda-seccion.php?seccion=4" style="color:black;">
              <div class="show1" onclick="contenedor2()">
                <div class="show-descrip" style="width: 89%;">
                  <h2>
                    <strong>Manejo de Registro <i class="bi bi-journal-text"></i></strong>
                  </h2>
                </div>
              </div>
            </a>
            






          </div>
        </div>
      </div>
    </div>  
  </section>
</main>

<?php include_once("footer.php"); ?>