<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="public/assets/logo.ico">

  <!-- Importación de librería jQuery -->
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <!-- Agrega las hojas de estilo de Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- Agrega la fuente Poppins desde Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700" rel="stylesheet">
  <!-- Implementación de funcionalidades para la vista cliente -->
  <script src="app/View/func/password-toggle.js"></script>
  <!-- Implementación de iconos -->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <title class="text-center text-3xl font-poppins">Sistema HelpDesk MDE</title>

  <style>
    .scaled-container {
      transform: scale(0.90);
      transform-origin: center;
    }
  </style>
</head>

<body class="bg-green-50 relative">
  <!-- Fondo con imagen -->
  <img src="public/assets/fondo1.jpeg" alt="Fondo" class="absolute inset-0 w-full h-full object-cover opacity-30 z-0">

  <!-- Fondo verde transparente -->
  <div class="absolute inset-0 bg-green-500 opacity-20 z-0"></div>

  <!-- Contenedor principal centrado vertical y horizontalmente -->
  <div class="flex justify-center items-center min-h-screen relative z-10">

    <!-- Contenedor del formulario -->
    <div class="scaled-container bg-white p-5 rounded-3xl shadow-lg max-w-screen-lg relative">
      <!-- Panel de logo MDE con video de fondo -->
      <div class="w-full hidden md:block mb-4 relative">
        <video src="public/assets/video_login.mp4" autoplay muted loop class='videoLogin rounded-xl w-full max-w-md mx-auto h-48 object-cover'></video>
        <!-- Texto sobre el video -->
        <h3 class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-3xl font-bold text-white font-poppins w-full text-center">Sistema de Gestión de Incidencias</h3>
      </div>


      <!-- Panel del formulario -->
      <div class="formDiv mx-auto">
        <!-- Encabezado y logo -->
        <div class="headerDiv text-center">
          <img src="public/assets/logo_01.png" alt="imagen de mde" class="img_logo_login w-24 h-auto mx-auto mb-2" />
        </div>

        <!-- Formulario de inicio de sesión -->
        <form action="index.php?action=login" method="POST" class="form max-w-sm mx-auto">
          <!-- Campo de entrada para usuario -->
          <div class="inputDiv w-60 mb-2 mx-auto">
            <div class="input flex items-center border rounded-2xl p-2">
              <i class='bx bxs-user icon-input icon text-green-500 text-2xl mr-2'></i>
              <input type='text' id='username' placeholder='Ingrese su usuario' name='username' autofocus class="w-full max-w-xs outline-none text-md font-poppins ml-2 text-gray-600">
            </div>
          </div>
          <!-- Campo de entrada para contraseña -->
          <div class="inputDiv w-60 mb-4 mx-auto">
            <div class="input flex items-center border rounded-2xl p-2">
              <i class="bx bxs-lock icon-input icon text-green-500 text-2xl mr-2"></i>
              <input type="password" id="password" placeholder="Ingrese su contraseña" name="password" class="w-full max-w-xs outline-none text-sm font-poppins ml-2 text-gray-600">
              <!-- Icono para mostrar/ocultar contraseña -->
              <div id="togglePassword" class="show-hide-link icon cursor-pointer text-gray-400 text-lg"><i class='bx bx-show icon text-gray-400 text-md'></i></div>
            </div>
          </div>
          <p class="mb-2 text-center text-gray-800 text-sm">¿Olvidaste tu contrase&ntilde;a?
            <a class="f-w-400 text-green-500 text-sm" href="#">Pedir ayuda</a>
          </p>


          <!-- Botón de inicio de sesión -->
          <div class="flex justify-center">
            <button type='submit' class='btn btn-form w-36 py-2 bg-green-500 text-white rounded-lg hover:bg-green-700 transition-colors mt-3 mb-4 shadow-lg hover:shadow-lg shadow-xl text-xs' name='btnIniciarSesion' content='Iniciar Sesi&oacute;n'>
              <span class="text-xl">Iniciar Sesi&oacute;n</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>

<script src="./app/View/func/func_login.js"></script>

<!-- Modal -->
<div id="helpModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
  <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
    <h2 class="text-2xl font-bold mb-4">¿Cómo podemos ayudarte?</h2>
    <p class="mb-4">Aquí puedes describir el problema que estás experimentando y nuestro equipo te asistirá lo antes posible.</p>
    <textarea class="w-full border rounded-lg p-2 mb-4" rows="4" placeholder="Escribe tu mensaje aquí..."></textarea>
    <div class="flex justify-end">
      <button id="closeModal" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-700">Cerrar</button>
    </div>
  </div>
  <div class="fixed inset-0 bg-gray-500 opacity-50"></div>
</div>

<!-- modal-backdrop fade show -->