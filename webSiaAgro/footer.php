
<!-- ======= Footer ======= -->
<!DOCTYPE html>
<html>

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

<style>
/* Estilos para el pie de página */
#footer {
    background-color: transparent;
}
</style>


<footer id="footer" class="footer position-relative">
    <div class="text-center">
        <!-- Texto de copyright centrado -->
        <div class="copyright">
            <p class="mb-0">&copy; Copyright <strong><span>U.P.T. Estado Aragua.</span></strong> Todos los derechos reservados.</p>
        </div>
    </div>
    <!-- Fecha, hora y duración de la sesión -->
    <div class="fecha-hora position-absolute end-0 me-3">
        <p id="fechaHora" class="mb-0 fw-bold"></p>
        <p id="duracionSesion" class="mb-0 text-muted"></p>
    </div>
</footer>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/chart.js/chart.umd.js"></script>
<script src="assets/vendor/echarts/echarts.min.js"></script>
<script src="assets/vendor/quill/quill.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

<script>
    // Función para actualizar la fecha y hora actual
    function actualizarFechaHora() {
        const ahora = new Date();
        const opciones = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit' };
        document.getElementById('fechaHora').innerText = "Fecha y hora: " + ahora.toLocaleString('es-ES', opciones);
    }

    // Función para calcular y mostrar la duración de la sesión
    const inicioSesion = new Date(<?php echo json_encode($_SESSION['inicio_sesion'] ?? date('Y-m-d H:i:s')); ?>);
    function actualizarDuracionSesion() {
        const ahora = new Date();
        const diferencia = Math.floor((ahora - inicioSesion) / 1000); // Diferencia en segundos
        const horas = Math.floor(diferencia / 3600);
        const minutos = Math.floor((diferencia % 3600) / 60);
        const segundos = diferencia % 60;
        document.getElementById('duracionSesion').innerText = `Duración de la sesión: ${horas}h ${minutos}m ${segundos}s`;
    }

    // Actualizar fecha, hora y duración de la sesión cada segundo
    setInterval(() => {
        actualizarFechaHora();
        actualizarDuracionSesion();
    }, 1000);

    // Llamar a las funciones al cargar la página
    actualizarFechaHora();
    actualizarDuracionSesion();
</script>
</body>
</html>