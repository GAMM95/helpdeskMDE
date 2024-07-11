<div class="pcoded-main-container">
  <div class="pcoded-content">
    <!-- Header -->
    <h1 class="text-2xl font-bold mb-4">Consultar Incidencia</h1>

    <form id="formConsultarIncidencia" action="consultar-incidencia-admin.php?action=consultar" method="GET" class="border bg-white shadow-md p-6 w-full text-sm rounded-md mb-4">
      <div class="flex flex-wrap -mx-2 justify-center">
        <!-- BUSCAR POR AREA -->
        <div class="w-full md:w-1/3 px-2 mb-2">
          <label for="area" class="block mb-1 font-bold text-xs">&Aacute;rea:</label>
          <select id="cbo_area" name="area" class="border p-2 w-full text-xs cursor-pointer">
            <!-- Aquí puedes cargar opciones de área dinámicamente si es necesario -->
          </select>
        </div>

        <!-- BUSCAR POR CODIGO PATRIMONIAL -->
        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="codigo_patrimonial" class="block mb-1 font-bold text-xs">C&oacute;digo Patrimonial:</label>
          <input type="text" id="codigoPatrimonial" name="codigoPatrimonial" class="border p-2 w-full text-xs" maxlength="12" pattern="\d{1,12}" inputmode="numeric" title="Ingrese solo dígitos" placeholder="Ingrese c&oacute;digo patrimonial">
        </div>

        <!-- BUSCAR POR FECHA DE INICIO-->
        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="fechaInicio" class="block mb-1 font-bold text-xs">Fecha Inicio:</label>
          <input type="date" id="fechaInicio" name="fechaInicio" class="w-full border p-2 text-xs cursor-pointer">
        </div>

        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="fechaFin" class="block mb-1 font-bold text-xs">Fecha Fin:</label>
          <input type="date" id="fechaFin" name="fechaFin" class="w-full border p-2 text-xs cursor-pointer">
        </div>
      </div>

      <!-- BOTONES DEL FORMULARIO -->
      <div class="flex justify-center space-x-2 mt-2">
        <button type="submit" id="buscar-incidencias" class="bg-blue-500 text-white font-bold hover:bg-[#4c8cf5] py-2 px-4 rounded">
          Buscar
        </button>
        <button type="reset" id="limpiarCampos" class="bg-green-400 text-white font-bold hover:bg-gray-400 py-2 px-4 rounded">
          Limpiar
        </button>
      </div>
    </form>


    <!-- TABLA DE RESULTADOS DE LAS INCIDENCIAS -->

    <div class="relative shadow-md sm:rounded-lg">
      <div class="max-w-full overflow-hidden">
        <table id="tablaConsultarIncidencias" class="w-full text-sm text-left rtl:text-right text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-lime-300">
            <tr>
              <th scope="col" class="px-3 py-3">N°</th>
              <th scope="col" class="px-3 py-3">Fecha y Hora</th>
              <th scope="col" class="px-3 py-3">&Aacute;rea</th>
              <th scope="col" class="px-3 py-3">C&oacute;digo Patrimonial</th>
              <th scope="col" class="px-3 py-3">Categor&iacute;a</th>
              <th scope="col" class="px-3 py-3">Asunto</th>
              <th scope="col" class="px-3 py-3">Descripci&oacute;n</th>
              <th scope="col" class="px-3 py-3">Documento</th>
              <th scope="col" class="px-3 py-3">Estado</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Aquí deberías inicializar $resultadoBusqueda con la consulta adecuada
            if (!empty($resultadoBusqueda)) {
              foreach ($resultadoBusqueda as $incidencia) {
                echo "<tr class='bg-white hover:bg-green-100 hover:scale-[101%] transition-all border-b'>";
                echo "<td class='px-3 py-2'>" . $incidencia['INC_numero'] . "</td>";
                echo "<td class='px-3 py-2'>" . $incidencia['fechaIncidenciaFormateada'] . "</td>";
                echo "<td class='px-3 py-2'>" . $incidencia['area'] . "</td>";
                echo "<td class='px-3 py-2'>" . $incidencia['codigoPatrimonial'] . "</td>";
                echo "<td class='px-3 py-2'>" . $incidencia['CAT_nombre'] . "</td>";
                echo "<td class='px-3 py-2'>" . $incidencia['INC_asunto'] . "</td>";
                echo "<td class='px-3 py-2'>" . $incidencia['INC_descripcion'] . "</td>";
                echo "<td class='px-3 py-2'>" . $incidencia['INC_documento'] . "</td>";
                echo "<td class='px-3 py-2'>" . $incidencia['EST_descripcion'] . "</td>";
                echo "</tr>";
              }
            } else {
              echo "<tr><td colspan='9' class='text-center py-4'>No se encontraron incidencias.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</main>
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
