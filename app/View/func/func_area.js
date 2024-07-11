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
  });

  function nuevoRegistro() {
    const form = document.getElementById('formarea');
    form.reset();
    $('#txt_codigoArea').val('');
    $('tr').removeClass('bg-blue-200 font-semibold');

    // Cambiar la acción del formulario a registrar
    $('#form-action').val('registrar');
  }

  $('#nuevo-registro').on('click', nuevoRegistro);

  function enviarFormulario(action) {
    var nombreArea = $('#txt_nombreArea').val();

    if (!nombreArea) {
      toastr.error('El campo "Nombre área" no puede estar vacío');
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