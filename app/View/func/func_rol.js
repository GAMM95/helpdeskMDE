$(document).ready(function () {
  $('tr').click(function () {
    var cod = $(this).find('th[data-codrol]').data('codrol');
    var nom = $(this).find('th[data-rol]').data('rol');

    $('#txt_codigoRol').val(cod);
    $('#txt_nombreRol').val(nom);
    $(this).addClass('bg-blue-200 font-semibold');
    $('tr').not(this).removeClass('bg-blue-200 font-semibold');

    // Cambiar la acción del formulario a editar
    $('#form-action').val('editar');
  });

  function nuevoRegistro() {
    const form = document.getElementById('formrol');
    form.reset();
    $('#txt_codigoRol').val('');
    $('tr').removeClass('bg-blue-200 font-semibold');

    // Cambiar la acción del formulario a registrar
    $('#form-action').val('registrar');
  }

  $('#nuevo-registro').on('click', nuevoRegistro);

  function enviarFormulario(action) {
    var nombreRol = $('#txt_codigoRol').val();

    if(!nombreRol){
      toastr.error('El campo "Nombre rol" no puede estar vacío');
      return;
    }
    // Habilitar el campo antes de enviar
    $('#txt_codigoRol').prop('disabled', false);

    var formData = $('#formrol').serialize();

    $.ajax({
      url: 'modulo-rol.php?action=' + action,
      method: 'POST',
      data: formData,
      success: function (response) {
        if (action === 'registrar') {
          toastr.success('Rol registrado exitosamente');
        } else if (action === 'editar') {
          toastr.success('Rol actualizado exitosamente');
        }
        setTimeout(function () {
          location.reload();
        }, 1500);
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseText);
        toastr.error('Error al guardar rol');
      },
      complete: function () {
        // Volver a deshabilitar el campo después de enviar
        $('#txt_codigoRol').prop('disabled', true);
      }
    });
  }

  $('#guardar-rol').on('click', function (e) {
    e.preventDefault();
    enviarFormulario($('#form-action').val());
  });

  $('#editar-rol').on('click', function (e) {
    e.preventDefault();
    enviarFormulario('editar');
  });
});