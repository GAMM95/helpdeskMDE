$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

// TODO: SETEO DE COMBO AREA
$(document).ready(function () {
  console.log("FETCHING")
  $.ajax({
    url: 'ajax/getAreaData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_area');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione un &aacute;rea</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.ARE_codigo + '">' + value.ARE_nombre + '</option>');
      });
      // if (areaRegistrada !== '') {
      //   select.val(areaRegistrada);
      // } else {
      //   select.val('');
      // }
    },
    error: function (error) {
      console.error(error);
    }
  });
});

// TODO: SETEO DEL COMBO CATEGORIA
$(document).ready(function () {
  console.log("FETCHING");
  $.ajax({
    url: 'ajax/getCategoryData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_categoria');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione una categoría</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.CAT_codigo + '">' + value.CAT_nombre + '</option>');
      });

      // // Verificar y establecer el valor seleccionado
      // if (categoriaRegistrada !== '') {
      //   select.val(categoriaRegistrada);
      // } else {
      //   select.val('');
      // }
    },
    error: function (error) {
      console.error('Error en la carga de categorías:', error);
    }
  });
});

// TODO: BUSCADOR PARA EL COMBO CATEGORIA Y AREA
$(document).ready(function () {
  $('#cbo_area').select2({
    placeholder: "Seleccione un area",
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs',
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });

  $('#cbo_categoria').select2({
    placeholder: "Seleccione una categoria",
    allowClear: true,
    width: '100%',
    dropdownCssClass: 'text-xs',
    language: {
      noResults: function () {
        return "No se encontraron resultados";
      }
    }
  });
});


