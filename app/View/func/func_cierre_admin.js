$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Seteo del combo condicion
  $.ajax({
    url: 'ajax/getOperatividadData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_operatividad');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione una condici&oacute;n</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.CON_codigo + '">' + value.CON_descripcion + '</option>');
      });
    },
    error: function (error) {
      console.error('Error en la carga de condiciones:', error);
    }
  });

  // BUSCADOR PARA EL COMBO CATEGORIA Y AREA
  $('#cbo_operatividad').select2({
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs',
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  //Evento de clic en las filas de la tabla de recepciones sin cerrar
  $(document).on('click', '#tablaRecepcionesSinCerrar tbody tr', function () {
    // seteo del numero de recepcion
    var id = $(this).find('th').html();
    $('#tablaRecepcionesSinCerrar tbody tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');
    $('#recepcion').val(id);

    // Seteo del codigo de incidencia
    var numIncidencia = $(this).find('th').eq(1).html().trim();
    $('#tablaRecepcionesSinCerrar tbody tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');
    $('#num_incidencia').val(numIncidencia);

    // Seteo del numero formateado de la incidencia
    var incidenciaSeleccionada = $(this).find('td').eq(0).html().trim(); // Cambia el índice eq(0) dependiendo de la posición de la columna
    $('#incidenciaSeleccionada').val(incidenciaSeleccionada); // Asegúrate de que el input con ID 'descripcion' exista en tu HTML

    // Bloquear la tabla de cierres
    $('#tablaIncidenciasCerradas tbody tr').addClass('pointer-events-none opacity-50');
    document.getElementById('guardar-cierre').disabled = false;
    document.getElementById('nuevo-registro').disabled = false;

    // Reactivar el botón "Nuevo"
    $('#nuevo-registro').prop('disabled', false);
  });

  // Evento de click en las filas de la tabla de incidencias cerradas
  $(document).on('click', '#tablaIncidenciasCerradas tbody tr', function () {
    var numCierre = $(this).find('th').html();
    $('#tablaIncidenciasCerradas tbody tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');
    $('#num_cierre').val(numCierre);

    // Seteo del numero formateado de la incidencia
    var incidenciaSeleccionada = $(this).find('td').eq(0).html(); // Cambia el índice eq(0) dependiendo de la posición de la columna
    $('#incidenciaSeleccionada').val(incidenciaSeleccionada); // Asegúrate de que el input con ID 'descripcion' exista en tu HTML

    // Bloquear la tabla de cierres
    $('#tablaRecepcionesSinCerrar tbody tr').addClass('pointer-events-none opacity-50');
    // Reactivar el botón "Nuevo"
    $('#nuevo-registro').prop('disabled', false);
  });

  // Evento para nuevo registro
  $('#nuevo-registro').on('click', function () {
    nuevoRegistro();
    // Reactivar ambas tablas
    $('#tablaRecepcionesSinCerrar tbody tr').removeClass('pointer-events-none opacity-50');
    $('#tablaIncidenciasCerradas tbody tr').removeClass('pointer-events-none opacity-50');
    location.reload();
  });

  // Cargar las opciones de condicion
  $.ajax({
    url: 'ajax/getOperatividad.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_operatividad');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione condici&oacute;n</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.CON_codigo + '">' + value.CON_descripcion + '</option>');
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

  // Guardar el cierre
  $('#guardar-cierre').click(function (event) {
    event.preventDefault(); // Prevenir el comportamiento predeterminado del botón

    // Validar campos antes de enviar
    if (!validarCampos()) {
      return; // Si hay campos inválidos, detener el envío del formulario
    }

    var form = $('#formCierre');
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
          toastr.success('Incidencia cerrada');
        } else if (action.includes('editar')) {
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
      mensajeError = 'Debe seleccionar una incidencia pendiente para cierre. ';
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
        mensajeError += 'Debe seleccionar condici&oacute;n. ';
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
      toastr.warning(mensajeError.trim());
    }
    return valido;
  }

  // Función para limpiar los campos del formulario
  function nuevoRegistro() {
    document.getElementById('formCierre').reset();
    // document.reload();
  }
});


// Función para cambiar páginas de la tabla de recepciones sin cerrar
function changePageTablaSinCerrar(page) {
  fetch(`?page=${page}`)
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const newDocument = parser.parseFromString(data, 'text/html');
      const newTable = newDocument.querySelector('#tablaRecepcionesSinCerrar');
      const newPagination = newDocument.querySelector('#paginadorRecepcionesSinCerrar');

      // Reemplazar la tabla actual con la nueva tabla obtenida
      const currentTable = document.querySelector('#tablaRecepcionesSinCerrar');
      if (currentTable && newTable) {
        currentTable.parentNode.replaceChild(newTable, currentTable);
      }

      // Reemplazar la paginación actual con la nueva paginación obtenida
      const currentPagination = document.querySelector('#paginadorRecepcionesSinCerrar');
      if (currentPagination && newPagination) {
        currentPagination.parentNode.replaceChild(newPagination, currentPagination);
      }
    })
    .catch(error => {
      console.error('Error al cambiar de página:', error);
    });
}

// Función para cambiar páginas de la tabla de cierres
function changePageCierres(page) {
  fetch(`?pageCierres=${page}`)
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const newDocument = parser.parseFromString(data, 'text/html');
      const newTable = newDocument.querySelector('#tablaIncidenciasCerradas');
      const newPagination = newDocument.querySelector('#paginadorCierres');

      // Reemplazar la tabla actual con la nueva tabla obtenida
      const currentTable = document.querySelector('#tablaIncidenciasCerradas');
      if (currentTable && newTable) {
        currentTable.parentNode.replaceChild(newTable, currentTable);
      }

      // Reemplazar la paginación actual con la nueva paginación obtenida
      const currentPagination = document.querySelector('#paginadorCierres');
      if (currentPagination && newPagination) {
        currentPagination.parentNode.replaceChild(newPagination, currentPagination);
      }
    })
    .catch(error => {
      console.error('Error al cambiar de página:', error);
    });
}

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
