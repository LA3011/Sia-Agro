<?php
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();

//////////////////////////////////////////////////////////////////////
$totalPrecios = 0; // Variable para almacenar la suma de precios 
$totaldieta = 0;
$total = 0;

// Consulta para obtener la inversión realizada en la dieta animal
$consultaInversion = 'SELECT SUM("Precio") AS suma_inversion, COUNT(*) AS total_registros FROM dieta_animal';
$stmtInversion = $conn->prepare($consultaInversion);
$stmtInversion->execute();
$filaInversion = $stmtInversion->fetch(PDO::FETCH_ASSOC);
$inversionDieta = $filaInversion['suma_inversion'];
$totalRegistrosDieta = $filaInversion['total_registros'];

// Consulta para obtener la inversión realizada en la dieta animal
$consultaInversionVet = 'SELECT SUM("Precio") AS suma_inversion_vet, COUNT(*) AS total_registros_vet FROM datos_veterinarios';
$stmtInversionVet = $conn->prepare($consultaInversionVet);
$stmtInversionVet->execute();
$filaInversionVet = $stmtInversionVet->fetch(PDO::FETCH_ASSOC);
$inversionVet = $filaInversionVet['suma_inversion_vet'];
$totalRegistrosVet = $filaInversionVet['total_registros_vet'];

// Costo de comida animal
$sql1 = 'SELECT SUM("total_costo") AS suma_total FROM comida_animal';
$stmt1 = $conn->prepare($sql1);
$stmt1->execute();
$row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
$total1 = $row1["suma_total"];

// Porcentaje de inversión animales
$sqlVeterinario = 'SELECT SUM("veterinario") AS suma_veterinario FROM inversion';
$stmtVeterinario = $conn->prepare($sqlVeterinario);
$stmtVeterinario->execute();
$rowVeterinario = $stmtVeterinario->fetch(PDO::FETCH_ASSOC);

$sqlDieta = 'SELECT SUM("dieta") AS suma_dieta FROM inversion';
$stmtDieta = $conn->prepare($sqlDieta);
$stmtDieta->execute();
$rowDieta = $stmtDieta->fetch(PDO::FETCH_ASSOC);

$sqlComida = 'SELECT SUM("comida") AS suma_comida FROM inversion';
$stmtComida = $conn->prepare($sqlComida);
$stmtComida->execute();
$rowComida = $stmtComida->fetch(PDO::FETCH_ASSOC);

$sumaVeterinario = $rowVeterinario["suma_veterinario"];
$sumaDieta = $rowDieta["suma_dieta"];
$sumaComida = $rowComida["suma_comida"];

$inversionTotal = $sumaVeterinario + $sumaDieta + $sumaComida;

if ($inversionTotal != 0) {
    $porcentaje_veterinario = number_format(($sumaVeterinario / $inversionTotal) * 100, 2);
    $porcentaje_dieta = number_format(($sumaDieta / $inversionTotal) * 100, 2);
    $porcentaje_comida = number_format(($sumaComida / $inversionTotal) * 100, 2);
} else {
    $porcentaje_veterinario = 0;
    $porcentaje_dieta = 0;
    $porcentaje_comida = 0;
}

// Total de ganancia animal
$sql2 = 'SELECT SUM("ganancia") AS suma_ganancia FROM factura';
$stmt2 = $conn->prepare($sql2);
$stmt2->execute();
$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
$total2 = $row2["suma_ganancia"];

// Inversión en cultivos
$sqlCultivos = 'SELECT SUM("fertilizante") AS total_fertilizante, SUM("funguisida") AS total_funguisida, SUM("semillas") AS total_semillas, SUM("equipos") AS total_equipos FROM inversion_cultivos';
$stmtCultivos = $conn->prepare($sqlCultivos);
$stmtCultivos->execute();
$rowCultivos = $stmtCultivos->fetch(PDO::FETCH_ASSOC);

$totalFertilizante = $rowCultivos["total_fertilizante"];
$totalFunguisida = $rowCultivos["total_funguisida"];
$totalSemillas = $rowCultivos["total_semillas"];
$totalEquipos = $rowCultivos["total_equipos"];

