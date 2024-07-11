<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="public/assets/logo.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
  <title class="text-center text-3xl font-poppins">Sistema de Incidencias</title>
</head>

<body class="bg-green-50 flex items-center justify-center min-h-screen">

  <!-- Contenido principal -->
  <main class="bg-[#eeeff1] flex-1 p-4 overflow-y-auto rounded-lg shadow-md">
    <?php
    global $incidenciaRegistrada;
    ?>
    <!-- Header -->
    <h1 class="text-xl font-bold mb-4">Registro de Incidencia</h1>
    <!-- TODO: FORMULARIO -->
    <form id="formIncidencia" action="registro-incidencia-user.php?action=registrar" method="POST" class="border bg-white shadow-md p-6 w-full text-sm rounded-md">

      <!-- TODO: FILA OCULTA DEL FORMULARIO - NUMERO DE INCIDENCIA -->
      <div class="flex items-center mb-4 ">
        <label for="numero_incidencia" class="block font-bold mb-1 mr-1 text-lime-500">Nro Incidencia:</label>
        <input type="text" id="numero_incidencia" name="numero_incidencia" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-sm" readonly>
      </div>

      <!-- TODO: PRIMERA FILA DEL FORMULARIO  -->
      <div class="flex flex-wrap -mx-2">
        <!-- AREA DEL USUARIO -->
        <div class="w-full sm:w-1/6 px-2 mb-2 ">
          <label for="area" class="block mb-1 font-bold text-sm">&Aacute;rea:</label>
          <input type="text" id="area" name="area" class="border border-gray-200 bg-gray-100 p-2 w-full text-sm" value="<?php echo $_SESSION['codigoArea']; ?>" readonly>
        </div>
        <div class="w-full sm:w-1/2 px-2 mb-4">
          <label for="area" class="block font-bold mb-1 text-sm">&Aacute;rea:</label>
          <input type="text" id="area" name="area" class="border border-gray-200 bg-gray-100 p-2 w-full text-sm" value="<?php echo $_SESSION['area']; ?>" readonly>
        </div>

        <!-- FECHA DE LA INCIDENCIA -->
        <div class="w-full sm:w-1/6 px-2 mb-2">
          <label for="fecha_incidencia" class="block mb-1 font-bold text-sm">Fecha:</label>
          <input type="date" id="fecha_incidencia" name="fecha_incidencia" class="border border-gray-200 bg-gray-100 p-2 w-full text-sm" value="<?php echo date('Y-m-d'); ?>" readonly>
        </div>

        <!-- HORA DE LA INCIDENCIA -->
        <div class="w-full md:w-1/6 px-2 mb-2">
          <label for="hora" class="block font-bold mb-1">Hora:</label>
          <?php
          // Establecer la zona horaria deseada
          date_default_timezone_set('America/Lima');
          $fecha_actual = date('Y-m-d');
          $hora_actual = date('H:i:s');
          ?>
          <input type="text" id="hora" name="hora" class="border border-gray-200 bg-gray-100 p-2 w-full text-sm" value="<?php echo $hora_actual; ?>" readonly>
        </div>

        <!-- USUARIO QUE ABRE LA INCIDENCIA -->
        <div class="w-full md:w-1/6 px-2 mb-2">
          <label for="usuarioDisplay" class="block font-bold mb-1">Usuario:</label>
          <input type="text" id="usuarioDisplay" name="usuarioDisplay" class="border border-gray-200 bg-gray-100 p-2 w-full text-sm" value="<?php echo $_SESSION['usuario']; ?>" readonly>
        </div>
        <div class="w-full md:w-1/6 px-2 mb-2">
          <label for="usuario" class="block font-bold mb-1">Usuario:</label>
          <input type="text" id="usuario" name="usuario" class="border border-gray-200 bg-gray-100 p-2 w-full text-sm" value="<?php echo $_SESSION['codigoUsuario']; ?>" readonly>
        </div>
      </div>

      <!-- TODO: SEGUNDA FILA DEL FORMULARIO -->
      <div class="flex flex-wrap -mx-2">
        <!-- CATEGORIA SELECCIONADA -->
        <div class="w-full sm:w-1/2 px-2 mb-2">
          <label for="categoria" class="block font-bold mb-1">Categor&iacute;a:</label>
          <select id="cbo_categoria" name="categoria" class="border p-2 w-full text-sm cursor-pointer">
          </select>
        </div>

        <!-- CODIGO PATRIMONIAL -->
        <div class="w-full sm:w-1/4 px-2 mb-2">
          <label for="codigo_patrimonial" class="block mb-1 font-bold text-sm">C&oacute;digo Patrimonial:</label>
          <input type="text" id="codigo_patrimonial" name="codigo_patrimonial" class="border p-2 w-full text-sm" maxlength="12" pattern="\d{1,12}" inputmode="numeric" title="Ingrese solo dígitos" required oninput="this.value = this.value.replace(/[^0-9]/g, ''); " placeholder="Ingrese c&oacute;digo patrimonial">
        </div>

        <!-- ASUNTO DE LA INCIDENCIA -->
        <div class="w-full sm:w-1/4 px-2 mb-2">
          <label for="asunto" class="block mb-1 font-bold text-sm">Asunto:</label>
          <input type="text" id="asunto" name="asunto" class="border p-2 w-full text-sm" placeholder="Ingrese asunto">
        </div>
      </div>

      <!-- TODO: TERCERA FILA DEL FORMULARIO -->
      <div class="flex flex-wrap -mx-2">
        <!-- DOCUMENTO DE LA INCIDENCIA -->
        <div class="w-full sm:w-1/3 px-2 mb-2">
          <label for="documento" class="block mb-1 font-bold text-sm">Documento:</label>
          <input type="text" id="documento" name="documento" class="border p-2 w-full text-sm" placeholder="Ingrese documento">
        </div>

        <!-- DESCRIPCION DE LA INCIDENCIA -->
        <div class="w-full md:w-2/3 px-2 mb-2">
          <label for="descripcion" class="block mb-1 font-bold text-sm">Descripci&oacute;n:</label>
          <input type="text" id="descripcion" name="descripcion" class="border p-2 w-full text-sm mb-3" placeholder="Ingrese descripci&oacute;n (opcional)">
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
        <button type="submit" id="guardar-incidencia" class="bg-[#87cd51] text-white font-bold hover:bg-[#8ce83c] py-2 px-4 rounded">Guardar</button>
        <button type="button" class="bg-blue-500 text-white font-bold hover:bg-blue-600 py-2 px-4 rounded">
          Editar
        </button>
        <button type="button" id="imprimirDatos" class="bg-yellow-500 text-white font-bold hover:bg-yellow-600 py-2 px-4 rounded w-full md:w-auto mt-2 md:mt-0">
          Imprimir
        </button>
        <button type="button" id="nuevoRegistro" class="bg-gray-500 text-white font-bold hover:bg-gray-600 py-2 px-4 rounded w-full md:w-auto mt-2 md:mt-0">
          Nuevo
        </button>
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
      <div class="relative max-h-[800px] mt-2 overflow-x-hidden shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
          <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-blue-300">
            <tr>
              <th scope="col" class="px-6 py-3">N°</th>
              <th scope="col" class="px-6 py-3">Fecha y Hora</th>
              <th scope="col" class="px-6 py-3">Area</th>
              <th scope="col" class="px-6 py-3">Código Patrimonial</th>
              <th scope="col" class="px-6 py-3">Categoría</th>
              <th scope="col" class="px-6 py-3">Asunto</th>
              <th scope="col" class="px-6 py-3">Usuario</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($incidencias as $incidencia) : ?>
              <tr class='second-table bg-white hover:bg-green-100 hover:scale-[101%] transition-all border-b'>
                <th scope='row' class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap'> <?= $incidencia['INC_numero']; ?></th>
                <td class='px-6 py-4'><?= $incidencia['fechaIncidenciaFormateada']; ?></td>
                <td class='px-6 py-4'><?= $incidencia['ARE_nombre']; ?></td>
                <td class='px-6 py-4'><?= $incidencia['INC_codigoPatrimonial']; ?></td>
                <td class='px-6 py-4'><?= $incidencia['CAT_nombre']; ?></td>
                <td class='px-6 py-4'><?= $incidencia['INC_asunto']; ?></td>
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
  </main>

  <script src="./app/View/func/func_incidencia_user.js"></script>
</body>

</html>