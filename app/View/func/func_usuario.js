$(document).ready(function () {
  // Configuración de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Carga de datos en el combo persona
  $.ajax({
    url: 'ajax/getPersona.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_persona');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione una persona</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.PER_codigo + '">' + value.persona + '</option>');
      });
    },
    error: function (error) {
      console.error("Error fetching personas:", error);
    }
  });

  // Carga de datos en el combo area
  $.ajax({
    url: 'ajax/getAreaData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_area');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un area</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.ARE_codigo + '">' + value.ARE_nombre + '</option>');
      });
    },
    error: function (error) {
      console.error("Error fetching areas:", error);
    }
  });

  // Carga de datos en el combo rol
  $.ajax({
    url: 'ajax/getRol.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_rol');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un rol</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.ROL_codigo + '">' + value.ROL_nombre + '</option>');
      });
    },
    error: function (error) {
      console.error("Error fetching roles:", error);
    }
  });

  // Buscador para los combos persona, area y rol
  $('#cbo_persona, #cbo_area, #cbo_rol').select2({
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs',
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  // Evento para guardar al usuario
  $('#guardar-usuario').on('click', function (e) {
    e.preventDefault();
    enviarFormulario($('#form-action').val());
  });

  // Evento para editar usuario
  $('#editar-usuario').on('click', function (e) {
    e.preventDefault();
    enviarFormulario($('#form-action').val());
  });

  // Evento para nuevo registro
  $('#nuevo-registro').on('click', nuevoRegistro);

});


// Metodo para enviar formulario
function enviarFormulario(action) {
  if (!validarCampos()) {
    return; // si hay campos inválidos, detener el envío
  }

  var url = 'modulo-usuario.php?action=' + action;
  var data = $('#formUsuario').serialize();

  $.ajax({
    url: url,
    method: 'POST',
    data: data,
    dataType: 'text',
    success: function (response) {
      try {
        // Convertir la respuesta en un objeto JSON
        var jsonResponse = JSON.parse(response);
        console.log('Parsed JSON:', jsonResponse);

        if (jsonResponse.success) {
          if (action === 'registrar') {
            toastr.success('Usuario registrado.');
          } else if (action === 'editar') {
            toastr.success('Datos del usuario actualizados.');
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

  // Función para validar campos antes de enviar
  function validarCampos() {
    var valido = true;
    var mensajeError = '';

    // Validar campos
    var faltaPersona = ($('#cbo_persona').val() === null || $('#cbo_persona').val() === '');
    var faltaArea = ($('#cbo_area').val() === null || $('#cbo_area').val() === '');
    var faltaRol = ($('#cbo_rol').val() === null || $('#cbo_rol').val() === '');
    var faltaUsername = ($('#username').val() === null || $('#username').val() === '');
    var faltaPassword = ($('#password').val() === null || $('#password').val() === '');

    if (faltaPersona && faltaArea && faltaRol && faltaUsername && faltaPassword) {
      mensajeError += 'Debe completar todos los campos.';
      valido = false;
    } else if (faltaPersona) {
      mensajeError += 'Debe seleccionar un trabajador.';
      valido = false;
    } else if (faltaArea) {
      mensajeError += 'Debe seleccionar un area.';
      valido = false;
    } else if (faltaRol) {
      mensajeError += 'Debe seleccionar un rol.';
      valido = false;
    } else if (faltaUsername) {
      mensajeError += 'Debe ingresar un nombre de usuario.';
      valido = false;
    } else if (faltaPassword) {
      mensajeError += 'Debe ingresar una contrase&ntilde;a.';
      valido = false;
    }

    // Mostrar mensaje de error si hay
    if (!valido) {
      toastr.warning(mensajeError.trim());
    }
    return valido;
  }
}

// Seteo de valores en los inputs y combos
$(document).on('click', '#tablaListarUsuarios tbody tr', function () {
  $('#tablaListarUsuarios tbody tr').removeClass('bg-blue-200 font-semibold');
  $(this).addClass('bg-blue-200 font-semibold');

  // Establecer valores en el formulario según la fila seleccionada
  const celdas = $(this).find('td');
  const codUsuario = $(this).find('th').text().trim();
  const personaValue = celdas.eq(0).text().trim();
  const areaValue = celdas.eq(1).text().trim();
  const usernameValue = celdas.eq(2).text().trim();
  const passwordValue = celdas.eq(3).text().trim();
  const rolValue = celdas.eq(4).text().trim();

  $('#CodUsuario').val(codUsuario);
  $('#username').val(usernameValue);
  $('#password').val(passwordValue);

  setComboValue('cbo_persona', personaValue);
  setComboValue('cbo_area', areaValue);
  setComboValue('cbo_rol', rolValue);

  // Cambiar estado de los botones
  $('#guardar-usuario').prop('disabled', true);
  $('#editar-usuario').prop('disabled', false);
  $('#nuevo-registro').prop('disabled', false);
});

function setComboValue(comboId, value) {
  const select = document.getElementById(comboId);
  const options = select.options;

  let valueFound = false;
  for (let i = 0; i < options.length; i++) {
    if (options[i].text.trim() === value) {
      select.value = options[i].value;
      valueFound = true;
      break;
    }
  }
  if (!valueFound) {
    select.value = '';
  }
  $(select).trigger('change');
}

// Función para limpiar los campos del formulario y reactivar tablas
function nuevoRegistro() {
  document.getElementById('formUsuario').reset(); // Resetear el formulario completo

  // Limpiar los valores específicos de inputs y combos
  $('#CodUsuario').val('');
  $('#username').val('');
  $('#password').val('');

  // Limpiar los combos y forzar la actualización con Select2
  $('#cbo_persona').val('').trigger('change');
  $('#cbo_area').val('').trigger('change');
  $('#cbo_rol').val('').trigger('change');

  // Remover clases de selección y estilos de todas las filas de ambas tablas
  $('tr').removeClass('bg-blue-200 font-semibold');

  // Reactivar ambas tablas
  $('#tablaListarUsuarios tbody tr').removeClass('pointer-events-none opacity-50');

  // Configurar los botones en su estado inicial
  $('#form-action').val('registrar');  // Cambiar la acción a registrar
  $('#guardar-usuario').prop('disabled', false);  // Activar el botón de guardar
  $('#editar-usuario').prop('disabled', true);    // Desactivar el botón de editar
  $('#nuevo-registro').prop('disabled', false);     // Asegurarse que el botón de nuevo registro está activo
}