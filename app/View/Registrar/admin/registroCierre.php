<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <?php
    global $cierreRegistrado;
    require_once './app/Model/RecepcionModel.php';
    $recepcionModel = new RecepcionModel();
    $limit = 2; //Numero de filas por pagina
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
    $start = ($page - 1) * $limit; // Calcula el índice de inicio
    $totalRecepcionesSinCerrar = $recepcionModel->contarRecepcionesSinCerrar();
    $totalPages = ceil($totalRecepcionesSinCerrar / $limit);
    $recepciones = $recepcionModel->obtenerRecepcionesSinCerrar($start, $limit);
    ?>

    <!-- Segundo Apartado - Formulario de registro de Recepcion de incidencia -->
    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Cierre de incidencias</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-edit"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Registros</a></li>
              <li class="breadcrumb-item"><a href="">Cierres</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- TODO: TITULO TABLA DE INCIDENCIAS NO RECEPCIONADAS -->
    <div id="noRecepcion" class="flex justify-between items-center mb-2">
      <h1 class="text-xl text-gray-400">Incidencias recepcionadas</h1>
      <input type="text" id="searchInput" class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-lime-300" placeholder="Buscar..." oninput="filtrarTablaRecepcionesSinCerrar()" />
    </div>

    <!-- TODO: TABLA DE RECEPCIONES SIN CERRAR -->
    <input type="hidden" id="recepcionCount" value="<?php echo count($recepciones); ?>">

    <div>
      <div id="tablaContainer" class="relative max-h-[300px] overflow-x-hidden shadow-md sm:rounded-lg">
        <table id="tablaRecepcionesSinCerrar" class="w-full text-xs text-left rtl:text-right text-gray-500">
          <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-lime-300">
            <tr>
              <th scope="col" class="px-6 py-3">N°</th>
              <th scope="col" class="px-6 py-3">Fecha recepci&oacute;n</th>
              <th scope="col" class="px-6 py-3">&Aacute;rea</th>
              <th scope="col" class="px-6 py-3">C&oacute;digo Patrimonial</th>
              <th scope="col" class="px-6 py-3">Asunto</th>
              <th scope="col" class="px-6 py-3">Prioridad</th>
              <th scope="col" class="px-6 py-3">Impacto</th>
              <th scope="col" class="px-6 py-3">Usuario</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recepciones as $recepcion) : ?>
              <tr class='bg-white hover:bg-green-100 hover:scale-[101%] transition-all hover:cursor-pointer border-b '>
                <th scope='row' class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap'><?= $recepcion['REC_numero']; ?></th>
                <td class='px-6 py-4'><?= $recepcion['fechaRecepcionFormateada']; ?></th>
                <td class='px-6 py-4'><?= $recepcion['ARE_nombre']; ?></th>
                <td class='px-6 py-4'><?= $recepcion['INC_codigoPatrimonial']; ?></th>
                <td class='px-6 py-4'><?= $recepcion['INC_asunto']; ?></th>
                <td class='px-6 py-4'><?= $recepcion['PRI_nombre']; ?></th>
                <td class='px-6 py-4'><?= $recepcion['IMP_descripcion']; ?></th>
                <td class='px-6 py-4'><?= $recepcion['USU_nombre']; ?></th>
              </tr>
            <?php endforeach; ?>

            <?php if (empty($recepciones)) : ?>
              <tr>
                <td colspan="8" class="text-center py-4">No hay incidencias recepionadas sin cerrar.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <?php if ($totalPages > 0) : ?>
        <div class="flex justify-end items-center mt-1">
          <?php if ($page > 1) : ?>
            <a href="#" class="px-4 py-2 bg-gray-200 text-gray-800 hover:bg-gray-300" onclick="changePageTablaSinCerrar(<?php echo $page - 1; ?>)">&lt;</a>
          <?php endif; ?>
          <span class="mx-2">P&aacute;gina <?php echo $page; ?> de <?php echo $totalPages; ?></span>
          <?php if ($page < $totalPages) : ?>
            <a href="#" class="px-4 py-2 bg-gray-200 text-gray-800 hover:bg-gray-300" onclick="changePageTablaSinCerrar(<?php echo $page + 1; ?>)">&gt;</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Segundo Apartado - Formulario de registro de Cierre de incidencia -->
    <h1 class="text-xl font-bold text-gray-800 mt-4 mb-2">Cierre de incidencia</h1>
    <!-- TODO: Formulario -->
    <form id="formCierre" action="registro-cierre-admin.php?action=registrar" method="POST" class="card table-card bg-white shadow-md p-6 w-full text-xs">

      <!-- NUMERO DE RECEPCION -->
      <div class="flex justify-center mx-2 mb-4">
        <div class="flex-1 max-w-[500px] px-2 mb-2 flex items-center ">
          <label for="recepcion" class="block font-bold mb-1 mr-3 text-lime-500">N&uacute;mero de Recepci&oacute;n:</label>
          <input type="text" id="recepcion" name="recepcion" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" readonly>
        </div>

        <!-- INPUT ESCONDIDO PARA EL NUMERO DE CIERRE -->
        <div class="flex-1 max-w-[500px] px-2 mb-2 flex items-center">
          <label for="num_cierre" class="block font-bold mb-1 mr-3 text-lime-500">N&uacute;mero Cierre:</label>
          <input type="text" id="num_cierre" name="num_cierre" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" disabled>
        </div>
      </div>

      <!-- TODO: PRIMERA FILA DEL FORMULARIO -->
      <div class="flex flex-wrap -mx-2">
        <!-- FECHA DE CIERRE -->
        <div class="w-full md:w-1/4 px-2 mb-2 hidden">
          <label for="fecha_cierre" class="block font-bold mb-1">Fecha de Cierre:</label>
          <input type="date" id="fecha_cierre" name="fecha_cierre" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo date('Y-m-d'); ?>" readonly>
        </div>

        <!-- HORA DE CIERRE -->
        <div class="w-full md:w-1/4 px-2 mb-2 hidden">
          <label for="hora" class="block font-bold mb-1">Hora:</label>
          <?php
          // Establecer la zona horaria deseada
          date_default_timezone_set('America/Lima');
          $fecha_actual = date('Y-m-d');
          // Obtener la hora actual en formato de 24 horas (HH:MM)
          $hora_actual = date('H:i:s');
          ?>
          <input type="text" id="hora" name="hora" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $hora_actual; ?>" readonly>
        </div>

        <!-- USUARIO QUE HARA EL CIERRE -->
        <div class="w-full md:w-1/4 px-2 mb-2 hidden">
          <label for="usuarioDisplay" class="block font-bold mb-1">Usuario:</label>
          <input type="text" id="usuarioDisplay" name="usuarioDisplay" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['usuario']; ?>" readonly>
        </div>
        <div class="w-full md:w-1/4 px-2 mb-2 hidden">
          <label for="usuario" class="block font-bold mb-1">Usuario:</label>
          <input type="text" id="usuario" name="usuario" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['codigoUsuario']; ?>" readonly>
        </div>

        <!-- OPERATIVIDAD -->
        <div class="w-full md:w-1/5 px-2 mb-2">
          <label for="operatividad" class="block font-bold mb-1 text-xs">Operatividad:</label>
          <select id="cbo_operatividad" name="operatividad" class="border p-2 w-full text-xs cursor-pointer">
          </select>
        </div>

        <!-- ASUNTO DEL CIERRE -->
        <div class="w-full md:w-2/5 px-2 mb-2">
          <label for="asunto" class="block mb-1 font-bold text-xs">Asunto:</label>
          <input type="text" id="asunto" name="asunto" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese asunto de cierre">
        </div>

        <!-- DOCUMENTO DE CIERRE -->
        <div class="w-full md:w-2/5 px-2 mb-2">
          <label for="documento" class="block mb-1 font-bold text-xs">Documento:</label>
          <input type="text" id="documento" name="documento" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese documento de cierre">
        </div>
      </div>

      <!-- TODO: SEGUNDA FILA DEL FORMULARIO -->
      <div class="flex flex-wrap -mx-2 ">
        <!-- DIAGNOSTICO DEL CIERRE -->
        <div class="w-full md:w-1/3 px-2 mb-2">
          <label for="diagnostico" class="block mb-1 font-bold text-xs">Diagn&oacute;stico:</label>
          <input type="text" id="diagnostico" name="diagnostico" class="border p-2 w-full text-xs" placeholder="Ingrese diagnóstico">
        </div>

        <!-- SOLUCION DE LA INCIDENCIA -->
        <div class="w-full md:w-1/3 px-2 mb-2">
          <label for="solucion" class="block mb-1 font-bold text-xs">Soluci&oacute;n:</label>
          <input type="text" id="solucion" name="solucion" class="border p-2 w-full text-xs" placeholder="Ingrese solución (opcional)">
        </div>

        <!-- RECOMENDACIONES -->
        <div class="w-full md:w-1/3 px-2 mb-2">
          <label for="recomendaciones" class="block mb-1 font-bold text-xs">Recomendaciones:</label>
          <input type="text" id="recomendaciones" name="recomendaciones" class="border p-2 w-full text-xs" placeholder="Ingrese recomendaciones (opcional)">
        </div>
      </div>

      <!-- TODO: RECOPILACION DE VALORES DE CADA INPUT Y COMBOBOX     -->
      <script>
        document.getElementById('recepcion').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['REC_codigo'] : ''; ?>';
        document.getElementById('fecha').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['CIE_fecha'] : $fecha_actual; ?>';
        document.getElementById('hora').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['REC_hora'] : $hora_actual; ?>';
        document.getElementById('operatividad').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['OPE_codigo'] : ''; ?>';
        document.getElementById('usuarioDisplay').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['codigoUsuario'] : $_SESSION['usuario']; ?>';
        document.getElementById('usuario').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['codigoUsuario'] : $_SESSION['codigoUsuario']; ?>';
        document.getElementById('asunto').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['REC_asunto'] : ''; ?>';
        document.getElementById('documento').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['REC_documento'] : ''; ?>';
        document.getElementById('diagnostico').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['REC_diagnostico'] : ''; ?>';
        document.getElementById('solucion').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['REC_solucion'] : ''; ?>';
        document.getElementById('recomendaciones').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['REC_recomendaciones'] : ''; ?>';
      </script>

      <!-- TODO: BOTONES DEL FORMULARIO -->
      <div class="flex justify-center space-x-4 mt-2">
        <button type="submit" id="guardar-cierre" class="bg-[#87cd51] text-white font-bold hover:bg-[#8ce83c] py-2 px-4 rounded">Guardar</button>
        <button type="button" class="bg-blue-500 text-white font-bold hover:bg-blue-600 py-2 px-4 rounded">Editar</button>
        <button type="button" id="nuevoRegistro" class="bg-gray-500 text-white font-bold hover:bg-gray-600 py-2 px-4 rounded w-full md:w-auto mt-2 md:mt-0">Nuevo</button>
      </div>
    </form>

    <!-- TODO: TABLA DE INCIDENCIAS REGISTRADAS -->
    <?php
    require_once './app/Model/CierreModel.php';

    $cierreModel = new CierreModel();
    $limit = 5; // Número de filas por página
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
    $start = ($page - 1) * $limit; // Calcula el índice de inicio

    // Obtiene el total de registros
    $totalCierres = $cierreModel->contarIncidenciasCerradas();
    $totalPages = ceil($totalCierres / $limit);

    // Obtiene las incidencias para la página actual
    $cierres = $cierreModel->obtenerIncidenciasCerradas($start, $limit);
    ?>

    <div>
      <div class="relative max-h-[800px] mt-2 overflow-x-hidden shadow-md sm:rounded-lg">
        <table class="w-full text-xs text-left rtl:text-right text-gray-500">
          <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-blue-300">
            <tr>
              <th scope="col" class="px-6 py-3">Incidencia</th>
              <th scope="col" class="px-6 py-3">Fecha Incidencia</th>
              <th scope="col" class="px-6 py-3">&Aacute;rea</th>
              <th scope="col" class="px-6 py-3">C&oacute;digo patrimonial</th>
              <th scope="col" class="px-6 py-3">Fecha Cierre</th>
              <th scope="col" class="px-6 py-3">Asunto Cierre</th>
              <th scope="col" class="px-6 py-3">Documento Cierre</th>
              <th scope="col" class="px-6 py-3">Operatividad</th>
              <th scope="col" class="px-6 py-3">Usuario</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cierres as $incidencia) : ?>
              <tr class='second-table bg-white hover:bg-green-100 hover:scale-[101%] transition-all border-b'>
                <th scope='row' class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap'> <?= $incidencia['INC_numero']; ?></th>
                <td class='px-6 py-4'><?= $incidencia['fechaIncidenciaFormateada']; ?></td>
                <td class='px-6 py-4'><?= $incidencia['ARE_nombre']; ?></td>
                <td class='px-6 py-4'><?= $incidencia['INC_codigoPatrimonial']; ?></td>
                <td class='px-6 py-4'><?= $incidencia['fechaCierreFormateada']; ?></td>
                <td class='px-6 py-4'><?= $incidencia['CIE_asunto']; ?></td>
                <td class='px-6 py-4'><?= $incidencia['CIE_documento']; ?></td>
                <td class='px-6 py-4'><?= $incidencia['OPE_descripcion']; ?></td>
                <td class='px-6 py-4'><?= $incidencia['USU_nombre']; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <div class="flex justify-center items-center mt-4">
        <?php if ($page > 1) : ?>
          <a href="#" class="px-4 py-2 bg-gray-200 text-gray-800 hover:bg-gray-300" onclick="changePage(<?php echo $page - 1; ?>)">&lt;</a>
        <?php endif; ?>
        <span class="mx-2">P&aacute;gina <?php echo $page; ?> de <?php echo $totalPages; ?></span>
        <?php if ($page < $totalPages) : ?>
          <a href="#" class="px-4 py-2 bg-gray-200 text-gray-800 hover:bg-gray-300" onclick="changePage(<?php echo $page + 1; ?>)">&gt;</a>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>
<!-- <script src="./app/View/func/func_cierre.js"></script> -->
<script src="./app/View/func/func_cierre_admin.js"></script>
<script src="https://cdn.tailwindcss.com"></script>