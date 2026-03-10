<?php
ob_start(); // Inicia el buffer de salida
session_start();
if (!isset($_SESSION['Aceso'])) {
    header("location: index.html");
    exit;
}

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



// Verifica si 'coordinates' y otros parámetros están definidos
if (isset($_GET['coordinates']) && isset($_GET['area']) && isset($_GET['geographicLocation']) && isset($_GET['descriptions'])) {
    $coordinates = json_decode(urldecode($_GET['coordinates']), true);
    $area = urldecode($_GET['area']);
    $geographicLocation = json_decode(urldecode($_GET['geographicLocation']), true);
  // Recibir el ID del polígono desde la URL
$poligono_id = $_GET['id']; // Asegúrate de que el ID esté presente

    // Comprobamos si bounds está definido.
    if (isset($_GET['bounds'])) {
        $bounds = urldecode($_GET['bounds']);
    } else {
        $bounds = "No se especificaron límites";
    }
} else {
    echo "No se recibieron los datos necesarios.";
    exit;
}

?>
<?php include_once("header.php") ?>
<?php include_once("Sidebar.php") ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualización del Polígono</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Fuente personalizada -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        body {
            margin-top: 50px;
            font-family: 'Poppins', sans-serif;
            background-color: #f0f8ff; /* Fondo azul claro */
            color: #333;
            margin-left: 290px;
        }
        h1 {
            text-align: center;
            margin-bottom: 50px;
            color: #2c3e50;
            font-weight: 600;
            margin-left: 360px;
        }
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15); /* Sombra */
        }
        .card-title {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.2rem;
        }
        .card-subtitle {
            color: #95a5a6;
            font-size: 0.9rem;
        }
        .card-body {
            background-color: #ecf0f1; /* Fondo suave */
            border-top: 5px solid #3498db; /* Barra superior de color */
        }
        .card-link {
            color: #3498db; /* Azul para enlaces */
        }
        .card-link:hover {
            text-decoration: underline;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .card-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 2rem;
            color: #3498db;
        }
        /* Colores personalizados para cada tarjeta */
        .card:nth-child(1) .card-body { border-top: 5px solid #3498db; } /* Azul */
        .card:nth-child(2) .card-body { border-top: 5px solid #e74c3c; } /* Rojo */
        .card:nth-child(3) .card-body { border-top: 5px solid #f1c40f; } /* Amarillo */
        .card:nth-child(4) .card-body { border-top: 5px solid #2ecc71; } /* Verde */
        .card:nth-child(5) .card-body { border-top: 5px solid #9b59b6; } /* Púrpura */
    </style>
</head><body>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="me-auto ">
            Detalles del Polígono
            <i class="fas fa-compass"></i>
        </h1>
        
        <!-- Menú desplegable -->
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Exportar
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
            <li>
    <a class="dropdown-item" href="pdf/formato_pdf_ficha_tecnica.php?id=<?= htmlspecialchars($poligono_id); ?>">Ficha Técnica</a>
</li>

        <li><a class="dropdown-item" href="pdf/formato_dxf_poligono.php?id=<?= htmlspecialchars($poligono_id); ?>">Polígono</a></li>
    </ul>
        </div>
    </div>

    <!-- Coordenadas -->
    <div class="row row-cols-1 row-cols-md-4 g-4 mt-4">
        <?php foreach ($coordinates as $index => $coordinate): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-icon">
                        <i class="fas fa-compass"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Coordenada-punto <?php echo $index + 1; ?></h5>
                        <h6 class="card-subtitle mb-2">Norte: <?php echo htmlspecialchars($coordinate['lat']); ?></h6>
                        <p class="card-text">Este: <?php echo htmlspecialchars($coordinate['lng']); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Área y Ubicación Geográfica en la misma fila -->
    <div class="row my-5">
        <div class="col text-center">
            <h2>Área Total</h2>
            <p class="lead"><?php echo htmlspecialchars($area); ?> Metros Cuadrado</p>
        </div>
        <div class="col text-center">
            <h2>Ubicación Del poligono</h2>
            <p>Norte: <?php echo htmlspecialchars($geographicLocation['lat']); ?></p>
            <p>Este: <?php echo htmlspecialchars($geographicLocation['lng']); ?></p>
        </div>
    </div>


<!-- Bootstrap JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

</body>