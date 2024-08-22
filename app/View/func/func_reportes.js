$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

// Cargar opciones de áreas
$(document).ready(function () {
  // console.log("FETCHING");
  $.ajax({
    url: 'ajax/getAreaData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#area');
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

   // Setear el código del área seleccionada
   $('#area').change(function() {
    var selectedOption = $(this).find('option:selected');
    var areaCodigo = selectedOption.val(); // Obtén el valor (código) del área seleccionada
    console.log("Código de área seleccionada:", areaCodigo); // Para depuración

    // Aquí puedes setear este valor en otro campo si es necesario
    $('#codigoArea').val(areaCodigo); // Ejemplo: campo oculto o visible
  });
});


// TODO: BUSCADOR PARA EL COMBO PERSONA AREA
$(document).ready(function () {
  $('#area').select2({
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
});


