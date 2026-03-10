<?php session_start();            ?>
<?php
  if(!isset($_SESSION['Aceso'])){
    header("location: index.html");
  }
?>
<?php include_once("header.php")  ?>
<?php include_once("Sidebar.php") ?>

<?php
if( isset($_GET['seccion']) && ($_GET['seccion'] == 2)){
  $title1 = ' Perfil';
  $descripcion1 = "";
}
if( isset($_GET['seccion']) && ($_GET['seccion'] == 3)){
  $title1 = ' Usuario';
  $descripcion1 = "";
}
if( isset($_GET['seccion']) && ($_GET['seccion'] == 4)){
  $title1 = 'Manejos de Registros';
  $descripcion1 = "";
}
if( isset($_GET['seccion']) && ($_GET['seccion'] == 5)){
  $title1 = 'Solucion de Problemas';
  $descripcion1 = "";
}
if( isset($_GET['seccion']) && ($_GET['seccion'] == 6)){
  $title1 = 'Contactos';
  $descripcion1 = "";
}

// <i class="bi bi-play-circle"></i>

?>
<!DOCTYPE html>
<html>
<head>
  <title>Ayuda Sección</title>
  <link rel="stylesheet" type="text/css" href="css_personalizado/ayuda-seccion.css">
</head>
<body>

