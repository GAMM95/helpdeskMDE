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

    var partesNombre = nombreCompleto.split(' ');

    var apellidoMaterno = partesNombre.pop();
    var apellidoPaterno = partesNombre.pop();
    var nombre = partesNombre.join(' ');

    $('#txt_codPersona').val(cod);
    $('#txt_dni').val(dni);
    $('#txt_nombre').val(nombre);
    $('#txt_apellidoPaterno').val(apellidoPaterno);
    $('#txt_apellidoMaterno').val(apellidoMaterno);
    $('#txt_celular').val(celular);
    $('#txt_email').val(email);

    $('tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');

    $('#form-action').val('editar');
  });

  function nuevoRegistro() {
    const form = document.getElementById('formPersona');
    form.reset();
    $('#txt_codPersona').val('');
    $('tr').removeClass('bg-blue-200 font-semibold');
    $('#form-action').val('registrar');
  }

  $('#nuevo-registro').on('click', nuevoRegistro);

  function validarDniExistente(dni, callback) {
    $.ajax({
      url: 'modulo-persona.php?action=validar_dni',
      method: 'POST',
      data: { dni: dni },
      success: function (response) {
        console.log('DNI validation response:', response); // Log response
        callback(response.exists);
      },
      error: function (xhr, status, error) {
        console.log('DNI validation error:', xhr.responseText); // Log error
        toastr.error('Error al validar el DNI');
        callback(false);
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

    if (!dni || !nombre || !apellidoPaterno || !apellidoMaterno || !celular || !email) {
      toastr.error('Todos los campos son obligatorios');
      return;
    }

    validarDniExistente(dni, function (exists) {
      if (exists && action === 'registrar') {
        toastr.error('El DNI ya está registrado');
        return;
      }

      $('#txt_codPersona').prop('disabled', false);
      var formData = $('#formPersona').serialize();
      var actionUrl = $('#formPersona').attr('action');

      console.log('Formulario:', formData);
      console.log('URL de acción:', actionUrl);

      $.ajax({
        url: actionUrl,
        method: 'POST',
        data: formData,
        success: function (response) {
          console.log('Respuesta del servidor:', response);
          if (action === 'registrar') {
            if (!exists) {
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
          console.log('Error en la solicitud:', xhr.responseText);
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
