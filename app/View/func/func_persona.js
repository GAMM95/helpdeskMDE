$(document).ready(function () {
  // Configurar la posición de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "3000"
  };

  // Evento de clic en una fila de la tabla
  $('tr').click(function () {
    var cod = $(this).find('th').data('cod');
    var dni = $(this).find('td[data-dni]').text();
    var nombreCompleto = $(this).find('td[data-nombre]').text();
    var celular = $(this).find('td[data-celular]').text();
    var email = $(this).find('td[data-email]').text();

    // Separar el nombre completo en partes: nombre, apellido paterno y apellido materno
    var partesNombre = nombreCompleto.split(' ');

    // Asumir que el último nombre es el apellido materno y el penúltimo es el apellido paterno
    var apellidoMaterno = partesNombre.pop();
    var apellidoPaterno = partesNombre.pop();

    // Lo que queda es el nombre, que puede tener uno o más palabras
    var nombre = partesNombre.join(' ');

    // Establecer los valores en los campos del formulario
    $('#txt_codPersona').val(cod);
    $('#txt_dni').val(dni);
    $('#txt_nombre').val(nombre);
    $('#txt_apellidoPaterno').val(apellidoPaterno);
    $('#txt_apellidoMaterno').val(apellidoMaterno);
    $('#txt_celular').val(celular);
    $('#txt_email').val(email);

    // Aplicar estilos de selección a la fila seleccionada y quitarlos de las demás filas
    $('tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');

    // Cambiar la acción del formulario a editar
    $('#form-action').val('editar');
  });

  function nuevoRegistro() {
    const form = document.getElementById('formPersona');
    form.reset();
    $('#txt_codPersona').val('');
    $('tr').removeClass('bg-blue-200 font-semibold');

    // Cambiar la acción del formulario a registrar
    $('#form-action').val('registrar');
  }

  $('#nuevo-registro').on('click', nuevoRegistro);

  function validarDniExistente(dni, callback) {
    $.ajax({
      url: 'modulo-persona.php?action=validar_dni',
      method: 'POST',
      data: { dni: dni },
      success: function (response) {
        callback(response.exists); // Envía el resultado de existencia al callback
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseText);
        toastr.error('Error al validar el DNI');
        callback(false); // Indica que no se encontró el DNI (o hubo error)
      }
    });
  }

  function enviarFormulario(action) {
    var dni = $('#txt_dni').val();
    var nombre = $('#txt_nombre').val();
    var apellidoPaterno = $('#txt_apellidoPaterno').val();
    var apellidoMaterno = $('#txt_apellidoMaterno').val();
    var celular = $('#txt_celular').val();
    var email = $('#txt_email').val();

    if (!dni) {
      toastr.error('El campo "DNI" no puede estar vacío');
      return;
    }

    if (!nombre) {
      toastr.error('El campo "Nombres" no puede estar vacío');
      return;
    }

    if (!apellidoPaterno) {
      toastr.error('El campo "Apellido Paterno" no puede estar vacío');
      return;
    }

    if (!apellidoMaterno) {
      toastr.error('El campo "Apellido Materno" no puede estar vacío');
      return;
    }

    if (!celular) {
      toastr.error('El campo "Celular" no puede estar vacío');
      return;
    }

    if (!email) {
      toastr.error('El campo "Email" no puede estar vacío');
      return;
    }

    // Validar la existencia del DNI antes de enviar el formulario
    validarDniExistente(dni, function (exists) {
      if (exists && action === 'registrar') {
        toastr.error('El DNI ya está registrado');
        return;
      }

      // Habilitar el campo antes de enviar
      $('#txt_codPersona').prop('disabled', false);

      var formData = $('#formPersona').serialize();

      $.ajax({
        url: 'modulo-persona.php?action=' + action,
        method: 'POST',
        data: formData,
        success: function (response) {
          if (action === 'registrar') {
            if (!exists) { // Mostrar mensaje solo si no existe el DNI
              toastr.success('Persona registrada');
            }
          } else if (action === 'editar') {
            toastr.success('Datos actualizados');
          }
          setTimeout(function () {
            location.reload();
          }, 1500);
        },
        error: function (xhr, status, error) {
          console.log(xhr.responseText);
          toastr.error('Error al guardar persona');
        },
        complete: function () {
          $('#txt_codPersona').prop('disabled', true);
        }
      });
    });
  }

  $('#guardar-persona').on('click', function (e) {
    e.preventDefault();
    enviarFormulario($('#form-action').val());
  });

  $('#editar-persona').on('click', function (e) {
    e.preventDefault();
    enviarFormulario('editar');
  });
});
