<?php
session_start();
// Verificar si no hay una sesión iniciada
if (!isset($_SESSION['usuario'])) {
  header("Location: index.php"); // Redirigir a la página de inicio de sesión si no hay sesión iniciada
  exit();
}
$action = $_GET['action'] ?? '';
$state = $_GET['state'] ?? '';
$REC_numero = $_GET['REC_numero'] ?? '';

require_once 'app/Controller/recepcionController.php';
require_once 'app/Model/recepcionModel.php';
require_once 'app/Model/incidenciaModel.php';

$recepcionController = new RecepcionController();
$recepcionModel = new RecepcionModel();
$incidenciaModel = new IncidenciaModel();

// Paginacion de la tabla de incidencias sin recepcionar
$limit = 2; // Número de filas por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
$start = ($page - 1) * $limit; // Calcula el índice de inicio
// Obtener el total de registros
$totalIncidenciasSinRecepcionar = $incidenciaModel->contarIncidenciasAdministrador();
$totalPages = ceil($totalIncidenciasSinRecepcionar / $limit);
// Listar las incidencias para la pagina actual
$resultadoIncidencias = $incidenciaModel->listarIncidenciasRegistroAdmin($start, $limit);

// Paginacion para la tabla de incidencias recepcionadas
$limite = 5; // Numero de filas para la tabla de recepciones
$pageRecepciones =  isset($_GET['pageRecepciones']) ? (int)$_GET['pageRecepciones'] : 1; // pagina de la tabla actual
$inicio = ($pageRecepciones - 1) * $limite;
// Obtener el total de registros
$totalRecepciones = $recepcionModel->contarRecepcionesSinCerrar();
$totalPagesRecepciones = ceil($totalRecepciones / $limite);
// Listar incidencias recepcionadas
$resultadoRecepciones = $recepcionModel->listarRecepciones($inicio, $limite);

if ($REC_numero != '') {
  global $recepcionRegistrada;
  $recepcionRegistrada = $recepcionModel->obtenerRecepcionPorId($REC_numero);
} else {
  $incidenciaRegistrada = null;
}

switch ($action) {
  case 'registrar':
    $recepcionController->registrarRecepcion();
    break;
  case 'editar':
    $recepcionController->actualizarRecepcion();
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
  <!-- [ Pre-loader ] End -->

  <?php include('app/View/partials/admin/navbar.php');  ?>
  <?php include('app/View/partials/admin/header.php');   ?>
  <?php include('app/View/Registrar/admin/registroRecepcion.php');    ?>

  <!-- Required Js -->
  <script src="dist/assets/js/vendor-all.min.js"></script>
  <script src="dist/assets/js/plugins/bootstrap.min.js"></script>
  <script src="dist/assets/js/pcoded.min.js"></script>
  <script src="dist/assets/js/plugins/apexcharts.min.js"></script>


  <!-- custom-chart js -->
  <script src="dist/assets/js/pages/dashboard-main.js"></script>
  <script src="./app/View/func/func_recepcion_admin.js"></script>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>

</html>