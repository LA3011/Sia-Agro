<?php
session_start();
include("../conexion/conexion.php");
$conn = cconexion::ConexionBD(); // Asegúrate de que esta función devuelva una conexión PDO válida

$program = [];
$contIssetModul = 0;
$contIssetSubProgram = 0;
$Sub_program[0] = null;

$Id_Perfilp = $_SESSION['Id_Perfilp'];
$estado = $_POST['estado'];        // Estado (ACTIVO/INACTIVO)
$Id_Perfil = $_POST['Id_Perfil'];  // ---> 45
$nombre_perfil = $_POST['nombre_perfil'];
$programaG = $_POST['programaG'];  // ---> CHECKED GENERAL [animales,cultivos,finanzas,empleados,configuracion]
echo 'ID  ====> ' . $Id_Perfil . '<br>';

// Programas (string)
$cp = ["ANIMALES", "CULTIVOS", "VENTA", "FINANZAS", "RECURSOS_HUMANOS", null, "CONFIGURACION"];
// Sub-programas (string)
$csp = ["general_animales", "movimiento_animal", "general_cultivos", "seguimiento_cultivos", "venta", "general_finanzas", "costos", "empleados", "ajustes"];
// Módulos (string)
$mdl = [
    1 => "registro_animales", 2 => "reproducciones_animales", 3 => "registro_potreros", 4 => "actividad_animal",
    5 => "pastoreo", 6 => "insumos_animal", 7 => "siembra", 8 => "espacios", 9 => "actividades",
    10 => "control_fertilizante", 11 => "control_plagas", 12 => "insumos_cultivo", 14 => "orden_salida",
    15 => "animales_venta", 16 => "animales", 17 => "cultivo", 18 => "costo_fijo", 19 => "costo_variable",
    20 => "usuarios", 21 => "permisos", 22 => "bitacora"
];

// --- PROGRAMAS (asignación)
for ($i = 0; $i < 7; $i++) {
    if (isset($_POST[$cp[$i]])) {
        $program[$i] = $i + 1;
        $contIssetModul++;
    }
}

// --- SUB-PROGRAMAS (asignación)
for ($i = 0; $i < 9; $i++) {
    if (isset($_POST[$csp[$i]])) {
        $Sub_program[$i] = $i + 1;
        $contIssetModul++;
    }
}

// --- MÓDULOS (asignación)
for ($i = 1; $i < 23; $i++) {
    if (isset($_POST[$mdl[$i]])) {
        $modulo[$i] = $i;
    } else {
        $contIssetModul++;
    }
}

// --- VALIDACIONES
if ($contIssetModul == 22) {
    $_SESSION['validacion_permisos'] = "VALIDACIÓN #0005: <br> PERFIL SIN ASIGNACIONES... <br> <p>Asegúrese de que el perfil tenga al menos un módulo o subprograma asignado para poder actualizarlo.</p>";
    header('location: ../permisos_agro.php');
    exit();
}

if ($Id_Perfil == $Id_Perfilp) {
    $_SESSION['validacion_permisos'] = "VALIDACIÓN #0002: <br> ESTE PERFIL ESTÁ EN USO... <br> <p>Asegúrese de que el perfil que desea editar sea diferente al utilizado actualmente.</p>";
    header('location: ../permisos_agro.php');
    exit();
} elseif ($Id_Perfil == 1) {
    $_SESSION['validacion_permisos'] = "VALIDACIÓN #0003: <br> PERFIL NO ADMITIDO... <br> <p>Este perfil no puede ser editado por razones de seguridad.</p>";
    header('location: ../permisos_agro.php');
    exit();
}

try {
    // Actualizar el perfil
    $sql = "UPDATE perfil SET estado = :estado, nombre_perfil = :nombre_perfil WHERE Id_Perfil = :Id_Perfil";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
    $stmt->bindParam(':nombre_perfil', $nombre_perfil, PDO::PARAM_STR);
    $stmt->bindParam(':Id_Perfil', $Id_Perfil, PDO::PARAM_INT);
    $stmt->execute();

    // Eliminar y volver a insertar los programas
    $sql = "DELETE FROM perfil_programa WHERE Id_Perfil = :Id_Perfil";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':Id_Perfil', $Id_Perfil, PDO::PARAM_INT);
    $stmt->execute();

    foreach ($program as $prog) {
        $sql = "INSERT INTO perfil_programa (Id_Perfil, Id_Programa) VALUES (:Id_Perfil, :Id_Programa)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':Id_Perfil', $Id_Perfil, PDO::PARAM_INT);
        $stmt->bindParam(':Id_Programa', $prog, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Eliminar y volver a insertar los subprogramas
    $sql = "DELETE FROM perfil_subprograma WHERE Id_Perfil = :Id_Perfil";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':Id_Perfil', $Id_Perfil, PDO::PARAM_INT);
    $stmt->execute();

    foreach ($Sub_program as $subprog) {
        $sql = "INSERT INTO perfil_subprograma (Id_Perfil, Id_Subprograma) VALUES (:Id_Perfil, :Id_Subprograma)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':Id_Perfil', $Id_Perfil, PDO::PARAM_INT);
        $stmt->bindParam(':Id_Subprograma', $subprog, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Eliminar y volver a insertar los módulos
    $sql = "DELETE FROM perfil_modulo WHERE Id_Perfil = :Id_Perfil";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':Id_Perfil', $Id_Perfil, PDO::PARAM_INT);
    $stmt->execute();

    foreach ($modulo as $mod) {
        $sql = "INSERT INTO perfil_modulo (Id_Perfil, Id_Modulo) VALUES (:Id_Perfil, :Id_Modulo)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':Id_Perfil', $Id_Perfil, PDO::PARAM_INT);
        $stmt->bindParam(':Id_Modulo', $mod, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Actualizar los privilegios
    $sql = "UPDATE privilegios SET ver = :ver, editar = :editar, eliminar = :eliminar, imprimir = :imprimir, agregar = :agregar WHERE Id_Perfil = :Id_Perfil";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':ver', $_POST['ver'], PDO::PARAM_BOOL);
    $stmt->bindParam(':editar', $_POST['editar'], PDO::PARAM_BOOL);
    $stmt->bindParam(':eliminar', $_POST['eliminar'], PDO::PARAM_BOOL);
    $stmt->bindParam(':imprimir', $_POST['imprimir'], PDO::PARAM_BOOL);
    $stmt->bindParam(':agregar', $_POST['agregar'], PDO::PARAM_BOOL);
    $stmt->bindParam(':Id_Perfil', $Id_Perfil, PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION['mensaje'] = "El registro se actualizó con éxito.";
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
}

header("location: ../permisos_agro.php");
?>