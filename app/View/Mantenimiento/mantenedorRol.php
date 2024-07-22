<!doctype html>
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

  <!-- Contenido principal -->
  <main class="bg-[#eeeff1] flex-1 p-4 overflow-y-auto">
    <!-- Header -->
    <h1 class="text-2xl font-bold mb-4 ">M&oacute;dulo / Rol</h1>

    <form id="formrol" action="modulo-rol.php" method="POST" class="border bg-white shadow-md p-6 w-full text-xs rounded-md">
      <input type="hidden" id="form-action" name="action" value="registrar">

      <!-- PRIMERA FILA Campo para mostrar el número de incidencia -->
      <div class="flex justify-center -mx-2 mb-5">
        <div class="flex items-center mb-4">
          <div class="flex items-center">
            <label for="CodRol" class="block font-bold mb-1 mr-3 text-lime-500">C&oacute;digo de Rol:</label>
            <input type="text" id="txt_codigoRol" name="CodRol" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" readonly disabled>
          </div>
        </div>
      </div>

      <!-- SEGUNDA FILA: campo para ingresar e nuevo nombre del rol -->
      <div class="flex flex-wrap -mx-2">
        <div class="w-full sm:w-1/4 px-2 mb-2">
          <label for="NombreRol" class="block mb-1 font-bold text-xs">Nombre rol:</label>
          <input type="text" id="txt_nombreRol" name="NombreRol" class="border p-2 w-full text-xs">
        </div>
      </div>

      <!-- TERCERA FILA: botones -->
      <div class="flex justify-center space-x-4">
        <button type="submit" id="guardar-rol" class="bg-[#87cd51] text-white font-bold hover:bg-[#8ce83c] py-2 px-4 rounded">
          Guardar
        </button>
        <button type="button" id="editar-rol" class="bg-blue-500 text-white font-bold hover:bg-blue-600 py-2 px-4 rounded">
          Editar
        </button>
        <button type="reset" id="nuevo-registro" class="bg-gray-500 text-white font-bold hover:bg-gray-600 py-2 px-4 rounded ">
          Nuevo
        </button>
      </div>

    </form>

    <!-- TABLA -->
    <div class="relative max-h-[450px] overflow-x-hidden shadow-md sm:rounded-lg mt-5">
      <table class="w-full text-xs text-left rtl:text-right text-gray-500">
        <!-- ENCABEZADO DE LA TABLA -->
        <thead class="sticky top-2 text-xs text-gray-70 uppercase bg-lime-300">
          <tr>
            <th scope="col" class="px-10 py-3 w-1/6">N°</th>
            <th scope="col" class="px-6 py-3 w-5/6">Rol</th>
          </tr>
        </thead>
        <!-- CUERPO DE LA TABLA -->
        <tbody>
          <?php
          $roles = $rolModel->listarRol();
          foreach ($roles as $rol) {
            echo "<tr class='bg-white hover:bg-green-100 hover:scale-[101%] transition-all hover:cursor-pointer border-b'>";
            echo "<th scope='col' class='px-10 py-4 font-medium text-gray-900 whitespace-nowrap' data-codrol='" . htmlspecialchars($rol['ROL_codigo']) . "'>";
            echo htmlspecialchars($rol['ROL_codigo']);
            echo "</th>";
            echo "<th scope='row' class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap' data-rol='" . htmlspecialchars($rol['ROL_nombre']) . "'>";
            echo htmlspecialchars($rol['ROL_nombre']);
            echo "</th>";
            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>

  </main>

  <script src="./app/View/func/func_rol.js"></script>
</body>

</html>