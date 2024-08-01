$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

// TODO: SETEO DE COMBO AREA
$(document).ready(function () {
  console.log("FETCHING")
  $.ajax({
    url: 'ajax/getAreaData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_area');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un &aacute;rea</option>');
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
      console.error(error);
    }
  });
});

// TODO: BUSCADOR PARA EL COMBO CATEGORIA Y AREA
$(document).ready(function () {
  $('#cbo_area').select2({
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
});

// TODO: METODO PARA LIMPIAR LOS CAMPOS 
$('#limpiarCampos').click(limpiarCampos);
function limpiarCampos() {
  document.getElementById('formConsultarIncidencia').reset();
}

// TODO: METODO PARA BUSCAR INCIDENCIAS
// $(document).ready(function () {
//   $('#buscar-incidencias').submit(function (event) {
//     event.preventDefault(); // Evita el envío normal del formulario

//     // Obtener los valores de los campos del formulario
//     var area = $('#cbo_area').val();
//     var codigoPatrimonial = $('#codigo_patrimonial').val();
//     var fechaInicio = $('#fechaInicio').val();
//     var fechaFin = $('#fechaFin').val();

//     // Enviar la solicitud AJAX
//     $.ajax({
//       url: 'consultar-incidencia-admin.php?action=consultar', // URL a la que se envía la solicitud
//       method: 'GET', // Método HTTP (GET es el predeterminado para formularios GET)
//       data: {
//         area: area,
//         codigoPatrimonial: codigoPatrimonial,
//         fechaInicio: fechaInicio,
//         fechaFin: fechaFin
//       },
//       success: function (response) {
//         // Actualizar la tabla de incidencias con los resultados recibidos
//         $('#tablaConsultarIncidencias tbody').html(response);
//       },
//       error: function (xhr, status, error) {
//         // Manejar errores si los hubiera
//         console.error(error);
//         toastr.error('Ocurrió un error al buscar incidencias.');
//       }
//     });
//   });
// });


