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

    <div class="mb-2">
      <div id="tablaContainer" class="relative max-h-[300px] overflow-x-hidden shadow-md sm:rounded-lg">
        <table id="tablaRecepcionesSinCerrar" class="w-full text-xs text-left rtl:text-right text-gray-500 bg-white">
          <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-lime-300">
            <tr>
              <th scope="col" class="px-6 py-2 hidden">N° recepcion</th>
              <th scope="col" class="px-6 py-2">N° inc</th>
              <th scope="col" class="px-6 py-2">Fecha recepci&oacute;n</th>
              <th scope="col" class="px-6 py-2">&Aacute;rea</th>
              <th scope="col" class="px-6 py-2">C&oacute;digo Patrimonial</th>
              <th scope="col" class="px-6 py-2">Asunto</th>
              <th scope="col" class="px-6 py-2">Documento</th>
              <th scope="col" class="px-6 py-2">Prioridad</th>
              <!-- <th scope="col" class="px-6 py-2">Impacto</th> -->
              <th scope="col" class="px-6 py-2">Usuario</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recepciones as $recepcion) : ?>
              <tr class='hover:bg-green-100 hover:scale-[101%] transition-all hover:cursor-pointer border-b '>
                <th scope='row' class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap hidden'><?= $recepcion['REC_numero']; ?></th>
                <th scope='row' class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap'><?= $recepcion['INC_numero']; ?></th>
                <td class='px-6 py-1'><?= $recepcion['fechaRecepcionFormateada']; ?></th>
                <td class='px-6 py-1'><?= $recepcion['ARE_nombre']; ?></th>
                <td class='px-6 py-1'><?= $recepcion['INC_codigoPatrimonial']; ?></th>
                <td class='px-6 py-1'><?= $recepcion['INC_asunto']; ?></th>
                <td class='px-6 py-1'><?= $recepcion['INC_documento']; ?></th>
                <td class='px-6 py-1'><?= $recepcion['PRI_nombre']; ?></th>
                <!-- <td class='px-6 py-1'><?= $recepcion['IMP_descripcion']; ?></th> -->
                <td class='px-6 py-1'><?= $recepcion['Usuario']; ?></th>
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

    <!-- TODO: Formulario -->
    <form id="formCierre" action="registro-cierre-admin.php?action=registrar" method="POST" class="card table-card  bg-white shadow-md p-6 w-full text-xs mb-2">

      <!-- NUMERO DE RECEPCION -->
      <div class="flex justify-center mx-2 mb-4">
        <div class="flex-1 max-w-[500px] px-2 mb-2 flex items-center ">
          <label for="num_incidencia" class="block font-bold mb-1 mr-3 text-lime-500">N&uacute;mero de Incidencia:</label>
          <input type="text" id="num_incidencia" name="num_incidencia" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" readonly>
        </div>

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
          <label for="operatividad" class="block font-bold mb-1 text-xs">Condici&oacute;n: *</label>
          <select id="cbo_operatividad" name="operatividad" class="border p-2 w-full text-xs cursor-pointer rounded-md">
          </select>
        </div>

        <!-- ASUNTO DEL CIERRE -->
        <div class="w-full md:w-2/5 px-2 mb-2">
          <label for="asunto" class="block mb-1 font-bold text-xs">Asunto: *</label>
          <input type="text" id="asunto" name="asunto" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese asunto de cierre">
        </div>

        <!-- DOCUMENTO DE CIERRE -->
        <div class="w-full md:w-2/5 px-2 mb-2">
          <label for="documento" class="block mb-1 font-bold text-xs">Documento de Cierre: *</label>
          <input type="text" id="documento" name="documento" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese documento de cierre">
        </div>
      </div>

      <!-- DIAGNOSTICO DEL CIERRE -->
      <div class="flex flex-wrap -mx-2">
        <div class="w-full px-2 mb-2">
          <label for="diagnostico" class="block mb-1 font-bold text-xs">Diagn&oacute;stico:</label>
          <input type="text" id="diagnostico" name="diagnostico" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese diagnóstico (opcional)">
        </div>
      </div>

      <!-- TODO: SEGUNDA FILA DEL FORMULARIO -->
      <div class="flex flex-wrap -mx-2 mb-3">
        <!-- SOLUCION DE LA INCIDENCIA -->
        <div class="w-full md:w-1/2 px-2 mb-2">
          <label for="solucion" class="block mb-1 font-bold text-xs">Soluci&oacute;n:</label>
          <input type="text" id="solucion" name="solucion" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese solución (opcional)">
        </div>

        <!-- RECOMENDACIONES -->
        <div class="w-full md:w-1/2 px-2 mb-2">
          <label for="recomendaciones" class="block mb-1 font-bold text-xs">Recomendaciones:</label>
          <input type="text" id="recomendaciones" name="recomendaciones" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese recomendaciones (opcional)">
        </div>
      </div>

      <!-- TODO: RECOPILACION DE VALORES DE CADA INPUT Y COMBOBOX     -->
      <script>
        document.getElementById('recepcion').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['REC_codigo'] : ''; ?>';
        document.getElementById('fecha').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['CIE_fecha'] : $fecha_actual; ?>';
        document.getElementById('hora').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['REC_hora'] : $hora_actual; ?>';
        document.getElementById('operatividad').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['CON_codigo'] : ''; ?>';
        document.getElementById('usuarioDisplay').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['codigoUsuario'] : $_SESSION['usuario']; ?>';
        document.getElementById('usuario').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['codigoUsuario'] : $_SESSION['codigoUsuario']; ?>';
        document.getElementById('asunto').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['REC_asunto'] : ''; ?>';
        document.getElementById('documento').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['REC_documento'] : ''; ?>';
        document.getElementById('diagnostico').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['REC_diagnostico'] : ''; ?>';
        document.getElementById('solucion').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['REC_solucion'] : ''; ?>';
        document.getElementById('recomendaciones').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['REC_recomendaciones'] : ''; ?>';
      </script>

      <!-- TODO: BOTONES DEL FORMULARIO -->
      <div class="flex justify-center space-x-4">
        <button type="submit" id="guardar-cierre" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-save"></i>Guardar</button>
        <button type="button" id="editar-cierre" class="bn btn-info text-xs text-white font-bold py-2 px-3 rounded-md" disabled><i class="feather mr-2 icon-edit"></i>Editar</button>
        <button type="button" id="nuevo-registro" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md" disabled> <i class="feather mr-2 icon-plus-square"></i>Nuevo</button>
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
        <table class="w-full text-xs text-left rtl:text-right text-gray-500 cursor-pointer bg-white">
          <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-blue-300">
            <tr>
              <th scope="col" class="px-6 py-2">Incidencia</th>
              <th scope="col" class="px-6 py-2">Fecha Incidencia</th>
              <th scope="col" class="px-6 py-2">&Aacute;rea</th>
              <th scope="col" class="px-6 py-2">C&oacute;digo patrimonial</th>
              <th scope="col" class="px-6 py-2">Fecha Cierre</th>
              <th scope="col" class="px-6 py-2">Asunto Cierre</th>
              <th scope="col" class="px-6 py-2">Documento Cierre</th>
              <th scope="col" class="px-6 py-2">Condici&oacute;n</th>
              <th scope="col" class="px-6 py-2">Usuario</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cierres as $incidencia) : ?>
              <tr class='second-table hover:bg-green-100 hover:scale-[101%] transition-all border-b'>
                <th scope='row' class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap'> <?= $incidencia['INC_numero']; ?></th>
                <td class='px-6 py-3'><?= $incidencia['fechaIncidenciaFormateada']; ?></td>
                <td class='px-6 py-3'><?= $incidencia['ARE_nombre']; ?></td>
                <td class='px-6 py-3'><?= $incidencia['INC_codigoPatrimonial']; ?></td>
                <td class='px-6 py-3'><?= $incidencia['fechaCierreFormateada']; ?></td>
                <td class='px-6 py-3'><?= $incidencia['CIE_asunto']; ?></td>
                <td class='px-6 py-3'><?= $incidencia['CIE_documento']; ?></td>
                <td class='px-6 py-3'><?= $incidencia['CON_descripcion']; ?></td>
                <td class='px-6 py-3'><?= $incidencia['Usuario']; ?></td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($cierres)) : ?>
              <tr>
                <td colspan="9" class="text-center py-3">No hay incidencias cerradas.</td>
              </tr>
            <?php endif; ?>
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