$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

// TODO: SETEO DE COMBO AREA
$(document).ready(function () {
  $.ajax({
    url: 'ajax/getAreaData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      console.log("Areas cargadas:", data); // Depuración

      var select = $('#area');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un &aacute;rea</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.ARE_codigo + '">' + value.ARE_nombre + '</option>');
      });
    },
    error: function (error) {
      console.error(error);
    }
  });
});

// TODO: SETEO DE COMBO ESTADO
$(document).ready(function () {
  $.ajax({
    url: 'ajax/getEstadoIncidencia.php',
    type: 'POST',
    dataType: 'json',
    success: function (data) {
      console.log("Estados recibidos:", data); // Depuración

      var select = $('#estado');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un estado</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.EST_codigo + '">' + value.EST_descripcion + '</option>');
      });
    },
    error: function (error) {
      console.error(error);
    }
  });
});

// TODO: BUSCADOR PARA EL COMBO AREA Y ESTADO
$(document).ready(function () {
  $('#area').select2({
    placeholder: "Seleccione un área",
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs', // Use Tailwind CSS class
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  $('#estado').select2({
    placeholder: "Seleccione un estado",
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

function nuevaConsulta() {
  const form = document.getElementById('formConsultarIncidencia');
  form.reset();

  // Restablecer Select2 manualmente
  $('#area').val(null).trigger('change');
  $('#estado').val(null).trigger('change');
}

// Evento para nueva consulta
$('#limpiarCampos').on('click', nuevaConsulta);


// // TODO: METODO PARA BUSCAR INCIDENCIAS
// function enviarFormulario(action) {

//   // Validar campos antes de enviar
//   if (!validarCampos()) {
//     return; // si hay campos invalidos, detener el envio
//   }

//   // Obtener los valores de los campos del formulario
//   var data = $(this).serialize();
//   var url = 'consultar-incidencia-admin.php?';

//   $.ajax({
//     url: url,
//     method: 'GET',
//     data: data,
//     success: function (response) {
//       // Actualizar la tabla de incidencias con los resultados recibidos
//       $('#tablaConsultarIncidencias tbody').html(response);
//     },
//     error: function (xhr, status, error) {
//       // Manejar errores si los hubiera
//       console.error(error);
//       toastr.error('Ocurrió un error al buscar incidencias.');
//     }
//   });
// }
// $('#buscar-incidencias').on('click', function (e) {
//   e.preventDefault();
//   enviarFormulario($('#form-action').val());
// })

// function validarCampos() {
//   var valido = false;
//   var mensajeError = '';

//   var areaSeleccionada = ($('#cbo_area').val() !== null && $('#cbo_area').val() !== '');
//   var estadoSeleccionado = ($('#cbo_estado').val() !== null && $('#cbo_estado').val() !== '');
//   var fechaInicioSeleccionada = ($('#fechaInicio').val() !== null && $('#fechaInicio').val() !== '');
//   var fechaFinSeleccionada = ($('#fechaFin').val() !== null && $('#fechaFin').val() !== '');

//   // Verificar si al menos un campo está lleno
//   if (areaSeleccionada || estadoSeleccionado || fechaInicioSeleccionada || fechaFinSeleccionada) {
//     valido = true;
//   } else {
//     mensajeError = 'Debe completar al menos un campo para realizar la búsqueda.';
//   }

//   if (!valido) {
//     toastr.warning(mensajeError.trim());
//   }

//   return valido;
// }
