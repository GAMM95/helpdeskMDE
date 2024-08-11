<?php

require_once './app/Controller/IncidenciaController.php';
require_once './app/Model/IncidenciaModel.php';

$action = $_GET['action'] ?? '';

$incidenciaController = new IncidenciaController();
$incidenciaModel = new IncidenciaModel();

$resultadoBusqueda = NULL;

if ($action === 'consultar') {
  $resultadoBusqueda = $incidenciaController->consultarIncidenciaAdministrador();
} else {
  $resultadoBusqueda =  $incidenciaModel->listarIncidenciasAdministrador();
}

if (!empty($resultadoBusqueda)) {
  foreach ($resultadoBusqueda as $incidencia) {
    echo "<tr class='hover:bg-green-100 hover:scale-[101%] transition-all border-b'>";
    echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['INC_numero']) . "</td>";
    echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['fechaIncidenciaFormateada']) . "</td>";
    echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['ARE_nombre']) . "</td>";
    echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['INC_codigoPatrimonial']) . "</td>";
    echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['CAT_nombre']) . "</td>";
    echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['INC_asunto']) . "</td>";
    echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['INC_documento']) . "</td>";
    echo "<td class='px-3 py-2 text-center text-xs align-middle'>";

    // Asignación de clases según el estado
    $estadoDescripcion = htmlspecialchars($incidencia['EST_descripcion']);
    $badgeClass = '';
    switch ($estadoDescripcion) {
      case 'Abierta':
        $badgeClass = 'badge-light-danger';
        break;
      case 'Recepcionado':
        $badgeClass = 'badge-light-success';
        break;
      case 'Cerrado':
        $badgeClass = 'badge-light-primary';
        break;
      default:
        $badgeClass = 'badge-light-secondary';
        break;
    }
    echo "<label class='badge {$badgeClass}'>{$estadoDescripcion}</label>";
    echo "</td>";
    echo "</tr>";
  }
} else {
  echo "<tr><td colspan='8' class='text-center py-4'>No se encontraron incidencias.</td></tr>";
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
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>

</html>