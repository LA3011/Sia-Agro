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
<!-- ---- ↑↑ CODIGO A COPIAR ↑↑ ---- -->
 
<?php include_once("header.php")  ?>
<?php include_once("Sidebar.php") ?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Ordenes de salida</title>
  <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script> 
  <script type="text/javascript" src="js/sweetalert2.all.min.js"></script>
  <link rel="stylesheet"type="text/css" href="css_personalizado/tabla_orden_salida.css">
</head>
<body>

  <?php
  // Verifica si hay un mensaje almacenado en la variable de sesión
  if (isset($_SESSION['mensaje'])) {
    echo "
    <script>
    $(document).ready(function() {
      Swal.fire({
        text: '".$_SESSION['mensaje']."',
        icon: 'success',
        confirmButtonText: 'Aceptar'
        });
        });
        </script>
        ";

         // Elimina el mensaje de la variable de sesión para que no se muestre nuevamente después de la actualización de la página
        unset($_SESSION['mensaje']);
      }
      ?>
      
    <!-- Codigo traducion footer Table -->
        <style type="text/css">
         tr > .datatable-empty{
          color: white;
          }
        </style>
    <!-- ---------------------------- -->
       
  <!------- tabla -------->
  <main id="main" class="main">
    <section class="section">
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item" style="">Venta</li>
          <li class="breadcrumb-item" style="">Ventas</li>
          <li class="breadcrumb-item active" style="color:#172871;">Ordenes Salida</li>
        </ol>
      </nav> 
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <p style="position: absolute; right:165px; top:130px;"> Buscar... </p>
              <h5 class="card-title" style="color:black; font-size:40px; margin-left:7%;">Ordenes de salida</h5>
              <a href="orden_salida.php" class="btn btn-success" style="margin-right:82.5%; margin-top:10px; margin-bottom:8px;" title="Agregar">
    <i class="ri-add-fill" style="color:white;"></i>
    Agregar &nbsp;
</a>
          <table class="table datatable">
            <thead>
              <tr>
                <th scope="col">Cliente</th>
                <th scope="col">Despachador</th>
                <th scope="col">serie</th>
                <th scope="col">Número</th>
                <th scope="col">Cantidad de animales</th>
                <th scope="col">tipo de público</th>
                <th scope="col">fecha de salida</th>
                <th scope="col">Acción</th>
              </tr>
            </thead>
            <tbody>
            <?php
include_once("conexion/conexion.php");
$conn = cconexion::ConexionBD();

$sql = "SELECT * FROM factura ORDER BY id";
$result = $conn->query($sql);

