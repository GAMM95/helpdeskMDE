$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Manejador de eventos para la tecla Escape
  $(document).keydown(function (event) {
    // Verificar si la tecla presionada es ESC
    if (event.key === 'Escape') {
      nuevoRegistro();
    }
  });
});

// TODO: SETEO DEL COMBO CATEGORIA
$(document).ready(function () {
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
});

// TODO: BUSCADOR PARA EL COMBO CATEGORIA 
$(document).ready(function () {
  $('#cbo_categoria').select2({
    placeholder: "Seleccione una categoria",
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

// TODO: CAMBIAR PAGINAS DE LA TABLA DE INCIDENCIAS
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

// TODO: GUARDAR INCIDENCIA
$(document).ready(function () {
  $('#guardar-incidencia').click(function (event) {
    event.preventDefault();

    // Validar campos antes de enviar
    if (!validarCampos()) {
      return; // si hay campos invalidos, detener el envio
    }

    var form = $('#formIncidencia');
    var data = form.serialize();
    console.log(data); // verifica las veces de envio

    var action = form.attr('action');
    $.ajax({
      url: action,
      type: "POST",
      data: data,
      success: function (response) {
        if (action === 'registro-incidencia-user.php?action=registrar') {
          toastr.success('Incidencia registrada');
        } else if (action === 'registro-incidencia-user.php?action=editar') {
          toastr.success('Incidencia actualizada');
        }
        setTimeout(function () {
          location.reload();
        }, 1500);
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
        toastr.error('Error al guardar la incidencia');
      }
    });
  });

  // FUNCION PARA VALIDAR LOS CAMPOS ANTES DE ENVIAR
  function validarCampos() {
    var valido = true;
    var mensajeError = '';

    // validar combos
    var faltaCategoria = ($('#cbo_categoria').val() === null || $('#cbo_categoria').val() === '');
    var faltaAsunto = ($('#asunto').val() === null || $('#asunto').val() === '');
    var faltaDocumento = ($('#documento').val() === null || $('#documento').val() === '');


    if (faltaCategoria && faltaAsunto && faltaDocumento) {
      mensajeError += 'Debe completar los campos requeridos.';
      valido = false;
    } else if (faltaCategoria) {
      mensajeError += 'Debe seleccionar una categoria.';
      valido = false;
    } else if (faltaAsunto) {
      mensajeError += 'Ingrese asunto de la incidencia.';
      valido = false;
    } else if (faltaDocumento) {
      mensajeError += 'Ingrese documento de la incidencia';
      valido = false;
    }

    // Mostrar mensaje de error si hay
    if (!valido) {
      toastr.error(mensajeError.trim());
    }
    return valido;
  }
});

// TODO: Seteo de los valores de los inputs y combos
$(document).on('click', '#tablaListarIncidencias tbody tr', function () {
  $('#tablaListarIncidencias tbody tr').removeClass('bg-blue-200 font-semibold');
  $(this).addClass('bg-blue-200 font-semibold');

  // Establecer valores en el formulario segun la fila seleccionada
  const celdas = $(this).find('td');
  const codIncidencia = $(this).find('th').text().trim();
  const codigoPatrimonialValue = celdas[2].innerText.trim();
  const asuntoValue = celdas[3].innerText.trim();
  const documentoValue = celdas[4].innerText.trim();
  const categoriaValue = celdas[5].innerText.trim();
  const descripcionValue = celdas[7].innerText.trim();


  // Seteo de valores en los inputs
  document.getElementById('numero_incidencia').value = codIncidencia;
  document.getElementById('codigoPatrimonial').value = codigoPatrimonialValue;
  document.getElementById('asunto').value = asuntoValue;
  document.getElementById('documento').value = documentoValue;
  document.getElementById('descripcion').value = descripcionValue;

  // Seteo de los valores en los combos
  setComboValue('cbo_categoria', categoriaValue);

  // Cambiar estado de los botones
  document.getElementById('guardar-incidencia').disabled = true;
  document.getElementById('editar-incidencia').disabled = false;
  document.getElementById('nuevo-registro').disabled = false;

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


// seteo de los valores de los combos
function setComboValue(comboId, value) {
  const select = document.getElementById(comboId);
  const options = select.options;

  // console.log("Seteo de los valores para: ", comboId, "Valor: ", value);

  // Verificar si el valor esta en el combo
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
