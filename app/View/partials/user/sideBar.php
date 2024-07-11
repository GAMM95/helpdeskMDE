<?php
// Inicia la sesión solo aquí
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE-edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="/public/assets/logo.ico">

  <!-- Importación de librería jQuery -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <script src="https:////cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <!-- Agrega las hojas de estilo de Tailwind CSS -->
  <link href="http://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Agrega la fuente Poppins desde Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700" rel="stylesheet">
  <!-- Implementación de funcionalidades para la vista cliente -->
  <script src="app/Views/func/password-toggle.js"></script>
  <!-- Implementación de iconos-->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <!-- Incluye Alpine.js -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
  <!-- Enlace al archivo CSS externo para los estilos de la barra de desplazamiento -->
  <link rel="stylesheet" href="app/View/partials/scrollbar-styles.css">
  <title class="text-center text-3xl font-poppins">Sistema de Incidencias</title>


</head>

<body class="bg-green-50 flex items-center justify-center min-h-screen">

  <!-- Sidebar -->
  <aside class="bg-white text-gray-800 w-60 flex flex-col shadow-md overflow-y-auto rounded-lg">
    <!-- Usuario -->
    <div class="py-4 px-4 mt-10 border-gray-300 flex flex-col items-center justify-center">
      <i class='bx bxs-user icon-input icon text-gray-500 text-7xl mb-2'></i>
      <p class="text-center text-sm font-semibold">
        <?php
        if (isset($_SESSION['nombreDePersona'])) {
          echo htmlspecialchars($_SESSION['nombreDePersona'], ENT_QUOTES, 'UTF-8');
          echo "<br><br>";
          echo htmlspecialchars($_SESSION['area'], ENT_QUOTES, 'UTF-8');
        } else {
          echo "Usuario no logueado";
        }
        ?>
      </p>
    </div>

    <!-- Etiquetas con submenús -->
    <nav class="flex-1 mt-20">
      <a href="inicio.php" class="block py-2 px-4 hover:bg-[#d5fab4]">Inicio</a>
      <a href="registro-incidencia-user.php" class="block py-2 px-4 hover:bg-[#d5fab4]">Registrar Incidencia</a>

      <!-- Etiqueta con submenú: Consultar -->
      <div x-data="{ open: false }">
        <button @click="open = !open" class="block py-2 px-4 hover:bg-[#d5fab4] flex justify-between w-full">
          Consultar
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300" :class="{'rotate-180': open, 'rotate-0': !open}" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 1.414L10 12.414l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
          </svg>
        </button>

        <div x-show="open" x-transition class="pl-4">
          <a href="consultar-incidencia-user.php" class="block py-2 px-4 hover:bg-[#d5fab4]">Incidencia</a>
          <a href="consultar-recepcion.php" class="block py-2 px-4 hover:bg-[#d5fab4]">Recepción</a>
          <a href="consultar-cierre.php" class="block py-2 px-4 hover:bg-[#d5fab4]">Cierre</a>
        </div>
      </div>

      <!-- Boton Cerrar Sesion -->
      <div class="py-4 px-4 border-gray-300">
        <button class="w-full mt-20 mb-2 text-white bg-green-500 rounded-lg hover:bg-green-700 transition-colors font-bold py-2 px-4 shadow-lg hover:shadow-xl">
          <a href="logout.php">Cerrar Sesión</a>
        </button>
      </div>
    </nav>
  </aside>

</body>

</html>