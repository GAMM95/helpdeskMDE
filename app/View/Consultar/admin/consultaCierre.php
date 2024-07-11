<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="public/assets/logo.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
  <title class="text-center text-3xl font-poppins">Sistema de Incidencias</title>
</head>

<body class="bg-green-50 flex items-center justify-center min-h-screen overflow-x-hidden">
  <main class="bg-[#eeeff1] flex-1 p-4 overflow-y-auto">
    <!-- Header -->
    <h1 class="text-2xl font-bold mb-4">Consultar cierre de incidencias</h1>

    <form id="formConsultarCierre" action="modulo-rol.php" method="POST" class="border bg-white shadow-md p-6 w-full text-sm rounded-md mb-4">
      <div class="flex flex-wrap -mx-2">
        <div class="w-full md:w-1/3 px-2 mb-2">
          <label for="area" class="block mb-1 font-bold text-sm">&Aacute;rea:</label>
          <select id="cbo_area" name="area" class="border p-2 w-full text-sm">
          </select>
        </div>
        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="codigoPatrimonial" class="block mb-1 font-bold text-sm">C&oacute;digo Patrimonial:</label>
          <input type="text" id="txt_codigoPatrimonial" name="codigoPatrimonial" class="w-full border p-2 text-sm">
        </div>
        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="fecha" class="block mb-1 font-bold text-sm">Fecha:</label>
          <input type="date" id="fecha" name="fecha" class="w-full border p-2 text-sm">
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
        <table id="tablaConsultarCierres" class="w-full text-sm text-left rtl:text-right text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-lime-300">
            <tr>
              <th scope="col" class="px-3 py-3">N° incidencia</th>
              <th scope="col" class="px-3 py-3">Fecha Incidencia</th>
              <th scope="col" class="px-3 py-3">&Aacute;rea</th>
              <th scope="col" class="px-3 py-3">Categor&iacute;a</th>
              <th scope="col" class="px-3 py-3">Asunto</th>
              <th scope="col" class="px-3 py-3">Documento Ingreso</th>
              <th scope="col" class="px-3 py-3">C&oacute;digo Patrimonial</th>
              <th scope="col" class="px-3 py-3">Fecha Cierre</th>
              <th scope="col" class="px-3 py-3">Operatividad</th>
              <th scope="col" class="px-3 py-3">Usuario</th>
            </tr>
          </thead>
          <tbody>
            <?php
            require_once './app/Model/CierreModel.php';
            $cierreModel = new CierreModel();
            $cierres = $cierreModel->listarCierresAdministrador();
            foreach ($cierres as $cierre) {
              echo "<tr class='bg-white hover:bg-green-100 hover:scale-[101%] transition-all border-b'>";
              echo "<td class='px-3 py-2'>" . $cierre['INC_numero'] . "</td>";
              echo "<td class='px-3 py-2'>" . $cierre['fechaIncidenciaFormateada'] . "</td>";
              echo "<td class='px-3 py-2'>" . $cierre['ARE_nombre'] . "</td>";
              echo "<td class='px-3 py-2'>" . $cierre['CAT_nombre'] . "</td>";
              echo "<td class='px-3 py-2'>" . $cierre['INC_asunto'] . "</td>";
              echo "<td class='px-3 py-2'>" . $cierre['INC_documento'] . "</td>";
              echo "<td class='px-3 py-2'>" . $cierre['INC_codigoPatrimonial'] . "</td>";
              echo "<td class='px-3 py-2'>" . $cierre['fechaCierreFormateada'] . "</td>";
              echo "<td class='px-3 py-2'>" . $cierre['OPE_descripcion'] . "</td>";
              echo "<td class='px-3 py-2'>" . $cierre['USU_nombre'] . "</td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>

  </main>
  <script src="./app/View/func/func_incidencias_admin.js"></script>
</body>

</html>