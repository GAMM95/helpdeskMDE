<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <?php
    global $recepcionRegistrada;
    require_once './app/Model/IncidenciaModel.php';
    $incidenciaModel = new IncidenciaModel();
    $limit = 2; // Número de filas por página
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
    $start = ($page - 1) * $limit; // Calcula el índice de inicio
    $totalIncidenciasSinRecepcionar = $incidenciaModel->contarIncidenciasSinRecepcionar();
    $totalPages = ceil($totalIncidenciasSinRecepcionar / $limit);
    $incidencias = $incidenciaModel->obtenerIncidenciasSinRecepcionar($start, $limit);
    ?>


    <!-- Segundo Apartado - Formulario de registro de Recepcion de incidencia -->
    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Recepci&oacute;n de incidencias</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-edit"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Registros</a></li>
              <li class="breadcrumb-item"><a href="">Recepci&oacute;n</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- TODO: TITULO TABLA DE INCIDENCIAS NO RECEPCIONADAS -->
    <!-- Buscador de incidencias nuevas -->
    <div id="noIncidencias" class="flex justify-between items-center mb-2">
      <h1 class="text-xl text-gray-400">Nuevas incidencias</h1>
      <input type="text" id="searchInput" class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-lime-300" placeholder="Buscar..." oninput="filtrarTablaIncidenciasSinRecepcionar()" />
    </div>
    <!-- Fin de buscador -->

    <!-- Tabla de incidencias sin recepcionar -->
    <input type="hidden" id="incidenciaCount" value="<?php echo count($incidencias); ?>">

    <div class="mb-2">
      <div id="tablaContainer" class="relative max-h-[300px] overflow-x-hidden shadow-md sm:rounded-lg">
        <table id="tablaIncidenciasSinRecepcionar" class="w-full text-xs text-left rtl:text-right text-gray-500 bg-white">
          <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-lime-300">
            <tr>
              <th scope="col" class="px-6 py-2">N°</th>
              <th scope="col" class="px-6 py-2">Fecha incidencia</th>
              <th scope="col" class="px-6 py-2">&Aacute;rea</th>
              <th scope="col" class="px-6 py-2">C&oacute;digo Patrimonial</th>
              <th scope="col" class="px-6 py-2">Categor&iacute;a</th>
              <th scope="col" class="px-6 py-2">Asunto</th>
              <th scope="col" class="px-6 py-2">Documento</th>
              <th scope="col" class="px-6 py-2">Usuario</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($incidencias as $incidencia) : ?>
              <tr class=' hover:bg-green-100 hover:scale-[101%] transition-all hover:cursor-pointer border-b'>
                <th scope='row' class='px-6 py-3 font-medium text-gray-900 whitespace-nowrap'><?= $incidencia['INC_numero']; ?></th>
                <td class='px-6 py-2'><?= $incidencia['fechaIncidenciaFormateada']; ?></td>
                <td class='px-6 py-2'><?= $incidencia['ARE_nombre']; ?></td>
                <td class='px-6 py-2'><?= $incidencia['INC_codigoPatrimonial']; ?></td>
                <td class='px-6 py-2'><?= $incidencia['CAT_nombre']; ?></td>
                <td class='px-6 py-2'><?= $incidencia['INC_asunto']; ?></td>
                <td class='px-6 py-2'><?= $incidencia['INC_documento']; ?></td>
                <td class='px-6 py-2'><?= $incidencia['Usuario']; ?></td>
              </tr>
            <?php endforeach; ?>

            <?php if (empty($incidencias)) : ?>
              <tr>
                <td colspan="7" class="text-center py-4">No hay incidencias sin recepcionar.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <?php if ($totalPages > 0) : ?>
        <div class="flex justify-end items-center mt-1">
          <?php if ($page > 1) : ?>
            <a href="#" class="px-4 py-2 bg-gray-200 text-gray-800 hover:bg-gray-300" onclick="changePageTablaSinRecepcionar(<?php echo $page - 1; ?>)">&lt;</a>
          <?php endif; ?>
          <span class="mx-2">P&aacute;gina <?php echo $page; ?> de <?php echo $totalPages; ?></span>
          <?php if ($page < $totalPages) : ?>
            <a href="#" class="px-4 py-2 bg-gray-200 text-gray-800 hover:bg-gray-300" onclick="changePageTablaSinRecepcionar(<?php echo $page + 1; ?>)">&gt;</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>


    <div class="flex space-x-4 ">

      <!-- TODO: Formulario -->
      <div class="flex flex-col w-1/5">
        <form id="formRecepcion" action="registro-recepcion-admin.php?action=registrar" method="POST" class="card table-card bg-white shadow-md p-6 w-full text-xs">
          <div class="card-body">
            <!-- NUMERO DE INCIDENCIA -->
            <div class="flex justify-center mx-2 mb-2">
              <div class="flex-1 max-w-[500px] px-2 mb-2 flex items-center">
                <label for="incidencia" class="block font-bold mb-1 mr-3 text-[#32cfad]">Incidencia seleccionada:</label>
                <input type="text" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" id="incidencia" name="incidencia" readonly required>
              </div>
            </div>

            <!-- INPUT ESCONDIDO PARA EL NUMERO DE RECEPCION -->
            <div class="flex justify-center mx-2 mb-2 hidden">
              <div class="flex-1 max-w-[500px] px-2 mb-2 flex items-center">
                <label for="num_recepcion" class="block font-bold mb-1 mr-3 text-lime-500">N&uacute;mero de Recepci&oacute;n:</label>
                <input type="text" id="num_recepcion" name="num_recepcion" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" readonly>
              </div>
            </div>

            <!-- PRIMERA FILA DEL FORMULARIO -->
            <div class="flex flex-wrap -mx-2 mb-2">
              <!-- FECHA DE RECEPCION -->
              <div class="w-full md:w-1/5 px-2 mb-2 hidden">
                <label for="fecha_recepcion" class="block font-bold mb-1">Fecha de Recepci&oacute;n:</label>
                <input type="date" id="fecha_recepcion" name="fecha_recepcion" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo date('Y-m-d'); ?>" readonly>
              </div>

              <!-- HORA DE RECEPCION -->
              <div class="w-full md:w-1/5 px-2 mb-2 hidden">
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

              <!-- USUARIO QUE REGISTRA LA RECEPCION -->
              <div class="w-full md:w-1/5 px-2 mb-2 hidden">
                <label for="usuarioDisplay" class="block font-bold mb-1">Usuario:</label>
                <input type="text" id="usuarioDisplay" name="usuarioDisplay" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['usuario']; ?>" readonly>
              </div>
              <div class="w-full md:w-1/5 px-2 mb-2 hidden">
                <label for="usuario" class="block font-bold mb-1">Usuario:</label>
                <input type="text" id="usuario" name="usuario" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['codigoUsuario']; ?>">
              </div>
            </div>

            <!-- SELECT PRIORIDAD -->
            <div class="flex flex-wrap -mx-2">
              <div class="w-full px-2 mb-3">
                <label for="prioridad" class="block font-bold mb-1">Prioridad:</label>
                <select id="prioridad" name="prioridad" class="border p-2 w-full text-xs cursor-pointer rounded-md">
                </select>
              </div>
            </div>

            <!-- SELECT IMPACTO -->
            <div class="flex flex-wrap -mx-2">
              <div class="w-full px-2 mb-3">
                <label for="impacto" class="block font-bold mb-1">Impacto:</label>
                <select id="impacto" name="impacto" class="border p-2 w-full text-xs cursor-pointer rounded-md">
                </select>
              </div>
            </div>

            <!-- RECOPILACION DE VALORES DE CADA INPUT Y COMBOBOX -->
            <script>
              document.getElementById('incidencia').value = '<?php echo $recepcionRegistrada ? $recepcionRegistrada['INC_numero'] : ''; ?>';
              document.getElementById('hora').value = '<?php echo $recepcionRegistrada ? $recepcionRegistrada['REC_hora'] : $hora_actual; ?>';
              document.getElementById('fecha').value = '<?php echo $recepcionRegistrada ? $recepcionRegistrada['REC_fecha'] : $fecha_actual; ?>';
              document.getElementById('prioridad').value = '<?php echo $recepcionRegistrada ? $recepcionRegistrada['PRI_codigo'] : ''; ?>';
              document.getElementById('impacto').value = '<?php echo $recepcionRegistrada ? $recepcionRegistrada['IMP_codigo'] : ''; ?>';
            </script>

            <!-- BOTONES DE FORMULARIO -->
            <div class="flex flex-wrap -mx-2">
              <div class="w-full px-2">
                <div class="flex flex-col items-center space-y-4">
                  <button type="submit" id="guardar-recepcion" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-save"></i>Guardar</button>
                  <button type="button" id="editar-recepcion" class="bn btn-info text-xs text-white font-bold py-2 px-3 rounded-md" disabled><i class="feather mr-2 icon-edit"></i>Editar</button>
                  <button type="button" id="nuevo-registro" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md" disabled> <i class="feather mr-2 icon-plus-square"></i>Nuevo</button>
                </div>
              </div>
            </div>


          </div>
        </form>
      </div>


      <!-- TODO: TABLA DE INCIDENCIAS  RECEPCIONADAS -->
      <div class="w-4/5">
        <div class="relative max-h-[500px] overflow-x-hidden shadow-md sm:rounded-lg">
          <table id="tablaIncidenciasRecepcionadas" class="w-full text-xs text-left rtl:text-right text-gray-500 cursor-pointer bg-white">
            <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-blue-300">
              <tr>
                <th scope="col" class="px-6 py-2 hidden">Recepci&oacute;n</th>
                <th scope="col" class="px-6 py-2">Incidencia</th>
                <th scope="col" class="px-6 py-2">Fecha de recepci&oacute;n</th>
                <th scope="col" class="px-6 py-2">&Aacute;rea</th>
                <th scope="col" class="px-6 py-2">C&oacute;digo Patrimonial</th>
                <th scope="col" class="px-6 py-2">Categor&iacute;a</th>
                <th scope="col" class="px-6 py-2">Prioridad</th>
                <th scope="col" class="px-6 py-2">Impacto</th>
                <th scope="col" class="px-6 py-2">Usuario</th>
              </tr>
            </thead>
            <tbody>
              <?php
              require_once './app/Model/RecepcionModel.php';
              $recepcionModel = new RecepcionModel();
              $recepciones = $recepcionModel->listarRecepciones();
              foreach ($recepciones as $recepcion) {

                // echo "<tr class='second-table bg-white hover:bg-green-100 hover:scale-[101%] transition-all  border-b '>";
                echo "<tr class='second-table hover:bg-green-100 hover:scale-[101%] transition-all border-b' data-id='{$recepcion['REC_numero']}'>";
                echo "<th scope='row' class='px-6 py-3 font-medium text-gray-900 whitespace-nowrap hidden'>";
                echo $recepcion['REC_numero'];
                echo "</th>";
                echo "<td class='px-6 py-3'>";
                echo $recepcion['INC_numero'];
                echo "</td>";
                echo "<td class='px-6 py-3'>";
                echo $recepcion['fechaRecepcionFormateada'];
                echo "</td>";
                echo "<td class='px-6 py-3'>";
                echo $recepcion['ARE_nombre'];
                echo "</td>";
                echo "<td class='px-6 py-3'>";
                echo $recepcion['INC_codigoPatrimonial'];
                echo "</td>";
                echo "<td class='px-6 py-3'>";
                echo $recepcion['CAT_nombre'];
                echo "</td>";
                echo "<td class='px-6 py-3'>";
                echo $recepcion['PRI_nombre'];
                echo "</td>";
                echo "<td class='px-6 py-3'>";
                echo $recepcion['IMP_descripcion'];
                echo "</td>";
                echo "<td class='px-6 py-3'>";
                // echo $recepcion['USU_nombre'];
                echo $recepcion['Usuario'];
                echo "</td>";
                echo "</tr>";
              }
              ?>
              <?php if (empty($recepciones)) : ?>
                <tr>
                  <td colspan="8" class="text-center py-3">No hay incidencias recepcionadas.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.tailwindcss.com"></script>
<script src="./app/View/func/func_recepcion_admin.js"></script>