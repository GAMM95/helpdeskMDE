$(document).ready(function () {
  // Configurar la posición de Toastr
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "3000"
  };


  $('tr').click(function () {
    var cod = $(this).find('th[data-codcategoria]').data('codcategoria');
    var nom = $(this).find('th[data-categoria]').data('categoria');

    $('#txt_codigoCategoria').val(cod);
    $('#txt_nombreCategoria').val(nom);
    $(this).addClass('bg-blue-200 font-semibold');
    $('tr').not(this).removeClass('bg-blue-200 font-semibold');

    // Cambiar la acción del formulario a editar
    $('#form-action').val('editar');
  });

  function nuevoRegistro() {
    const form = document.getElementById('formcategoria');
    form.reset();
    $('#txt_codigoCategoria').val('');
    $('tr').removeClass('bg-blue-200 font-semibold');

    // Cambiar la acción del formulario a registrar
    $('#form-action').val('registrar');
  }

  $('#nuevo-registro').on('click', nuevoRegistro);

  function enviarFormulario(action) {
    var nombreCategoria = $('#txt_nombreCategoria').val();

    if (!nombreCategoria) {
      toastr.error('El campo "Nombre categoría" no puede estar vacío');
      return; // No enviar el formulario si el campo está vacío
    }

    // Habilitar el campo antes de enviar
    $('#txt_codigoCategoria').prop('disabled', false);

    var formData = $('#formcategoria').serialize();

    $.ajax({
      url: 'modulo-categoria.php?action=' + action,
      method: 'POST',
      data: formData,
      success: function (response) {
        if (action === 'registrar') {
          toastr.success('Categoría registrada');
        } else if (action === 'editar') {
          toastr.success('Categoría actualizada');
        }
        setTimeout(function () {
          location.reload();
        }, 1500);
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseText);
        toastr.error('Error al guardar la categoría');
      },
      complete: function () {
        // Volver a deshabilitar el campo después de enviar
        $('#txt_codigoCategoria').prop('disabled', true);
      }
    });
  }

  $('#guardar-categoria').on('click', function (e) {
    e.preventDefault();
    enviarFormulario($('#form-action').val());
  });

  $('#editar-categoria').on('click', function (e) {
    e.preventDefault();
    enviarFormulario('editar');
  });
});