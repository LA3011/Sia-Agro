<?php session_start();            ?>
<?php
  if(!isset($_SESSION['Aceso'])){
    header("location: index.html");
  }
?>
<?php include_once("header.php");  ?>
<?php include_once("Sidebar.php"); ?>

<!DOCTYPE html>
<html>
    <head>     
        <style>
            .form-control{
                width: 800px;
            }
        </style>
        <title>editar</title>   
    </head>
<body>
    <main id="main" class="main" style="background-color:rgb(223, 250, 220);">
        <section class="section" style="text-align:center;">     <!-- distribucion -->      
            <div class="row">
                <div class="card" style="padding:0">
                    <div class="card-body" style="padding:0">

                        <div class="alert alert-warning alert-dismissible fade show" style="background-color: rgb(232, 128, 36);">
                            <h1 class="card-title" style="display:inline; color:white; font-size:23px;">Registro Usuario</h1> 
                        </div> 

                        <form method="POST" action="registrar.php" style="padding: 0 50px 0 50px;">

                            <!-- General Form Elements -->	

                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Nombre y Apellido</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="nombre_completo" required placeholder="Ej:  Diego Flores">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Nombre Usuario</label>
                                <div class="col-sm-10">                                   
									<input type="text" class="form-control" id="validationCustom01" placeholder="Ej:  Servet123UPTA" required name="nombre">
                                </div>
                            </div>
							
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Clave</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="password" name="clave" required  id="floatingPassword" placeholder="Contraseña">
                                </div>
                            </div>
								
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Id</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="number" name="id" required placeholder="id">
                                </div>
                            </div>
							
                                <div style="display:inline-block; margin:20px 0 30px 0; border-top:1px solid black; border-bottom:1px solid black; width:900px" >
                                    <p style="display:inline">Preguntas de Seguridad </p>
                                    <p  style="display:inline; color:#008000;">(Datos Utilizados para Recuperar la Cuenta)</p>
                                </div>


                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Lugar de Nacimiento?</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="mascota" required placeholder="Ej: ">            
                                </div>
                            </div>
							
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Comida Favorita?</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="comida" required placeholder="Ej: Pabellón criollo">            
                                </div>
                            </div>
							
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Pelicula Favorita?</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="pelicula" required placeholder="Ej: Joker">            
                                </div>
                            </div>
							
                            <div class="row mb-3" style="padding-left:15%;">
                                <div class="col-sm-10" style="text-align:center">
                                    <a href="registro_usuario.php" class="btn btn-primary" style="width:100px;" >Vaciar</a> <input type="submit" class="btn btn-primary" value="Registrar" style="width:100px;">
                                </div>
                            </div>
                                
                            
                            

							<!-- End General Form Elements -->
                            
                        </form>
                    </div>
                </div>
            </div> 
        </seccion>  
    </main>  
</body>
</html>

<?php include_once("footer.php"); ?>