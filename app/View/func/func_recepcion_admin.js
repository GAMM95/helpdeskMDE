$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
  // Evento de clic en las filas de la tabla de incidencias sin recepcionar
  $(document).on('click', '#tablaIncidenciasSinRecepcionar tbody tr', function () {
    var id = $(this).find('th').html();
    $('#tablaIncidenciasSinRecepcionar tbody tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');
    $('#incidencia').val(id);

    var incidenciaSeleccionada = $(this).find('td').eq(0).html(); // Cambia el índice eq(0) dependiendo de la posición de la columna
    $('#incidenciaSeleccionada').val(incidenciaSeleccionada); // Asegúrate de que el input con ID 'descripcion' exista en tu HTML


    // Bloquear la tabla de incidencias recepcionadas
    $('#tablaIncidenciasRecepcionadas tbody tr').addClass('pointer-events-none opacity-50');
    document.getElementById('guardar-recepcion').disabled = false;
    document.getElementById('nuevo-registro').disabled = false;

    // Reactivar el botón "Nuevo"
    $('#nuevo-registro').prop('disabled', false);
  });

  // Evento de clic en las filas de la tabla de incidencias recepcionadas
  $(document).on('click', '#tablaIncidenciasRecepcionadas tbody tr', function () {
    var numRecepcion = $(this).data('id');
    $('#tablaIncidenciasRecepcionadas tbody tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');
    $('#num_recepcion').val(numRecepcion);

    var incidenciaSeleccionada = $(this).find('td').eq(0).html(); // Cambia el índice eq(0) dependiendo de la posición de la columna
    $('#incidenciaSeleccionada').val(incidenciaSeleccionada); // Asegúrate de que el input con ID 'descripcion' exista en tu HTML

    // Bloquear la tabla de incidencias sin recepcionar
    $('#tablaIncidenciasSinRecepcionar tbody tr').addClass('pointer-events-none opacity-50');

    // Reactivar el botón "Nuevo"
    $('#nuevo-registro').prop('disabled', false);
  });

  // Evento para nuevo registro
  $('#nuevo-registro').on('click', function () {
    nuevoRegistro();
    // Reactivar ambas tablas
    $('#tablaIncidenciasRecepcionadas tbody tr').removeClass('pointer-events-none opacity-50');
    $('#tablaIncidenciasSinRecepcionar tbody tr').removeClass('pointer-events-none opacity-50');
    location.reload();
  });


  // TODO: Manejo de la paginacion de la tabla de incidencias sin recepcionar
  $(document).on('click', '.pagination-link', function (e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    changePageTablaSinRecepcionar(page);
  });

  // Buscar en la tabla de incidencias sin recepcionar
  // $('#searchInput').on('input', function () {
  //   filtrarTablaIncidenciasSinRecepcionar();
  // });

  // Cargar opciones de prioridad
  $.ajax({
    url: 'ajax/getPrioridadData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#prioridad');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione una prioridad</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.PRI_codigo + '">' + value.PRI_nombre + '</option>');
      });
    },
    error: function (error) {
      console.error(error);
    }
  });

  // Cargar opciones de impacto
  $.ajax({
    url: 'ajax/getImpactoData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#impacto');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un impacto</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.IMP_codigo + '">' + value.IMP_descripcion + '</option>');
      });
    },
    error: function (error) {
      console.error(error);
    }
  });

  // Guardar la recepción
  $('#guardar-recepcion').click(function (event) {
    event.preventDefault(); // Prevenir el comportamiento predeterminado del botón

    // Validar campos antes de enviar
    if (!validarCampos()) {
      return; // Si hay campos inválidos, detener el envío del formulario
    }

    var form = $('#formRecepcion'); // Asegúrate de que el ID del formulario es 'formRecepcion'
    var data = form.serialize();
    console.log(data); // Para verificar cuántas veces se envía el formulario

    var action = form.attr('action');
    $.ajax({
      url: action,
      type: 'POST',
      data: data,
      success: function (response) {
        // Manejo de éxito de la solicitud AJAX
        if (action.includes('registrar')) {
          toastr.success('Incidencia recepcionada');
        } else if (action.includes('editar')) {
          toastr.success('Recepción de incidencia actualizada');
        }
        setTimeout(function () {
          location.reload(); // Recargar la página después de un tiempo
        }, 1500);
      },
      error: function (xhr, status, error) {
        // Manejo de error de la solicitud AJAX
        console.error(xhr.responseText);
        toastr.error('Error al registrar recepci&oacute;n');
      }
    });
  });

  // Validar campos antes de enviar el formulario
  function validarCampos() {
    var valido = true;
    var mensajeError = ''; // Inicializamos una variable para los mensajes de error

    // Validar campo de número de incidencia
    if ($('#incidencia').val() === '') {
      mensajeError += 'Debe seleccionar una incidencia. ';
      valido = false;
    }

    // Solo validamos los otros campos si la incidencia es valida
    if (valido) {
      // Validar campo de prioridad e impacto
      var faltaPrioridad = ($('#prioridad').val() === null || $('#prioridad').val() === '');
      var faltaImpacto = ($('#impacto').val() === null || $('#impacto').val() === '');

      if (faltaPrioridad && faltaImpacto) {
        mensajeError += 'Debe seleccionar una prioridad y un impacto.';
        valido = false;
      } else if (faltaPrioridad) {
        mensajeError += 'Debe seleccionar una prioridad.';
        valido = false;
      } else if (faltaImpacto) {
        mensajeError += 'Debe seleccionar un impacto.';
        valido = false;
      }
    }

    // Mostrar el mensaje de error si hay
    if (!valido) {
      toastr.warning(mensajeError.trim());
    }
    return valido;
  }

  // Función para limpiar los campos del formulario
  function nuevoRegistro() {
    document.getElementById('formRecepcion').reset();
  }

  // Ocultar tabla y buscador superior si no hay registros
  document.addEventListener("DOMContentLoaded", function () {
    const tablaContainer = document.getElementById("tablaContainer");
    const noIncidencias = document.getElementById("noIncidencias");

    if (parseInt(document.getElementById("incidenciaCount").value) === 0) {
      tablaContainer.classList.add("hidden");
      noIncidencias.classList.add("hidden");
    } else {
      tablaContainer.classList.remove("hidden");
      noIncidencias.classList.remove("hidden");
    }
  });
});

