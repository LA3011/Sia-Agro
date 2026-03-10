<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - NiceAdmin Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Dashboard - SB Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Dashboard - SB Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: May 30 2023 with Bootstrap v5.3.0
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center" style="background-color: #1c2833; ">

  <div class="d-flex align-items-center justify-content-between">
    <a href="inicio.php" class="logo d-flex align-items-center">
      <!-- <img src="./imagen/imagen4.jpg" alt="La Hacienda los Tucupidos"> -->
      <a href="index.html">
        <img src="imagen/logo11.png" style="width: 200px; position: absolute; top: 10px; left: 10px;">
      </a>
    </a>
    <i class="bi bi-list toggle-sidebar-btn" style="color:white;"></i>
  </div><!-- End Logo -->


  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

      <li class="nav-item d-block d-lg-none">

        <a class="nav-link nav-icon search-bar-toggle " href="#">
          <i class="bi bi-search"></i>
        </a>
        
        <li class="nav-item dropdown">
          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <style>
              .x1{border-radius:3px; border:.1px solid grey; margin-right:10px; padding:10px;} .x1:hover{ border:.2px solid blue; background-color:rgb(127 128 128 5);}
            </style>
            <!--  <a class="" href="#">Login&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>  -->
          </a>  
        </li>




        <div class="search-bar">
          <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
              <input class="form-control" type="text" placeholder="Buscar..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
              <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
          </form>
          <!-- AYUDA -->
          <a href="ayuda.php" class="nav-item-ayuda dropdown pe-3" style="color: white" title="Ayuda" >
           <i class="bi bi-question-circle">
            <span>Ayuda</span>
          </i>
        </a>
      </div>
      <!-- End Search Bar -->


           
      <li class="nav-item dropdown pe-3">
        <a class="nav-link dropdown-toggle d-flex align-items-center pe-0" href="#" role="button" data-bs-toggle="dropdown" id="panel_usuario" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-user-circle me-2" style="font-size: 1.5em; color: white;"></i>
          <span class="text-white">USUARIO</span>
        </a>
      
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="panel_usuario">
          <!-- Sección de perfil -->
          <div class="dropdown-header text-center">
            <img src="https://static.vecteezy.com/system/resources/previews/013/697/346/non_2x/users-avatars-silhouettes-free-vector.jpg" alt="Usuario" class="rounded-circle mb-2" style="width: 80px; height: 80px;">
            <p class="mt-2 mb-0 fw-bold"><?php echo isset($_SESSION['login']) ? ucwords($_SESSION['login']) : 'Usuario'; ?></p>
            <span class="text-muted">Administrador</span>
          </div>
          <div class="dropdown-divider"></div>
      
          <!-- Opciones del menú -->
          <a class="dropdown-item d-flex align-items-center" href="ayuda.php">
            <i class="fa fa-question-circle me-2" style="font-size: 1.2em; color: #17a2b8;"></i>
            <span>Ayuda</span>
          </a>
          <a class="dropdown-item d-flex align-items-center" href="salir.php">
            <i class="fa fa-sign-out-alt me-2" style="font-size: 1.2em; color: #dc3545;"></i>
            <span>Cerrar Sesión</span>
          </a>
        </div>
      </li>

    </ul>
  </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->