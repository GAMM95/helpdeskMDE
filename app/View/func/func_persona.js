$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };

  // Evento para manejar la tecla Enter cuando una fila está seleccionada
  $(document).on('keydown', function (e) {
    // Verificar si la tecla presionada es Enter (keyCode 13)
    if (e.key === 'Enter') {
      // Si la fila está seleccionada, proceder a actualizar
      if ($('.bg-blue-200.font-semibold').length > 0) {
        e.preventDefault();
        enviarFormulario('editar');
      }
    }
  });
  
  // Evento de clic en una fila de la tabla usando delegación de eventos
  $(document).on('click', '#tablaTrabajadores tr', function () {
    var cod = $(this).data('cod');
    var dni = $(this).data('dni');
    var nombreCompleto = $(this).data('nombre');
    var celular = $(this).data('celular');
    var email = $(this).data('email');

    var partesNombre = nombreCompleto.split(' ');

    var apellidoMaterno = partesNombre.pop();
    var apellidoPaterno = partesNombre.pop();
    var nombre = partesNombre.join(' ');

    $('#CodPersona').val(cod);
    $('#dni').val(dni);
    $('#nombres').val(nombre);
    $('#apellidoPaterno').val(apellidoPaterno);
    $('#apellidoMaterno').val(apellidoMaterno);
    $('#celular').val(celular);
    $('#email').val(email);

    $('#tablaTrabajadores tr').removeClass('bg-blue-200 font-semibold');
    $(this).addClass('bg-blue-200 font-semibold');

    $('#form-action').val('editar'); // Cambiar la acción a editar

    // Habilitar el botón de editar
    $('#guardar-persona').prop('disabled', true);
    $('#editar-persona').prop('disabled', false);
    $('#nuevo-registro').prop('disabled', false);
  });

  // Manejo de la paginación
  $(document).on('click', '.pagination-link', function (e) {
    e.preventDefault();
    var page = $(this).data('page');
    cambiarPaginaTablaTrabajadores(page);
  });

  // Buscar en la tabla de trabajadores
  $('#searchInput').on('input', function () {
    filtrarTablaTrabajador();
  });

  // función para cambiar de página en la tabla de trabajadores
  function cambiarPaginaTablaTrabajadores(page) {
    fetch(`?page=${page}`)
      .then(response => response.text())
      .then(data => {
        const parser = new DOMParser();
        const newDocument = parser.parseFromString(data, 'text/html');
        const newTable = newDocument.querySelector('#tablaTrabajadores');
        const newPagination = newDocument.querySelector('.flex.justify-end.items-center.mt-1');

        // Reemplazar la tabla actual con la nueva tabla obtenida
        document.querySelector('#tablaTrabajadores').parentNode.replaceChild(newTable, document.querySelector('#tablaTrabajadores'));

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

  // función para filtrar la tabla de trabajadores
  function filtrarTablaTrabajador() {
    var input, filtro, tabla, filas, celdas, i, j, match;
    input = document.getElementById('searchInput');
    filtro = input.value.toUpperCase();
    tabla = document.getElementById('tablaTrabajadores');
    filas = tabla.getElementsByTagName('tr');

    for (i = 1; i < filas.length; i++) {
      celdas = filas[i].getElementsByTagName('td');
      match = false;
      for (j = 0; j < celdas.length; j++) {
        if (celdas[j].innerText.toUpperCase().indexOf(filtro) > -1) {
          match = true;
          break;
        }
      }
      filas[i].style.display = match ? '' : 'none';
    }
  }

  // Función para manejar el nuevo registro
  function nuevoRegistro() {
    const form = document.getElementById('formPersona');
    form.reset();
    $('#CodPersona').val('');
    $('tr').removeClass('bg-blue-200 font-semibold');

    $('#form-action').val('registrar'); // Cambiar la acción a registrar

    // Deshabilitar el botón de editar
    $('#guardar-persona').prop('disabled', false);
    $('#editar-persona').prop('disabled', true);
    $('#nuevo-registro').prop('disabled', true);

    // Limpiar el campo de búsqueda y actualizar la tabla
    document.getElementById('searchInput').value = '';
    filtrarTablaTrabajador(); // Actualizar la tabla para reflejar que no hay filtros aplicados
  }

  // Evento para nuevo registro
  $('#nuevo-registro').on('click', nuevoRegistro);

  function enviarFormulario(action) {
    if (!validarCampos()) {
      return; // si hay campos inválidos, detener el envío
    }

    var url = 'modulo-persona.php?action=' + action;
    var data = $('#formPersona').serialize();

    $.ajax({
      url: url,
      method: 'POST',
      data: data,
      success: function (response, error, status) {
        // console.log('Error response:', xhr.responseText);
        console.log('Status:', status);
        console.log('Error:', error);
        console.log(response.success); // Verifica la respuesta aquí
        if (response.success) {
          if (action === 'registrar') {
            toastr.success('Persona registrada');
          } else if (action === 'editar') {
            toastr.success('Datos actualizados');
          }
          setTimeout(function () {
            location.reload();
          }, 1500);
        } else {
          toastr.warning(response.message);
        }
      },
      error: function (response, status, error) {
        // console.log('Error response:', xhr.responseText);
        console.log('Status:', status);
        console.log('Error:', error);
        console.log(response.error); // Verifica la respuesta aquí

        if (action === 'registrar') {
          toastr.success('Persona registrada');
        } else if (action === 'editar') {
          toastr.success('Datos actualizados');
        }
        setTimeout(function () {
          location.reload();
        }, 1500);
      }
    });
  }

  $('#guardar-persona').on('click', function (e) {
    e.preventDefault();
    enviarFormulario($('#form-action').val());
  });

  $('#editar-persona').on('click', function (e) {
    e.preventDefault();
    enviarFormulario('editar');
  });

  // Función para validar campos antes de enviar
  function validarCampos() {
    var valido = true;
    var mensajeError = '';

    // Validar campos
    var faltaDni = ($('#dni').val() === null || $('#dni').val() === '');
    var faltaNombres = ($('#nombres').val() === null || $('#nombres').val() === '');
    var faltaApellidoPaterno = ($('#apellidoPaterno').val() === null || $('#apellidoPaterno').val() === '');
    var faltaApellidoMaterno = ($('#apellidoMaterno').val() === null || $('#apellidoMaterno').val() === '');

    if (faltaDni || faltaNombres || faltaApellidoPaterno || faltaApellidoMaterno) {
      mensajeError += 'Debe completar todos los campos requeridos.';
      valido = false;
    } else if (faltaDni) {
      mensajeError += 'Debe ingresar DNI del trabajador.';
      valido = false;
    } else if (faltaNombres) {
      mensajeError += 'Debe ingresar nombres del trabajador.';
      valido = false;
    } else if (faltaApellidoPaterno) {
      mensajeError += 'Debe ingresar apellido paterno del trabajador.';
      valido = false;
    } else if (faltaApellidoMaterno) {
      mensajeError += 'Debe ingresar apellido materno del trabajador.';
      valido = false;
    }

    // Mostrar mensaje de error si hay
    if (!valido) {
      toastr.warning(mensajeError.trim());
    }
    return valido;
  }
});