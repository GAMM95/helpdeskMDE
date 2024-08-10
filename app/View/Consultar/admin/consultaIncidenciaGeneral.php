<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
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
    <form id="formConsultarIncidencia" action="consultar-incidencia-admin.php?action=consultar" method="GET" class="card table-card  bg-white shadow-md p-6 w-full text-xs mb-2">
      <div class="flex flex-wrap -mx-2 justify-center">
        <!-- BUSCAR POR AREA -->
        <div class="w-full md:w-1/3 px-2 mb-2">
          <label for="area" class="block mb-1 font-bold text-xs">&Aacute;rea:</label>
          <select id="cbo_area" name="area" class="border p-2 w-full text-xs cursor-pointer">
          </select>
        </div>

        <!-- BUSCAR POR CODIGO PATRIMONIAL -->
        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="codigo_patrimonial" class="block mb-1 font-bold text-xs">C&oacute;digo Patrimonial:</label>
          <input type="text" id="codigoPatrimonial" name="codigoPatrimonial" class="border p-2 w-full text-xs rounded-md" maxlength="12" pattern="\d{1,12}" inputmode="numeric" title="Ingrese solo dígitos" placeholder="Ingrese c&oacute;digo patrimonial">
        </div>

        <!-- BUSCAR POR FECHA DE INICIO-->
        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="fechaInicio" class="block mb-1 font-bold text-xs">Fecha Inicio:</label>
          <input type="date" id="fechaInicio" name="fechaInicio" class="w-full border p-2 text-xs cursor-pointer rounded-md">
        </div>

        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="fechaFin" class="block mb-1 font-bold text-xs">Fecha Fin:</label>
          <input type="date" id="fechaFin" name="fechaFin" class="w-full border p-2 text-xs cursor-pointer rounded-md">
        </div>
      </div>

      <!-- BOTONES DEL FORMULARIO -->
      <div class="flex justify-center space-x-2 mt-2">
        <button type="submit" id="buscar-incidencias" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-search"></i>Buscar</button>
        <button type="button" id="limpiarCampos" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-refresh-cw"></i>Limpiar</button>
      </div>
      <!-- Fin de Botones -->
    </form>
    <!-- Fin de formulario de consultas -->

    <!-- TABLA DE RESULTADOS DE LAS INCIDENCIAS -->
    <div class="relative shadow-md sm:rounded-lg">
      <div class="max-w-full overflow-hidden">
        <table id="tablaConsultarIncidencias" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-lime-300">
            <tr>
              <th scope="col" class="px-3 py-3">N°</th>
              <th scope="col" class="px-3 py-3">Fecha incidencia</th>
              <th scope="col" class="px-3 py-3">Categor&iacute;a</th>
              <th scope="col" class="px-3 py-3">Asunto</th>
              <th scope="col" class="px-3 py-3">C&oacute;digo Patrimonial</th>
              <!-- <th scope="col" class="px-3 py-3">Fecha Recepcion</th> -->
              <th scope="col" class="px-3 py-3">Prioridad</th>
              <!-- <th scope="col" class="px-3 py-3">Impacto</th> -->
              <th scope="col" class="px-3 py-3">Fecha Cierre</th>
              <!-- <th scope="col" class="px-3 py-3">Condic&oacute;n</th> -->
              <!-- <th scope="col" class="px-3 py-3">Usuario</th> -->
              <th scope="col" class="px-3 py-3">Estado</th>
            </tr>
          </thead>
          <tbody>
            <?php
            require_once './app/Model/IncidenciaModel.php';
            $incidenciaModel = new IncidenciaModel();
            $incidencias = $incidenciaModel->listarIncidenciasAdministradorGeneral();
            if (!empty($incidencias)) {
              foreach ($incidencias as $incidencia) {
                echo "<tr class='bg-white hover:bg-green-100 hover:scale-[101%] transition-all border-b'>";
                echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['INC_numero']) . "</td>";
                echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['fechaIncidenciaFormateada']) . "</td>";
                echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['CAT_nombre']) . "</td>";
                echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['INC_asunto']) . "</td>";
                echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['INC_codigoPatrimonial']) . "</td>";
                // echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['fechaRecepcionFormateada']) . "</td>";
                echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['PRI_nombre']) . "</td>";
                // echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['IMP_descripcion']) . "</td>";
                echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['fechaCierreFormateada']) . "</td>";
                // echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['CON_descripcion']) . "</td>";
                // echo "<td class='px-3 py-2'>" . htmlspecialchars($incidencia['USU_nombre']) . "</td>";

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

                // Renderización del estado con la clase correspondiente
                echo "<td class='px-3 py-2 text-center text-xs align-middle'>";
                echo "<label class='badge {$badgeClass}'>{$estadoDescripcion}</label>";
                echo "</td>";
                echo "</tr>";
              }
            } else {
              echo "<tr>
          <td colspan='12' class='text-center py-4'>No se encontraron incidencias.</td>
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
<script>
  $(document).ready(function() {
    $('#buscar-incidencias').submit(function(event) {
      event.preventDefault(); // Evitar el envío normal del formulario

      // Obtener los valores de los campos del formulario
      var area = $('#cbo_area').val();
      var codigoPatrimonial = $('#codigoPatrimonial').val();
      var fechaInicio = $('#fechaInicio').val();
      var fechaFin = $('#fechaFin').val();

      // Enviar la solicitud AJAX
      $.ajax({
        url: 'consultar-incidencia-admin.php?action=consultar', // URL a la que se envía la solicitud
        method: 'GET', // Método HTTP (GET es el predeterminado para formularios GET)
        data: {
          area: area,
          codigoPatrimonial: codigoPatrimonial,
          fechaInicio: fechaInicio,
          fechaFin: fechaFin
        },
        success: function(response) {
          // Actualizar la tabla de incidencias con los resultados recibidos
          $('#tablaConsultarIncidencias tbody').html(response);
        },
        error: function(xhr, status, error) {
          // Manejar errores si los hubiera
          console.error(error);
          toastr.error('Ocurrió un error al buscar incidencias.');
        }
      });
    });
  });
</script>