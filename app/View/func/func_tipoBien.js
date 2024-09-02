$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Evento para manejar la tecla Enter cuando una fila está seleccionada
  $(document).on('keydown', function (e) {
    // Verificar si la tecla presionada es Enter (keyCode 13)
    if (e.key === 'Enter') {
      // Si la fila está seleccionada, proceder a actualizar
      if ($('.bg-blue-200.font-semibold').length > 0) {
        e.preventDefault();
        enviarFormulario('editar');
      }
    }
  });

  // Buscar en la tabla de trabajadores
  $('#termino').on('input', function () {
    filtrarTablaBienes();
  });

  $('#guardar-bien').on('click', function (e) {
    e.preventDefault();
    enviarFormulario($('#form-action').val());
  });

  $('#editar-bien').on('click', function (e) {
    e.preventDefault();
    enviarFormulario('editar');
  });

  // Evento para nuevo registro
  $('#nuevo-registro').on('click', nuevoRegistro);
});

// Funcion para las validaciones de campos vacios y registro - actualizacion de bienes
function enviarFormulario(action) {
  if (!validarCampos()) {
    return;
  }

  var url = 'modulo-bien.php?action=' + action;
  var data = $('#formBienes').serialize();

  // Verificar los datos antes de enviarlos
  console.log('Datos enviados:', data);

  $.ajax({
    url: url,
    method: 'POST',
    data: data,
    dataType: 'text',
    success: function (response, error, status) {
      try {
        var jsonResponse = JSON.parse(response);
        console.log('Parsed JSON: ', jsonResponse);
        if (jsonResponse.success) {
          if (action === 'registrar') {
            toastr.success('Tipo de bien registrado.');
          } else if (action === 'editar') {
            toastr.success('Datos actualizados.');
          }
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

// Función para validar campos antes de enviar
function validarCampos() {
  var valido = true;
  var mensajeError = '';

  // Validar campos
  var faltaCodigoIdentificador = ($('#codigoIdentificador').val() === null || $('#codigoIdentificador').val() === '');
  var faltaNombreTipoBien = ($('#nombreTipoBien').val() === null || $('#nombreTipoBien').val() === '');

  if (faltaCodigoIdentificador && faltaNombreTipoBien) {
    mensajeError += 'Debe completar todos los campos.';
    valido = false;
  } else if (faltaCodigoIdentificador) {
    mensajeError += 'Debe ingresar un codigo identificador.';
    valido = false;
  } else if (faltaNombreTipoBien) {
    mensajeError += 'Debe ingresar un nombre para el tipo de bien.';
    valido = false;
  }

  // Mostrar mensaje de error si hay
  if (!valido) {
    toastr.warning(mensajeError.trim());
  }
  return valido;
}

// Seteo de los valores de los inputs y combos cuando se hace clic en una fila de la tabla
$(document).on('click', '#tablaListarBienes tbody tr', function () {
  // Desmarcar las filas anteriores
  $('#tablaListarBienes tbody tr').removeClass('bg-blue-200 font-semibold');
  // Marcar la fila actual
  $(this).addClass('bg-blue-200 font-semibold');

  // Obtener las celdas de la fila seleccionada
  const celdas = $(this).find('td');

  // Asegúrate de que 'codBien' esté correctamente asignado
  const codBien = $(this).find('th').text().trim();
  const codigoIdentificador = celdas[0].innerText.trim();
  const nombreTipoBien = celdas[1].innerText.trim();

  // Establecer valores en los inputs
  $('#codBien').val(codBien);
  $('#codigoIdentificador').val(codigoIdentificador);
  $('#nombreTipoBien').val(nombreTipoBien);

  // Cambiar el estado de los botones
  $('#form-action').val('editar'); // Cambiar la acción a editar
  $('#guardar-bien').prop('disabled', true);
  $('#editar-bien').prop('disabled', false);
  $('#nuevo-registro').prop('disabled', false);

  // Cambiar la acción a editar
  $('#form-action').val('editar');
});



// Funcionaliad boton nuevo
function nuevoRegistro() {
  const form = document.getElementById('formBienes');
  form.reset();
  $('#codBien').val('');
  $('tr').removeClass('bg-blue-200 font-semibold');

  // Cambiar la acción del formulario a registrar
  $('#form-action').val('registrar');

  // Deshabilitar el botón de editar
  $('#guardar-bien').prop('disabled', false);
  $('#editar-bien').prop('disabled', true);
  $('#nuevo-registro').prop('disabled', true);

  // Limpiar el campo de búsqueda y actualizar la tabla
  document.getElementById('termino').value = '';
  filtrarTablaBienes();
}


// función para filtrar la tabla de bienes
function filtrarTablaBienes() {
  var input, filtro, tabla, filas, celdas, i, j, match;
  input = document.getElementById('termino');
  filtro = input.value.toUpperCase();
  tabla = document.getElementById('tablaListarBienes');
  filas = tabla.getElementsByTagName('tr');

  for (i = 1; i < filas.length; i++) {
    celdas = filas[i].getElementsByTagName('td');
    match = false;
    for (j = 0; j < celdas.length; j++) {
      if (celdas[j].innerText.toUpperCase().indexOf(filtro) > -1) {
        match = true;
        break;
      }
    }
    filas[i].style.display = match ? '' : 'none';
  }
}