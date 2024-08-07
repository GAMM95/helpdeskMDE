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
  var url = 'modulo-usuario.php?action=' + action;
  var data = $('#formUsuario').serialize();
  $.ajax({
    url: url,
    method: 'POST',
    data: data,
    success: function (response) {
      if (response.success) {
        if (action === 'registrar') {
          toastr.success('Usuario registrada');
        } else if (action === 'editar') {
          toastr.success('Usuario actualizado');
        }
        setTimeout(function () {
          location.reload();
        }, 1500);
      }
      else {
        if (action === 'registrar') {
          toastr.success('Usuario registradsssso');
        } else if (action === 'editar') {
          toastr.success('Datos actualizados');
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