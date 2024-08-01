<?php
session_start();
// Verificar si no hay una sesión iniciada
if (!isset($_SESSION['usuario'])) {
  header("Location: index.php"); // Redirigir a la página de inicio de sesión si no hay sesión iniciada
  exit();
}

$action = $_GET['action'] ?? '';
$INC_numero = $_GET['INC_numero'] ?? '';

$area = isset($_GET['area']) ? $_GET['area'] : '';
$codigoPatrimonial = isset($_GET['codigoPatrimonial']) ? $_GET['codigoPatrimonial'] : '';
$fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : '';
$fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : '';

require_once 'app/Controller/incidenciaController.php';
$incidenciaController = new IncidenciaController();
$incidenciaModel = new IncidenciaModel();

if ($INC_numero != '') {
  global $incidenciaRegistrada;
  $incidenciaRegistrada = $incidenciaModel->obtenerIncidenciaPorId($INC_numero);
} else {
  $incidenciaRegistrada = null;
}

switch ($action) {
  case 'registrar':
    $incidenciaController->registrarIncidencia();
    break;
  case 'consultar':
    $resultadoBusqueda = NULL;
    if (!empty($area) || !empty($codigoPatrimonial) || !empty($fechaInicio) || !empty($fechaFin)) {
      $resultadoBusqueda = $incidenciaController->consultarIncidenciaAdministrador();
    } else {
      $error = "No se encontraron incidencias para los criterios especificados.";
    }
    break;
  default:
    break;
}
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

  <?php include('app/View/partials/admin/navbar.php'); ?>
  <?php include('app/View/partials/admin/header.php'); ?>
  <?php include('app/View/Consultar/admin/consultaIncidencia.php');  ?>

  <!-- Required Js -->
  <script src="dist/assets/js/vendor-all.min.js"></script>
  <script src="dist/assets/js/plugins/bootstrap.min.js"></script>
  <script src="dist/assets/js/pcoded.min.js"></script>
  <script src="dist/assets/js/plugins/apexcharts.min.js"></script>


  <!-- custom-chart js -->
  <script src="dist/assets/js/pages/dashboard-main.js"></script>

  <script src="./app/View/func/func_consulta_incidencia_admin.js"></script>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- Incluir CSS de Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- Incluir JS de Select2 -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>

</html>