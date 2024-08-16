$(document).ready(function () {
  // Configuración de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

// Cargar opciones de personas
$(document).ready(function () {
  // console.log("FETCHING")
  $.ajax({
    url: 'ajax/getPersona.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_persona');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione una persona</option>');
      $.each(data, function (index, value) {
        // console.log("Codigo: " + index + ", Area: ", value); // Mostrar índice y valor en la consola
        select.append('<option value="' + value.PER_codigo + '">' + value.persona + '</option>');
      });
      // if (typeof personaRegistrada !== 'undefined' && personaRegistrada !== '') {
      //   select.val(personaRegistrada);
      // } else {
      //   select.val('');
      // }
    },
    error: function (error) {
      console.error("Error fetching personas:", error);
    }
  });
});

// Cargar opciones de áreas
$(document).ready(function () {
  // console.log("FETCHING");
  $.ajax({
    url: 'ajax/getAreaData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_area');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un area</option>');
      $.each(data, function (index, value) {
        // console.log("Codigo: " + index + ", Area: ", value); // Mostrar índice y valor en la consola
        select.append('<option value="' + value.ARE_codigo + '">' + value.ARE_nombre + '</option>');
      });
    },
    error: function (error) {
      console.error("Error fetching areas:", error);
    }
  });
});

// Cargar opciones de roles
$(document).ready(function () {
  // console.log("FETCHING");
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
});

// TODO: BUSCADOR PARA EL COMBO PERSONA AREA Y ROL
$(document).ready(function () {
  $('#cbo_persona').select2({
    placeholder: "Seleccione un trabajador",
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs', // Use Tailwind CSS class
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  $('#cbo_area').select2({
    placeholder: "Seleccione un area",
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs', // Use Tailwind CSS class
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  $('#cbo_rol').select2({
    placeholder: "Seleccione un rol",
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs', // Use Tailwind CSS class
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });
});


// TODO: CAMBIAR LAS PAGINAS DE LA TABLA USUARIOS
function changePageTablaUsuarios(page) {
  fetch(`?page=${page}`)
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const newDocument = parser.parseFromString(data, 'text/html');
      const newTable = newDocument.querySelector('#tablaListarUsuarios');
      const newPagination = newDocument.querySelector('.flex.justify-end.items-center.mt-1');

      // Reemplazar la tabla actual con la nueva tabla obtenida
      document.querySelector('#tablaListarUsuarios').parentNode.replaceChild(newTable, document.querySelector('#tablaListarUsuarios'));

      // Reemplazar la paginación actual con la nueva paginación obtenida
      const currentPagination = document.querySelector('.flex.justify-end.items-center.mt-1');
      if (currentPagination && newPagination) {
        currentPagination.parentNode.replaceChild(newPagination, currentPagination);
      }
    })
    .catch(error => {
      console.error('Error al cambiar de página:', error);
    });
}

// TODO: Metodo para enviar formulario
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
    success: function (response, error, status) {
      console.log('Status:', status);
      console.log('Error de success:', error);
      console.log(response); // Verifica la respuesta aquí
      if (response.success) {
        if (action === 'registrar') {
          toastr.success('Usuario registrada 1');
        } else if (action === 'editar') {
          toastr.success('Usuario actualizado 1');
        }
        // setTimeout(function () {
        //   location.reload();
        // }, 1500);
      }
      else if (action === 'editar') {
        toastr.success('Usuario registrado');
      } else if (action === 'editar') {
        toastr.success('Usuario actualizados');
      }
      // setTimeout(function () {
      //   location.reload();
      // }, 1500);

    },
    error: function (response, status, error) {
      console.log('Status:', status);
      console.log('Error de error:', error);
      console.log(response.error); // Verifica la respuesta aquí

      if (action === 'registrar') {
        toastr.success('xxx registrada');
      } else if (action === 'editar') {
        toastr.success('Datos actualizados');
      }
      setTimeout(function () {
        location.reload();
      }, 1500);
    }
  });
}
$('#guardar-usuario').on('click', function (e) {
  e.preventDefault();
  enviarFormulario($('#form-action').val());
});

$('#editar-usuario').on('click', function (e) {
  e.preventDefault();
  enviarFormulario($('#form-action').val());
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

  if (faltaPersona || faltaArea || faltaRol || faltaUsername || faltaPassword) {
    mensajeError += 'Debe completar todos los campos requeridos.';
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


// TODO: Seteo de valores en los inputs y combos
// Resaltar fila seleccionada en la tabla de usuarios
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