// Función para filtrar la tabla de incidencias sin recepcionar
// function filtrarTablaIncidenciasSinRecepcionar() {
//   var input, filter, table, rows, cells, i, j, match;
//   input = document.getElementById('searchInput');
//   filter = input.value.toUpperCase();
//   table = document.getElementById('tablaIncidenciasSinRecepcionar');
//   rows = table.getElementsByTagName('tr');

//   for (i = 1; i < rows.length; i++) {
//     cells = rows[i].getElementsByTagName('td');
//     match = false;
//     for (j = 0; j < cells.length; j++) {
//       if (cells[j].innerText.toUpperCase().indexOf(filter) > -1) {
//         match = true;
//         break;
//       }
//     }
//     rows[i].style.display = match ? '' : 'none';
//   }
// }

// Función para cambiar de página en la tabla de incidencias sin recepcionar
function changePageTablaSinRecepcionar(page) {
  fetch(`?page=${page}`)
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const newDocument = parser.parseFromString(data, 'text/html');
      const newTable = newDocument.querySelector('#tablaIncidenciasSinRecepcionar');
      const newPagination = newDocument.querySelector('.flex.justify-end.items-center.mt-1');

      // Reemplazar la tabla actual con la nueva tabla obtenida
      document.querySelector('#tablaIncidenciasSinRecepcionar').parentNode.replaceChild(newTable, document.querySelector('#tablaIncidenciasSinRecepcionar'));

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

// TODO: Verificar la cantidad de registros y ocultar/ mostrar elementos
document.addEventListener("DOMContentLoaded", function () {
  const tablaContainer = document.getElementById("tablaContainer");
  const noIncidencias = document.getElementById("noIncidencias");

  // Ocultar tabla y buscador superior si no hay registros
  if (parseInt(document.getElementById("incidenciaCount").value) === 0) {
    // if (<? php echo count($incidencias); ?> === 0) {

    tablaContainer.classList.add("hidden");
    noIncidencias.classList.add("hidden");
  } else {
    tablaContainer.classList.remove("hidden");
    noIncidencias.classList.remove("hidden");
  }
});


// TODO: Seteo de los valores de los inputs y combos
document.addEventListener('DOMContentLoaded', (event) => {
  // Obtener todas las filas de la tabla
  const filas = document.querySelectorAll('#tablaIncidenciasRecepcionadas tbody tr');

  filas.forEach(fila => {
    fila.addEventListener('click', () => {
      // Obtener los datos de la fila
      const celdas = fila.querySelectorAll('td');

      // Mapeo de los valores de las celdas a los inputs del formulario

      const codRecepcion = fila.querySelector('th').innerText.trim();
      const prioridadValue = celdas[5].innerText.trim();
      const impactoValue = celdas[6].innerText.trim();


      // Seteo de valores en los inputs
      document.getElementById('num_recepcion').value = codRecepcion;

      // Seteo de los valores en los combos
      setComboValue('prioridad', prioridadValue);
      setComboValue('impacto', impactoValue);

      // Cambiar estado de los botones
      document.getElementById('guardar-recepcion').disabled = true;
      document.getElementById('editar-recepcion').disabled = false;
      document.getElementById('nuevo-registro').disabled = false;
    });
  });
});

// seteo de los valores de los combos
function setComboValue(comboId, value) {
  const select = document.getElementById(comboId);
  const options = select.options;

  console.log("Seteo de los valores para: ", comboId, "Valor: ", value);

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
