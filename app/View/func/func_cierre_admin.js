$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

// TODO: SETEAR LOS VALORES DEL COMBO OPERATIVIDAD
$(document).ready(function () {
  console.log("FETCHING");
  $.ajax({
    url: 'ajax/getOperatividad.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_operatividad');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione operatividad</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.OPE_codigo + '">' + value.OPE_descripcion + '</option>');
      });

      if (operatividadRegistrada !== '') {
        select.val(operatividadRegistrada);
      } else {
        select.val('');
      }
    },
    error: function (error) {
      console.error(error);
    }
  });
});


// TODO: METODO PARA HACER BUSQUEDA DE LA PRIMERA TABLA
$('#searchInput').on('input', function () {
  filtrarTablaRecepcionesSinCerrar();
});

// TODO: FILTRADO DE TABLA DE INCIDENCIAS SIN RECEPCIONAR
function filtrarTablaRecepcionesSinCerrar() {
  var input, filter, table, rows, cells, i, j, match;
  input = document.getElementById('searchInput');
  filter = input.value.toUpperCase();
  table = document.getElementById('tablaRecepcionesSinCerrar');
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

// TODO: FUNCION PARA CAMBIAR PAGINAS DE LA TABLA DE RECEPCIONES SIN CERRAR
function changePageTablaSinCerrar(page) {
  fetch(`?page=${page}`)
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const newDocument = parser.parseFromString(data, 'text/html');
      const newTable = newDocument.querySelector('#tablaRecepcionesSinCerrar');
      const newPagination = newDocument.querySelector('.flex.justify-end.items-center.mt-1');

      // Reemplazar la tabla actual con la nueva tabla obtenida
      document.querySelector('#tablaRecepcionesSinCerrar').parentNode.replaceChild(newTable, document.querySelector('#tablaRecepcionesSinCerrar'));

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

// TODO: SETEAR EL ID DE LA TABLA RECEPCIONES SIN CERRAR
$(document).ready(function () {
  //Evento de clic en las filas de la tabla de recepciones sin cerrar
  $(document).on('click', '#tablaRecepcionesSinCerrar tbody tr', function () {
    var id = $(this).find('th').html();
    $('#tablaRecepcionesSinCerrar tbody tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');
    $('#recepcion').val(id);
  });

  $(document).on('click', '#tablaRecepcionesSinCerrar tbody tr', function () {
    var numIncidencia = $(this).find('th').eq(1).html();
    $('#tablaRecepcionesSinCerrar tbody tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');
    $('#num_incidencia').val(numIncidencia);
  });
})

// TODO: VERIFICAR LA CANTIDAD DE REGISTROS Y OCULTAR/MOSTRAR ELEMENTOS
document.addEventListener("DOMContentLoaded", function () {
  const tablaContainer = document.getElementById("tablaContainer");
  const noRecepcion = document.getElementById("noRecepcion");

  // OCULTAR TABLA Y BUSCADOR SUPEIOR SI NO HAY REGISTROS
  if (parseInt(document.getElementById("recepcionCount").value) === 0) {
    tablaContainer.classList.add("hidden");
    noRecepcion.classList.add("hidden");
  } else {
    tablaContainer.classList.remove("hidden");
    noRecepcion.classList.remove("hidden");
  }
})

// TODO: GUARDAR EL CIERRE
$(document).ready(function () {
  $('#guardar-cierre').click(function (event) {
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
        if (action === 'registro-cierre-admin.php?action=registrar') {
          toastr.success('Incidencia cerrada');
        } else if (action === 'registro-cierre-admin.php?action=editar') {
          toastr.success('Cierre de incidencia actualizado');
        }
        setTimeout(function () {
          location.reload(); // Recargar la página después de un tiempo
        }, 1500);
      },
      error: function (xhr, status, error) {
        // Manejo de error de la solicitud AJAX
        console.error(xhr.responseText);
        toastr.error('Error al registrar cierre');
      }
    });
  });

  // Función para validar campos antes de enviar el formulario
  function validarCampos() {
    var valido = true;
    var mensajeError = ''; // Inicializamos una variable para los mensajes de error

    // Validar campo de número de incidencia
    if ($('#recepcion').val() === '') {
      mensajeError = 'Debe seleccionar una incidencia. ';
      valido = false;
    }

    // Solo validamos los otros campos si la incidencia es válida
    if (valido) {
      // Validar campo de prioridad e impacto
      var faltaOperatividad = ($('#cbo_operatividad').val() === null || $('#cbo_operatividad').val() === '');
      var faltaAsunto = ($('#asunto').val() === null || $('#asunto').val() === '');
      var faltaDocumento = ($('#documento').val() === null || $('#documento').val() === '');

      if (faltaOperatividad && faltaAsunto && faltaDocumento) {
        mensajeError += 'Ingrese campos requeridos.';
        valido = false;
      } else if (faltaOperatividad) {
        mensajeError += 'Debe seleccionar operatividad. ';
        valido = false;
      } else if (faltaAsunto) {
        mensajeError += 'Debe ingresar asunto de cierre. ';
        valido = false;
      } else if (faltaDocumento) {
        mensajeError += 'Debe ingresar documento de cierre. ';
        valido = false;
      }
    }

    // Mostrar el mensaje de error si hay
    if (!valido) {
      toastr.error(mensajeError.trim());
    }

    return valido;
  }


});
