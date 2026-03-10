<!DOCTYPE html>
<html lang="en">

<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->
    <title>SISTEMA AGRONOMO</title>
    <meta name="Siembra" content="">
    <meta name="Gestion de cultivos" content="">
    <meta name="UPTA" content="">
    <!-- bootstrap css -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="../css/style.css">
    <!-- Responsive-->
    <link rel="stylesheet" href="../css/responsive.css">
    <!-- fevicon -->
    <link rel="stylesheet" href="../css/jquery.mCustomScrollbar.min.css">
    <!-- Tweaks for older IEs-->
    <link rel="stylesheet" href="../https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
    <!--[if lt IE 9]>
      <script src="../https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="../https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
</head>
<!-- body -->
<body class="main-layout ">
    <!-- loader  -->
    <header>
        <!-- header inner -->
        <div class="header" style="display:inline-block; width: 110%;">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col logo_section">
                        <div class="full">
                            <div class="center-desk">
                                <div class="logo">
                                    <a href="../index.html"><img src="../imagen/logo11.png" alt="#"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                        <div class="location_icon_bottum_tt">
                             <ul>
                                <li><img src="../icon/loc1.png" />Aragua</li>
                                <li><img src="../icon/email1.png" />lostucupidos@gmail.com</li>
                                
                            </ul>
                        </div>
                    </div>
                </div>
                
                </div>
            </div>
        </div>
        <!-- end header inner -->
    </header>
    <!-- end header -->
 <section class="slider_section">
  <div id="myCarousel" class="carousel slide banner-main" data-ride="carousel">
    <div class="carousel-inner" style="display:inline-block; width: 110%;">
      <div class="carousel-item active">
        <img class="first-slide" src="../images/banner.jpg" alt="First slide">
        <div class="container">
          <div class="carousel-caption relative">
            <h1>SISTEMA AGRONOMO</h1>
            <span>LOS TUCUPIDOS</span>
            <p>SISTEMA DE INFORMACION PARA LA GESTION DE SIEMBRA</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <footer style="background-color:#30880D; position: relative; width: 110%; top:-29px; height:9vh; ">
      <div class="copyright">
        <div class="container">
          <p>© 2023 Derechos Reservados. <a href="https://html.design/"> Sistema para U.P.T. Estado Aragua</a></p>
        </div>
      </div>
    </footer>
</section>

<!-- Este div define una ventana modal con un fondo oscuro y se utiliza para mostrar un mensaje de que la clave ha sido reestablecida exitosamente -->
<div class="modal fade modal-static" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">

  <!-- Este div define la estructura y el tamaño de la ventana modal -->
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">

    <!-- Este div contiene el contenido de la ventana modal -->
    <div class="modal-content">

      <!-- Este div define el encabezado de la ventana modal y contiene el título "Recuperacion" -->
      <div class="modal-header text-center text-white" style="background-color: #0d6efd;">
        <h4 class="modal-title w-100 font-weight-bold text-center mx-auto">Recuperacion</h4>
      </div>

      <!-- Este formulario se utiliza para enviar los datos del formulario a la página "index.html" utilizando el método "POST" -->
      <form class="modal-content animate" action="../index.html" method="post">
       
        <!-- Este div contiene el mensaje que indica que la clave ha sido reestablecida exitosamente -->
        <div class="modal-body">
          <p class="text-center font-weight-bold">Su clave ha sido reestablecida exitosamente.</p>
        </div>

        <!-- Este div contiene el botón para continuar -->
        <div class="modal-footer justify-content-center">
          <button class="btn btn-success" type="submit" name="submit" style="width: 40%;">Continuar</button>
        </div>

      </form>

    </div>

  </div>

</div>
<!-- end footer -->
<!-- Javascript files-->
<script src="../js/jquery.min.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/jquery-3.0.0.min.js"></script>
<script src="../js/plugin.js"></script>
<!-- sidebar -->
<script src="../js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="../js/custom.js"></script>
<!-- javascript -->
<script src="../js/owl.carousel.js"></script>
<script src="https:cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>


<script>
  $(document).ready(function() {
    $('#modalLoginForm').modal('show');
  });
</script>
</body>
</html>