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

    const marginX = 20;
    const marginY = 5;

    function addHeader(doc) {
      doc.setFontSize(9);
      doc.setFont('helvetica', 'normal');

      const fechaImpresion = new Date().toLocaleDateString();
      const headerText1 = 'Municipalidad Distrital de La Esperanza';
      const headerText2 = 'Subgerencia de Informática y Sistemas';
      const reportTitle = 'REPORTE DE INCIDENCIA';

      const pageWidth = doc.internal.pageSize.width;
      const marginX = 10; // Margen desde el borde izquierdo
      const marginY = 10; // Margen desde el borde superior
      const logoWidth = 20;
      const logoHeight = 20;

      // Agregar el logo
      const logoX = marginX;
      const logoY = marginY;
      doc.addImage(logoUrl, 'PNG', logoX, logoY, logoWidth, logoHeight);

      // Posiciones para los textos
      const headerText1X = logoX + logoWidth + 10; // 10px de espacio después del logo
      const headerText1Y = logoY + logoHeight / 2; // Centrar verticalmente con el logo

      // Ajuste para el texto del centro (titulo del reporte)
      doc.setFontSize(16);
      doc.setFont('helvetica', 'bold');
      const titleWidth = doc.getTextWidth(reportTitle);
      const titleX = (pageWidth - titleWidth) / 2;
      const titleY = logoY + logoHeight + 15; // Ajustar posición vertical del título

      // Ajuste para el texto de la derecha (subgerencia y fecha)
      doc.setFontSize(8);
      doc.setFont('helvetica', 'normal');
      const fechaText = `Fecha de impresión: ${fechaImpresion}`;
      const headerText2Width = doc.getTextWidth(headerText2);
      const fechaTextWidth = doc.getTextWidth(fechaText);
      const headerText2X = pageWidth - marginX - headerText2Width;
      const headerText2Y = logoY + logoHeight / 2; // Centrar verticalmente con el logo
      const fechaTextX = pageWidth - marginX - fechaTextWidth;
      const fechaTextY = headerText2Y + 10; // Espacio debajo del texto de la subgerencia

      // Agregar texto del encabezado a la izquierda del logo
      doc.setFontSize(10);
      doc.setFont('helvetica', 'normal');
      doc.text(headerText1, headerText1X, headerText1Y);

      // Agregar el título centrado
      doc.text(reportTitle, titleX, titleY);
      doc.setLineWidth(0.5);
      doc.line(titleX, titleY + 3, titleX + titleWidth, titleY + 3);

      // Agregar texto de la subgerencia y fecha de impresión
      doc.text(headerText2, headerText2X, headerText2Y);
      doc.text(fechaText, fechaTextX, fechaTextY);

      // Línea horizontal debajo del encabezado
      doc.setLineWidth(0.1);
      doc.line(marginX, titleY + 20, pageWidth - marginX, titleY + 20);
    }

    // Llamar a la función para agregar el encabezado
    addHeader(doc);


    // Información de la incidencia
    const marginXDetalle = 30; // Margen horizontal para la información
    const lineHeight = 7; // Altura de línea
    const bulletSize = 0.5; // Tamaño de la viñeta (círculo)
    const textWidth = doc.internal.pageSize.width - 2 * marginXDetalle; // Ancho máximo del texto

    // Ajusta la posición vertical del subtítulo y la información
    const titleY = 60; // Posición vertical del subtítulo
    const infoStartY = 70; // Posición vertical inicial de la información

    doc.setFont('helvetica', 'normal');
    doc.setFontSize(12); // Tamaño del subtítulo
    doc.text('Detalle de la Incidencia:', marginX, titleY); // Subtítulo

    doc.setFontSize(11); // Tamaño de texto normal

    // Función para dibujar una viñeta (círculo)
    function drawBullet(x, y) {
      doc.setFillColor(0, 0, 0); // Color negro para la viñeta
      doc.circle(x, y - 1.5, bulletSize, 'F'); // Dibuja un círculo lleno
    }

    // Función para agregar texto con viñeta y ajuste de línea
    function addTextWithBullet(text, yPosition) {
      const lines = doc.splitTextToSize(text, textWidth); // Divide el texto en líneas ajustadas al ancho
      lines.forEach((line, index) => {
        drawBullet(marginXDetalle - bulletSize - 2, yPosition + (index * lineHeight));
        doc.text(line, marginXDetalle, yPosition + (index * lineHeight));
      });
    }

    // Añadir viñetas y texto
    let currentY = infoStartY;

    addTextWithBullet(`Número de incidencia: ${datos['th']}`, currentY);
    currentY += lineHeight * (doc.splitTextToSize(`Número de incidencia: ${datos['th']}`, textWidth).length);
    addTextWithBullet(`Fecha de entrada: ${datos[0]}`, currentY);
    currentY += lineHeight * (doc.splitTextToSize(`Fecha de entrada: ${datos[0]}`, textWidth).length);
    addTextWithBullet(`Código Patrimonial: ${datos[1]}`, currentY);
    currentY += lineHeight * (doc.splitTextToSize(`Código Patrimonial: ${datos[1]}`, textWidth).length);
    addTextWithBullet(`Asunto: ${datos[2]}`, currentY);
    currentY += lineHeight * (doc.splitTextToSize(`Asunto: ${datos[2]}`, textWidth).length);
    addTextWithBullet(`Documento: ${datos[3]}`, currentY);
    currentY += lineHeight * (doc.splitTextToSize(`Documento: ${datos[3]}`, textWidth).length);
    addTextWithBullet(`Categoría: ${datos[4]}`, currentY);
    currentY += lineHeight * (doc.splitTextToSize(`Categoría: ${datos[4]}`, textWidth).length);
    addTextWithBullet(`Área: ${datos[5]}`, currentY);
    currentY += lineHeight * (doc.splitTextToSize(`Área: ${datos[5]}`, textWidth).length);
    addTextWithBullet(`Descripción: ${datos[6]}`, currentY);
    currentY += lineHeight * (doc.splitTextToSize(`Descripción: ${datos[6]}`, textWidth).length);
    addTextWithBullet(`Usuario: ${datos[7]}`, currentY);
    // Fin de la informacion de la incidencia

    // Footer del PDF
    function addFooter(doc, pageNumber, totalPages) {
      doc.setFontSize(8);
      doc.setFont('helvetica', 'italic');
      const footerY = 285;
      doc.setLineWidth(0.05);
      doc.line(marginX, footerY - 5, doc.internal.pageSize.width - marginX, footerY - 5);

      const footerText = 'Sistema HelpDesk de la MDE';
      const pageInfo = `Página ${pageNumber} de ${totalPages}`;
      const pageWidth = doc.internal.pageSize.width;

      doc.text(footerText, marginX, footerY);
      doc.text(pageInfo, pageWidth - marginX - doc.getTextWidth(pageInfo), footerY);
    }

    const totalPages = doc.internal.getNumberOfPages();
    for (let i = 1; i <= totalPages; i++) {
      doc.setPage(i);
      addFooter(doc, i, totalPages);
    }
    // Fin del foote

    doc.output('dataurlnewwindow');
  });
});


$(document).ready(function () {
  $('#tablaListarIncidencias').on('click', 'tr', function () {
    $(this).toggleClass('selected').siblings().removeClass('selected');
  });
});


