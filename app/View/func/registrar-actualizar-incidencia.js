$(document).ready(function () {
  // Configuración de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

function enviarFormulario(action) {
  if (!validarCampos()) {
    return;
  }

  var url = 'registro-incidencia-admin.php?action=' + action;
  var data = $('#formIncidencia').serialize();

  $.ajax({
    url: url,
    method: 'POST',
    data: data,
    dataType: 'text',
    success: function (response) {
      console.log('Raw response:', response);
      try {
        // Convertir la respuesta en un objeto JSON
        var jsonResponse = JSON.parse(response);
        console.log('Parsed JSON:', jsonResponse);

        if (jsonResponse.success) {
          if (action === 'registrar') {
            toastr.success('Incidencia registrada.');
          } else if (action === 'editar') {
            toastr.success('Incidencia actualizada.');
          }
          // Recarga la página después de 1.5 segundos
          setTimeout(function () {
            location.reload();
          }, 1500);
        } else {
          toastr.warning(jsonResponse.message);
        }
      } catch (e) {
        console.error('JSON parsing error:', e);
        toastr.error('Error al procesar la respuesta.');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX Error:', error);
      toastr.error('Error en la solicitud AJAX.');
    }
  });
}

// Validación de campos del formulario
function validarCampos() {
  var valido = true;
  var mensajeError = '';

  var faltaCategoria = ($('#cbo_categoria').val() === null || $('#cbo_categoria').val() === '');
  var faltaArea = ($('#cbo_area').val() === null || $('#cbo_area').val() === '');
  var faltaAsunto = ($('#asunto').val() === null || $('#asunto').val() === '');
  var faltaDocumento = ($('#documento').val() === null || $('#documento').val() === '');

  if (faltaCategoria && faltaArea && faltaAsunto && faltaDocumento) {
    mensajeError += 'Debe completar los campos requeridos.';
    valido = false;
  } else if (faltaCategoria) {
    mensajeError += 'Debe seleccionar una categoría.';
    valido = false;
  } else if (faltaArea) {
    mensajeError += 'Debe seleccionar un &aacute;rea.';
    valido = false;
  } else if (faltaAsunto) {
    mensajeError += 'Ingrese asunto de la incidencia.';
    valido = false;
  } else if (faltaDocumento) {
    mensajeError += 'Ingrese documento de la incidencia';
    valido = false;
  }

  if (!valido) {
    toastr.warning(mensajeError.trim());
  }
  return valido;
}