if ($result->rowCount() > 0) {
    $contador = 1;
    while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {
                  ?>
                  <tr>
                    <td>
                      <?php echo $fila['cliente']; ?>
                    </td>
                    <td>
                      <?php echo $fila['despachador']; ?>
                    </td>
                    <td>
                      <?php echo $fila['serie']; ?>
                    </td>
                    <td>
                      <?php echo $fila['numero']; ?>
                    </td>
                    <td>
                      <?php echo $fila['cantidad_animales']; ?>
                    </td>
                    <td>
                      <?php echo $fila['tipopublico']; ?>
                    </td>
                    <td>
                      <?php echo date('d-m-Y', strtotime($fila['fecha']));?>
                    </td>
                    <td>
                            
                            <?php if($imprimir == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                              <a href='pdf/formato_pdf_ordsalida.php?id=<?php echo $fila["id"];?>' style="color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                                title="Imprimir">
                                <img src="icon/icon-pdf.jpg" style="height: 25px;" viewBox="0 0 512 512">
                              </a>
                            <?php } ?>  <!-- ← CODIGO A COPIAR -->

                            <?php if($eliminar == "true") { ?>  <!-- ← CODIGO A COPIAR -->
                              <a type="button" data-bs-toggle="modal" data-bs-target="#smallModal-<?php echo $fila["id"] ?>"
                                style="color:none;  margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                                title="Eliminar">
                                <i class="ri-delete-bin-2-line" style="color:#EE0D0D;"></i>
                              </a>
                            <?php } ?>  <!-- ← CODIGO A COPIAR -->







                    <div class="modal fade" id="basicModal-<?php echo $fila["id"]; ?>" tabindex="-1">
                      <div class="modal-dialog modal-lg" style="max-width: 1500;">
                        <div class="modal-content">
                          <div class="modal-header" style="background-color: green; color: white;">
                            <h5 class="modal-title text-center w-100"> Actualizar Informacion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <form method="POST">
                              <div class="row mb-2">
                                <div class="col-sm-3">
                                  <input type="hidden" class="form-control" name="id"
                                  value='<?php echo $fila["id"]; ?>'>
                                </div>
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Cliente</label>
                                <div class="col-sm-9">
                                  <input class="form-control form-control-sm bg-light" style="width: 70%;"
                                  value='<?php echo $fila["cliente"]; ?>' readonly>
                                </div>
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Despachador</label>
                                <div class="col-sm-9">
                                  <input class="form-control form-control-sm bg-light" style="width: 70%;"
                                  value='<?php echo $fila["despachador"]; ?>' readonly>
                                </div>
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Serie</label>
                                <div class="col-sm-9">
                                  <input class="form-control form-control-sm bg-light" style="width: 70%;"
                                  value='<?php echo $fila["serie"]; ?>' readonly>
                                </div>
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Número</label>
                                <div class="col-sm-9">
                                  <input class="form-control form-control-sm bg-light" style="width: 70%;"
                                  value='<?php echo $fila["numero"]; ?>' readonly>
                                </div>
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Cantidad de
                                animales</label>
                                <div class="col-sm-9">
                                  <input class="form-control form-control-sm bg-light" style="width: 70%;"
                                  value='<?php echo $fila["cantidad_animales"]; ?>' readonly>
                                </div>
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Tipo de público</label>
                                <div class="col-sm-9">
                                  <input class="form-control form-control-sm bg-light" style="width: 70%;"
                                  value='<?php echo $fila["tipoPublico"]; ?>' readonly>
                                </div>
                              </div>
                              <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" style="color:#21618C;">Fecha de
                                Despacho</label>
                                <div class="col-sm-9">
                                  <input class="form-control form-control-sm bg-light" style="width: 70%;"
                                  value='<?php echo $fila["fecha"]; ?>' readonly>
                                </div>
                              </div>

                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- modal [eliminar] -->
                    <div class="modal fade" id="smallModal-<?php echo $fila["id"] ?>" tabindex="-1">
                      <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                          <div class="modal-header"
                          style="text-align:center; display: inline-block; background-color:#F25050;">
                          <h5 class="modal-title" style="background-color:#F25050; color:white;">¡ATENCION!</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="position:absolute; left:91%; top:2px;"></button>
                        <div class="modal-body">
                          ¿Desea Eliminar este Registro?
                        </div>
                        <div class="modal-footer">
                          <a style="top:-1px; left:-60px; position: relative; color:none; margin:5px; width:32px; padding-left:2px; font-size:25px; background-color:none;"
                          href='deshabilitaciones/deshabilitar_orden_salida.php?id=<?php echo $fila["id"] ?>'
                          title="Eliminar">
                          <span class="btn btn-outline-danger">Eliminar</span>
                        </a>
                        <button style="left:px; position: relative;" type="button" class="btn btn-outline-success"
                        data-bs-dismiss="modal">Cerrar
                      </button>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <?php
                $contador++; // Incrementar el contador en cada iteración
              }
            }

            ?>
          </tbody>
        </table>
        <!-- End Table with stripped rows -->
        <!-- <span style="display:inline-block; position:relative; top:-20px;">N° de registros
          <?php $contador; ?>
        </span> -->
      </div>
    </div>
  </div>
</div>
</section>
</main><!-- End #main -->

<!-- ======= Footer ======= -->
<?php include_once("footer.php"); ?>

</body>
</html>