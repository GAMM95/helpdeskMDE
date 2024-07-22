<div class="pcoded-main-container overflow-y-auto">
  <div class="pcoded-content overflow-y-auto">
    <!-- Header -->
    <h1 class="text-2xl font-bold mb-4">Consultar Incidencia</h1>

    <form id="formConsultarIncidencia" action="modulo-rol.php" method="POST" class="border bg-white shadow-md p-6 w-full text-xs rounded-md mb-4">
      <div class="flex flex-wrap -mx-2 justify-center">
        <div class="w-full sm:w-1/4 px-2 mb-4 hidden">
          <label for="areaDisplay" class="block font-bold mb-1 text-xs">&Aacute;rea:</label>
          <input type="text" id="areaDisplay" name="areaDisplay" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['area']; ?>" readonly>
        </div>
        <div class="w-full md:w-1/5 px-2 mb-2 hidden">
          <label for="area" class="block font-bold mb-1">Area:</label>
          <input type="text" id="area" name="area" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['codigoArea']; ?>">
        </div>

        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="codigoPatrimonial" class="block mb-1 font-bold text-xs">Código Patrimonial:</label>
          <input type="text" id="txt_codigoPatrimonial" name="codigoPatrimonial" class="w-full border p-2 text-xs">
        </div>
        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="fecha" class="block mb-1 font-bold text-xs">Fecha:</label>
          <input type="date" id="fecha" name="fecha" class="w-full border p-2 text-xs">
        </div>
      </div>

      <!-- TODO: BOTONES DEL FORMULARIO -->
      <div class="flex justify-center space-x-2 mt-2">
        <button type="button" id="buscarIncidencia" class="bg-blue-500 text-white font-bold hover:bg-[#4c8cf5] py-2 px-4 rounded">
          Buscar
        </button>
        <button type="reset" class="bg-green-400 text-white font-bold hover:bg-gray-400 py-2 px-4 rounded">
          Limpiar
        </button>
      </div>
    </form>


    <!-- TODO: TABLA DE RESULTADOS DE LAS INCIDENCIAS -->
    <div class="relative shadow-md sm:rounded-lg">
      <div class="max-w-full overflow-hidden">
        <table id="tablaConsultarIncidencias" class="w-full text-xs text-left rtl:text-right text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-lime-300">
            <tr>
              <!-- <th scope="col" class="px-3 py-3">N°</th> -->
              <th scope="col" class="px-3 py-3">Fecha incidencia</th>
              <th scope="col" class="px-3 py-3">Categor&iacute;a</th>
              <th scope="col" class="px-3 py-3">Asunto</th>
              <th scope="col" class="px-3 py-3">C&oacute;digo Patrimonial</th>
              <th scope="col" class="px-3 py-3">Fecha Recepcion</th>
              <th scope="col" class="px-3 py-3">Prioridad</th>
              <th scope="col" class="px-3 py-3">Impacto</th>
              <th scope="col" class="px-3 py-3">Fecha Cierre</th>
              <th scope="col" class="px-3 py-3">Operatividad</th>
              <th scope="col" class="px-3 py-3">Usuario</th>
              <th scope="col" class="px-3 py-3">Estado</th>
            </tr>
          </thead>
          <tbody>
            <?php
            require_once './app/Model/IncidenciaModel.php';
            $incidenciaModel = new IncidenciaModel();
            $area_codigo = $_SESSION['codigoArea']; // Asumiendo que tengas almacenado el código de área en $_SESSION
            $incidencias = $incidenciaModel->listarIncidenciasUsuario($area_codigo);
            foreach ($incidencias as $incidencia) {
              echo "<tr class='bg-white hover:bg-green-100 hover:scale-[101%] transition-all border-b'>";
              // echo "<td class='px-3 py-2'>" . $incidencia['INC_numero'] . "</td>";
              echo "<td class='px-3 py-2'>" . $incidencia['fechaIncidenciaFormateada'] . "</td>";
              echo "<td class='px-3 py-2'>" . $incidencia['CAT_nombre'] . "</td>";
              echo "<td class='px-3 py-2'>" . $incidencia['INC_asunto'] . "</td>";
              echo "<td class='px-3 py-2'>" . $incidencia['INC_codigoPatrimonial'] . "</td>";
              echo "<td class='px-3 py-2'>" . $incidencia['fechaRecepcionFormateada'] . "</td>";
              echo "<td class='px-3 py-2'>" . $incidencia['PRI_nombre'] . "</td>";
              echo "<td class='px-3 py-2'>" . $incidencia['IMP_descripcion'] . "</td>";
              echo "<td class='px-3 py-2'>" . $incidencia['fechaCierreFormateada'] . "</td>";
              echo "<td class='px-3 py-2'>" . $incidencia['OPE_descripcion'] . "</td>";
              echo "<td class='px-3 py-2'>" . $incidencia['USU_nombre'] . "</td>";
              echo "<td class='px-3 py-2'>" . $incidencia['ESTADO'] . "</td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>
