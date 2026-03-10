
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
if (!isset($_SESSION['Acceso'])) { 
  $_SESSION['Acceso'] = true;
  // Mostrar el modal de bienvenida
  echo '
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById("myModal"), {
          keyboard: false
        });
        myModal.show();
      });
    </script>
  ';
}
?>

<?php include_once("header.php")  ?>
<?php include_once("Sidebar.php") ?>

<style>
.carousel-item {
  position: relative;
  z-index: 1; 
}

.carousel-item .overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 37%;
  height: 12%;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 2; 
 max-width: 1410px; transform: translateX(400px);
 font-size: 40px;
 font-weight: bold;
}

#img1 {
  /*background-color: rgba(255, 255, 255, 0.4);*/
  right: -95%;
  top: 15%;
}

#img2 {
  background-color: rgba(255, 255, 255, 0.4);
  right: -95%;
  top: 700%;
  /*padding: 10px;*/
}

#img3 {
  /*background-color: rgba(255, 255, 255, 0.4);*/
  right: -100%;
  top: 15%;
}

#img4 {
  /*background-color: rgba(255, 255, 255, 0.4);*/
  right: -105%;
  top: 15%;
}

#img5 {
  background-color: rgba(255, 255, 255, 0.4);
  right: -100%;
  top: 15%;
}

.carousel-item .overlay img {
  max-width: 100%;
  max-height: 100%;
}


.carousel-item img {
  position: relative;
  z-index: 1; 
}

</style>

<!-- Modal de bienvenida -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-white text-center" style="background-color:#69AB3C;">
        <h5 class="modal-title" id="myModalLabel">
          <i class="bi bi-person-fill"></i> ¡Bienvenido(a)!
        </h5>
      </div>
      <div class="modal-body">
        <p class="fw-bold">¡Hola! Bienvenido(a) a nuestro sistema Agrícola.</p>
        <p>Estamos encantados de que seas parte de nuestra comunidad y esperamos brindarte una gran experiencia.</p>
        <div class="text-center">
          <i class="af af-hand-wave fs-1"></i>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Carrusel de imágenes -->
<div id="myCarousel" class="carousel slide mx-auto mt-5" data-bs-ride="carousel" style="max-width: 1410px; transform: translateX(150px);">
  <!-- Indicadores -->
  <ol class="carousel-indicators">
    <li data-bs-target="#myCarousel" data-bs-slide-to="0" class="active"></li>
    <li data-bs-target="#myCarousel" data-bs-slide-to="1"></li>
    <li data-bs-target="#myCarousel" data-bs-slide-to="2"></li>
    <li data-bs-target="#myCarousel" data-bs-slide-to="3"></li>
    <li data-bs-target="#myCarousel" data-bs-slide-to="4"></li>
  </ol>

  <!-- Slides -->
  <div class="carousel-inner">

    <div class="carousel-item active">
      <div class="overlay">
        <img id="img1" src="imagen/logo11.png" alt="" class="rounded">
      </div>
      <img src="imagen/imagen224.png" alt="Imagen 1" class="d-block w-100 rounded">
    </div>

    <div class="carousel-item">
      <div class="overlay">
        <img id="img3" src="imagen/logo11.png" alt="HACIENDA LOS TUCUPIDOS" class="rounded">
      </div>
      <img src="imagen/imagen222.jpg" alt="Imagen 3" class="d-block w-100 rounded">
    </div>

    <div class="carousel-item">
      <div class="overlay">
        <img id="img4" src="imagen/logo11.png" alt="HACIENDA LOS TUCUPIDOS" class="rounded">
      </div>
      <img src="imagen/imagen223.jpg" alt="Imagen 4" class="d-block w-100 rounded">
    </div>

    <div class="carousel-item">
      <div class="overlay">
        <img id="img2" src="imagen/logo11.png" alt="HACIENDA LOS TUCUPIDOS" class="rounded">
      </div>
      <img src="imagen/imagen221.jpg" alt="Imagen 2" class="d-block w-100 rounded">
    </div>


    <div class="carousel-item">
      <div class="overlay">
        <img id="img5" src="imagen/logo11.png" alt="HACIENDA LOS TUCUPIDOS" class="rounded">
      </div>
      <img src="imagen/imagen225.png" alt="Imagen 5" class="d-block w-100 rounded">
    </div>



  </div>

  <!-- Controles de navegación -->
  <a class="carousel-control-prev" href="#myCarousel" role="button" data-bs-slide="prev">
    <span class="carousel-control-prev-icon rounded-circle" aria-hidden="true"></span>
    <span class="visually-hidden">Anterior</span>
  </a>
  <a class="carousel-control-next" href="#myCarousel" role="button" data-bs-slide="next">
    <span class="carousel-control-next-icon rounded-circle" aria-hidden="true"></span>
    <span class="visually-hidden">Siguiente</span>
  </a>
</div>
<?php include_once("footer.php"); ?>