$inversion_cultivos = $totalFertilizante + $totalFunguisida + $totalSemillas + $totalEquipos;

if ($inversion_cultivos != 0) {
    $porcentajeFertilizante = number_format(($totalFertilizante / $inversion_cultivos) * 100, 2);
    $porcentajeFunguisida = number_format(($totalFunguisida / $inversion_cultivos) * 100, 2);
    $porcentajeSemillas = number_format(($totalSemillas / $inversion_cultivos) * 100, 2);
    $porcentajeEquipos = number_format(($totalEquipos / $inversion_cultivos) * 100, 2);
} else {
    $porcentajeFertilizante = 0;
    $porcentajeFunguisida = 0;
    $porcentajeSemillas = 0;
    $porcentajeEquipos = 0;
}

// Consulta para obtener la suma de los precios de los animales
$consultaGanancia = 'SELECT SUM(precio) AS suma_precios, COUNT(*) AS total_animales FROM animales';
$stmtGanancia = $conn->prepare($consultaGanancia);

if ($stmtGanancia->execute()) {
    $filaGanancia = $stmtGanancia->fetch(PDO::FETCH_ASSOC);
    if ($filaGanancia) {
        $sumaPrecios = $filaGanancia['suma_precios'];
        $totalAnimales = $filaGanancia['total_animales'];

        // Verificar si la suma de los precios es cero
        if ($sumaPrecios != 0) {
            // Calcular la estimación de ganancia
            $estimacionGanancia2 = $sumaPrecios - $inversionTotal;
        } else {
            // La suma de los precios es cero, no se realiza la resta
            $estimacionGanancia = 0;
        }

      
    } else {
    
    }
} else {
    $errorInfo = $stmtGanancia->errorInfo();
    echo "Error ejecutando la consulta: " . $errorInfo[2] . "\n";
}

// Consulta para obtener la cantidad de ventas realizadas hoy
$fechaActual = date("Y-m-d");
$consultaVentas = 'SELECT COUNT(*) AS total_ventas FROM factura WHERE DATE("fecha") = CURRENT_DATE';
$stmtVentas = $conn->prepare($consultaVentas);
$stmtVentas->execute();
$filaVentas = $stmtVentas->fetch(PDO::FETCH_ASSOC);
$totalVentas = $filaVentas['total_ventas'];

// Consulta para obtener los animales vendidos hoy
$consultaAnimalesVendidos = 'SELECT a.* FROM animales a INNER JOIN factura f ON a.id_factura = f.id WHERE DATE(f.fecha) = CURRENT_DATE';
$stmtAnimalesVendidos = $conn->prepare($consultaAnimalesVendidos);
$stmtAnimalesVendidos->execute();
$resultadoAnimalesVendidos = $stmtAnimalesVendidos->fetchAll(PDO::FETCH_ASSOC);

// Recorrer los resultados y mostrar la información de los animales
foreach ($resultadoAnimalesVendidos as $fila) {
    // Consulta para verificar si el animal tiene un registro de dieta
    $consultaDieta = 'SELECT * FROM dieta_animal WHERE "id_animal" = :animal';
    $stmtDieta = $conn->prepare($consultaDieta);
    $stmtDieta->execute([':animal' => $fila['Id_animal']]);
    $resultadoDieta = $stmtDieta->fetch(PDO::FETCH_ASSOC);

    // Consulta para verificar si el animal tiene un registro veterinario
    $consultaVeterinario = 'SELECT * FROM datos_veterinarios WHERE "id_animal" = :animal';
    $stmtVeterinario = $conn->prepare($consultaVeterinario);
    $stmtVeterinario->execute([':animal' => $fila['Id_animal']]);
    $resultadoVeterinario = $stmtVeterinario->fetch(PDO::FETCH_ASSOC);

    if ($resultadoVeterinario) {
        // Obtener el precio del animal desde la tabla "datos_veterinarios"
        $totalPrecios += $resultadoVeterinario['Precio'];
    }

    if ($resultadoDieta) {
        // Obtener el precio del animal desde la tabla "dieta_animal"
        $totaldieta += $resultadoDieta['Precio'];
    }
}

$conn = null;
?>