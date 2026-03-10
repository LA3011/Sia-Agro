<?php
session_start();
if (!isset($_SESSION['Aceso'])) {
    header("location: index.html");
}
include_once("header.php");
include_once("Sidebar.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Galería de Animales en Venta</title>
  
</head>
<body>
<main id="main" class="main">
    <div class="pagetitle">
        <h1></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Venta</a></li>
                <li class="breadcrumb-item"><a href="index.html">Ventas</a></li>
                <li class="breadcrumb-item" style="color:#172871;"><strong>Animales en venta</strong></li>
                
            </ol>
        </nav>
       
    </div>
    <section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="titulo-venta">🐮 Galería de Animales en Venta 🐷</h1>
                    <p class="text-center">
                        <a href="raza_animales.php" class="btn btn-custom">📜 Información de Venta</a>
                    </p>
                    <table class="table datatable">
                        <thead>
                            <tr></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="container">
                                        <div class="gallery">
                                            <?php
                                            include_once("conexion/conexion.php");
                                            $conn = cconexion::ConexionBD();

                                            try {
                                                $consulta = 'SELECT * FROM animales WHERE "Venta" = :venta';
                                                $stmt = $conn->prepare($consulta);
                                                $stmt->execute(['venta' => 'Venta']);
                                                $animales = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                              if (count($animales) > 0) {
    foreach ($animales as $animal) {
        $imagen = is_resource($animal['Imagen']) ? stream_get_contents($animal['Imagen']) : $animal['Imagen'];

        echo '<div class="item" data-id="' . htmlspecialchars($animal['Id_animal']) . '">';
        echo '<img src="data:image/jpg;base64,' . base64_encode($imagen) . '">';
        echo '<div class="item-info">';
        echo '<h3 class="item-title">' . htmlspecialchars($animal['Nombre']) . '</h3>';
        echo '<p class="item-description">Peso: ' . htmlspecialchars($animal['Peso']) . 'kg<br>';
        echo 'Raza: ' . htmlspecialchars($animal['Raza']) . '<br>';
        echo 'Sexo: ' . htmlspecialchars($animal['Sexo']) . '<br>';
        echo 'Lote: ' . htmlspecialchars($animal['Lote']) . '<br>';
        echo 'Precio: Bs' . number_format($animal['precio'], 2, ',', '.') . '</p>';
        echo '</div>';
        echo '</div>';
    }
}
 else {
                                                    echo "<p class='no-animales'>No hay animales en venta.</p>";
                                                }
                                            } catch (PDOException $e) {
                                                echo "<p class='error-bd'>Error al conectar con la base de datos: " . $e->getMessage() . "</p>";
                                            }

                                            $conn = null;
                                            ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Estilo para el título */
.titulo-venta {
    text-align: center;
    font-size: 4rem;
    font-weight: bold;
    background: linear-gradient(90deg, #ff9a3c, #ff5252);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 20px;
}

/* Botón personalizado */
.btn-custom {
    display: inline-block;
    background: linear-gradient(45deg, #ff7e00, #ff5200);
    color: white;
    font-size: 1.2rem;
    padding: 20px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s ease-in-out;
}

.btn-custom:hover {
    background: linear-gradient(45deg, #ff5200, #ff7e00);
    transform: scale(1.1);
}

/* Mensaje cuando no hay animales */
.no-animales {
    text-align: center;
    font-size: 1.2rem;
    color: red;
    font-weight: bold;
}

/* Mensaje de error */
.error-bd {
    color: red;
    font-weight: bold;
    text-align: center;
}

/* Galería de imágenes */
.gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.item {
    background: white;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    padding: 15px;
    text-align: center;
    transition: 0.3s;
}

.item:hover {
    transform: scale(1.05);
}

.item img {
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
}

.item-info {
    margin-top: 10px;
}

.item-title {
    font-size: 1.2rem;
    font-weight: bold;
}

.item-description {
    font-size: 1rem;
}
</style>

</main>
<?php include_once("footer.php"); ?>
</body>
</html>