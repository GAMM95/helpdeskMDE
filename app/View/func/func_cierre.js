


$(document).ready(function () {
  //Evento de clic en las filas de la tabla de recepciones sin cerrar
  $(document).on('click', '#tablaRecepcionesSinCerrar tbody tr', function () {
    var id = $(this).find('th').html();
    $('#tablaRecepcionesSinCerrar tbody tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');
    $('#num_recepcion').val(id);
  });
})
// Add a listener to every row of the table
// $(document).ready(function () {
//   $('tr').click(function () {
//     var id = $(this).find('th').html();
//     $('tr').removeClass('bg-blue-200 font-semibold');
//     $(this).addClass('bg-blue-200 font-semibold');
//     $('#REC_numero').val(id);
//     $('#REC_codigo_visible').val(id);
//   });
// });

$(document).ready(function () {
  $('#submitButton').click(function () {
    var form = $('form');
    var data = form.serialize();
    console.log(data);
  });
});

$(document).ready(function () {
  console.log("FETCHING");
  $.ajax({
    url: '../../../ajax/getLastRecepcion.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var input = $('#num_recepcion');
      input.empty();
      input.val(data.REC_codigo);
    },
    error: function (error) {
      console.error(error);
    }
  });
});

function limpiarCampos() {
  // Obtener el formulario por su ID
  const form = document.getElementById('formCierre');
  // Limpiar los campos del formulario
  form.reset();
}
const btnLimpiar = document.getElementById('limpiarCampos');
btnLimpiar.addEventListener('click', limpiarCampos);

function nuevoRegistro() {
  const form = document.getElementById('formCierre');
  // Restablecer el formulario
  form.reset();
}
// Asignar el evento 'click' al botón 'Nuevo Registro'
const btnNuevo = document.getElementById('nuevoRegistro');
btnNuevo.addEventListener('click', nuevoRegistro);

// GUARDAR DATOS
$(document).ready(function () {
  $("#guardar-cierre").on("click", function () {
    // Obtener los datos del formulario
    var formData = $("form").serialize(); // Obtener los datos del formulario

    $.ajax({
      url: 'registro-cierre-admin.php' + action, // Reemplaza "tu_archivo_de_backend.php" con tu ruta de backend
      type: "POST",
      data: formData,
      success: function (response) {
        if (action === 'registrar') {
          toastr.success('Cierre registrado');
        } else if (action === 'editar') {
          toastr.success('Cierre actualizado');
        }
        setTimeout(function () {
          location.reload();
        }, 1500);
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseText);
        toastr.error('Error al guardar cierre');
      },
    });
  });
});

// Mostrar mensajes de error desde la sesión
$(document).ready(function () {
  if (errorMessage) {
    toastr.error(errorMessage);
  }
});
