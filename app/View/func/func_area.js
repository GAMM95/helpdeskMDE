$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

$(document).ready(function () {
  $('tr').click(function () {
    var cod = $(this).find('th[data-codarea]').data('codarea');
    var nom = $(this).find('th[data-area]').data('area');

    $('#txt_codigoArea').val(cod);
    $('#txt_nombreArea').val(nom);
    $(this).addClass('bg-blue-200 font-semibold');
    $('tr').not(this).removeClass('bg-blue-200 font-semibold');

    // Cambiar la acción del formulario a editar
    $('#form-action').val('editar');

    // Habilitar el botón de editar
    $('#guardar-area').prop('disabled', false);
    $('#editar-area').prop('disabled', false);
    $('#nuevo-registro').prop('disabled', false);
  });

  function nuevoRegistro() {
    const form = document.getElementById('formarea');
    form.reset();
    $('#txt_codigoArea').val('');
    $('tr').removeClass('bg-blue-200 font-semibold');

    // Cambiar la acción del formulario a registrar
    $('#form-action').val('registrar');

    // Deshabilitar el botón de editar
    $('#editar-area').prop('disabled', true);
    $('#nuevo-registro').prop('disabled', true);
  }

  $('#nuevo-registro').on('click', nuevoRegistro);

  function enviarFormulario(action) {
    var nombreArea = $('#txt_nombreArea').val();

    if (!nombreArea) {
      toastr.warning('Debe ingresar el nombre de la nueva &aacute;rea.');
      return;
    }

    // Habilitar el campo antes de enviar
    $('#txt_codigoArea').prop('disabled', false);

    var formData = $("#formarea").serialize();

    $.ajax({
      url: "modulo-area.php?action=" + action,
      type: "POST",
      data: formData,
      success: function (response) {
        if (action === 'registrar') {
          toastr.success("Área guardada exitosamente");
        } else if (action === 'editar') {
          toastr.success("Área actualizada exitosamente");
        }
        setTimeout(function () {
          location.reload();
        }, 1500);
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseText);
        toastr.error("Error al guardar el área");
      },
      complete: function () {
        // Volver a deshabilitar el campo después de enviar
        $('#txt_codigoArea').prop('disabled', true);
      }
    });
  }

  $('#guardar-area').on('click', function (e) {
    e.preventDefault();
    enviarFormulario($('#form-action').val());
  });

  $('#editar-area').on('click', function (e) {
    e.preventDefault();
    enviarFormulario('editar');
  });
});

// Manejo de la paginacion
$(document).on('click', '.pagination-link', function (e) {
  e.preventDefault();
  var page = $(this).data('page');
  cambiarPaginaTablaAreas(page);
});

// Funcion para cambiar de pagina en la tabla de areas
function cambiarPaginaTablaAreas(page) {
  fetch(`?page=${page}`)
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const newDocument = parser.parseFromString(data, 'text/html');
      const newTable = newDocument.querySelector('#tablaAreas');
      const newPagination = newDocument.querySelector('.flex.justify-end.items-center.mt-1');

      // Reemplazar la tabla actual con la nueva tabla obtenida
      document.querySelector('#tablaAreas').parentNode.replaceChild(newTable, document.querySelector('#tablaAreas'));

      // Reemplazar la paginación actual con la nueva paginación obtenida
      const currentPagination = document.querySelector('.flex.justify-end.items-center.mt-1');
      if (currentPagination && newPagination) {
        currentPagination.parentNode.replaceChild(newPagination, currentPagination);
      }

      // Aplicar el filtro nuevamente a la nueva tabla
      filtrarTablaTrabajador();
    })
    .catch(error => {
      console.error('Error al cambiar de página:', error);
    });
}