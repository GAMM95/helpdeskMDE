<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Consulta de Cierres</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-clipboard"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Consultas</a></li>
              <li class="breadcrumb-item"><a href="">Cierres</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- Formulario Consulta de cierres -->
    <form id="formConsultarCierre" action="consultar-cierre-admin.php?action=consultar" method="GET" class="card table-card  bg-white shadow-md p-6 w-full text-xs mb-2">
      <div class="flex flex-wrap -mx-2 justify-center">
        <!-- BUSCAR POR AREA -->
        <div class="w-full md:w-1/3 px-2 mb-2">
          <label for="area" class="block mb-1 font-bold text-xs">&Aacute;rea:</label>
          <select id="area" name="area" class="border p-2 w-full text-xs cursor-pointer">
          </select>
        </div>

        <!-- BUSCAR POR CODIGO PATRIMONIAL -->
        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="codigoPatrimonial" class="block mb-1 font-bold text-xs">C&oacute;digo Patrimonial:</label>
          <input type="text" id="codigoPatrimonial" name="codigoPatrimonial" class="border p-2 w-full text-xs rounded-md" maxlength="12" pattern="\d{1,12}" inputmode="numeric" title="Ingrese solo dígitos" placeholder="Ingrese c&oacute;digo patrimonial">
        </div>

        <!-- BUSCAR POR FECHA DE INICIO-->
        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="fechaInicio" class="block mb-1 font-bold text-xs">Fecha Inicio:</label>
          <input type="date" id="fechaInicio" name="fechaInicio" class="w-full border p-2 text-xs cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
        </div>

        <!-- Buscar por fecha fin -->
        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="fechaFin" class="block mb-1 font-bold text-xs">Fecha Fin:</label>
          <input type="date" id="fechaFin" name="fechaFin" class="w-full border p-2 text-xs cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
        </div>
      </div>

      <!-- BOTONES DEL FORMULARIO -->
      <div class="flex justify-center space-x-2 mt-2">
        <button type="submit" id="buscar-cierres" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-search"></i>Buscar</button>
        <button type="button" id="limpiarCampos" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-refresh-cw"></i>Nueva consulta</button>
      </div>
      <!-- Fin de Botones -->
    </form>
    <!-- Fin de formulario de consultas -->

    <!-- Tabla de resultados para cierres -->
    <div class="relative shadow-md sm:rounded-lg">
      <div class="max-w-full overflow-hidden">
        <table id="tablaCierres" class="w-full text-xs text-left rtl:text-right text-gray-500 bg-white">
          <!-- Encabezado de la tabla -->
          <thead class="text-xs text-gray-700 uppercase bg-lime-300">
            <tr>
              <th scope="col" class="px-3 py-3">N°</th>
              <th scope="col" class="px-3 py-3">Fecha</th>
              <th scope="col" class="px-3 py-3">&Aacute;rea</th>
              <th scope="col" class="px-3 py-3">C&oacute;digo Patrimonial</th>
              <th scope="col" class="px-3 py-3">Asunto</th>
              <th scope="col" class="px-3 py-3">Documento</th>
              <th scope="col" class="px-3 py-3">Prioridad</th>
              <th scope="col" class="px-3 py-3">Estado</th>
            </tr>
          </thead>
          <!-- Fin de encabezado -->

          <!-- Cuerpo de la tabla -->
          <tbody>
            <?php
            // require_once './app/Model/CierreModel.php';
            // $cierreModel = new CierreModel();
            // $resultadoBusqueda = $cierreModel->listarCierresAdministrador();
            if (!empty($resultadoBusqueda)): ?>
              <?php foreach ($resultadoBusqueda as $cierre): ?>
                <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                  <td class="px-3 py-2"><?= htmlspecialchars($cierre['INC_numero']) ?></td>
                  <td class="px-3 py-2"><?= htmlspecialchars($cierre['fechaCierreFormateada']) ?></td>
                  <td class="px-3 py-2"><?= htmlspecialchars($cierre['ARE_nombre']) ?></td>
                  <td class="px-3 py-2"><?= htmlspecialchars($cierre['INC_codigoPatrimonial']) ?></td>
                  <td class="px-3 py-2"><?= htmlspecialchars($cierre['INC_asunto']) ?></td>
                  <td class="px-3 py-2"><?= htmlspecialchars($cierre['CIE_documento']) ?></td>
                  <td class="px-3 py-2"><?= htmlspecialchars($cierre['PRI_nombre']) ?></td>
                  <td class="px-3 py-2 text-center text-xs align-middle">
                    <?php
                    $estadoDescripcion = htmlspecialchars($cierre['EST_descripcion']);
                    $badgeClass = '';
                    switch ($estadoDescripcion) {
                      case 'Cerrado':
                        $badgeClass = 'badge-light-primary';
                        break;
                      default:
                        $badgeClass = 'badge-light-secondary';
                        break;
                    }
                    ?>
                    <label class="badge <?= $badgeClass ?>"><?= $estadoDescripcion ?></label>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center py-4">No se encontraron cierres.</td>
              </tr>
            <?php endif; ?>
          </tbody>
          <!-- Fin de cuerpo de tabla -->
        </table>
      </div>
    </div>
    <!-- Fin de tabla de resultado de cierres -->
  </div>
</div>

<script src="https://cdn.tailwindcss.com"></script>