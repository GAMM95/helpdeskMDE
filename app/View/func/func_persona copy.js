// $(document).ready(function () {
//   toastr.options = {
//     "positionClass": "toast-bottom-right",
//     "progressBar": true,
//     "timeOut": "2000"
//   };

//   // Mensaje de prueba para verificar si Toastr está funcionando
//   // toastr.info('Toastr está funcionando correctamente');

//   // Evento de clic en una fila de la tabla
//   $('tr').click(function () {
//     var cod = $(this).find('th').data('cod');
//     var dni = $(this).find('td[data-dni]').text().trim();
//     var nombreCompleto = $(this).find('td[data-nombre]').text().trim();
//     var celular = $(this).find('td[data-celular]').text().trim();
//     var email = $(this).find('td[data-email]').text().trim();

//     var partesNombre = nombreCompleto.split(' ');

//     var apellidoMaterno = partesNombre.pop();
//     var apellidoPaterno = partesNombre.pop();
//     var nombre = partesNombre.join(' ');

//     $('#CodPersona').val(cod);
//     $('#dni').val(dni);
//     $('#nombres').val(nombre);
//     $('#apellidoPaterno').val(apellidoPaterno);
//     $('#apellidoMaterno').val(apellidoMaterno);
//     $('#celular').val(celular);
//     $('#email').val(email);

//     $('tr').removeClass('bg-blue-200 font-semibold');
//     $(this).addClass('bg-blue-200 font-semibold');

//     $('#form-action').val('editar');
//   });

//   // Guardar persona
//   $('#guardar-persona').click(function (event) {
//     event.preventDefault();

//     // Validar campos antes de enviar
//     if (!validarCampos()) {
//       return; // si hay campos inválidos, detener el envío
//     }

//     var form = $('#formPersona');
//     var data = form.serialize();
//     var action = form.attr('action');

//     $.ajax({
//       url: action,
//       type: "POST",
//       data: data,
//       success: function (response) {
//         if (action === 'modulo-persona.php?action=registrar') {
//           toastr.success('Persona registrada');
//         } else if (action === 'modulo-persona.php?action=editar') {
//           toastr.success('Datos actualizados');
//         }
//         setTimeout(function () {
//           location.reload();
//         }, 1500);
//       },
//       error: function (xhr, status, error) {
//         toastr.error('Error al registrar persona');
//       }
//     });
//   });

//   // Función para validar campos antes de enviar
//   function validarCampos() {
//     var valido = true;
//     var mensajeError = '';

//     // Validar campos
//     var faltaDni = ($('#dni').val() === null || $('#dni').val() === '');
//     var faltaNombres = ($('#nombres').val() === null || $('#nombres').val() === '');
//     var faltaApellidoPaterno = ($('#apellidoPaterno').val() === null || $('#apellidoPaterno').val() === '');
//     var faltaApellidoMaterno = ($('#apellidoMaterno').val() === null || $('#apellidoMaterno').val() === '');

//     if (faltaDni && faltaNombres && faltaApellidoPaterno && faltaApellidoMaterno) {
//       mensajeError += 'Debe completar los campos requeridos.';
//       valido = false;
//     } else if (faltaDni) {
//       mensajeError += 'Debe ingresar DNI.';
//       valido = false;
//     } else if (faltaNombres) {
//       mensajeError += 'Ingrese nombres del trabajador.';
//       valido = false;
//     } else if (faltaApellidoPaterno) {
//       mensajeError += 'Ingrese apellido paterno del trabajador.';
//       valido = false;
//     } else if (faltaApellidoMaterno) {
//       mensajeError += 'Ingrese apellido materno del trabajador.';
//       valido = false;
//     }

//     // Mostrar mensaje de error si hay
//     if (!valido) {
//       toastr.warning(mensajeError.trim());
//     }
//     return valido;
//   }
// });

// function valid_email() {
//   var email_sel = $("#email");
//   var email_value = email_sel.val();
//   var email_reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

//   if (email_reg.test(email_value)) {
//     return true;
//   } else {
//     return false;
//   }
// }

// function valid_celular() {
//   var celular_sel = $("#celular");
//   var celular_value = celular_sel.val();
//   var longitud_value = celular_value.length;
//   //validamos si solo contiene números
//   var isnum = /^\d+$/.test(celular_value);
//   if (!isnum) {
//     return false;
//   }
//   //validamos la longitud
//   if (longitud_value < 9 || longitud_value > 9) {
//     return false;
//   }
//   return true;
// }



