<div class="pcoded-main-container">
  <div class="pcoded-content">
    <!-- Header -->
    <h1 class="text-2xl font-bold mb-4">Consultar recepci&oacute;n de incidencias</h1>

    <form id="formConsultarRecepcion" action="modulo-rol.php" method="POST" class="border bg-white shadow-md p-6 w-full text-xs rounded-md mb-4">
      <div class="flex flex-wrap -mx-2">
        <div class="w-full md:w-1/3 px-2 mb-2">
          <label for="area" class="block mb-1 font-bold text-xs">&Aacute;rea:</label>
          <select id="cbo_area" name="area" class="border p-2 w-full text-xs">
          </select>
        </div>
        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="codigoPatrimonial" class="block mb-1 font-bold text-xs">C&oacute;digo Patrimonial:</label>
          <input type="text" id="txt_codigoPatrimonial" name="codigoPatrimonial" class="w-full border p-2 text-xs">
        </div>
        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="fecha" class="block mb-1 font-bold text-xs">Fecha:</label>
          <input type="date" id="fecha" name="fecha" class="w-full border p-2 text-xs">
        </div>
      </div>

      <!-- TODO: BOTONES DEL FORMULARIO -->
      <div class="flex justify-center space-x-2 mt-2">
        <button type="button" id="buscarRecepcion" class="bg-blue-500 text-white font-bold hover:bg-[#4c8cf5] py-2 px-4 rounded">
          Buscar
        </button>
        <button type="reset" class="bg-green-400 text-white font-bold hover:bg-gray-400 py-2 px-4 rounded">
          Limpiar
        </button>
        <button type="submit" id="enviar" class="bg-blue-500 text-white font-bold hover:bg-[#4c8cf5] py-2 px-4 rounded">
          Todos
        </button>
      </div>
    </form>
    <!-- TODO: TABLA DE RESULTADOS DE LAS INCIDENCIAS -->
    <div class="relative shadow-md sm:rounded-lg">
      <div class="max-w-full overflow-hidden">
        <table id="tablaConsultarRecepciones" class="w-full text-xs text-left rtl:text-right text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-lime-300">
            <tr>
              <th scope="col" class="px-3 py-3">N°</th>
              <th scope="col" class="px-3 py-3">Fecha y Hora</th>
              <th scope="col" class="px-3 py-3">&Aacute;rea</th>
              <th scope="col" class="px-3 py-3">C&oacute;digo Patrimonial</th>
              <th scope="col" class="px-3 py-3">Categor&iacute;a</th>
              <th scope="col" class="px-3 py-3">Asunto</th>
              <th scope="col" class="px-3 py-3">Prioridad</th>
              <th scope="col" class="px-3 py-3">Impacto</th>
              <th scope="col" class="px-3 py-3">Usuario</th>
            </tr>
          </thead>
          <tbody>
            <?php
            require_once './app/Model/RecepcionModel.php';
            $recepcionModel = new RecepcionModel();
            $recepciones = $recepcionModel->listarRecepcionesAdministrador();
            foreach ($recepciones as $recepcion) {
              echo "<tr class='bg-white hover:bg-green-100 hover:scale-[101%] transition-all border-b'>";
              echo "<td class='px-3 py-2'>" . $recepcion['REC_numero'] . "</td>";
              echo "<td class='px-3 py-2'>" . $recepcion['fechaRecepcionFormateada'] . "</td>";
              echo "<td class='px-3 py-2'>" . $recepcion['ARE_nombre'] . "</td>";
              echo "<td class='px-3 py-2'>" . $recepcion['INC_codigoPatrimonial'] . "</td>";
              echo "<td class='px-3 py-2'>" . $recepcion['CAT_nombre'] . "</td>";
              echo "<td class='px-3 py-2'>" . $recepcion['INC_asunto'] . "</td>";
              echo "<td class='px-3 py-2'>" . $recepcion['PRI_nombre'] . "</td>";
              echo "<td class='px-3 py-2'>" . $recepcion['IMP_descripcion'] . "</td>";
              echo "<td class='px-3 py-2'>" . $recepcion['USU_nombre'] . "</td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</main>
<script src="./app/View/func/func_incidencias_admin.js"></script>
</body>

</html>