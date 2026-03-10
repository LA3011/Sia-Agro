<?php session_start();            ?>
<?php if(!isset($_SESSION['Aceso'])){
  header("location: index.html");
}?>
<!-- ---- ↓↓ CODIGO A COPIAR ↓↓ ---- -->
<?php
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
?>

<?php include_once("header.php")  ?>
<?php include_once("Sidebar.php") ?>

<head>
  <title>Pastoreo Animal </title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="fontawesome/css/all.min.css">
  <link rel="stylesheet"type="text/css" href="css_personalizado/estilo_pastoreo.css">
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script>

</head>

<!-- Codigo traducion footer Table -->
<style type="text/css">
 tr > .datatable-empty{
  color: white;
}
</style>


<!-- ---------------------------- -->


<body>
  <main id="main" class="main" style="padding-left:50px; padding-top:0;">
    <section class="section" style="margin-top: 0%;">

      <nav style="display: inline-block;">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Animales</li>
          <li class="breadcrumb-item">Movimiento Animal</li>
          <a  class="breadcrumb-item active">Pastoreo</a>
        </ol>
      </nav>

      <div class="row mt-5">
        <div class="col-md-12">
          <div class="card-deck">



            <div class="card transparent-card" style="color:#737877;"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Periodo de Ocupación" aria-describedby="tooltip875113">
              <div class="card-body text-center" style="padding-top:10px;">
                <i class="fas fa-clock fa-2x"></i>
                <h5 class="card-title">P.O</h5>
                <!-- Agrega cualquier campo o información adicional según tus necesidades -->
              </div>
            </div>
            <div class="card transparent-card" style="padding-top:10px; color:rgb(37, 99, 201 );"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Periodo de Descanso" aria-describedby="tooltip875113">
              <div class="card-body text-center">
                <i class="fas fa-bed fa-2x"></i>
                <h5 class="card-title">P.D</h5>
                <!-- Agrega cualquier campo o información adicional según tus necesidades -->
              </div>
            </div>
            <div class="card transparent-card" style="padding-top:10px; "  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Número del Potrero" aria-describedby="tooltip875113">
              <div class="card-body text-center">
                <i class="fas fa-hashtag fa-2x"></i>
                <h5 class="card-title">N.P</h5>
                <!-- Agrega cualquier campo o información adicional según tus necesidades -->
              </div>
            </div>
            <div class="card transparent-card" style="padding-top:10px; color:#1A810D;"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Área del Potrero" aria-describedby="tooltip875113">
              <div class="card-body text-center">
                <i class="fas fa-chart-area fa-2x"></i>
                <h5 class="card-title">A.P</h5>
                <!-- Agrega cualquier campo o información adicional según tus necesidades -->
              </div>
            </div>
            <div class="card transparent-card" style="padding-top:10px; color:#E1AE03;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Acción" aria-describedby="tooltip875113">
              <div class="card-body text-center">
                <i class="fas fa-bolt fa-2x"></i>
                <h5 class="card-title">A</h5>
                <!-- Agrega cualquier campo o información adicional según tus necesidades -->
              </div>
            </div>
          </div> 
        </div>
      </div>
      <p style="position: absolute; right:175px; top:42%;"> Buscar... </p>
      <div class="row mt-3">
        <div class="col-md-12">
          <div style="overflow-x: auto;">
            <table class="table">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Periodo de Ocupación</th>
                    <th>Periodo de Descanso</th>
                    <th>Número del Potrero</th>
                    <th>Área del Potrero</th>
                    <th>Acción</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    include_once("conexion/conexion.php");
                    $conn = cconexion::ConexionBD(); // Inicializar la conexión
                    
                    $sql = "SELECT * FROM potreros ORDER BY \"Id_potreros\"";
  
                    $result = $conn->query($sql);
                    
                    // Verificar si se encontraron registros
                    if ($result->rowCount() > 0) {
                      // Variable para contar los registros
                        $contador = 1;
  
                      // Recorrer los registros y mostrar los datos en la tabla
                      while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                      <tr>
                        <td>
                          <?php echo $fila['Cantidad_dias_verdes']; ?>
                        </td>
                        <td>
                          <?php echo $fila['Cantidad_dias_secos']; ?>
                        </td>

                        <td>
                          <?php echo $fila['Id_potreros']; ?>
                        </td>
                        <td>
                          <?php echo $fila['area']; ?>
                        </td>
                        <td>
                          <div class="btn-group" role="group">

                            <?php if($ver == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                            <!-- Boton-modal [ver] -->
                            <a style="color: none; margin: 5px; width: 32px; padding-left: 2px; font-size: 25px; background-color: none;"
                            type="button" data-bs-toggle="modal"
                            data-bs-target="#basicModal-<?php echo $fila['Id_potreros']; ?>" title="Ver más">
                            <i class="ri-eye-fill" style="color:#17E45B" aria-describedby="tooltip831980"></i>
                          </a>
                          <?php } ?>  <!-- ← CODIGO A COPIAR -->

                          <?php if($imprimir == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                          <!-- Boton-modal [imprimir] -->
                          <a href="pdf/formato_pdf_pastoreo.php?id=<?php echo $fila['Id_potreros']; ?>&session_acceso=<?php echo isset($_SESSION['Usuario']) ? $_SESSION['Usuario'] : ''; ?>&session_id=<?php echo isset($_SESSION['Id_Usuario']) ? $_SESSION['Id_Usuario'] : ''; ?>"
                            style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                            title="Imprimir">
                            <img src="icon/icon-pdf.jpg" style="height: 25px;" viewBox="0 0 512 512">
                            <!-- <i class="bi bi-file-pdf" style="font-sizer:25px; color:black;"></i> -->
                          </a>
                          <?php } ?>  <!-- ← CODIGO A COPIAR -->

                          <div class="modal fade" id="basicModal-<?php echo $fila['Id_potreros']; ?>" tabindex="-1">
    <div class="modal-dialog modal-lg" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0b5ed7; color: white;">
                <h5 class="modal-title text-center w-100">PASTOREO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <?php
$potrero_id = $fila['Id_potreros'];
$mostrar_tabla = true; // Variable de control para mostrar/ocultar la tabla

include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD(); // Inicializar la conexión

try {
    // Consulta para obtener los datos del potrero específico
    $sql_potrero = "SELECT \"Nombre\", \"Cantidad_dias_verdes\", \"Cantidad_dias_secos\", \"Fecha_hora_registro\" FROM potreros WHERE \"Id_potreros\" = :potrero_id";
    $stmt_potrero = $conn->prepare($sql_potrero);
    $stmt_potrero->execute([':potrero_id' => $potrero_id]);

    if ($stmt_potrero->rowCount() > 0) {
        $row = $stmt_potrero->fetch(PDO::FETCH_ASSOC);

        $nombre_potrero = $row['Nombre'];
        $cantidad_dias_verdes = $row['Cantidad_dias_verdes'];
        $cantidad_dias_secos = $row['Cantidad_dias_secos'];
        $fecha_registro = $row['Fecha_hora_registro'];

        // Calcula las fechas en las que los lotes de animales pueden estar en el potrero
        $fechas_disponibles = array();
        $fecha_inicio = date('Y-m-d', strtotime("$fecha_registro +1 day"));
        $fecha_final = date('Y-m-d', strtotime("$fecha_inicio +$cantidad_dias_verdes days"));
        $fechas_disponibles[] = array(
            'inicio' => $fecha_inicio,
            'final' => $fecha_final
        );

        for ($i = 0; $i < 30; $i++) { // Genera 30 fechas adicionales
            $fecha_inicio = date('Y-m-d', strtotime("$fecha_final +$cantidad_dias_secos days"));
            $fecha_final = date('Y-m-d', strtotime("$fecha_inicio +$cantidad_dias_verdes days"));
            $fechas_disponibles[] = array(
                'inicio' => $fecha_inicio,
                'final' => $fecha_final
            );
        }

        // Obtener todos los lotes disponibles
        $sql_lotes = "SELECT nombre, numero FROM lotes";
        $stmt_lotes = $conn->prepare($sql_lotes);
        $stmt_lotes->execute();
        $lotes = $stmt_lotes->fetchAll(PDO::FETCH_ASSOC);

        if ($lotes) {
            if ($mostrar_tabla) {
                echo '<table class="table datatable" id="datatable-' . $fila['Id_potreros'] . '">';
                echo '<thead><tr><th>Lote</th><th>Número</th><th>Inicio</th><th>Final</th></tr></thead>';
                echo '<tbody>';

                foreach ($fechas_disponibles as $fecha) {
                    $lote_aleatorio = $lotes[array_rand($lotes)];
                    $lote = $lote_aleatorio['nombre'];
                    $numero = $lote_aleatorio['numero'];

                    $fecha_actual = date('Y-m-d');
                    $fecha_final_lote = $fecha['final'];
                    $fecha_pasada = ($fecha_actual > $fecha_final_lote);

                    echo '<tr>';
                    echo '<td>' . $lote . '</td>';
                    echo '<td>' . $numero . '</td>';
                    echo '<td>' . date("d/m/Y", strtotime($fecha['inicio'])) . '</td>';
                    echo '<td>' . date("d/m/Y", strtotime($fecha['final'])) . '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';

                // Inicializar DataTables
                echo '<script>
                    $(document).ready(function() {
                        $("#datatable-' . $fila['Id_potreros'] . '").DataTable({
                            responsive: true,
                            language: {
                                url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/es-ES.json"
                            }
                        });
                    });
                </script>';
            } else {
                echo 'No se puede mostrar la tabla en este momento.';
            }
        } else {
            echo '<table>';
            echo '<tr><td colspan="4">No hay lotes disponibles</td></tr>';
            echo '</table>';
        }
    } else {
        echo '<table>';
        echo '<tr><td colspan="4">No se encontró información del potrero.</td></tr>';
        echo '</table>';
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
                </tr>
                <?php
                        // Incrementar el contador en cada iteración
              }
            } ?>
            <!-- Agrega filas de datos aquí -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</section>
</main>
<?php include_once("footer.php"); ?>
</body>
</html>

<script type="text/javascript">
  
</script>

<!-- script inhabilitar retroceder -->  
<!--<script type="text/javascript">
    window.history.forward(1); //Esto es para cuando le pulse albotón de Atrás
    window.history.back(1); //Esto para cuando le pulse al botónde Adelante
  </script> -->
  <!-- script confirmacion de salida -->
<!-- <script>
    function registrarjs(){
      var bPreguntar = true;
      window.onbeforeunload = preguntarAntesDeSalir;
        function preguntarAntesDeSalir () {
          var respuesta;
          if ( bPreguntar ) {
            respuesta = alert("¿Seguro que desea salir, Sin antes mandar Formulario?");
            if ( respuesta ) {
              window.onunload = function () {
                return true;
            }
            } else {
              return false;
            }
          }
        }
      }
</script> -->