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
        select.append('<option value="' + value.PER_codigo + '">' + value.persona + '</option>');
      });
      if (personaRegistrada !== '') {
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
        select.append('<option value="' + value.ARE_codigo + '">' + value.ARE_nombre + '</option>');
      });
      if (areaRegistrada !== '') {
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

// TODO: SETEO DEL CODIGO DE USUARIO DESDE LA TABLA
$(document).ready(function () {
  $(document).on('click', '#tablaListarUsuarios tbody tr', function () {
    var id = $(this).find('th').html();
    $('#tablaListarUsuarios tbody tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');
    $('#txt_codigoUsuario').val(id);
  });
});


// TODO: SETEO DE VALORES EN LOS INPUTS
$(document).ready(function () {
  // Evento de clic en una fila de la tabla
  $('#tablaListarUsuarios tbody').on('click', 'tr', function () {
    var usuario = $(this).data('usuario');
    var cod = $(this).find('td[data-codusuario]').data('codusuario');
    var codPersona = $(this).find('td[data-codpersona]').data('persona');
    var codArea = $(this).find('td[data-area]').data('codarea');
    var codRol = $(this).find('td[data-codrol]').data('codrol');
    var usuario = $(this).find('td[data-usuario]').text();
    var password = $(this).find('td[data-password]').text();

    $('#txt_codigoUsuario').val(cod);
    $('#cbo_persona').val(codPersona);
    $('#cbo_area').val(codArea);
    $('#cbo_rol').val(codRol);
    $('#txt_nombreUsuario').val(usuario);
    $('#txt_password').val(password);

    $('tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');
  });
});


// Registrar usuario
// $(document).ready(function () {
//   $('#guardar-usuario').click(function (event) {
//     event.preventDefault();

//     // Validar campos antes de enviar
//     if (!validarCampos()) {
//       return; // si hay campos invalidos, detener el envio
//     }

//     var form = $('#formUsuario');
//     var data = form.serialize();
//     console.log(data); //verifica las veces de envio

//     var acton = form.attr('action');
//     $.ajax({
//       url: action,
//       type: "POST",
//       data: data,
//       success: function (response) {
//         if (action === 'modulo-usuario.php?action=registrar') {
//           toastr.success('Usuario registrado');
//         } else if (action === 'modulo-usuario.php?action=editar') {
//           toastr.success('Usuario actualizado');
//         }
//         setTimeout(function () {
//           location.reload();
//         }, 1500);
//       },
//       error: function (xhr, status, error) {
//         console.error(xhr.responseText);
//         toastr.error('Error al guardar usuario');
//       }
//     })
//   });

//   // Funcion para validar los campos antes de enviar
//   function validarCampos() {
//     var valido = true;
//     var mensajeError = '';

//     // validar inputs
//     var faltaTrabajador = ($('#cbo_persona').val() === null || $('#cbo_persona').val() === '');
//     var faltaArea = ($('#cbo_area').val() === null || $('#cbo_area').val() === '');
//     var faltaRol = ($('#cbo_rol').val() === null || $('#cbo_rol').val() === '');
//     var faltaUsername = ($('#username').val() === null || $('#username').val() === '');
//     var faltaPassword = ($('#password').val() === null || $('#password').val() === '');

//     if (faltaTrabajador && faltaArea && faltaRol && faltaUsername && faltaPassword) {
//       mensajeError += 'Debe completar los campos requeridos';
//       valido = false;
//     } else if (faltaTrabajador) {
//       mensajeError += 'Debe seleccionar un trabajador';
//       valido = false;
//     } else if (faltaArea) {
//       mensajeError += 'Debe seleccionar un area';
//       valido = false;
//     } else if (faltaRol) {
//       mensajeError += 'Debe seleccionar un rol';
//       valido = false;
//     } else if (faltaUsername) {
//       mensajeError += 'Debe ingresar un nombre de usuario';
//       valido = false;
//     } else if (faltaPassword) {
//       mensajeError += 'Debe ingresar una contrase&ntilde;a';
//       valido = false;
//     }

//     // Mostrar mensaje de error si hay
//     if (!valido) {
//       toastr.error(mensajeError.trim());
//     }
//     return valido;
//   }
// })

// Manejo del cambio del switch
$(document).ready(function () {
  // Manejar el cambio en el switch
  $(document).on('change', '.custom-control-input', function () {
    // Obtener el estado del switch y el código de usuario
    const isChecked = $(this).is(':checked');
    const usuarioCodigo = $(this).attr('id').replace('customswitch', '');

    // Enviar una solicitud AJAX para habilitar o deshabilitar al usuario
    $.ajax({
      url: 'ruta_a_tu_controlador.php', // Cambia esto a la ruta de tu controlador
      type: 'POST',
      data: {
        USU_codigo: usuarioCodigo,
        action: isChecked ? 'habilitar' : 'deshabilitar'
      },
      success: function (response) {
        const data = JSON.parse(response);
        if (data.success) {
          toastr.success('Estado actualizado correctamente.');
        } else {
          toastr.error('Error al actualizar el estado: ' + (data.error || 'Desconocido'));
        }
      },
      error: function (xhr, status, error) {
        toastr.error('Error al actualizar el estado: ' + error);
      }
    });
  });
});

// actualizar estado del switch
$(document).ready(function () {
  // Inicializar el estado de los switches basado en el estado del usuario
  $('#tablaListarUsuarios .custom-control-input').each(function () {
    const estado = $(this).data('estado'); // Suponiendo que el atributo data-estado está configurado en el backend
    $(this).prop('checked', estado === 'Activo');
  });
});
