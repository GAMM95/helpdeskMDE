<div class="pcoded-main-container">
  <div class="pcoded-content">
    <!-- Header -->
    <h1 class="text-2xl font-bold mb-4 ">Registro de categor&iacute;as</h1>

    <!-- Conteneder principal para el forumalrio y la tabla -->
    <div class="flex space-x-4">

      <!-- TODO: FORMULARIO -->
      <div class="flex flex-col w-1/3">
        <form id="formcategoria" action="modulo-categoria.php" method="POST" class="border bg-white shadow-md p-6 w-full text-xs rounded-md">
          <input type="hidden" id="form-action" name="action" value="registrar">
          <!-- PRIMERA FILA -->
          <div class="flex justify-center -mx-2 mb-5 hidden">
            <div class="flex items-center mb-4">
              <div class="flex items-center">
                <label for="CodCategoria" class="block font-bold mb-1 mr-3 text-lime-500">C&oacute;digo de categor&iacute;a:</label>
                <input type="text" id="txt_codigoCategoria" name="CodCategoria" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" readonly disabled>
              </div>
            </div>
          </div>

          <!-- SEGUNDA FILA -->
          <div class="flex flex-wrap -mx-2">
            <div class="w-full px-2 mb-3">
              <label for="NombreCategoria" class="block mb-1 font-bold text-xs">Nombre de categor&iacute;a:</label>
              <input type="text" id="txt_nombreCategoria" name="NombreCategoria" class="border p-2 w-full text-xs" pattern="[A-Za-z]+" title="Ingrese categoría" placeholder="Ingrese nueva categor&iacute;a" required>
            </div>
          </div>

          <!-- BOTONES -->
          <div class="flex justify-center space-x-4">
            <button type="submit" id="guardar-categoria" class="bg-[#87cd51] text-white font-bold hover:bg-[#8ce83c] py-2 px-4 rounded">
              Guardar
            </button>
            <button type="button" id="editar-categoria" class="bg-blue-500 text-white font-bold hover:bg-blue-600 py-2 px-4 rounded">
              Editar
            </button>
            <button type="reset" id="nuevo-registro" class="bg-gray-500 text-white font-bold hover:bg-gray-600 py-2 px-4 rounded">
              Nuevo
            </button>
          </div>
        </form>
      </div>

      <!-- TODO: TABLA DE CATEGORIAS -->
      <div class="w-2/3">
        <div class="relative max-h-[800px] overflow-x-hidden shadow-md sm:rounded-lg">
          <table class="w-full text-xs text-left rtl:text-right text-gray-500">
            <!-- ENCABEZADO DE LA TABLA -->
            <thead class="sticky top-0 text-xs text-gray-70 uppercase bg-lime-300">
              <tr>
                <th scope="col" class="px-10 py-2 w-1/6 hidden">N°</th>
                <th scope="col" class="px-6 py-2 w-5/6">Categor&iacute;a</th>
              </tr>
            </thead>
            <!-- CUERPO DE LA TABLA -->
            <tbody>
              <?php
              $categorias = $categoriaModel->listarCategorias();
              foreach ($categorias as $categoria) {
                echo "<tr class='bg-white hover:bg-green-100 hover:scale-[101%] transition-all hover:cursor-pointer border-b'>";
                echo "<th scope='col' class='px-10 py-2 font-medium text-gray-900 whitespace-nowrap hidden' data-codcategoria='" . htmlspecialchars($categoria['CAT_codigo']) . "'>";
                echo htmlspecialchars($categoria['CAT_codigo']);
                echo "</th>";
                echo "<th scope='row' class='px-6 py-2 font-medium text-gray-900 whitespace-nowrap' data-categoria='" . htmlspecialchars($categoria['CAT_nombre']) . "'>";
                echo htmlspecialchars($categoria['CAT_nombre']);
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