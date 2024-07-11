<div class="pcoded-main-container">
  <div class="pcoded-content">
    <!-- Header -->
    <h1 class="text-2xl font-bold mb-4">Registro de &aacute;reas</h1>

    <!-- Contenedor principal para el fomulario y la tabla -->
    <div class="flex space-x-4">
      <div class="flex flex-col w-1/3">
        <form id="formarea" action="modulo-area.php" method="POST" class="border bg-white shadow-md p-6 w-full text-sm rounded-md">
          <input type="hidden" id="form-action" name="action" value="registrar">
          <!-- PRIMERA FILA  -->
          <div class="flex justify-center -mx-2 mb-5">
            <div class="flex items-center mb-4">
              <div class="flex items-center">
                <label for="CodArea" class="block font-bold mb-1 mr-3 text-lime-500">C&oacute;digo de &aacute;rea:</label>
                <input type="text" id="txt_codigoArea" name="CodArea" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-sm text-center" readonly disabled>
              </div>
            </div>
          </div>

          <!-- SEGUNDA FILA -->
          <div class="flex flex-wrap -mx-2">
            <div class="w-full sm:w-1/4 px-2 mb-2">
              <label for="NombreArea" class="block mb-1 font-bold text-sm">Nombre &aacute;rea:</label>
              <input type="text" id="txt_nombreArea" name="NombreArea" class="border p-2 w-full text-sm" pattern="[A-Za-z]+" title="Ingrese nueva área" required>
            </div>
          </div>

          <!-- BOTONES -->
          <div class="flex justify-center space-x-4">
            <button type="submit" id="guardar-area" class="bg-[#87cd51] text-white font-bold hover:bg-[#8ce83c] py-2 px-4 rounded">
              Guardar
            </button>
            <button type="button" id="editar-area" class="bg-blue-500 text-white font-bold hover:bg-blue-600 py-2 px-4 rounded">
              Editar
            </button>
            <button type="reset" id="nuevo-registro" class="bg-gray-500 text-white font-bold hover:bg-gray-600 py-2 px-4 rounded">
              Nuevo
            </button>
          </div>
        </form>
      </div>

      <!-- TABLA -->
      <div class="w-2/3">

        <div class="flex justify-between items-center mt-2">
          <input type="text" id="searchInput" class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-lime-300" placeholder="Buscar categor&iacute;a..." oninput="filtrarTablaPersonas()" />
        </div>
        <div class="relative max-h-[550px] overflow-x-hidden shadow-md sm:rounded-lg mt-5">
          <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <!-- ENCABEZADO DE LA TABLA -->
            <thead class="sticky top-2 text-xs text-gray-70 uppercase bg-lime-300">
              <tr>
                <th scope="col" class="px-10 py-3 w-1/6">N°</th>
                <th scope="col" class="px-6 py-3 w-5/6">&Aacute;rea</th>
              </tr>
            </thead>
            <!-- CUERPO DE LA TABLA -->
            <tbody>
              <?php
              $areas = $areaModel->listarArea();
              foreach ($areas as $area) {
                echo "<tr class='bg-white hover:bg-green-100 hover:scale-[101%] transition-all hover:cursor-pointer border-b'>";
                echo "<th scope='col' class='px-10 py-3 font-medium text-gray-900 whitespace-nowrap' data-codarea='" . htmlspecialchars($area['ARE_codigo']) . "'>";
                echo htmlspecialchars($area['ARE_codigo']);
                echo "</th>";
                echo "<th scope='row' class='px-6 py-3 font-medium text-gray-900 whitespace-nowrap' data-area='" . htmlspecialchars($area['ARE_nombre']) . "'>";
                echo htmlspecialchars($area['ARE_nombre']);
                echo "</th>";
                echo "</tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>