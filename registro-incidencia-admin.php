<?php
session_start();
// Verificar si no hay una sesión iniciada
if (!isset($_SESSION['usuario'])) {
  header("Location: index.php"); // Redirigir a la página de inicio de sesión si no hay sesión iniciada
  exit();
}
$action = $_GET['action'] ?? '';
$state = $_GET['state'] ?? '';
$INC_numero = $_GET['INC_numero'] ?? '';

require_once 'app/Controller/incidenciaController.php';
require_once 'app/Model/incidenciaModel.php';

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
  case 'editar':
    $incidenciaController->actualizarIncidenciaAdministrador();
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
  <meta name="author" content="GAMM95" />

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
  <?php include('app/View/Registrar/admin/registroIncidencias.php'); ?>

  <!-- Required Js -->
  <script src="dist/assets/js/vendor-all.min.js"></script>
  <script src="dist/assets/js/plugins/bootstrap.min.js"></script>
  <script src="dist/assets/js/pcoded.min.js"></script>
  <script src="dist/assets/js/plugins/apexcharts.min.js"></script>

  <script src="dist/assets/js/pages/dashboard-main.js"></script>

  <script src="./app/View/func/func_incidencia_admin.js"></script>
  <script src="./app/View/func/Reports/reporteIncidencia.js"></script>
  <script src="./app/View/func/registrar-actualizar-incidencia.js"></script>
  <script src="./app/View/func/tipoBien.js"></script>

  <!-- <script src="https://cdn.tailwindcss.com"></script> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- <script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script> -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.20/jspdf.plugin.autotable.min.js"></script>
</body>

</html>