</body>
</html>
  <main id="main" class="main">
    <section class="section">
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item" style="">
            Configuracion  /  
          </li>
          <a href="ayuda.php">
            <li class="breadcrumb-item" style="color:;">
              Ayuda
            </li>
          </a>
          <a>
            <li class="breadcrumb-item" style="color:#172871;">
              /  <?php echo $title1;?>
            </li>
          </a>
        </ol>
      </nav>
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body" style="text-align: center;">
            <h5 class="card-title" style="color:black; font-size:50px; margin-left:-2%;">
              Centro de Ayuda  <i class="bi bi-question-circle"></i>
            </h5>
            <hr style="margin-bottom: 2%; width:79%; margin-left: 10%;">
            <?php if(isset($_GET['seccion']) && ($_GET['seccion'] == 2)){ ?>
              <h1 style="font-size: 50px; position: relative; left: -20%; color: #0B5ED7;"><?php echo ""; ?></h1><br>
              <div class="show-check">
                <div class="showVideo">
                  <video class="vid" controls>
                    <source src="videos/Manejo_Perfil/CAMBIO_PERFIL.mp4" type="video/mp4">
                    <!-- <source src="20220307_095339." type="video/."> -->
                  </video>
                  <div class="show-descrip" style="width: 89%;">
                    <p>
                      <strong><?php echo "Como cambiar mi Perfil " . '<i class="bi bi-play-circle"> </i><br>'; ?></strong>
                      <?php echo ""; ?>
                    </p>
                  </div>
                </div>
                <div class="showVideo">
                  <video class="vid" controls>
                    <source src="videos/Manejo_Perfil/CREAR_PERFIL.mp4" type="video/mp4">
                    <!-- <source src="20220307_095339." type="video/."> -->
                  </video>
                  <div class="show-descrip" style="width: 89%;">
                    <p>
                      <strong><?php echo "Como Crear un Perfil" . '<i class="bi bi-play-circle"></i><br>'; ?></strong>
                      <?php echo ""; ?>
                    </p>
                  </div>
                </div>
                <div class="showVideo">
                  <video class="vid" controls>
                    <source src="videos/Manejo_Perfil/GESTION_PERFIL.mp4" type="video/mp4">
                    <!-- <source src="20220307_095339." type="video/."> -->
                  </video>
                  <div class="show-descrip" style="width: 89%;">
                    <p>
                      <strong><?php echo "Como puedo Eliminar un Registro " . '<i class="bi bi-play-circle"></i><br>'; ?></strong>
                      <?php echo ""; ?>
                    </p>
                  </div>
                </div>
              </div>
            <?php } ?>
            <?php if(isset($_GET['seccion']) && ($_GET['seccion'] == 3)){ ?>
              <h1 style="font-size: 50px; position: relative; left: -20%; color: #0B5ED7;"><?php echo ""; ?></h1><br>
              <div class="show-check">
                <div class="showVideo">
                  <video class="vid" controls>
                    <source src="videos/Manejo_Usuario/ACCIONES_USUARIO.mp4" type="video/mp4">
                    <!-- <source src="20220307_095339." type="video/."> -->
                  </video>
                  <div class="show-descrip" style="width: 89%;">
                    <p>
                      <strong><?php echo "Acciones a un Usuario " . ' <i class="bi bi-play-circle"></i><br>'; ?></strong>
                      <?php echo ""; ?>
                    </p>
                  </div>
                </div>
                <div class="showVideo">
                  <video class="vid" controls>
                    <source src="videos/Manejo_Usuario/BITACORA.mp4" type="video/mp4">
                    <!-- <source src="20220307_095339." type="video/."> -->
                  </video>
                  <div class="show-descrip" style="width: 89%;">
                    <p>
                      <strong><?php echo "Bitacora " . '<i class="bi bi-play-circle"> </i><br>'; ?></strong>
                      <?php echo ""; ?>
                    </p>
                  </div>
                </div>
                <div class="showVideo">
                  <video class="vid" controls>
                    <source src="videos/Manejo_Usuario/CREAR_INICIAR_USUARIO.mp4" type="video/mp4">
                    <!-- <source src="20220307_095339." type="video/."> -->
                  </video>
                  <div class="show-descrip" style="width: 89%;">
                    <p>
                      <strong><?php echo "Como Crear e Iniciar un Usuario " . '<i class="bi bi-play-circle"></i><br>'; ?></strong>
                      <?php echo ""; ?>
                    </p>
                  </div>
                </div>
              </div>
              <div class="showVideo">
                  <video class="vid" controls>
                    <source src="videos/Manejo_Usuario/RECUPERAR_CONTRASEÑA.mp4" type="video/mp4">
                    <!-- <source src="20220307_095339." type="video/."> -->
                  </video>
                  <div class="show-descrip" style="width: 89%;">
                    <p>
                      <strong><?php echo "  Como Recuperar mi Contraseña " . '<i class="bi bi-play-circle"></i><br>'; ?></strong>
                      <?php echo ""; ?>
                    </p>
                  </div>
                </div>
            <?php } ?>
            <?php if(isset($_GET['seccion']) && ($_GET['seccion'] == 4)){ ?>
              <div class="show-check">
                <div class="showVideo">
                  <video class="vid" controls>
                    <source src="videos/Manejo_Registro/AGREGAR_REGISTRO.mp4" type="video/mp4">
                    <!-- <source src="20220307_095339." type="video/."> -->
                  </video>
                  <div class="show-descrip" style="width: 89%;">
                    <p>
                      <strong><?php echo "Como Agregar un Registro al Sistema" . ' <i class="bi bi-play-circle"></i><br>'; ?></strong>
                      <?php echo ""; ?>
                    </p>
                  </div>
                </div>
                <div class="showVideo">
                  <video class="vid" controls>
                    <source src="videos/Manejo_Registro/EDITAR_REGISTRO.mp4" type="video/mp4">
                    <!-- <source src="20220307_095339." type="video/."> -->
                  </video>
                  <div class="show-descrip" style="width: 89%;">
                    <p>
                      <strong><?php echo "Como Editar un Registro del Sistema " . '<i class="bi bi-play-circle"> </i><br>'; ?></strong>
                      <?php echo ""; ?>
                    </p>
                  </div>
                </div>
                
                <div class="showVideo">
                  <video class="vid" controls>
                    <source src="videos/Manejo_Registro/VER_REGISTRO.mp4" type="video/mp4">
                    <!-- <source src="20220307_095339." type="video/."> -->
                  </video>
                  <div class="show-descrip" style="width: 89%;">
                    <p>
                      <strong><?php echo "Como Visualizar un Registro " . '<i class="bi bi-play-circle"></i><br>'; ?></strong>
                      <?php echo ""; ?>
                    </p>
                  </div>
                </div>
              </div>
            <?php } ?>
            <?php if(isset($_GET['seccion']) && ($_GET['seccion'] == 5)){ ?>
              <div class="card">
                <div class="card-body">
                  <h1 style="color:#0B5ED7;">Solucion de Problemas</h1>
                  <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne2" aria-expanded="false" aria-controls="collapseOne">
                          <h2>¿Por No Puedo Ver ni Ingresar a algunas Opciones?</h2>
                        </button>
                      </h2>
                      <div id="collapseOne2" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                        <div class="accordion-body">
                          <strong>This is the first item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree3" aria-expanded="false" aria-controls="collapseThree">
                          <h2>¿Por que no se puedo Eliminar, Editar, Ver o Imprimir Registros?</h2>
                        </button>
                      </h2>
                      <div id="collapseThree3" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample" style="">
                        <div class="accordion-body">
                          <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne1" aria-expanded="false" aria-controls="collapseOne">
                          <h2>¿Como Puedo Editar mi Informacion?</h2>
                        </button>
                      </h2>
                      <div id="collapseOne1" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                        <div class="accordion-body">
                          <strong>This is the first item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                          <h2>¿Como se Puedo Editar mi foto de Perfil?</h2>
                        </button>
                      </h2>
                      <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                        <div class="accordion-body">
                          <strong>This is the first item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                        </div>
                      </div>
                    </div>
                  </div><!-- End Default Accordion Example -->
                </div>
              </div>
            <?php } ?>
            <?php if(isset($_GET['seccion']) && ($_GET['seccion'] == 6)){ ?>
              <div class="card">
                <div class="card-body">
                  <h1 style="color:#0B5ED7;">Contacto</h1>
                  <div class="row" style="margin-top: 50px;">
                    <div class="col-lg-4" style="background-color: #F6F9FF; display: inline-block;">
                      <div class="info-box card">
                        <br><br>
                        <i class="bi bi-geo-alt" style="font-size: 50px; color: "></i>
                        <h3>Direccion</h3>
                        <p>Av,<br>Calle</p>
                        <br><br>
                      </div>
                    </div>
                    <div class="col-lg-4" style="background-color: #F6F9FF; display: inline-block;">
                      <div class="info-box card">
                        <br><br>
                        <i class="bi bi-telephone" style="font-size: 50px; color: "></i>
                        <h3>Llámanos</h3>
                        <p>+58 412 00 00<br>+58 416 00 00</p>
                        <br><br>
                      </div>
                    </div>
                    <div class="col-lg-4" style="background-color: #F6F9FF; display: inline-block;">
                      <div class="info-box card">
                        <br><br>
                        <i class="bi bi-envelope" style="font-size: 50px; color: "></i>
                        <h3>Correos Electronicos</h3>
                        <p>Upta2024@example.com<br>SiaAgro@example.com</p>
                        <br><br>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>  
    </section>
  </main>

<?php include_once("footer.php"); ?>