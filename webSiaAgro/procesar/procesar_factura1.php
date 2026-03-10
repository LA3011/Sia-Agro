<?php
session_start();
include_once("../conexion/conexion.php");
$conn = cconexion::ConexionBD();

$fecha = $_POST['fecha'];
$cliente = $_POST['cliente'];
$despachador = $_POST['despachador'];
$serie = $_POST['serie'];
$numero = $_POST['numero'];
$tipoPublico = $_POST['tipoPublico'];
$precioanimal = 0;
$precioanimal_dieta = 0;
$precioanimal_veterinario = 0;

$val = 0;     // inicializando [validar]
$des = "x";   // valor inicial para insertarlo en la BD [se precenta error al no hacerlo]
$precio = 0;
$ganancia = 0;

if ($_SESSION['pdf'] === true) {
    try {
        $sql = "INSERT INTO factura (fecha, cliente, despachador, serie, numero, tipoPublico, descripcion, precio, ganancia) 
                VALUES (:fecha, :cliente, :despachador, :serie, :numero, :tipoPublico, :descripcion, :precio, :ganancia)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':fecha' => $fecha,
            ':cliente' => $cliente,
            ':despachador' => $despachador,
            ':serie' => $serie,
            ':numero' => $numero,
            ':tipoPublico' => $tipoPublico,
            ':descripcion' => $des,
            ':precio' => $precio,
            ':ganancia' => $ganancia
        ]);
        $idFactura = $conn->lastInsertId();

        $_SESSION['pdf'] = false;
    } catch (PDOException $e) {
        echo "Error al insertar la factura: " . $e->getMessage();
        exit;
    }
}

if (isset($_POST['animalesSeleccionados'])) {
    $animalesSeleccionados = json_decode($_POST['animalesSeleccionados'], true);
    $cantidadAnimales = count($animalesSeleccionados);

    try {
        $sqlUpdateCantidadAnimales = "UPDATE factura SET cantidad_animales = :cantidadAnimales WHERE id = :idFactura";
        $stmt = $conn->prepare($sqlUpdateCantidadAnimales);
        $stmt->execute([':cantidadAnimales' => $cantidadAnimales, ':idFactura' => $idFactura]);

        foreach ($animalesSeleccionados as $animal) {
            $nombre = $animal['nombre'];
            $raza = $animal['raza'];
            $peso = $animal['peso'];
            $lote = $animal['lote'];
            $imagen = $animal['imagen'];

            $sqlCheckAnimal = "SELECT * FROM animales WHERE \"Nombre\" = :nombre AND \"Raza\" = :raza";
            $stmt = $conn->prepare($sqlCheckAnimal);
            $stmt->execute([':nombre' => $nombre, ':raza' => $raza]);
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                $sqlUpdate = "UPDATE animales SET \"Venta\" = 'Vendido', \"id_factura\" = :idFactura 
                              WHERE \"Nombre\" = :nombre AND \"Raza\" = :raza";
                $stmt = $conn->prepare($sqlUpdate);
                $stmt->execute([':idFactura' => $idFactura, ':nombre' => $nombre, ':raza' => $raza]);

                $sqlPrecioAnimal = "SELECT \"precio\" FROM animales WHERE \"Nombre\" = :nombre AND \"Raza\" = :raza";
                $stmt = $conn->prepare($sqlPrecioAnimal);
                $stmt->execute([':nombre' => $nombre, ':raza' => $raza]);
                $filaPrecioAnimal = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($filaPrecioAnimal) {
                    $precioAnimal = $filaPrecioAnimal['precio'];
                    $precioanimal += $precioAnimal;
                }
            }

            $val += 1;
            $razar = trim($raza);
            $razax[$val] = $razar;
            date_default_timezone_set("America/Caracas");
            $fecha_ejec = date("d-m-Y h.i.s a");
        }

        $razaBD = implode(',', $razax);
        $queryBD = "UPDATE factura SET descripcion = :descripcion WHERE id = :idFactura";
        $stmt = $conn->prepare($queryBD);
        $stmt->execute([':descripcion' => $razaBD, ':idFactura' => $idFactura]);

        $razax[$val + 1] = "";
        sort($razax);
        $razax[$val + 1] = "";
        $arrayUnico = [];
        $arrayRep = [];
        $contadorArray = 1;

        for ($i = 1; $i <= $val; $i++) {
            if (($razax[$i + 1] == $razax[$i])) {
                $contadorArray++;
            } else {
                $arrayUnico[] = $razax[$i];
                $arrayRep[] = $contadorArray;
                $contadorArray = 1;
            }
        }

        $limit = count($arrayUnico);

    } catch (PDOException $e) {
        echo "Error al actualizar la factura: " . $e->getMessage();
        exit;
    }
}

if ($idFactura !== null) {
    try {
        $sql = "SELECT \"Raza\", COUNT(*) AS \"NumeroAnimales\" FROM animales WHERE \"id_factura\" = :idFactura GROUP BY \"Raza\"";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':idFactura' => $idFactura]);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultado) > 0) {
            $razas = array();

            foreach ($resultado as $fila) {
                $raza = $fila['Raza'];
                $numeroAnimales = $fila['NumeroAnimales'];

                if (array_key_exists($raza, $razas)) {
                    $razas[$raza] += $numeroAnimales;
                } else {
                    $razas[$raza] = $numeroAnimales;
                }
            }

            foreach ($animalesSeleccionados as $animal) {
                $nombre = $animal['Id_animal'];

                $sqlDatosVeterinarios = "SELECT \"Precio\" FROM datos_veterinarios WHERE \"id_animal\" = :nombre";
                $stmt = $conn->prepare($sqlDatosVeterinarios);
                $stmt->execute([':nombre' => $nombre]);
                $resultadoDatosVeterinarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($resultadoDatosVeterinarios) > 0) {
                    foreach ($resultadoDatosVeterinarios as $filaDatosVeterinarios) {
                        $precioVeterinario = $filaDatosVeterinarios['Precio'];
                        $precioanimal_veterinario += $precioVeterinario;
                    }
                }
            }

            foreach ($animalesSeleccionados as $animal) {
                $nombre = $animal['Id_animal'];

                $sqlDietaAnimal = "SELECT \"Precio\" FROM dieta_animal WHERE \"id_animal\" = :nombre";
                $stmt = $conn->prepare($sqlDietaAnimal);
                $stmt->execute([':nombre' => $nombre]);
                $resultadoDietaAnimal = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($resultadoDietaAnimal) > 0) {
                    foreach ($resultadoDietaAnimal as $filaDietaAnimal) {
                        $dieta = $filaDietaAnimal['precio'];
                        $precioanimal_dieta += $dieta;
                    }
                }
            }
        }

        $totalsuma_inversion = $precioanimal_dieta + $precioanimal_veterinario;
        $gananciatotal = $precioanimal - $totalsuma_inversion;

        $sqlUpdateFactura = "UPDATE factura SET Precio = :precioanimal, ganancia = :gananciatotal WHERE id = :idFactura";
        $stmt = $conn->prepare($sqlUpdateFactura);
        $stmt->execute([':precioanimal' => $precioanimal, ':gananciatotal' => $gananciatotal, ':idFactura' => $idFactura]);

    } catch (PDOException $e) {
        echo "Error al actualizar los valores en la factura: " . $e->getMessage();
        exit;
    }
}

$conn = null;

$_SESSION['mensaje'] = "El registro se guardó con éxito.";

header('Location: ../tabla_orden_salida.php');
?>
