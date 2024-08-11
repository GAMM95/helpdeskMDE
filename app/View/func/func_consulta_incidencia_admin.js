$(document).ready(function () {
  // Configuración de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // TODO: SETEO DE COMBO AREA
  $.ajax({
    url: 'ajax/getAreaData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      // console.log("Areas cargadas:", data); // Depuración

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

  // TODO: SETEO DE COMBO ESTADO
  $.ajax({
    url: 'ajax/getEstadoIncidencia.php',
    type: 'POST',
    dataType: 'json',
    success: function (data) {
      // console.log("Estados recibidos:", data); // Depuración

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

  // TODO: BUSCADOR PARA EL COMBO AREA Y ESTADO
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

  function nuevaConsulta() {
    const form = document.getElementById('formConsultarIncidencia');
    form.reset();

    // Restablecer Select2 manualmente
    $('#area').val(null).trigger('change');
    $('#estado').val(null).trigger('change');

    window.location.reload();
  }

  // Evento para nueva consulta
  $('#limpiarCampos').on('click', nuevaConsulta);


  // $('#formConsultarIncidencia').submit(function (event) {
  //   event.preventDefault(); // Evita el envío del formulario por defecto

  //   // Verifica si los campos son válidos
  //   if (!validarCampos()) {
  //     return; // Detiene el envío si los campos no son válidos
  //   }

  //   // Recopila los datos del formulario
  //   var formData = $(this).serializeArray();

  //   // Crea un objeto para los datos del formulario
  //   var dataObject = {};

  //   // Recorre los datos del formulario
  //   formData.forEach(function (item) {
  //     // Solo agrega los parámetros al objeto si tienen valor
  //     if (item.value.trim() !== '') {
  //       dataObject[item.name] = item.value;
  //     }
  //   });

  //   // Realiza la solicitud AJAX
  //   $.ajax({
  //     url: 'consultar-incidencia-admin.php?action=consultar',
  //     type: 'GET',
  //     data: dataObject,
  //     success: function (response) {
  //       console.log("Resultados filtrados:", response); // Depuración

  //       // Limpia el contenido actual de la tabla antes de agregar nuevos datos
  //       $('#tablaIncidencias tbody').empty();

  //       // Actualiza el contenido de la tabla con la respuesta
  //       $('#tablaIncidencias tbody').append(response);
  //     },
  //     error: function (xhr, status, error) {
  //       console.error('Error en la consulta AJAX:', error);
  //     }
  //   });


  //   function validarCampos() {
  //     var valido = false;
  //     var mensajeError = '';

  //     var areaSeleccionada = ($('#area').val() !== null && $('#area').val().trim() !== '');
  //     var estadoSeleccionado = ($('#estado').val() !== null && $('#estado').val().trim() !== '');
  //     var fechaInicioSeleccionada = ($('#fechaInicio').val() !== null && $('#fechaInicio').val().trim() !== '');
  //     var fechaFinSeleccionada = ($('#fechaFin').val() !== null && $('#fechaFin').val().trim() !== '');

  //     // Verificar si al menos un campo está lleno
  //     if (areaSeleccionada || estadoSeleccionado || fechaInicioSeleccionada || fechaFinSeleccionada) {
  //       valido = true;
  //     } else {
  //       mensajeError = 'Debe completar al menos un campo para realizar la búsqueda.';
  //     }

  //     if (!valido) {
  //       toastr.warning(mensajeError.trim());
  //     }

  //     return valido;
  //   }
  // });
  $('#formConsultarIncidencia').submit(function (event) {
    event.preventDefault(); // Evita el envío del formulario por defecto

    // Verifica si los campos son válidos
    if (!validarCampos()) {
      return; // Detiene el envío si los campos no son válidos
    }

    // Recopila los datos del formulario
    var formData = $(this).serializeArray();

    // Crea un objeto para los datos del formulario
    var dataObject = {};

    // Recorre los datos del formulario
    formData.forEach(function (item) {
      // Solo agrega los parámetros al objeto si tienen valor
      if (item.value.trim() !== '') {
        dataObject[item.name] = item.value;
      }
    });

    // Realiza la solicitud AJAX
    $.ajax({
      url: 'consultar-incidencia-admin.php?action=consultar',
      type: 'GET',
      data: dataObject,
      success: function (response) {
        console.log("Resultados filtrados:", response); // Depuración

        // Limpia el contenido actual de la tabla antes de agregar nuevos datos
        $('#tablaIncidencias tbody').empty();

        // Actualiza el contenido de la tabla con la respuesta
        $('#tablaIncidencias tbody').html(response);
      },
      error: function (xhr, status, error) {
        console.error('Error en la consulta AJAX:', error);
      }
    });

    function validarCampos() {
      var valido = false;
      var mensajeError = '';

      var areaSeleccionada = ($('#area').val() !== null && $('#area').val().trim() !== '');
      var estadoSeleccionado = ($('#estado').val() !== null && $('#estado').val().trim() !== '');
      var fechaInicioSeleccionada = ($('#fechaInicio').val() !== null && $('#fechaInicio').val().trim() !== '');
      var fechaFinSeleccionada = ($('#fechaFin').val() !== null && $('#fechaFin').val().trim() !== '');

      // Verificar si al menos un campo está lleno
      if (areaSeleccionada || estadoSeleccionado || fechaInicioSeleccionada || fechaFinSeleccionada) {
        valido = true;
      } else {
        mensajeError = 'Debe completar al menos un campo para realizar la búsqueda.';
      }

      if (!valido) {
        toastr.warning(mensajeError.trim());
      }

      return valido;
    }
  });


});

