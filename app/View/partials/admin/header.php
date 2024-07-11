<!doctype html>
<html lang="es">

<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>

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
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Agrega la fuente Poppins desde Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700" rel="stylesheet">
  <!-- Implementación de funcionalidades para la vista cliente -->
  <script src="app/Views/func/password-toggle.js"></script>
  <!-- Implementación de iconos-->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <!-- Enlace al archivo CSS externo para los estilos de la barra de desplazamiento -->
  <link rel="stylesheet" href="app/View/partials/scrollbar-styles.css">
  <!-- Incluye Alpine.js -->
  <title class="text-center text-3xl font-poppins">Sistema de Incidencias</title>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <style>
    /* Estilo adicional para superponer el header */
    header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 50;
    }
  </style>
</head>

<body class="bg-green-50 flex flex-col items-center min-h-screen">

  <!-- Header -->
  <header class="bg-green-50 text-gray-800 w-full flex flex-col items-center shadow-md">
    <!-- Navegación -->
    <nav class="w-full flex justify-between items-center py-4 px-4 border-b">
      <div class="flex items-center justify-center space-x-6">
        <a href="inicio.php">
          <img src="public/assets/escudo_mde.png" alt="Escudo" class="w-20 h-20 object-contain ml-20 mr-5">
        </a>
        <a href="inicio.php" class="hover:bg-[#d5fab4] px-3 py-2 rounded-lg  relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-[2px] after:bg-green-500 after:transition-all after:duration-300 hover:after:w-full text-lg">Inicio</a>

        <div x-data="{ openRegistrar: false }" class="relative">
          <button @click="openRegistrar = !openRegistrar" class="hover:bg-[#d5fab4] px-3 py-2 rounded-lg flex items-center space-x-1 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-[2px] after:bg-green-500 after:transition-all after:duration-300 hover:after:w-full">
            <span class="text-lg">Registrar</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform" :class="{'rotate-180': openRegistrar, 'rotate-0': !openRegistrar}" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 1.414L10 12.414l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
            </svg>
          </button>
          <div x-show="openRegistrar" @click.away="openRegistrar = false" class="absolute left-0 mt-2 w-48 bg-white shadow-md rounded-lg py-2">
            <a href="registro-incidencia-admin.php" class="block px-4 py-2 hover:bg-[#d5fab4]">Incidencia</a>
            <a href="registro-recepcion-admin.php" class="block px-4 py-2 hover:bg-[#d5fab4]">Recepción</a>
            <a href="registro-cierre-admin.php" class="block px-4 py-2 hover:bg-[#d5fab4]">Cierre</a>
          </div>
        </div>

        <div x-data="{ openConsultar: false }" class="relative">
          <button @click="openConsultar = !openConsultar" class="hover:bg-[#d5fab4] px-3 py-2 rounded-lg flex items-center space-x-1 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-[2px] after:bg-green-500 after:transition-all after:duration-300 hover:after:w-full">
            <span class="text-lg">Consultar</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform" :class="{'rotate-180': openConsultar, 'rotate-0': !openConsultar}" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 1.414L10 12.414l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
            </svg>
          </button>
          <div x-show="openConsultar" @click.away="openConsultar = false" class="absolute left-0 mt-2 w-48 bg-white shadow-md rounded-lg py-2">
            <a href="consultar-incidencia-admin.php" class="block px-4 py-2 hover:bg-[#d5fab4]">Incidencia</a>
            <a href="consultar-recepcion-admin.php" class="block px-4 py-2 hover:bg-[#d5fab4]">Recepción</a>
            <a href="consultar-cierre-admin.php" class="block px-4 py-2 hover:bg-[#d5fab4]">Cierre</a>
          </div>
        </div>

        <div x-data="{ openMantenedor: false }" class="relative">
          <button @click="openMantenedor = !openMantenedor" class="hover:bg-[#d5fab4] px-3 py-2 rounded-lg flex items-center space-x-1 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-[2px] after:bg-green-500 after:transition-all after:duration-300 hover:after:w-full">
            <span class="text-lg">Mantenedor</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform" :class="{'rotate-180': openMantenedor, 'rotate-0': !openMantenedor}" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 1.414L10 12.414l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
            </svg>
          </button>
          <div x-show="openMantenedor" @click.away="openMantenedor = false" class="absolute left-0 mt-2 w-48 bg-white shadow-md rounded-lg py-2">
            <a href="modulo-persona.php" class="block px-4 py-2 hover:bg-[#d5fab4]">Persona</a>
            <a href="modulo-usuario.php" class="block px-4 py-2 hover:bg-[#d5fab4]">Usuario</a>
            <a href="modulo-area.php" class="block px-4 py-2 hover:bg-[#d5fab4]">Área</a>
            <!-- <a href="modulo-rol.php" class="block px-4 py-2 hover:bg-[#d5fab4]">Rol</a> -->
            <a href="modulo-categoria.php" class="block px-4 py-2 hover:bg-[#d5fab4]">Categoría</a>
          </div>
        </div>

      </div>


      <div class="flex items-center space-x-4 ml-auto">
        <div class="flex items-center justify-end space-x-4">
          <div>
            <p class="text-sm font-semibold text-right">
              <?php
              if (isset($_SESSION['nombreDePersona'])) {
                echo htmlspecialchars($_SESSION['nombreDePersona'], ENT_QUOTES, 'UTF-8');
                echo "<br>";
                echo htmlspecialchars($_SESSION['area'], ENT_QUOTES, 'UTF-8');
              } else {
                echo "Usuario no logueado";
              }
              ?>
            </p>
          </div>
          <div class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-200">
            <i class='bx bxs-user icon-input icon text-gray-500 text-3xl'></i>
          </div>
        </div>

        <div x-data="{ open: false }" class="relative">
          <button @click="open = !open" class="hover:bg-[#d5fab4] px-3 py-2 rounded-lg flex items-center space-x-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform" :class="{'rotate-180': open, 'rotate-0': !open}" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 1.414L10 12.414l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
            </svg>
          </button>
          <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white shadow-md rounded-lg py-2">
            <a href="modulo-persona.php" class="block px-4 py-2 hover:bg-[#d5fab4]">Persona</a>
            <a href="modulo-usuario.php" class="block px-4 py-2 hover:bg-[#d5fab4]">Perfil</a>
            <a href="logout.php" class="block px-4 py-2 hover:bg-[#d5fab4]">Cerrar Sesión </a>
          </div>

        </div>
      </div>

    </nav>
  </header>
</body>

</html>