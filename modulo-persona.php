<?php
$action = $_GET['action'] ?? '';
$CodPersona = $_GET['PER_codigo'] ?? '';

require_once 'app/Controller/PersonaController.php';
require_once 'app/Model/PersonaModel.php';

// Crear una instancia del controlador PersonaController
$personaController = new PersonaController();
$personaModel = new PersonaModel();

if ($CodPersona != '') {
  global $PersonaRegistrada;
  $PersonaRegistrada = $personaModel->obtenerPersonaPorId($CodPersona);
} else {
  $PersonaRegistrada = null;
}

switch ($action) {
  case 'registrar':
    $personaController->registrarPersona();
    break;
  case 'editar':
    // $personaController->actualizarPersona();
    break;
  default:
    break;
}
?>

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
  include('app/View/partials/admin/navbar.php');
  ?>
  <!-- [ navigation menu ] end -->

<!-- [ Header ] start -->
<?php
include('app/View/partials/admin/header.php');
?>
<!-- [ Header ] end -->

  <!-- [ Main Content ] start -->
  <?php
  include('app/View/Mantenimiento/mantenedorPersona.php');
  ?>
  <!-- [ Main Content ] end -->


  <!-- Required Js -->
  <script src="dist/assets/js/vendor-all.min.js"></script>
  <script src="dist/assets/js/plugins/bootstrap.min.js"></script>
  <script src="dist/assets/js/pcoded.min.js"></script>
  <script src="dist/assets/js/plugins/apexcharts.min.js"></script>


  <!-- custom-chart js -->
  <script src="dist/assets/js/pages/dashboard-main.js"></script>

  <script src="./app/View/func/func_persona.js"></script>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>

</html>