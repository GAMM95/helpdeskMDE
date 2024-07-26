<div class="pcoded-main-container">
  <div class="pcoded-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h5 class="m-b-10">Dashboard</h5>
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
            <h2 class="m-0"><?php echo $cantidades['incidencias_mes_actual']; ?></h2>
            <span class="text-c-blue">INCIDENCIAS</span>
            <?php
            setlocale(LC_TIME, 'es_ES.UTF-8',  'Spanish_Spain', 'Spanish');
            $nombreMes = strftime('%B');
            ?>
            <p class="mb-3 mt-3">Total de incidencias en el mes de <?php echo $nombreMes; ?> de <?php echo date('Y'); ?>.</p>

          </div>
          <div id="support-chart"></div> <!-- Asegúrate de tener este div -->
          <div class="card-footer bg-primary text-white">
            <div class="row text-center">
              <div class="col">
                <h4 class="m-0 text-white"><?php echo $cantidades['pendientes_mes_actual']; ?></h4>
                <span>Abiertas</span>
              </div>
              <div class="col">
                <h4 class="m-0 text-white"><?php echo $cantidades['recepciones_mes_actual']; ?></h4>
                <span>Recepcionadas</span>
              </div>
              <div class="col">
                <h4 class="m-0 text-white"><?php echo $cantidades['cierres_mes_actual']; ?></h4>
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
                  <h5> <?php echo $cantidades['usuarios_total']; ?></h5>
                  <span>Usuarios</span>
                </div>
              </div>
            </div>
            <div class="col-sm-6 card-body">
              <div class="row">
                <div class="col-sm-4">
                  <i class="icon feather icon-flag text-c-red mb-1 d-block"></i>
                </div>
                <div class="col-sm-8 text-md-center">
                  <h5><?php echo $cantidades['cantidadAreas']; ?></h5>
                  <span>&Aacute;reas</span>
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
                  <h5><?php echo $cantidades['cantidadIncidencias']; ?></h5>
                  <span>Incidencias</span>
                </div>
              </div>
            </div>
            <div class="col-sm-6 card-body">
              <div class="row">
                <div class="col-sm-4">
                  <i class="icon feather icon-target text-c-yellow mb-1 d-block"></i>
                </div>
                <div class="col-sm-8 text-md-center">
                  <h5><?php echo $cantidades['cantidadCategorias']; ?></h5>
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
              <h6 class="text-sm">&Aacute;rea con m&aacute;s incidencias</h6>
              <h5 class="text-white"><?php echo $cantidades['areaMasIncidencia']; ?></h5>
            </div>
          </div>
        </div>
        <!-- widget primary card end -->
      </div>
      <!-- table card-1 end -->


      <!-- prject ,team member start -->
      <div class="col-xl-12 col-md-12">
        <div class="card table-card">
          <div class="card-header">
            <h5>Nuevas incidencias</h5>
            <div class="card-header-right">
              <div class="btn-group card-option">
                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
          </div>

          <!-- TABLA DE NUEVAS INCIDENCIAS -->
          <?php
          require_once './app/Model/IncidenciaModel.php';

          $incidenciaModel = new IncidenciaModel();
          $incidencias = $incidenciaModel->listarNuevasIncidenciasInicioAdmin();
          ?>

          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <!-- Encabezado -->
                <thead>
                  <tr>
                    <th class="text-center">Usuario</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Categor&iacute;a</th>
                    <th class="text-center">Incidencia</th>
                    <th class="text-center">Documento</th>
                    <th class="text-center">Estado</th>
                  </tr>
                </thead>

                <!-- Cuerpo -->
                <tbody>
                  <?php foreach ($incidencias as $incidencia) : ?>
                    <tr>
                      <!-- Usuario y area -->
                      <td>
                        <div class="d-inline-block align-middle">
                          <img class="img-radius wid-40 align-top m-r-15" src="dist/assets/images/user/avatar.jpg" alt="User-Profile-Image">
                          <div class="d-inline-block">
                            <h6><?= htmlspecialchars($incidencia['Usuario']); ?></h6>
                            <p class="text-muted m-b-0"><?= htmlspecialchars($incidencia['ARE_nombre']); ?></p>
                          </div>
                        </div>
                      </td>
                      <!-- Fecha de la incidencia -->
                      <td class="text-center"><?= htmlspecialchars($incidencia['fechaIncidenciaFormateada']); ?></td>
                      <!-- Categoria de la incidencia -->
                      <td class="text-center"><?= htmlspecialchars($incidencia['CAT_nombre']); ?></td>
                      <!-- Descripcion de la incidencia -->
                      <td class="text-center"><?= htmlspecialchars($incidencia['INC_asunto']); ?></td>
                      <!-- Documento de la incidencia -->
                      <td class="text-center"><?= htmlspecialchars($incidencia['INC_documento']); ?></td>
                      <!-- Estado -->
                      <!-- <td class="text-center"><?= htmlspecialchars($incidencia['ESTADO']); ?></td> -->
                      <td class="text-center">
                        <?php
                        $estado = htmlspecialchars($incidencia['ESTADO']);
                        $badgeClass = '';

                        switch ($estado) {
                          case 'Abierta':
                            $badgeClass = 'badge-light-danger';
                            break;
                          case 'Recepcionado':
                            $badgeClass = 'badge-light-success'; // Asegúrate de que esta clase existe o ajusta según el diseño deseado
                            break;
                          case 'Cerrado': // Reemplaza 'OtroEstado' con los nombres de estados adicionales
                            $badgeClass = 'badge-light-primary';
                            break;
                          default:
                            $badgeClass = 'badge-light-secondary'; // Clase por defecto para estados no especificados
                            break;
                        }
                        ?>
                        <label class="badge <?= $badgeClass; ?>"><?= $estado; ?></label>
                      </td>
                    </tr>

                    <!-- <td class="text-right"><label class="badge badge-light-danger">Low</label></td>
<td class="text-right"><label class="badge badge-light-success">medium</label></td>
<td class="text-right"><label class="badge badge-light-primary">high</label></td> -->
                  <?php endforeach; ?>

                  <?php if (empty($incidencias)) : ?>
                    <tr>
                      <td colspan="5" class="text-center py-3">No hay incidencias para hoy.</td>
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

<!-- <td class="text-right"><label class="badge badge-light-danger">Low</label></td>
<td class="text-right"><label class="badge badge-light-success">medium</label></td>
<td class="text-right"><label class="badge badge-light-primary">high</label></td> -->