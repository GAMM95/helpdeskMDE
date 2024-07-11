// TODO: SETEO DE COMBO PERSONAS
$(document).ready(function () {
  console.log("FETCHING PERSONAS");
  $.ajax({
    url: 'ajax/getPersona.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_persona');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione una persona</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.PER_codigo + '">' + value.persona + '</option>');
      });
    },
    error: function (error) {
      console.error("Error fetching personas:", error);

    }
  });
});

// TODO: SETEO DE COMBO AREA
$(document).ready(function () {
  console.log("FETCHING PERSONAS");
  console.log("FETCHING AREAS");
  $.ajax({
    url: 'ajax/getAreaData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_area');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un área</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.ARE_codigo + '">' + value.ARE_nombre + '</option>');
      });
      document.getElementById('area').value = '<?php echo $incidenciaRegistrada ? $incidenciaRegistrada["ARE_codigo"] : "; ?>';
    },
    error: function (error) {
      console.error("Error fetching areas:", error);
    }
  });
});

// TODO: SETEO DE COMBO ROL
$(document).ready(function () {
  console.log("FETCHING PERSONAS");
  console.log("FETCHING AREAS");
  $.ajax({
    url: 'ajax/getRol.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_rol');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione rol</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.ROL_codigo + '">' + value.ROL_nombre + '</option>');
      });
      document.getElementById('rol').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado["ROL_codigo"] : "; ?>';
    },
    error: function (error) {
      console.error("Error fetching roles:", error);
    }
  });
});

// TODO: CAMBIAR LAS PAGINAS DE LA TABLA USUARIOS
function changePageTablaUsuarios(page) {
  fetch(`?page=${page}`)
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const newDocument = parser.parseFromString(data, 'text/html');
      const newTable = newDocument.querySelector('#tablaListarUsuarios');
      const newPagination = newDocument.querySelector('.flex.justify-end.items-center.mt-1');

      // Reemplazar la tabla actual con la nueva tabla obtenida
      document.querySelector('#tablaListarUsuarios').parentNode.replaceChild(newTable, document.querySelector('#tablaListarUsuarios'));

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

// TODO: SETEO DEL CODIGO DE USUARIO DESDE LA TABLA
$(document).ready(function () {
  $(document).on('click', '#tablaListarUsuarios tbody tr', function () {
    var id = $(this).find('th').html();
    $('#tablaListarUsuarios tbody tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');
    $('#txt_codigoUsuario').val(id);
  });
});


// TODO: SETEO DE VALORES EN LOS INPUTS
$(document).ready(function () {
  // Evento de clic en una fila de la tabla
  $('#tablaListarUsuarios tbody').on('click', 'tr', function () {
    var usuario = $(this).data('usuario');
    var cod = $(this).find('td[data-codusuario]').data('codusuario');
    var codPersona = $(this).find('td[data-codpersona]').data('persona');
    var codArea = $(this).find('td[data-area]').data('codarea');
    var codRol = $(this).find('td[data-codrol]').data('codrol');
    var usuario = $(this).find('td[data-usuario]').text();
    var password = $(this).find('td[data-password]').text();

    $('#txt_codigoUsuario').val(cod);
    $('#cbo_persona').val(codPersona);
    $('#cbo_area').val(codArea);
    $('#cbo_rol').val(codRol);
    $('#txt_nombreUsuario').val(usuario);
    $('#txt_password').val(password);

    $('tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');
  });
});