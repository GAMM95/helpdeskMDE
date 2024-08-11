<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h5 class="text-2xl font-bold mb-2">Dashboard</h5>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a>
              </li>
              <li class="breadcrumb-item"><a href="inicio.php">Inicio</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
      <!-- Widget primary-success card start -->
      <div id="grafico" class="col-md-12 col-xl-8">
        <div class="card support-bar overflow-hidden">
          <div class="card-body pb-0">
            <!-- Contar el total de incidencias en el mes -->
            <h2 class="m-0 text-lg font-bold"><?php echo $cantidades['incidencias_mes_actual']; ?></h2>
            <span class="text-c-blue font-bold">INCIDENCIAS</span>

            <?php
            // setlocale(LC_TIME, 'es_ES.UTF-8',  'Spanish_Spain', 'Spanish');
            // $nombreMes = strftime('%B');


            // Establecer la configuración regional para el idioma español
            setlocale(LC_TIME, 'es_ES.UTF-8', 'Spanish_Spain', 'Spanish');

            // Establecer la zona horaria
            date_default_timezone_set('America/Lima');

            // Crear un objeto DateTime para la fecha actual
            $dateTimeObj = new DateTime('now', new DateTimeZone('America/Lima'));

            // Crear un objeto IntlDateFormatter para formatear la fecha
            $formatter = new IntlDateFormatter(
              'es_ES', // Configuración regional para el idioma español
              IntlDateFormatter::NONE, // Sin formato de fecha completa
              IntlDateFormatter::NONE, // Sin formato de tiempo
              null, // Usar la zona horaria predeterminada
              null, // Calendario gregoriano
              'MMMM' // Formato para mes y año
            );

            // Obtener el nombre del mes
            $nombreMes = $formatter->format($dateTimeObj);
            ?>

            <p class="mb-3 mt-3">Total de incidencias en el mes de <?php echo $nombreMes; ?> del <?php echo date('Y'); ?>.</p>

          </div>
          <div id="support-chart"></div> <!-- Asegúrate de tener este div -->
          <div class="card-footer bg-primary text-white">
            <div class="row text-center">
              <div class="col">
                <h4 class="m-0 text-white font-bold"><?php echo $cantidades['pendientes_mes_actual']; ?></h4>
                <span>Abiertas</span>
              </div>
              <div class="col">
                <h4 class="m-0 text-white font-bold"><?php echo $cantidades['recepciones_mes_actual']; ?></h4>
                <span>Recepcionadas</span>
              </div>
              <div class="col">
                <h4 class="m-0 text-white font-bold"><?php echo $cantidades['cierres_mes_actual']; ?></h4>
                <span>Cerradas</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Incluye las librerías necesarias -->
      <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script>
        // Pasar datos de PHP a JavaScript
        var incidenciasData = <?php echo json_encode([
                                (int)$cantidades['pendientes_mes_actual'],
                                (int)$cantidades['recepciones_mes_actual'],
                                (int)$cantidades['cierres_mes_actual']
                              ]); ?>;
      </script>



      <!-- table card-1 start -->
      <div id="contador" class="col-md-12 col-xl-4">
        <div class="card flat-card">
          <div class="row-table">
            <div class="col-sm-6 card-body br">
              <div class="row">
                <div class="col-sm-4">
                  <i class="icon feather icon-users text-c-green mb-1 d-block"></i>
                </div>
                <div class="col-sm-8 text-md-center">
                  <h5 class="font-bold text-lg"> <?php echo $cantidades['usuarios_total']; ?></h5>
                  <span>Usuarios activos</span>
                </div>
              </div>
            </div>
            <div class="col-sm-6 card-body">
              <div class="row">
                <div class="col-sm-4">
                  <i class="icon feather icon-flag text-c-red mb-1 d-block"></i>
                </div>
                <div class="col-sm-8 text-md-center">
                  <h5 class="font-bold text-lg"><?php echo $cantidades['cantidadAreas']; ?></h5>
                  <span>&Aacute;reas de la MDE</span>
                </div>
              </div>
            </div>
          </div>
          <div class="row-table">
            <div class="col-sm-6 card-body br">
              <div class="row">
                <div class="col-sm-4">
                  <i class="icon feather icon-file-text text-c-blue mb-1 d-block"></i>
                </div>
                <div class="col-sm-8 text-md-center">
                  <h5 class="font-bold text-lg"><?php echo $cantidades['cantidadIncidencias']; ?></h5>
                  <span>Incidencias totales</span>
                </div>
              </div>
            </div>
            <div class="col-sm-6 card-body">
              <div class="row">
                <div class="col-sm-4">
                  <i class="icon feather icon-target text-c-yellow mb-1 d-block"></i>
                </div>
                <div class="col-sm-8 text-md-center">
                  <h5 class="font-bold text-lg"><?php echo $cantidades['cantidadCategorias']; ?></h5>
                  <span>categor&iacute;as</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- widget primary card start -->
        <div class="card flat-card widget-primary-card">
          <div class="row-table">
            <div class="col-sm-3 card-body">
              <i class="feather icon-alert-triangle"></i>
            </div>
            <div class="col-sm-9">
              <h6 class="text-xs mb-2">&Aacute;rea con m&aacute;s incidencias</h6>
              <h5 class="text-white font-bold"><?php echo $cantidades['areaMasIncidencia']; ?></h5>
            </div>
          </div>
        </div>
        <!-- widget primary card end -->
      </div>
      <!-- table card-1 end -->


      <!-- prject ,team member start -->
      <div class="col-xl-12 col-md-12">
        <div class="card table-card">


          <div class="card-header py-2 flex items-center justify-between">
            <!-- Título alineado a la izquierda -->
            <h5 class="flex-shrink-0">Incidencias del d&iacute;a</h5>

            <div class="flex-grow flex justify-center">
              <form action="" method="get" id="fechaForm">
                <input type="date" name="fecha" class="form-input mr-4 cursor-pointer" aria-label="Seleccionar fecha" value="<?= isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d'); ?>" onchange="document.getElementById('fechaForm').submit();">
              </form>
            </div>

            <!-- Botones alineados a la derecha -->
            <div class="btn-group card-option flex-shrink-0">
              <button type="button" class="btn " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="feather icon-more-horizontal"></i>
              </button>
              <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> Maximizar</span><span style="display:none"><i class="feather icon-minimize"></i> Restaurar</span></a></li>
                <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> Minimizar</span><span style="display:none"><i class="feather icon-plus"></i> Expandir</span></a></li>
                <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> Recargar</a></li>
                <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> Eliminar</a></li>
              </ul>
            </div>

          </div>

          <!-- TABLA DE NUEVAS INCIDENCIAS -->
          <?php
          require_once './app/Model/IncidenciaModel.php';

          // Obtener la fecha desde un formulario o definirla aquí
          $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d'); // Por defecto, usa la fecha actual si no se especifica ninguna

          $incidenciaModel = new IncidenciaModel();
          $incidencias = $incidenciaModel->listarIncidenciasAdminFecha($fecha);
          ?>

          <div id="tabla-incidencias" class="card-body p-0">
            <div class="table-responsive overflow-y-auto max-h-96">
              <table class="table table-hover mb-0 text-xs">
                <!-- Encabezado -->
                <thead>
                  <tr>
                    <th class="text-center">N°</th>
                    <th class="text-center">Usuario</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Incidencia</th>
                    <th class="text-center">Documento</th>
                    <th class="text-center">Estado</th>
                  </tr>
                </thead>

                <!-- Cuerpo -->
                <tbody>
                  <?php foreach ($incidencias as $incidencia) : ?>
                    <tr class="align-middle"> <!-- Añadido 'align-middle' para centrar verticalmente -->
                      <!-- código de la incidencia -->
                      <td class="text-center text-xs align-middle"><?= htmlspecialchars($incidencia['INC_numero']); ?></td>
                      <!-- Usuario y área -->
                      <td>
                        <div class="flex items-center">
                          <img class="rounded-full w-10 h-10 mr-4" src="dist/assets/images/user/avatar.jpg" alt="User-Profile-Image">
                          <div>
                            <h6 class="text-xs"><?= htmlspecialchars($incidencia['Usuario']); ?></h6>
                            <p class="text-muted text-xs"><?= htmlspecialchars($incidencia['ARE_nombre']); ?></p>
                          </div>
                        </div>
                      </td>
                      <!-- Fecha de la incidencia -->
                      <td class="text-center text-xs align-middle"><?= htmlspecialchars($incidencia['fechaIncidenciaFormateada']); ?></td>
                      <!-- Descripción de la incidencia -->
                      <td class="text-center text-xs align-middle"><?= htmlspecialchars($incidencia['INC_asunto']); ?></td>
                      <!-- Documento de la incidencia -->
                      <td class="text-center text-xs align-middle"><?= htmlspecialchars($incidencia['INC_documento']); ?></td>
                      <!-- Estado -->
                      <td class="text-center text-xs align-middle">
                        <?php
                        $estado = htmlspecialchars($incidencia['ESTADO']);
                        $badgeClass = '';

                        switch ($estado) {
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
                        ?>
                        <label class="badge <?= $badgeClass; ?>"><?= $estado; ?></label>
                      </td>
                    </tr>
                  <?php endforeach; ?>

                  <?php if (empty($incidencias)) : ?>
                    <tr>
                      <td colspan="6" class="text-center py-3">No hay incidencias para la fecha seleccionada.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>

              </table>
            </div>
          </div>
          <!-- Fin tabla de nuevas incidencias -->

        </div>

      </div>
    </div>
    <!-- [ Main Content ] end -->
  </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>
<script src="./app/View/func/func_inicio_admin.js"></script>