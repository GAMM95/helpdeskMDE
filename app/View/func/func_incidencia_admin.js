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
      var select = $('#cbo_area');
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

// TODO: BUSCADOR PARA EL COMBO CATEGORIA Y AREA
$(document).ready(function () {
  $('#cbo_area').select2({
    placeholder: "Seleccione un area",
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs',
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  $('#cbo_categoria').select2({
    placeholder: "Seleccione una categoria",
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs',
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
        if (action === 'registro-incidencia-admin.php?action=registrar') {
          toastr.success('Incidencia registrada');
        } else if (action === 'registro-incidencia-admin.php?action=editar') {
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
    var faltaArea = ($('#cbo_area').val() === null || $('#cbo_area').val() === '');
    var faltaAsunto = ($('#asunto').val() === null || $('#asunto').val() === '');
    var faltaDocumento = ($('#documento').val() === null || $('#documento').val() === '');


    if (faltaCategoria && faltaArea && faltaAsunto && faltaDocumento) {
      mensajeError += 'Debe completar los campos requeridos.';
      valido = false;
    } else if (faltaCategoria) {
      mensajeError += 'Debe seleccionar una categoria.';
      valido = false;
    } else if (faltaArea) {
      mensajeError += 'Debe seleccionar un &aacute;rea.';
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
      toastr.warning(mensajeError.trim());
    }
    return valido;
  }
});

// TODO: Seteo de los valores de los inputs y combos
document.addEventListener('DOMContentLoaded', (event) => {
  // Obtener todas las filas de la tabla
  const filas = document.querySelectorAll('#tablaListarIncidencias tbody tr');

  filas.forEach(fila => {
    fila.addEventListener('click', () => {
      // Obtener los datos de la fila
      const celdas = fila.querySelectorAll('td');

      // Mapeo de los valores de las celdas a los inputs del formulario

      // const codIncidencia = fila.querySelector('th').innerText.trim();
      const incidencia = celdas[0].innerText.trim();
      const codigoPatrimonialValue = celdas[2].innerText.trim();
      const asuntoValue = celdas[3].innerText.trim();
      const documentoValue = celdas[4].innerText.trim();
      const categoriaValue = celdas[5].innerText.trim();
      const areaValue = celdas[6].innerText.trim();
      const descripcionValue = celdas[7].innerText.trim();


      // Seteo de valores en los inputs
      // document.getElementById('numero_incidencia').value = codIncidencia;
      document.getElementById('numero_incidencia').value = incidencia;
      document.getElementById('codigo_patrimonial').value = codigoPatrimonialValue;
      document.getElementById('asunto').value = asuntoValue;
      document.getElementById('documento').value = documentoValue;
      document.getElementById('descripcion').value = descripcionValue;

      // Seteo de los valores en los combos
      setComboValue('cbo_categoria', categoriaValue);
      setComboValue('cbo_area', areaValue);

      // Cambiar estado de los botones
      document.getElementById('guardar-incidencia').disabled = true;
      document.getElementById('editar-incidencia').disabled = false;
      document.getElementById('nuevo-registro').disabled = false;
    });
  });
});

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

// TODO: Funcion para manejar el nuevo registro
function nuevoRegistro() {
  const form = document.getElementById('formIncidencia');
  form.reset();
  $('#numero_incidencia').val('');
  $('tr').removeClass('bg-blue-200 font-semibold');

  $('#form-action').val('registrar'); // Cambiar la acción a registrar

  // Deshabilitar el botón de editar
  $('#guardar-incidencia').prop('disabled', false);
  $('#editar-incidencia').prop('disabled', true);
  $('#nuevo-registro').prop('disabled', true);

  // Seteo de valores por defecto en los combos
  setComboValue('cbo_categoria', 'Valor por defecto de categoría');
  setComboValue('cbo_area', 'Valor por defecto de área');
}

// Evento para nuevo registro
$('#nuevo-registro').on('click', nuevoRegistro);


