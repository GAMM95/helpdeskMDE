<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <?php
    global $incidenciaRegistrada;
    ?>

    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Registro de incidencias</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-edit"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Registros</a></li>
              <li class="breadcrumb-item"><a href="">Incidencias</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- TODO: FORMULARIO -->
    <form id="formIncidencia" action="registro-incidencia-user.php?action=registrar" method="POST" class="card table-card  bg-white shadow-md p-6 w-full text-xs mb-2">

      <!-- TODO: FILA OCULTA DEL FORMULARIO - NUMERO DE INCIDENCIA -->
      <div class="flex items-center mb-4 ">
        <label for="numero_incidencia" class="block font-bold mb-1 mr-1 text-lime-500">Nro Incidencia:</label>
        <input type="text" id="numero_incidencia" name="numero_incidencia" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs" readonly>
      </div>

      <!-- TODO: PRIMERA FILA DEL FORMULARIO  -->
      <div class="flex flex-wrap -mx-2">
        <!-- AREA DEL USUARIO -->
        <div class="w-full sm:w-1/6 px-2 mb-2 hidden">
          <label for="codigoArea" class="block mb-1 font-bold text-xs">C&oacute;digo &Aacute;rea:</label>
          <input type="text" id="codigoArea" name="codigoArea" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['codigoArea']; ?>" readonly>
        </div>
        <div class="w-full sm:w-1/2 px-2 mb-4 hidden">
          <label for="area" class="block font-bold mb-1 text-xs">&Aacute;rea:</label>
          <input type="text" id="area" name="area" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['area']; ?>" readonly>
        </div>

        <!-- FECHA DE LA INCIDENCIA -->
        <div class="w-full sm:w-1/6 px-2 mb-2 hidden">
          <label for="fecha_incidencia" class="block mb-1 font-bold text-xs">Fecha:</label>
          <input type="date" id="fecha_incidencia" name="fecha_incidencia" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo date('Y-m-d'); ?>" readonly>
        </div>

        <!-- HORA DE LA INCIDENCIA -->
        <div class="w-full md:w-1/6 px-2 mb-2 hidden">
          <label for="hora" class="block font-bold mb-1 text-xs">Hora:</label>
          <?php
          // Establecer la zona horaria deseada
          date_default_timezone_set('America/Lima');
          $fecha_actual = date('Y-m-d');
          $hora_actual = date('H:i:s');
          ?>
          <input type="text" id="hora" name="hora" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $hora_actual; ?>" readonly>
        </div>

        <!-- USUARIO QUE ABRE LA INCIDENCIA -->
        <div class="w-full md:w-1/6 px-2 mb-2 hidden">
          <label for="usuarioDisplay" class="block font-bold mb-1 text-xs">Usuario:</label>
          <input type="text" id="usuarioDisplay" name="usuarioDisplay" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['usuario']; ?>" readonly>
        </div>
        <div class="w-full md:w-1/6 px-2 mb-2 hidden">
          <label for="codigoUsuario" class="block font-bold mb-1 text-xs">C&oacute;digo Usuario:</label>
          <input type="text" id="codigoUsuario" name="codigoUsuario" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['codigoUsuario']; ?>" readonly>
        </div>
      </div>

      <!-- TODO: SEGUNDA FILA DEL FORMULARIO -->
      <div class="flex flex-wrap -mx-2">
        <!-- CATEGORIA SELECCIONADA -->
        <div class="w-full sm:w-1/2 px-2 mb-2">
          <label for="categoria" class="block font-bold mb-1">Categor&iacute;a: *</label>
          <select id="cbo_categoria" name="categoria" class="border p-2 w-full text-xs cursor-pointer">
          </select>
        </div>

        <!-- CODIGO PATRIMONIAL -->
        <div class="w-full sm:w-1/4 px-2 mb-2">
          <label for="codigo_patrimonial" class="block mb-1 font-bold text-xs">C&oacute;digo Patrimonial:</label>
          <input type="text" id="codigo_patrimonial" name="codigo_patrimonial" class="border p-2 w-full text-xs rounded-md" maxlength="12" pattern="\d{1,12}" inputmode="numeric" title="Ingrese solo dígitos" required oninput="this.value = this.value.replace(/[^0-9]/g, ''); " placeholder="Ingrese c&oacute;digo patrimonial">
        </div>
      </div>

      <!-- TODO: TERCERA FILA DEL FORMULARIO -->
      <div class="flex flex-wrap -mx-2">
        <!-- ASUNTO DE LA INCIDENCIA -->
        <div class="w-full sm:w-1/2 px-2 mb-2">
          <label for="asunto" class="block mb-1 font-bold text-xs">Asunto: *</label>
          <input type="text" id="asunto" name="asunto" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese asunto">
        </div>
        <!-- DOCUMENTO DE LA INCIDENCIA -->
        <div class="w-full sm:w-1/2 px-2 mb-2">
          <label for="documento" class="block mb-1 font-bold text-xs">Documento: *</label>
          <input type="text" id="documento" name="documento" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese documento">
        </div>
      </div>

      <!-- TODO: CUARTA FILA DEL FORMULARIO -->
      <div class="flex flex-wrap -mx-2">
        <!-- DESCRIPCION DE LA INCIDENCIA -->
        <div class="w-full md:w-1/1 px-2 mb-2">
          <label for="descripcion" class="block mb-1 font-bold text-xs">Descripci&oacute;n:</label>
          <input type="text" id="descripcion" name="descripcion" class="border p-2 w-full text-xs mb-3 rounded-md" placeholder="Ingrese descripci&oacute;n (opcional)">
        </div>
      </div>


      <!-- TODO: RECOPILACION DE VALORES DE CADA INPUT Y COMBOBOX     -->
      <script>
        // Asignación de valores predefinidos al cargar la página
        document.getElementById('fecha').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['INC_fecha'] : $fecha_actual; ?>';
        document.getElementById('hora').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['INC_hora'] : $hora_actual; ?>';
        document.getElementById('cbo_area').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['ARE_codigo'] : ''; ?>';
        document.getElementById('codigo_patrimonial').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['INC_codigo_patrimonial'] : ''; ?>';
        document.getElementById('cbo_categoria').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['CAT_codigo'] : ''; ?>';
        document.getElementById('asunto').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['INC_asunto'] : ''; ?>';
        document.getElementById('documento').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['INC_documento'] : ''; ?>';
        document.getElementById('descripcion').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada['INC_descripcion'] : ''; ?>';
      </script>

      <!-- TODO: BOTONES -->
      <div class="flex justify-center space-x-4">
        <button type="submit" id="guardar-incidencia" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-save"></i>Guardar</button>
        <button type="button" id="editar-incidencia" class="bn btn-info text-xs text-white font-bold py-2 px-3 rounded-md" disabled><i class="feather mr-2 icon-edit"></i>Editar</button>
        <button type="button" id="nuevo-registro" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md" disabled> <i class="feather mr-2 icon-plus-square"></i>Nuevo</button>
        <button type="button" id="imprimir-incidencia" class="bn btn-warning text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-printer"></i>Imprimir</button>
      </div>
    </form>

    <!-- TODO: TABLA DE INCIDENCIAS REGISTRADAS -->
    <?php
    require_once './app/Model/IncidenciaModel.php';

    $incidenciaModel = new IncidenciaModel();
    $limit = 5; // Número de filas por página
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
    $start = ($page - 1) * $limit; // Calcula el índice de inicio

    // Obtiene el total de registros
    $totalIncidencias = $incidenciaModel->contarIncidenciasUsuario($_SESSION['codigoArea']);
    $totalPages = ceil($totalIncidencias / $limit);

    // Obtiene las incidencias para la página actual
    $incidencias = $incidenciaModel->listarIncidenciasRegistroUsuario($_SESSION['codigoArea'], $start, $limit);
    ?>

    <div>
      <?php if ($totalPages > 0) : ?>
        <div class="flex justify-end items-center mt-1">
          <?php if ($page > 1) : ?>
            <a href="#" class="px-4 py-2 bg-gray-200 text-gray-800 hover:bg-gray-300" onclick="changePageTablaListarIncidencias(<?php echo $page - 1; ?>)">&lt;</a>
          <?php endif; ?>
          <span class="mx-2">P&aacute;gina <?php echo $page; ?> de <?php echo $totalPages; ?></span>
          <?php if ($page < $totalPages) : ?>
            <a href="#" class="px-4 py-2 bg-gray-200 text-gray-800 hover:bg-gray-300" onclick="changePageTablaListarIncidencias(<?php echo $page + 1; ?>)">&gt;</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="relative max-h-[800px] mt-2 overflow-x-hidden shadow-md sm:rounded-lg">
        <table id="tablaListarIncidencias" class="w-full text-xs text-left rtl:text-right text-gray-500 cursor-pointer bg-white">
          <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-blue-300">
            <tr>
              <th scope="col" class="px-6 py-2 hidden">N°</th>
              <th scope="col" class="px-6 py-2">n° Incidencia</th>
              <th scope="col" class="px-6 py-2">Fecha de entrada</th>
              <th scope="col" class="px-6 py-2">C&oacute;digo Patrimonial</th>
              <th scope="col" class="px-6 py-2">Asunto</th>
              <th scope="col" class="px-6 py-2">Documento</th>
              <th scope="col" class="px-6 py-2">Categor&iacute;a</th>
              <th scope="col" class="px-6 py-2 hidden">Area</th>
              <th scope="col" class="px-6 py-2 hidden">descripcion</th>
              <th scope="col" class="px-6 py-2 text-center hidden">Estado</th>
              <th scope="col" class="px-6 py-2 text-center">Estado</th>
              <th scope="col" class="px-6 py-2">Usuario</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($incidencias as $incidencia) : ?>
              <tr class='second-table hover:bg-green-100 hover:scale-[101%] transition-all border-b'>
                <th scope='row' class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap hidden'> <?= $incidencia['INC_numero']; ?></th>
                <td class='px-6 py-3'><?= $incidencia['INC_numero_formato']; ?></td>
                <td class='px-6 py-3'><?= $incidencia['fechaIncidenciaFormateada']; ?></td>
                <td class='px-6 py-3'><?= $incidencia['INC_codigoPatrimonial']; ?></td>
                <td class='px-6 py-3'><?= $incidencia['INC_asunto']; ?></td>
                <td class='px-6 py-3'><?= $incidencia['INC_documento']; ?></td>
                <td class='px-6 py-3'><?= $incidencia['CAT_nombre']; ?></td>
                <td class='px-6 py-3 hidden'><?= $incidencia['ARE_nombre']; ?></td>
                <td class='px-6 py-3 hidden'><?= $incidencia['INC_descripcion']; ?></td>
                <td class='px-6 py-3 text-center hidden'><?= $incidencia['ESTADO']; ?></td>
                <td class="px-3 py-3 text-center">
                  <?php
                  $estadoDescripcion = htmlspecialchars($incidencia['ESTADO']);
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
                  ?>
                  <label class="badge <?= $badgeClass ?>"><?= $estadoDescripcion ?></label>
                </td>
                <td class='px-6 py-3'><?= $incidencia['Usuario']; ?></td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($incidencias)) : ?>
              <tr>
                <td colspan="8" class="text-center py-3">No hay incidencias.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>

<script src="./app/View/func/func_incidencia_user.js"></script>