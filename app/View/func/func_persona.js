$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Evento de clic en una fila de la tabla
  $('tr').click(function () {
    var cod = $(this).find('th').data('cod');
    var dni = $(this).find('td[data-dni]').text().trim();
    var nombreCompleto = $(this).find('td[data-nombre]').text().trim();
    var celular = $(this).find('td[data-celular]').text().trim();
    var email = $(this).find('td[data-email]').text().trim();

    var partesNombre = nombreCompleto.split(' ');

    var apellidoMaterno = partesNombre.pop();
    var apellidoPaterno = partesNombre.pop();
    var nombre = partesNombre.join(' ');

    $('#CodPersona').val(cod);
    $('#dni').val(dni);
    $('#nombres').val(nombre);
    $('#apellidoPaterno').val(apellidoPaterno);
    $('#apellidoMaterno').val(apellidoMaterno);
    $('#celular').val(celular);
    $('#email').val(email);

    $('tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');

    $('#form-action').val('editar'); // Cambiar la acción a editar

    // Habilitar el botón de editar
    $('#guardar-persona').prop('disabled', false);
    $('#editar-persona').prop('disabled', false);
    $('#nuevo-registro').prop('disabled', false);
  });

  // Función para manejar el nuevo registro
  function nuevoRegistro() {
    const form = document.getElementById('formPersona');
    form.reset();
    $('#CodPersona').val('');
    $('tr').removeClass('bg-blue-200 font-semibold');

    $('#form-action').val('registrar'); // Cambiar la acción a registrar

    // Deshabilitar el botón de editar
    $('#editar-persona').prop('disabled', true);
    $('#nuevo-registro').prop('disabled', true);
  }

  // Evento para nuevo registro
  $('#nuevo-registro').on('click', nuevoRegistro);

  function enviarFormulario(action) {
    if (!validarCampos()) {
      return; // si hay campos inválidos, detener el envío
    }

    var url = 'modulo-persona.php?action=' + action;
    var data = $('#formPersona').serialize();

    $.ajax({
      url: url,
      method: 'POST',
      data: data,
      success: function (response) {
        console.log(response); // Verifica la respuesta aquí

        if (response.success) {
          if (action === 'registrar') {
            toastr.success('Persona registrada');
          } else if (action === 'editar') {
            toastr.success('Datos actualizados');
          }
          setTimeout(function () {
            location.reload();
          }, 1500);
        } else {
          toastr.warning(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseText);
        if (response.error) {
          if (action === 'registrar') {
            toastr.success('Persona registrada');
          } else if (action === 'editar') {
            toastr.success('Datos actualizados');
          }
          setTimeout(function () {
            location.reload();
          }, 1500);
        }
      }
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


  // Función para validar campos antes de enviar
  function validarCampos() {
    var valido = true;
    var mensajeError = '';

    // Validar campos
    var faltaDni = ($('#dni').val() === null || $('#dni').val() === '');
    var faltaNombres = ($('#nombres').val() === null || $('#nombres').val() === '');
    var faltaApellidoPaterno = ($('#apellidoPaterno').val() === null || $('#apellidoPaterno').val() === '');
    var faltaApellidoMaterno = ($('#apellidoMaterno').val() === null || $('#apellidoMaterno').val() === '');

    if (faltaDni || faltaNombres || faltaApellidoPaterno || faltaApellidoMaterno) {
      mensajeError += 'Debe completar todos los campos requeridos.';
      valido = false;
    }

    // Mostrar mensaje de error si hay
    if (!valido) {
      toastr.warning(mensajeError.trim());
    }
    return valido;
  }
});
