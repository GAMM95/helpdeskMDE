<?php
session_start();
// Verificar si no hay una sesión iniciada
if (!isset($_SESSION['usuario'])) {
  header("Location: index.php"); // Redirigir a la página de inicio de sesión si no hay sesión iniciada
  exit();
}
$action = $_GET['action'] ?? '';
require_once './app/Controller/IncidenciaController.php';
require_once './app/Model/IncidenciaModel.php';

$incidenciaController = new IncidenciaController();
$incidenciaModel = new IncidenciaModel();

// Capturar los datos del formulario
$area = $_GET['area'] ?? '';
$estado = $_GET['estado'] ?? '';
$fechaInicio = $_GET['fechaInicio'] ?? '';
$fechaFin = $_GET['fechaFin'] ?? '';
$resultadoBusqueda = NULL;

if ($action === 'consultar') {
  // Depuración: mostrar los parámetros recibidos
  error_log("Área: " . $area);
  error_log("Estado: " . $estado);
  error_log("Fecha Inicio: " . $fechaInicio);
  error_log("Fecha Fin: " . $fechaFin);

  // Obtener los resultados de la búsqueda
  $resultadoBusqueda = $incidenciaController->consultarIncidenciasPendientesAdministrador($area, $estado, $fechaInicio, $fechaFin);

  // Imprime el resultado para depuración
  error_log("Resultado de la consulta: " . print_r($resultadoBusqueda, true));

  // Dibujar tabla de consultas
  $html = '';
  if (!empty($resultadoBusqueda)) {
    foreach ($resultadoBusqueda as $incidencia) {
      $html .= '<tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($incidencia['INC_numero_formato']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($incidencia['fechaIncidenciaFormateada']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($incidencia['ARE_nombre']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($incidencia['INC_codigoPatrimonial']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($incidencia['CAT_nombre']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($incidencia['INC_asunto']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($incidencia['INC_documento']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center text-xs align-middle">';

      $estadoDescripcion = htmlspecialchars($incidencia['ESTADO']);
      $badgeClass = '';
      switch ($estadoDescripcion) {
        case 'ABIERTO':
          $badgeClass = 'badge-light-danger';
          break;
        case 'RECEPCIONADO':
          $badgeClass = 'badge-light-success';
          break;
        case 'CERRADO':
          $badgeClass = 'badge-light-primary';
          break;
        default:
          $badgeClass = 'badge-light-secondary';
          break;
      }

      $html .= '<label class="badge ' . $badgeClass . '">' . $estadoDescripcion . '</label>';
      $html .= '</td></tr>';
    }
  } else {
    $html = '<tr><td colspan="8" class="text-center py-3">No se encontraron incidencias pendientes de cierre.</td></tr>';
  }

  // Devolver el HTML de las filas
  echo $html;
  exit;
} else {
  // Si no hay acción, obtener la lista de incidencias
  $resultadoBusqueda = $incidenciaModel->listarIncidenciasPendientesAdministrador();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <title>Sistema de Gestión de Incidencias</title>
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

  <?php include_once('app/View/partials/admin/navbar.php'); ?>
  <?php include_once('app/View/partials/admin/header.php'); ?>
  <?php include_once('app/View/Consultar/admin/consultaIncidencia.php');  ?>

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
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>

</html>