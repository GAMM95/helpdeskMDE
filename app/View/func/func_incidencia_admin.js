$(document).ready(function () {
  // Configuración de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // SETEO DE COMBO AREA
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
    },
    error: function (error) {
      console.error('Error en la carga de áreas:', error);
    }
  });

  // SETEO DEL COMBO CATEGORIA
  $.ajax({
    url: 'ajax/getCategoryData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_categoria');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione una categoría</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.CAT_codigo + '">' + value.CAT_nombre + '</option>');
      });
    },
    error: function (error) {
      console.error('Error en la carga de categorías:', error);
    }
  });

  // BUSCADOR PARA EL COMBO CATEGORIA Y AREA
  $('#cbo_area, #cbo_categoria').select2({
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs',
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  // Evento para guardar incidencia
  $('#guardar-incidencia').on('click', function (e) {
    e.preventDefault();
    enviarFormulario($('#form-action').val());
  });

  // Evento para editar incidencia
  $('#editar-incidencia').on('click', function (e) {
    e.preventDefault();
    enviarFormulario('editar');
  });

  // Evento para nuevo registro
  $('#nuevo-registro').on('click', nuevoRegistro);
});

// Función para cambiar páginas en la tabla de incidencias
function changePageTablaListarIncidencias(page) {
  fetch(`?page=${page}`)
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const newDocument = parser.parseFromString(data, 'text/html');
      const newTable = newDocument.querySelector('#tablaListarIncidencias');
      const newPagination = newDocument.querySelector('.flex.justify-end.items-center.mt-1');

      // Reemplazar la tabla actual con la nueva tabla obtenida
      document.querySelector('#tablaListarIncidencias').parentNode.replaceChild(newTable, document.querySelector('#tablaListarIncidencias'));

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

// Establecer valores en el formulario según la fila seleccionada
$(document).ready(function () {
  // Seteo de los valores de los inputs y combos cuando se hace clic en una fila de la tabla
  $(document).on('click', '#tablaListarIncidencias tbody tr', function () {
    $('#tablaListarIncidencias tbody tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');

    // Establecer valores en el formulario según la fila seleccionada
    const celdas = $(this).find('td');
    const codIncidencia = $(this).find('th').text().trim();
    const codigoPatrimonialValue = celdas[2].innerText.trim();
    const asuntoValue = celdas[3].innerText.trim();
    const documentoValue = celdas[4].innerText.trim();
    const categoriaValue = celdas[5].innerText.trim();
    const areaValue = celdas[6].innerText.trim();
    const descripcionValue = celdas[7].innerText.trim();

    // Seteo de valores en los inputs
    document.getElementById('numero_incidencia').value = codIncidencia;
    document.getElementById('codigoPatrimonial').value = codigoPatrimonialValue;
    document.getElementById('asunto').value = asuntoValue;
    document.getElementById('documento').value = documentoValue;
    document.getElementById('descripcion').value = descripcionValue;

    // Seteo de los valores en los combos
    setComboValue('cbo_categoria', categoriaValue);
    setComboValue('cbo_area', areaValue);

    // Cambiar estado de los botones
    $('#guardar-incidencia').prop('disabled', true);
    $('#editar-incidencia').prop('disabled', false);
    $('#nuevo-registro').prop('disabled', false);

    // Si existe un código patrimonial, buscar el tipo de bien
    if (codigoPatrimonialValue) {
      buscarTipoBien(codigoPatrimonialValue);
    } else {
      // Si no hay código patrimonial, dejar el campo de tipo de bien en blanco
      $('#tipoBien').val('');
    }
  });

  // Función para buscar el tipo de bien en el servidor
  function buscarTipoBien(codigo) {
    // Limitar el código a los primeros 12 dígitos y obtener los primeros 8 dígitos para búsqueda
    var codigoLimite = codigo.substring(0, 12);
    var codigoBusqueda = codigoLimite.substring(0, 8);

    if (codigoBusqueda.length === 8) {
      $.ajax({
        url: 'ajax/getTipoBien.php',
        type: 'GET',
        data: { codigo_patrimonial: codigoBusqueda },
        success: function (response) {
          if (response.tipo_bien) {
            $('#tipoBien').val(response.tipo_bien);
          } else {
            $('#tipoBien').val('No encontrado');
          }
        },
        error: function () {
          $('#tipoBien').val('Error al buscar');
        }
      });
    } else {
      $('#tipoBien').val('Código inválido');
    }
  }
});

// seteo de los valores de los combos
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
  // Si no se encontró el valor, seleccionar el primer elemento
  if (!valueFound) {
    select.value = ''; // O establece un valor predeterminado si lo deseas
  }

  // Forzar actualización del select2 para mostrar el valor seleccionado
  $(select).trigger('change');
};

// Funcion para manejar el nuevo registro
function nuevoRegistro() {
  const form = document.getElementById('formIncidencia');
  form.reset();
  $('#numero_incidencia').val('');
  $('tr').removeClass('bg-blue-200 font-semibold');

  $('#form-action').val('registrar'); // Cambiar la acción a registrar

  // Deshabilitar el botón de editar
  $('#guardar-incidencia').prop('disabled', false);
  $('#editar-incidencia').prop('disabled', true);
  $('#nuevo-registro').prop('disabled', false);

  // Vaciar y resetear los valores de los selects de categoría y área
  $('#cbo_categoria').val('').trigger('change');
  $('#cbo_area').val('').trigger('change');

  $('#codigoPatrimonial').val('');
  $('#asunto').val('');
  $('#documento').val('');
  $('#descripcion').val('');
}
