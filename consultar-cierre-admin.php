<?php
session_start();
$action = $_GET['action'] ?? '';
$state = $_GET['state'] ?? '';

require_once 'app/Controller/cierreController.php';
require_once './app/Model/CierreModel.php';

$cierreController = new cierreController();
$cierreModel = new CierreModel();

// Capturar los datos del fomrulario
$area = $_GET['area'] ?? '';
$codigoPatrimonial = $_GET['codigoPatrimonial'] ?? '';
$fechaInicio = $_GET['fechaInicio'] ?? '';
$fechaFin = $_GET['fechaFin'] ?? '';
$resultadoBusqueda = NULL;

if ($action = 'consultar') {
  // Depuración: mostrar los parámetros recibidos
  error_log("Área: " . $area);
  error_log("Codigo Patrimonial: " . $codigoPatrimonial);
  error_log("Fecha Inicio: " . $fechaInicio);
  error_log("Fecha Fin: " . $fechaFin);
  // Obtener los resultados de la búsqueda
  $resultadoBusqueda = $cierreController->consultarCierres($area, $codigoPatrimonial, $fechaInicio, $fechaFin);

  // // Dibujar tabla de consultas
  // $html = '';
  // require_once './app/Model/CierreModel.php';
  // $cierreModel = new CierreModel();
  // $resultadoBusqueda = $cierreModel->buscarCierres($area, $codigoPatrimonial, $fechaInicio, $fechaFin);
  // if (!empty($resultadoBusqueda)) {
  //   foreach ($resultadoBusqueda as $cierre) {
  //     $html .= '<tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">';
  //     $html .= '<td class="px-3 py-2">' . htmlspecialchars($cierre['INC_numero']) . '</td>';
  //     $html .= '<td class="px-3 py-2">' . htmlspecialchars($cierre['fechaCierreFormateada']) . '</td>';
  //     $html .= '<td class="px-3 py-2">' . htmlspecialchars($cierre['ARE_nombre']) . '</td>';
  //     $html .= '<td class="px-3 py-2">' . htmlspecialchars($cierre['INC_codigoPatrimonial']) . '</td>';
  //     $html .= '<td class="px-3 py-2">' . htmlspecialchars($cierre['INC_asunto']) . '</td>';
  //     $html .= '<td class="px-3 py-2">' . htmlspecialchars($cierre['CIE_documento']) . '</td>';
  //     $html .= '<td class="px-3 py-2">' . htmlspecialchars($cierre['PRI_nombre']) . '</td>';
  //     $html .= '<td class="px-3 py-2 text-center text-xs align-middle">';

  //     $estadoDescripcion = htmlspecialchars($cierre['ESTADO']);
  //     $badgeClass = '';
  //     switch ($estadoDescripcion) {
  //       case 'Cerrado':
  //         $badgeClass = 'badge-light-primary';
  //         break;
  //       default:
  //         $badgeClass = 'badge-light-secondary';
  //         break;
  //     }

  //     $html .= '<label class="badge ' . $badgeClass . '">' . $estadoDescripcion . '</label>';
  //     $html .= '</td></tr>';
  //   }
  // } else {
  //   $html = '<tr><td colspan="8" class="text-center py-4">No se encontraron incidencias.</td></tr>';
  // }

  // // Devolver el HTML de las filas
  // echo $html;
  // exit;
} else {
  // Si no hay acción, obtener la lista de incidencias
  $resultadoBusqueda = $cierreModel->listarCierresAdministrador();
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
  <!-- [ Pre-loader ] End -->

  <!-- [ navigation menu ] start -->
  <?php include('app/View/partials/admin/navbar.php');  ?>
  <?php include('app/View/partials/admin/header.php');  ?>
  <?php include('app/View/Consultar/admin/consultaCierre.php'); ?>
  <!-- [ Main Content ] end -->


  <!-- Required Js -->
  <script src="dist/assets/js/vendor-all.min.js"></script>
  <script src="dist/assets/js/plugins/bootstrap.min.js"></script>
  <script src="dist/assets/js/pcoded.min.js"></script>
  <script src="dist/assets/js/plugins/apexcharts.min.js"></script>


  <!-- custom-chart js -->
  <script src="dist/assets/js/pages/dashboard-main.js"></script>

  <script src="./app/View/func/func_consulta_cierre_admin.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>

</html>