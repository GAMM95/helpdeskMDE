<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <?php
    global $incidenciaConsultada;
    ?>

    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Consulta de incidencias</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-clipboard"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Consultas</a></li>
              <li class="breadcrumb-item"><a href="">Incidencias</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- Formulario Consulta de incidencias -->
    <form id="formConsultarIncidencia" action="consultar-incidencia-admin.php?action=consultar" method="GET" class="card table-card bg-white shadow-md p-6 w-full text-xs mb-2">
      <input type="hidden" id="form-action" name="action" value="consultar">

      <div class="flex flex-wrap items-center -mx-2 justify-center space-x-2">
        <!-- BUSCAR POR AREA (Más grande) -->
        <div class="w-full md:w-2/5 px-2 mb-2">
          <label for="area" class="block mb-1 font-bold text-xs">&Aacute;rea:</label>
          <select id="area" name="area" class="border p-2 w-full text-xs cursor-pointer">
          </select>
        </div>

        <!-- BUSCAR POR CODIGO PATRIMONIAL (Más pequeño) -->
        <div class="w-full md:w-1/6 px-2 mb-2">
          <label for="estado" class="block mb-1 font-bold text-xs">Estado:</label>
          <select id="estado" name="estado" class="border p-2 w-full text-xs cursor-pointer">
          </select>
        </div>

        <!-- BUSCAR POR FECHA DE INICIO (Más pequeño) -->
        <div class="w-full md:w-1/6 px-2 mb-2">
          <label for="fechaInicio" class="block mb-1 font-bold text-xs">Fecha Inicio:</label>
          <input type="date" id="fechaInicio" name="fechaInicio" class="w-full border p-2 text-xs cursor-pointer rounded-md">
        </div>

        <!-- BUSCAR POR FECHA DE FIN (Más pequeño) -->
        <div class="w-full md:w-1/6 px-2 mb-2">
          <label for="fechaFin" class="block mb-1 font-bold text-xs">Fecha Fin:</label>
          <input type="date" id="fechaFin" name="fechaFin" class="w-full border p-2 text-xs cursor-pointer rounded-md">
        </div>
      </div>

      <!-- BOTONES DEL FORMULARIO -->
      <div class="flex justify-center space-x-2 mt-2">
        <button type="submit" id="buscar-incidencias" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-search"></i>Buscar </button>
        <button type="button" id="limpiarCampos" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-refresh-cw"></i>Limpiar </button>
      </div>
      <!-- Fin de Botones -->
    </form>
    <!-- Fin de formulario de consultas -->

    <!-- Recopilacion de valores de cada input y combobox -->
    <script>
      document.getElementById('area').value = '<?php echo $incidenciaConsultada ? $incidenciaConsultada['ARE_codigo'] : ''; ?>';
      document.getElementById('estado').value = '<?php echo $incidenciaConsultada ? $incidenciaConsultada['EST_codigo'] : ''; ?>';
      document.getElementById('fechaInicio').value = '<?php echo $incidenciaConsultada ? $incidenciaConsultada['fechaInicio'] : ''; ?>';
      document.getElementById('fechaFin').value = '<?php echo $incidenciaConsultada ? $incidenciaConsultada['fechaFin'] : ''; ?>';
    </script>

    <!-- TABLA DE RESULTADOS DE LAS INCIDENCIAS -->
    <div class="relative shadow-md sm:rounded-lg">
      <div class="max-w-full overflow-hidden">
        <table id="tablaConsultarIncidencias" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-lime-300">
            <tr>
              <th scope="col" class="px-3 py-2">N°</th>
              <th scope="col" class="px-3 py-2">Fecha</th>
              <th scope="col" class="px-3 py-2">&Aacute;rea</th>
              <th scope="col" class="px-3 py-2">C&oacute;digo Patrimonial</th>
              <th scope="col" class="px-3 py-2">Categor&iacute;a</th>
              <th scope="col" class="px-3 py-2">Asunto</th>
              <th scope="col" class="px-3 py-2">Documento</th>
              <th scope="col" class="px-3 py-2">Estado</th>
            </tr>
          </thead>
          <tbody>
            <?php

            require_once './app/Model/IncidenciaModel.php';
            $incidenciaModel = new IncidenciaModel();
            $incidencias = $incidenciaModel->listarIncidenciasAdministrador();
            if (!empty($incidencias)) {
              foreach ($incidencias as $incidencia) {
                echo "<tr class='hover:bg-green-100 hover:scale-[101%] transition-all border-b'>";
                echo "<td class='px-3 py-2'>" . $incidencia['INC_numero'] . "</td>";
                echo "<td class='px-3 py-2'>" . $incidencia['fechaIncidenciaFormateada'] . "</td>";
                echo "<td class='px-3 py-2'>" . $incidencia['ARE_nombre'] . "</td>";
                echo "<td class='px-3 py-2'>" . $incidencia['INC_codigoPatrimonial'] . "</td>";
                echo "<td class='px-3 py-2'>" . $incidencia['CAT_nombre'] . "</td>";
                echo "<td class='px-3 py-2'>" . $incidencia['INC_asunto'] . "</td>";
                echo "<td class='px-3 py-2'>" . $incidencia['INC_documento'] . "</td>";
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
                // Renderización del contenido con la clase correspondiente
                echo "<label class='badge {$badgeClass}'>{$estadoDescripcion}</label>";
                echo "</td>";
                echo "</tr>";
              }
            } else {
              echo "<tr>
              <td colspan='9' class='text-center py-4'>No se encontraron incidencias.</td>
            </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Fin tabla de resultados de incidencias -->
  </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>