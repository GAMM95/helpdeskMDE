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
    dataType: 'json',
    success: function (xhr, response, error, status) {
      console.log('Error response:', data);
      console.log('Status:', status);
      console.log('Error:', error);
      console.log(response.success); // Verifica la respuesta aquí
      if (response.success) {
        if (action === 'registrar') {
          toastr.success('Incidencia registrada');
        } else if (action === 'editar') {
          toastr.success('Incidencia actualizada');
        }
        setTimeout(function () {
          location.reload();
        }, 1500);
      } else {
        toastr.warning(response.message);
      }
    },
    error: function (xhr, response, status, error) {
      console.log(xhr.responseText); // Esto te mostrará la respuesta completa del servidor
      console.log('Error response:', data);
      console.log('Status:', status);
      console.log('Error:', error);
      console.log(response.error); // Verifica la respuesta aquí

      if (action === 'registrar') {
        toastr.success('Incidencia registrada');
      } else if (action === 'editar') {
        toastr.success('Incidencia actualizada');
      }
      // setTimeout(function () {
      //   location.reload();
      // }, 1500);
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
