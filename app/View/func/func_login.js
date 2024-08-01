$(document).ready(function () {
  // Configuración de Toastr
  toastr.options = {
    "positionClass": "toast-top-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Verificar si hay un parámetro de estado en la URL
  var state = new URLSearchParams(window.location.search).get('state');
  if (state === 'failed') {
    toastr.error('Credenciales incorrectas.', 'Inicio de sesión fallido.');
  }

  // Manejar la presentación del formulario
  $('.form').submit(function (event) {
    var valido = true;
    var mensajeError = '';

    var faltaUsername = ($('#username').val().trim() === '');
    var faltaPassword = ($('#password').val().trim() === '');

    if (faltaUsername && faltaPassword) {
      mensajeError = 'Por favor, complete ambos campos.';
      valido = false;
    } else if (faltaUsername) {
      mensajeError = 'Por favor, ingrese su nombre de usuario.';
      valido = false;
    } else if (faltaPassword) {
      mensajeError = 'Por favor, ingrese su contrase&ntilde;a.';
      valido = false;
    }

    // Mostrar mensaje de error si hay
    if (!valido) {
      toastr.error(mensajeError);
    }
    return valido;
  });
});


document.addEventListener("DOMContentLoaded", function () {
  // Obtener referencia al campo de entrada de contraseña y al icono de alternar
  const passwordInput = document.getElementById("password");
  const togglePassword = document.getElementById("togglePassword");

  // Variable para rastrear si la contraseña está visible u oculta
  let passwordVisible = false;

  // Agregar un event listener para el clic en el icono de alternar
  togglePassword.addEventListener("click", () => {
    // Cambiar el tipo de entrada entre "password" y "text"
    if (passwordVisible) {
      passwordInput.type = "password";
      togglePassword.innerHTML = "<i class='bx bx-show icon'></i>";
    } else {
      passwordInput.type = "text";
      togglePassword.innerHTML = "<i class='bx bx-hide icon'></i>";
    }

    // Cambiar el estado de la variable passwordVisible
    passwordVisible = !passwordVisible;
  });
});


  $(document).ready(function() {
    // Mostrar el modal
    $("a[href='#']").click(function (event) {
      event.preventDefault();
      $("#helpModal").removeClass('hidden');
    });

  // Cerrar el modal
  $("#closeModal").click(function() {
    $("#helpModal").addClass('hidden');
    });
  });


