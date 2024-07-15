<?php
session_start();

if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
}

require_once 'config/conexion.php';
require_once 'app/Model/IncidenciaModel.php';
require_once 'app/Model/RecepcionModel.php';
require_once 'app/Model/CierreModel.php';
require_once 'app/Controller/InicioController.php';

$conexion = new Conexion();
$conector = $conexion->getConexion();

$rol = $_SESSION['rol'];
$area = $_SESSION['codigoArea'];
// Creacion de instancias de los modelos
$incidenciasModel =  new IncidenciaModel();
$recepcionesModel = new RecepcionModel();
$cierresModel = new CierreModel();
$controller = new InicioController();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <title>Sistema HelpDesk MDE</title>
  <link rel="icon" href="public/assets/logo.ico">
  <!-- Meta -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="" />
  <meta name="keywords" content="">
  <meta name="author" content="Phoenixcoded" />
  <!-- Favicon icon -->
  <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
  <!-- vendor css -->
  <link rel="stylesheet" href="dist/assets/css/style.css">

</head>

<body class="">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->
  <!-- [ navigation menu ] start -->
  <?php
  if ($rol === 'Administrador' || $rol === 'Soporte') {
    include('app/View/partials/admin/navbar.php');
    include('app/View/partials/admin/header.php');
    include('app/View/Inicio/admin/inicio.php');
  } else {
    include('app/View/partials/user/navbar.php');
    include('app/View/partials/user/header.php');
    include('app/View/Inicio/user/inicio.php');
  }
  ?>

  <!-- Required Js -->
  <script src="dist/assets/js/vendor-all.min.js"></script>
  <script src="dist/assets/js/plugins/bootstrap.min.js"></script>
  <script src="dist/assets/js/pcoded.min.js"></script>

  <!-- Apex Chart -->
  <script src="dist/assets/js/plugins/apexcharts.min.js"></script>


  <!-- custom-chart js -->
  <script src="dist/assets/js/pages/dashboard-main.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.css">

</body>

</html>