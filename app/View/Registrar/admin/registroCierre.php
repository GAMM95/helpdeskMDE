<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <?php
    global $cierreRegistrado;
    ?>

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

    <!-- Titulo de recepciones y paginador -->
    <div id="noRecepcion" class="flex justify-between items-center mb-2">
      <h1 class="text-xl text-gray-400">Incidencias pendientes para cierre</h1>
      <div id="paginadorRecepcionesSinCerrar" class="flex justify-end items-center mt-1">
        <!-- Paginación aquí -->
        <?php if ($totalPages > 1) : ?>
          <?php if ($page > 1) : ?>
            <a href="#" class="px-2 py-1 bg-gray-400 text-gray-200 hover:bg-gray-600 rounded-l-md" onclick="changePageTablaSinCerrar(<?php echo $page - 1; ?>)"><i class="feather mr-2 icon-chevrons-left"></i> Anterior</a>
          <?php endif; ?>
          <span class="px-2 py-1 bg-gray-400 text-gray-200"><?php echo $page; ?> de <?php echo $totalPages; ?></span>
          <?php if ($page < $totalPages) : ?>
            <a href="#" class="px-2 py-1 bg-gray-400 text-gray-200 hover:bg-gray-600 rounded-r-md" onclick="changePageTablaSinCerrar(<?php echo $page + 1; ?>)"> Siguiente <i class="feather ml-2 icon-chevrons-right"></i></a>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Tabla de recepciones sin cerrar -->
    <input type="hidden" id="recepcionCount" value="<?php echo count($recepciones); ?>">
    <div class="mb-4">
      <div id="tablaContainer" class="relative max-h-[300px] overflow-x-hidden shadow-md sm:rounded-lg">
        <table id="tablaRecepcionesSinCerrar" class="w-full text-xs text-left rtl:text-right text-gray-500 bg-white">
          <!-- Encabezado de la tabla -->
          <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-lime-300">
            <tr>
              <th scope="col" class="px-6 py-2 hidden">N&deg; recepcion</th>
              <th scope="col" class="px-6 py-2 hidden">N&deg; inc</th>
              <th scope="col" class="px-6 py-2">INCIDENCIA</th>
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
          <!-- Fin de encabezado -->

          <!-- Cuerpo de la tabla -->
          <tbody>
            <?php foreach ($recepciones as $recepcion) : ?>
              <tr class='hover:bg-green-100 hover:scale-[101%] transition-all hover:cursor-pointer border-b '>
                <th scope='row' class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap hidden'><?= $recepcion['REC_numero']; ?></th>
                <th scope='row' class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap hidden'><?= $recepcion['INC_numero']; ?></th>
                <td class='px-6 py-2'><?= $recepcion['INC_numero_formato']; ?></th>
                <td class='px-6 py-2'><?= $recepcion['fechaRecepcionFormateada']; ?></th>
                <td class='px-6 py-2'><?= $recepcion['ARE_nombre']; ?></th>
                <td class='px-6 py-2'><?= $recepcion['INC_codigoPatrimonial']; ?></th>
                <td class='px-6 py-2'><?= $recepcion['INC_asunto']; ?></th>
                <td class='px-6 py-2'><?= $recepcion['INC_documento']; ?></th>
                <td class='px-6 py-2'><?= $recepcion['PRI_nombre']; ?></th>
                  <!-- <td class='px-6 py-2'><?= $recepcion['IMP_descripcion']; ?></th> -->
                <td class='px-6 py-2'><?= $recepcion['Usuario']; ?></th>
              </tr>
            <?php endforeach; ?>

            <?php if (empty($recepciones)) : ?>
              <tr>
                <td colspan="8" class="text-center py-4">No hay incidencias recepionadas sin cerrar.</td>
              </tr>
            <?php endif; ?>
          </tbody>
          <!-- Fin de cuerpo de la tabla -->
        </table>
      </div>
    </div>
    <!-- Fin de tabla de recepciones sin cerrar -->

    <!-- Formulario de registro de cierres -->
    <form id="formCierre" action="registro-cierre.php?action=registrar" method="POST" class="card table-card  bg-white shadow-md p-6 w-full text-xs mb-2">
      <!-- NUMERO DE RECEPCION -->
      <div class="flex justify-center mx-2 mb-4">
        <div class="flex-1 max-w-[500px] px-2 mb-2 flex items-center hidden ">
          <label for="num_incidencia" class="block font-bold mb-1 mr-3 text-lime-500">N&uacute;mero de Incidencia:</label>
          <input type="text" id="num_incidencia" name="num_incidencia" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" readonly>
        </div>

        <!-- INCIDENCIA SELECCIONADA -->
        <div class="flex justify-center items-center mr-5 ml-5">
          <div class="text-center">
            <label for="incidenciaSeleccionada" class="block font-bold mb-1 text-[#32cfad]">Incidencia seleccionada:</label>
            <input type="text" class="border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center w-full" id="incidenciaSeleccionada" name="incidenciaSeleccionada" readonly required>
          </div>
        </div>

        <!-- Numero de recepcion -->
        <div class="flex-1 max-w-[500px] px-2 mb-2 flex items-center hidden">
          <label for="recepcion" class="block font-bold mb-1 mr-3 text-lime-500">N&uacute;mero de Recepci&oacute;n:</label>
          <input type="text" id="recepcion" name="recepcion" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" readonly>
        </div>

        <!-- INPUT ESCONDIDO PARA EL NUMERO DE CIERRE -->
        <div class="flex-1 max-w-[500px] px-2 mb-2 flex items-center hidden">
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

        <!-- OPERATIVIDAD -->
        <div class="w-full md:w-1/5 px-2 mb-2">
          <label for="operatividad" class="block font-bold mb-1 text-xs">Condici&oacute;n: *</label>
          <select id="cbo_operatividad" name="operatividad" class="border p-2 w-full text-xs cursor-pointer rounded-md">
          </select>
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
        <!-- RECOMENDACIONES -->
        <div class="w-full md:w-1/1 px-2 mb-2">
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
        document.getElementById('recomendaciones').value = '<?php echo $cierreRegistrado ? $cierreRegistrado['REC_recomendaciones'] : ''; ?>';
      </script>

      <!-- Botones del formulario -->
      <div class="flex justify-center space-x-4">
        <button type="submit" id="guardar-cierre" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-save"></i>Guardar</button>
        <button type="button" id="editar-cierre" class="bn btn-info text-xs text-white font-bold py-2 px-3 rounded-md" disabled><i class="feather mr-2 icon-edit"></i>Editar</button>
        <button type="button" id="nuevo-registro" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md" disabled> <i class="feather mr-2 icon-plus-square"></i>Nuevo</button>
        <button type="button" id="imprimir-cierre" class="bn btn-warning text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-printer"></i>Imprimir</button>
      </div>
      <!-- Fin de botones -->
    </form>
    <!-- Fin de formulario -->

    <!-- Titulo y paginacion de tabla de recepciones -->
    <div class="flex justify-between items-center mb-2">
      <h1 class="text-xl text-gray-400">Lista de incidencias cerradas</h1>
      <div id="paginadorCierres" class="flex justify-end items-center mt-1">
        <?php if ($totalPagesCierres > 1) : ?>
          <?php if ($pageCierres > 1) : ?>
            <a href="#" class="px-2 py-1 bg-gray-400 text-gray-200 hover:bg-gray-600 rounded-l-md" onclick="changePageCierres(<?php echo $pageCierres - 1; ?>)"><i class="feather mr-2 icon-chevrons-left"></i> Anterior</a>
          <?php endif; ?>
          <span class="px-2 py-1 bg-gray-400 text-gray-200"><?php echo $pageCierres; ?> de <?php echo $totalPagesCierres; ?></span>
          <?php if ($pageCierres < $totalPagesCierres) : ?>
            <a href="#" class="px-2 py-1 bg-gray-400 text-gray-200 hover:bg-gray-600 rounded-r-md" onclick="changePageCierres(<?php echo $pageCierres + 1; ?>)"> Siguiente <i class="feather ml-2 icon-chevrons-right"></i></a>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Tabla de incidencias cerradas -->
    <div class="relative max-h-[800px] mt-2 overflow-x-hidden shadow-md sm:rounded-lg">
      <table id="tablaIncidenciasCerradas" class="w-full text-xs text-left rtl:text-right text-gray-500 cursor-pointer bg-white">
        <!-- Encabezado de la tabla -->
        <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-blue-300">
          <tr>
            <th scope="col" class="px-6 py-2 hidden">num Cierre</th>
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
        <!-- Fin de encabezado -->

        <!-- Cuerpo de la tabla -->
        <tbody>
          <?php foreach ($cierres as $incidencia) : ?>
            <tr class='second-table hover:bg-green-100 hover:scale-[101%] transition-all border-b'>
              <th scope='row' class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap hidden'> <?= $incidencia['CIE_numero']; ?></th>
              <td class='px-6 py-3'><?= $incidencia['INC_numero_formato']; ?></td>
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
        <!-- Fin del cuerpo de la tabla -->
      </table>
    </div>
    <!-- Fin de la tabla -->
  </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>