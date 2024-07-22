// TODO: MOSTRAR MENSAJE Y UBICACION
$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

// TODO: METODO EVENTO PARA CLICKEO DE TABLAS
$(document).ready(function () {
  // Evento de clic en las filas de la tabla de incidencias sin recepcionar
  $(document).on('click', '#tablaIncidenciasSinRecepcionar tbody tr', function () {
    var id = $(this).find('th').html();
    $('#tablaIncidenciasSinRecepcionar tbody tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');
    $('#incidencia').val(id);
  });

  // Evento de clic en las filas de la tabla de incidencias recepcionadas
  $(document).on('click', '#tablaIncidenciasRecepcionadas tbody tr', function () {
    var numRecepcion = $(this).data('id');
    $('#tablaIncidenciasRecepcionadas tbody tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');
    $('#num_recepcion').val(numRecepcion);
  });


  // TODO: METODO PARA LIMPIAR LOS CAMPOS 
  $('#nuevoRegistro').click(nuevoRegistro);

  // Función para cambiar de página en la tabla de incidencias sin recepcionar
  $(document).on('click', '.pagination-link', function (e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    changePageTablaSinRecepcionar(page);
  });
});


// TODO: METODO PARA HACER BUSQUEDA DE LA PRIMERA TABLA
$('#searchInput').on('input', function () {
  filtrarTablaIncidenciasSinRecepcionar();
});

function filtrarTablaIncidenciasSinRecepcionar() {
  var input, filter, table, rows, cells, i, j, match;
  input = document.getElementById('searchInput');
  filter = input.value.toUpperCase();
  table = document.getElementById('tablaIncidenciasSinRecepcionar');
  rows = table.getElementsByTagName('tr');

  for (i = 1; i < rows.length; i++) {
    cells = rows[i].getElementsByTagName('td');
    match = false;
    for (j = 0; j < cells.length; j++) {
      if (cells[j].innerText.toUpperCase().indexOf(filter) > -1) {
        match = true;
        break;
      }
    }
    rows[i].style.display = match ? '' : 'none';
  }
}

// TODO: FUNCION PARA CAMBIAR PAGINAS DE LA TABLA DE INCIDENCIAS SIN RECEPCIONAR
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

// TODO: SETEAR LOS VALORES DEL COMBO PRIORIDAD
$(document).ready(function () {
  console.log("FETCHING")
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

      if (prioridadRegistrada !== '') {
        select.val(prioridadRegistrada);
      } else {
        select.val('');
      }
    },
    error: function (error) {
      console.error(error);
    }
  });
});

// TODO: SETEAR LOS VALORES DEL COMBO IMPACTO
$(document).ready(function () {
  console.log("FETCHING")
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

      if (impactoRegistrado !== '') {
        select.val(impactoRegistrado);
      } else {
        select.val('');
      }
    },
    error: function (error) {
      console.error(error);
    }
  });
});

// TODO: GUARDAR LA RECEPCION
$(document).ready(function () {
  $('#guardar-recepcion').click(function (event) {
    event.preventDefault(); // Prevenir el comportamiento predeterminado del botón

    // Validar campos antes de enviar
    if (!validarCampos()) {
      return; // Si hay campos inválidos, detener el envío del formulario
    }

    var form = $('form');
    var data = form.serialize();
    console.log(data); // Para verificar cuántas veces se envía el formulario

    var action = form.attr('action');
    $.ajax({
      url: action,
      type: 'POST',
      data: data,
      success: function (response) {
        // Manejo de éxito de la solicitud AJAX
        if (action === 'registro-recepcion-admin.php?action=registrar') {
          toastr.success('Recepción de incidencia registrada');
        } else if (action === 'registro-recepcion-admin.php?action=editar') {
          toastr.success('Recepción de incidencia actualizada');
        }
        setTimeout(function () {
          location.reload(); // Recargar la página después de un tiempo
        }, 1500);
      },
      error: function (xhr, status, error) {
        // Manejo de error de la solicitud AJAX
        console.error(xhr.responseText);
        toastr.error('Error al registrar recepción');
      }
    });
  });

  // Función para validar campos antes de enviar el formulario
  function validarCampos() {
    var valido = true;
    var mensajeError = ''; // Inicializamos una variable para los mensajes de error

    // Validar campo de número de incidencia
    if ($('#incidencia').val() === '') {
      mensajeError += 'Debe seleccionar una incidencia. ';
      valido = false;
    }

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
    // Mostrar el mensaje de error si hay
    if (!valido) {
      toastr.error(mensajeError.trim());
    }
    return valido;
  }
});

// TODO: METODO PARA LIMPIAR CAJAS
function nuevoRegistro() {
  document.getElementById('formRecepcion').reset();
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