// TODO: CAMBIAR PAGINAS DE LA TABLA DE INCIDENCIAS
function changePageTablaListarIncidencias(page) {
  fetch(`?page=${page}`)
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const newDocument = parser.parseFromString(data, 'text/html');
      const newTable = newDocument.querySelector('#tablaListarIncidencias');
      const newPagination = newDocument.querySelector('.flex.justify-end.items-center.mt-1');

      // Reemplazar la tabla actual con la nueva tabla obtenida
      document.querySelector('#tablaListarIncidencias').parentNode.replaceChild(newTable, document.querySelector('#tablaListarIncidencias'));

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

// TODO: GUARDAR INCIDENCIA
$(document).ready(function () {
  $('#guardar-incidencia').click(function (event) {
    event.preventDefault();

    // Validar campos antes de enviar
    if (!validarCampos()) {
      return; // si hay campos invalidos, detener el envio
    }

    var form = $('#formIncidencia');
    var data = form.serialize();
    console.log(data); // verifica las veces de envio

    var action = form.attr('action');
    $.ajax({
      url: action,
      type: "POST",
      data: data,
      success: function (response) {
        if (action === 'registro-incidencia-admin.php?action=registrar') {
          toastr.success('Incidencia registrada');
        } else if (action === 'registro-incidencia-admin.php?action=editar') {
          toastr.success('Incidencia actualizada');
        }
        setTimeout(function () {
          location.reload();
        }, 1500);
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
        toastr.error('Error al guardar la incidencia');
      }
    });
  });

  // FUNCION PARA VALIDAR LOS CAMPOS ANTES DE ENVIAR
  function validarCampos() {
    var valido = true;
    var mensajeError = '';

    // validar combos
    var faltaCategoria = ($('#cbo_categoria').val() === null || $('#cbo_categoria').val() === '');
    var faltaArea = ($('#cbo_area').val() === null || $('#cbo_area').val() === '');
    var faltaAsunto = ($('#asunto').val() === null || $('#asunto').val() === '');
    var faltaDocumento = ($('#documento').val() === null || $('#documento').val() === '');


    if (faltaCategoria && faltaArea && faltaAsunto && faltaDocumento) {
      mensajeError += 'Debe completar los campos requeridos.';
      valido = false;
    } else if (faltaCategoria) {
      mensajeError += 'Debe seleccionar una categoria.';
      valido = false;
    } else if (faltaArea) {
      mensajeError += 'Debe seleccionar un &aacute;rea.';
      valido = false;
    } else if (faltaAsunto) {
      mensajeError += 'Ingrese asunto de la incidencia.';
      valido = false;
    } else if (faltaDocumento) {
      mensajeError += 'Ingrese documento de la incidencia';
      valido = false;
    }

    // Mostrar mensaje de error si hay
    if (!valido) {
      toastr.warning(mensajeError.trim());
    }
    return valido;
  }
});

// TODO: Seteo de los valores de los inputs y combos
document.addEventListener('DOMContentLoaded', (event) => {
  // Obtener todas las filas de la tabla
  const filas = document.querySelectorAll('#tablaListarIncidencias tbody tr');

  filas.forEach(fila => {
    fila.addEventListener('click', () => {
      // Obtener los datos de la fila
      const celdas = fila.querySelectorAll('td');

      // Mapeo de los valores de las celdas a los inputs del formulario

      const codIncidencia = fila.querySelector('th').innerText.trim();
      const codigoPatrimonialValue = celdas[1].innerText.trim();
      const asuntoValue = celdas[2].innerText.trim();
      const documentoValue = celdas[3].innerText.trim();
      const categoriaValue = celdas[4].innerText.trim();
      const areaValue = celdas[5].innerText.trim();
      const descripcionValue = celdas[6].innerText.trim();


      // Seteo de valores en los inputs
      document.getElementById('numero_incidencia').value = codIncidencia;
      document.getElementById('codigo_patrimonial').value = codigoPatrimonialValue;
      document.getElementById('asunto').value = asuntoValue;
      document.getElementById('documento').value = documentoValue;
      document.getElementById('descripcion').value = descripcionValue;

      // Seteo de los valores en los combos
      setComboValue('cbo_categoria', categoriaValue);
      setComboValue('cbo_area', areaValue);

      // Cambiar estado de los botones
      document.getElementById('guardar-incidencia').disabled = true;
      document.getElementById('editar-incidencia').disabled = false;
      document.getElementById('nuevo-registro').disabled = false;
    });
  });
});

// seteo de los valores de los combos
function setComboValue(comboId, value) {
  const select = document.getElementById(comboId);
  const options = select.options;

  console.log("Seteo de los valores para: ", comboId, "Valor: ", value);

  // Verificar si el valor esta en el combo
  let valueFound = false;
  for (let i = 0; i < options.length; i++) {
    if (options[i].text.trim() === value) {
      select.value = options[i].value;
      valueFound = true;
      break;
    }
  }
  // Si no se encontró el valor, seleccionar el primer elemento
  if (!valueFound) {
    select.value = ''; // O establece un valor predeterminado si lo deseas
  }

  // Forzar actualización del select2 para mostrar el valor seleccionado
  $(select).trigger('change');
};

// TODO: Funcion para manejar el nuevo registro
function nuevoRegistro() {
  const form = document.getElementById('formIncidencia');
  form.reset();
  $('#numero_incidencia').val('');
  $('tr').removeClass('bg-blue-200 font-semibold');

  $('#form-action').val('registrar'); // Cambiar la acción a registrar

  // Deshabilitar el botón de editar
  $('#guardar-incidencia').prop('disabled', false);
  $('#editar-incidencia').prop('disabled', true);
  $('#nuevo-registro').prop('disabled', true);

  // Seteo de valores por defecto en los combos
  setComboValue('cbo_categoria', 'Valor por defecto de categoría');
  setComboValue('cbo_area', 'Valor por defecto de área');
}

// Evento para nuevo registro
$('#nuevo-registro').on('click', nuevoRegistro);

// TODO: Funcion para exportar pdf

document.addEventListener('DOMContentLoaded', function () {
  const { jsPDF } = window.jspdf;

  $('#imprimir-incidencia').click(function () {
    const filaSeleccionada = $('#tablaListarIncidencias .selected');
    if (filaSeleccionada.length === 0) {
      toastr.warning('Selecciona una fila para imprimir.');
      return;
    }

    const datos = filaSeleccionada.find('td').map(function () {
      return $(this).text();
    }).get();

    const doc = new jsPDF();
    const logoUrl = './public/assets/escudo_mde.png';

    // Espaciado para ajustar todo el contenido
    const marginX = 20; // Margen horizontal
    const marginY = 20; // Margen vertical

    // Función para agregar encabezado
    function addHeader(doc) {
      doc.setFontSize(9);
      doc.setFont('helvetica', 'normal');

      // Obtener la fecha actual
      const fechaImpresion = new Date().toLocaleDateString();

      // Definir el texto del encabezado
      const headerText1 = 'Municipalidad Distrital de La Esperanza';
      const headerText2 = 'Subgerencia de Informática y Sistemas';

      // Obtener el ancho de la página
      const pageWidth = doc.internal.pageSize.width;

      // Definir posiciones para el logo, el título y el texto adicional
      const logoX = marginX; // Posición horizontal del logo
      const logoY = marginY; // Posición vertical del logo
      const logoWidth = 20; // Ancho del logo
      const logoHeight = 20; // Alto del logo

      // Agregar el logo a la izquierda
      doc.addImage(logoUrl, 'PNG', logoX, logoY, logoWidth, logoHeight);

      // Agregar el texto al lado del logo
      doc.setFontSize(10);
      doc.setFont('helvetica', 'normal');
      const textLines = doc.splitTextToSize(headerText1, pageWidth - (logoWidth + 30 + marginX));
      doc.text(textLines, logoX + logoWidth + 10, logoY + 10); // Texto a la derecha del logo

      // Centrar el título
      doc.setFontSize(16);
      doc.setFont('helvetica', 'bold');
      const title = 'Detalles de la Incidencia';
      const titleWidth = doc.getTextWidth(title);
      const titleX = (pageWidth - titleWidth) / 2;
      const titleY = 40; // Ajusta la posición vertical del título

      // Agregar el título al centro
      doc.text(title, titleX, titleY);
      doc.setLineWidth(0.5);
      doc.line(titleX, titleY + 3, titleX + titleWidth, titleY + 3); // Subrayar el título

      // Información adicional a la derecha
      doc.setFontSize(8);
      doc.setFont('helvetica', 'normal');
      doc.text(headerText2, pageWidth - marginX - doc.getTextWidth(headerText2), 50); // Ajusta la posición vertical si es necesario
      doc.text(`Fecha de impresión: ${fechaImpresion}`, pageWidth - marginX - doc.getTextWidth(`Fecha de impresión: ${fechaImpresion}`), 60); // Ajusta la posición vertical si es necesario

      // Línea horizontal debajo del encabezado
      doc.setLineWidth(0.1);
      doc.line(marginX, 75, pageWidth - marginX, 75); // Línea horizontal debajo del encabezado
    }

    // Agregar encabezado
    addHeader(doc);

    // Información de la incidencia
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(11);
    doc.text(`Número de incidencia: ${datos['th']}`, marginX, 85);
    doc.text(`Fecha de entrada: ${datos[0]}`, marginX, 95);
    doc.text(`Código Patrimonial: ${datos[1]}`, marginX, 105);
    doc.text(`Asunto: ${datos[2]}`, marginX, 115);
    doc.text(`Documento: ${datos[3]}`, marginX, 125);
    doc.text(`Categoría: ${datos[4]}`, marginX, 135);
    doc.text(`Área: ${datos[5]}`, marginX, 145);
    doc.text(`Descripción: ${datos[6]}`, marginX, 155);
    doc.text(`Usuario: ${datos[7]}`, marginX, 165);

    // Función para agregar pie de página
    function addFooter(doc, pageNumber, totalPages) {
      doc.setFontSize(10);
      doc.setFont('helvetica', 'normal');

      // Agregar una línea horizontal encima del pie de página
      const footerY = 285; // Posición vertical del pie de página
      doc.setLineWidth(0.1);
      doc.line(marginX, footerY - 5, doc.internal.pageSize.width - marginX, footerY - 5); // Línea encima del pie de página

      // Agregar texto en el pie de página
      const footerText = 'Sistema HelpDesk de la MDE';
      const pageInfo = `Página ${pageNumber} de ${totalPages}`;

      // Obtener el ancho de la página
      const pageWidth = doc.internal.pageSize.width;

      // Posicionar el texto en el pie de página
      doc.text(footerText, marginX, footerY); // Texto alineado a la izquierda
      doc.text(pageInfo, pageWidth - marginX - doc.getTextWidth(pageInfo), footerY); // Texto alineado a la derecha
    }

    // Agregar pie de página
    const totalPages = doc.internal.getNumberOfPages();
    for (let i = 1; i <= totalPages; i++) {
      doc.setPage(i);
      addFooter(doc, i, totalPages);
    }

    doc.save('Detalle de incidencia.pdf');
  });
});

$(document).ready(function () {
  $('#tablaListarIncidencias').on('click', 'tr', function () {
    $(this).toggleClass('selected').siblings().removeClass('selected');
  });
});


