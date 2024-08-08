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
  console.log("FETCHING")
  $.ajax({
    url: 'ajax/getPersona.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_persona');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione una persona</option>');
      $.each(data, function (index, value) {
        console.log("Codigo: " + index + ", Area: ", value); // Mostrar índice y valor en la consola
        select.append('<option value="' + value.PER_codigo + '">' + value.persona + '</option>');
      });
      if (typeof personaRegistrada !== 'undefined' && personaRegistrada !== '') {
        select.val(personaRegistrada);
      } else {
        select.val('');
      }
    },
    error: function (error) {
      console.error("Error fetching personas:", error);
    }
  });
});

// Cargar opciones de áreas
$(document).ready(function () {
  console.log("FETCHING");
  $.ajax({
    url: 'ajax/getAreaData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_area');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un area</option>');
      $.each(data, function (index, value) {
        console.log("Codigo: " + index + ", Area: ", value); // Mostrar índice y valor en la consola
        select.append('<option value="' + value.ARE_codigo + '">' + value.ARE_nombre + '</option>');
      });
      if (typeof areaRegistrada !== 'undefined' && areaRegistrada !== '') {
        select.val(areaRegistrada);
      } else {
        select.val('');
      }
    },
    error: function (error) {
      console.error("Error fetching areas:", error);
    }
  });
});

// Cargar opciones de roles
$(document).ready(function () {
  console.log("FETCHING");
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
      if (rolRegistrado !== '') {
        select.val(rolRegistrado);
      } else {
        select.val('');
      }
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
    success: function (response) {
      if (response.success) {
        if (action === 'registrar') {
          toastr.success('Usuario registrada 1');
        } else if (action === 'editar') {
          toastr.success('Usuario actualizado 1');
        }
        setTimeout(function () {
          location.reload();
        }, 1500);
      }
      else {
        if (action === 'registrar') {
          toastr.success('Usuario registrado');
        } else if (action === 'editar') {
          toastr.success('Usuario actualizados');
        }
        setTimeout(function () {
          location.reload();
        }, 1500);
      }
    },
    error: function (error) {
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
document.addEventListener('DOMContentLoaded', (event) => {
  // Obtén todas las filas de la tabla
  const filas = document.querySelectorAll('#tablaListarUsuarios tbody tr');

  filas.forEach(fila => {
    fila.addEventListener('click', () => {
      // Obtén los datos de la fila
      const celdas = fila.querySelectorAll('td');

      // Mapea los valores de las celdas a los inputs del formulario
      const codUsuario = fila.querySelector('th').innerText.trim();
      const personaValue = celdas[0].innerText.trim();
      const areaValue = celdas[1].innerText.trim();
      const usernameValue = celdas[2].innerText.trim();
      const passwordValue = celdas[3].innerText.trim();
      const rolValue = celdas[4].innerText.trim();

      // Setear valores en los inputs
      document.getElementById('CodUsuario').value = codUsuario;
      document.getElementById('username').value = usernameValue;
      document.getElementById('password').value = passwordValue;

      // Debug: Verifica los valores que estás estableciendo
      console.log("CodUsuario:", codUsuario);
      console.log("Persona:", personaValue);
      console.log("Area:", areaValue);
      console.log("Username:", usernameValue);
      console.log("Password:", passwordValue);
      console.log("Rol:", rolValue);

      // Setear valores en los combos
      setComboValue('cbo_persona', personaValue);
      setComboValue('cbo_area', areaValue);
      setComboValue('cbo_rol', rolValue);

      // Cambiar estado de los botones
      document.getElementById('guardar-usuario').disabled = true;
      document.getElementById('editar-usuario').disabled = false;
      document.getElementById('nuevo-registro').disabled = false;
    });
  });
});

function setComboValue(comboId, value) {
  const select = document.getElementById(comboId);
  const options = select.options;

  console.log("Setting value for:", comboId, "Value:", value);

  // Verificar si el valor está en el combo
  let valueFound = false;
  for (let i = 0; i < options.length; i++) {
    if (options[i].text.trim() === value) {
      select.value = options[i].value;
      valueFound = true;
      break;
    }
  }

  // Si no se encontró el valor, seleccionar el primer elemento
  if (!valueFound) {
    select.value = ''; // O establece un valor predeterminado si lo deseas
  }

  // Forzar actualización del select2 para mostrar el valor seleccionado
  $(select).trigger('change');
}