<?php session_start(); ?>
<?php if (!isset($_SESSION['Aceso'])) {
	header("location: index.html");
} ?>
<?php include_once("header.php") ?>
<?php include_once("Sidebar.php") ?>

<!DOCTYPE html>
<html> 
<head>
	<meta charset="utf-8"> 
	<title>Listado de fertilizantes</title>
</head>


<main id="main" class="main">
	<section class="section">
		<nav>
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Animales</li>
				<li class="breadcrumb-item">Movimiento Animal</li>
				<li class="breadcrumb-item active">Insumos</li>
			</ol>
		</nav>
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-4">
					<div class="card" style="background-color:  #99ff99; color: white;border: 1px solid black; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="icon">
									<i class="fas fa-chart-bar fa-3x"></i>
								</div>
								<div class="content ml-3 text-right">
									<h5 class="card-title">Veterinario</h5>
									<a  style="width: 150px;" href="datos_veterinarios.php" class="btn btn-primary mt-2 float-right">Ver más</a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-4">
					<div class="card" style="background-color: #80F2F4; color: white;border: 1px solid black; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="icon">
									<i class="fas fa-chart-pie fa-3x"></i>
								</div>
								<div class="content ml-3 text-right">
									<h5 class="card-title">Dieta Animal</h5>
									<a  style="width: 150px;"href="dieta_animal.php" class="btn btn-primary mt-2 float-right">Ver más</a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-4">
					<div class="card" style="background-color: #F3B665; color: white; border:1px solid black; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div class="icon">
									<i class="fas fa-chart-pie fa-3x"></i>
								</div>
								<div class="content ml-3 text-right">
									<h5 class="card-title">Comida Animal</h5>
									<a href="comida_animal.php"  1px solid black; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);style="width: 150px;"href="#" class="btn btn-primary mt-2 float-right">Ver más</a>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section>
</main>

<?php include_once("footer.php"); ?>







