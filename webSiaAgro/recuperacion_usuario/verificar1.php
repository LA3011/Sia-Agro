<?php
session_start();
include("../conexion/conexion.php"); // Archivo que contiene la conexión a PostgreSQL
$conn = cconexion::ConexionBD(); // Asegúrate de que esta función devuelva una conexión PDO válida

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['Usuario'] ?? null;

    if ($usuario) {
        try {
            // Consulta para verificar si el usuario existe en la base de datos
            $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE \"Usuario\" = :usuario";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado['total'] > 0) {
                // Si el usuario existe, guardar el nombre de usuario en una variable de sesión
                $_SESSION['usuario'] = $usuario;
                header("Location: recuperacion.php");
                exit;
            } else {
                // Si el usuario no existe, mostrar un mensaje de error
                $_SESSION["user_not_exist"] = "El usuario no existe.";
                header("Location: verificar1.php");
                exit;
            }
        } catch (PDOException $e) {
            // Manejo de errores de la base de datos
            $_SESSION["user_not_exist"] = "Error al verificar el usuario: " . $e->getMessage();
            header("Location: verificar1.php");
            exit;
        }
    } else {
        // Si no se proporciona un usuario, mostrar un mensaje de error
        $_SESSION["user_not_exist"] = "Por favor, ingrese un nombre de usuario.";
        header("Location: verificar1.php");
        exit;
    }
}
?>

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
  <!-- Scrollbar Custom CSS -->
  <link rel="stylesheet" href="../css/jquery.mCustomScrollbar.min.css">
  <!-- Tweaks for older IEs-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
</head>

<body class="main-layout">
  <header style="display:inline-block; width: 110%;">
    <div class="header">
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
  </header>

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
    <footer style="background-color:#30880D; position: relative; width: 110%; top:-29px; height:9vh;">
      <div class="copyright">
        <div class="container">
          <p>© 2023 Derechos Reservados. <a href="https://html.design/"> Sistema para U.P.T. Estado Aragua</a></p>
        </div>
      </div>
    </footer>
  </section>

  <div class="modal fade modal-static" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header text-center text-white" style="background-color: #0d6efd;">
          <h4 class="modal-title w-100 font-weight-bold">Recuperación de cuenta</h4>
        </div>
        <form class="modal-content animate" action="verificar1.php" method="post">
          <div class="modal-body mx-3">
            <div class="form-group text-center">
              <label for="usuario" style="color: green; text-align: center;"><b>Usuario</b></label>
              <input type="text" class="form-control form-control-sm" id="Usuario" placeholder="Usuario" name="Usuario" required>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-success">Continuar</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='../index.html'">Regresar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="container">
    <?php 
    if (isset($_SESSION["user_not_exist"])) { 
      ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-octagon me-1"></i>
        <?php echo $_SESSION["user_not_exist"]; 
        session_unset(); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php
    }
    ?>
  </div>

  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.bundle.min.js"></script>
  <script src="../js/jquery-3.0.0.min.js"></script>
  <script src="../js/plugin.js"></script>
  <script src="../js/jquery.mCustomScrollbar.concat.min.js"></script>
  <script src="../js/custom.js"></script>
  <script src="../js/owl.carousel.js"></script>
  <script src="https:cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#modalLoginForm').modal('show');
    });
  </script>
</body>
</html>