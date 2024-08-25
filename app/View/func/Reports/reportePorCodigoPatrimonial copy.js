$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "1500"
  };
});

// Realizar una solicitud AJAX para obtener el tipo de bien
$.ajax({
  url: 'ajax/getTipoBienReporte.php',
  method: 'GET',
  data: { codigo_patrimonial: codigoPatrimonial },
  dataType: 'json',
  success: function (response) {
    if (response.tipo_bien === 'No encontrado' || response.tipo_bien === 'Error') {
      toastr.warning('No se encontr&oacute; informaci&oacute;n para el c&oacute;digo patrimonial ingresado.');
      return;
    }

    const tipoBien = response.tipo_bien;

    // Realizar la solicitud AJAX para obtener los datos de la incidencia
    $.ajax({
      url: 'ajax/getReportePorCodigoPatrimonial.php',
      method: 'GET',
      data: { codigoPatrimonial: codigoPatrimonial },
      dataType: 'json',
      success: function (data) {
        if (data.error) {
          toastr.error('Error en la solicitud: ' + data.error);
          return;
        }

        // Generar PDF pasando el tipo de bien
        generarPDFControlPatrimonial(data, tipoBien);

      },
      error: function (xhr, status, error) {
        toastr.error('Hubo un error al obtener los datos del c&oacute;digo patrimonial.');
        console.error('Error al realizar la solicitud AJAX:', error);
      }
    });

  },
  error: function (xhr, status, error) {
    toastr.error('Hubo un error al obtener el tipo de bien.');
    console.error('Error al realizar la solicitud AJAX:', error);
  }
});

// $('#reportes-codigoPatrimonial').click(function () {
//   const codigoPatrimonial = $('#codigoPatrimonial').val();

//   if (!codigoPatrimonial) {
//     toastr.warning('Ingrese c&oacute;digo patrimonial para generar reporte.');
//     return;
//   }

//   // Realizar una solicitud AJAX para obtener los datos de la incidencia
//   $.ajax({
//     url: 'ajax/getReportePorCodigoPatrimonial.php',
//     method: 'GET',
//     data: { codigoPatrimonial: codigoPatrimonial },
//     dataType: 'json',
//     success: function (data) {
//       if (data.error) {
//         toastr.error('Error en la solicitud: ' + data.error);
//         return;
//       }

//       // Generar PDF
//       generarPDFControlPatrimonial(data);

//     },
//     error: function (xhr, status, error) {
//       toastr.error('Hubo un error al obtener los datos del c&oacute;digo patrimonial.');
//       console.error('Error al realizar la solicitud AJAX:', error);
//     }
//   });
// });

function generarPDFControlPatrimonial(data) {
  if (!data || data.length === 0) {
    toastr.warning('No se encontr&oacute; informaci&oacute;n para el c&oacute;digo patrimonial ingresado.');
    return;
  }
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF('landscape');

  //  Añadir el encabezado
  const logoUrl = './public/assets/escudo.png';
  addHeader(doc, logoUrl);

  // Detalle del título respecto al código patrimonial
  const titleY = 23;
  doc.setFont('helvetica', 'bold');
  doc.setFontSize(12);

  const codigoText = 'Código Patrimonial:';
  const codigoWidth = doc.getTextWidth(codigoText);
  const codigoValue = ` ${$('#codigoPatrimonial').val()}`;
  const codigoValueWidth = doc.getTextWidth(codigoValue);

  const pageWidth = doc.internal.pageSize.width;
  const totalWidth = codigoWidth + codigoValueWidth;
  const startX = (pageWidth - totalWidth) / 2;

  // Dibujar el texto "Código Patrimonial" en negrita
  doc.text(codigoText, startX, titleY);

  // Cambiar a estilo normal para el valor del código patrimonial
  doc.setFont('helvetica', 'normal');
  doc.text(codigoValue, startX + codigoWidth, titleY);

  // Detalle del título respecto al tipo de bien
  const titleU = 28;
  doc.setFont('helvetica', 'bold');
  doc.setFontSize(12);

  const bienText = 'Tipo de bien:';
  const bienTextWidth = doc.getTextWidth(bienText);
  const bienValue = ` ${$('#codigoPatrimonial').val()}`;
  const bienValueWidth = doc.getTextWidth(bienValue);

  const totalBienWidth = bienTextWidth + bienValueWidth;
  const startBienX = (pageWidth - totalBienWidth) / 2;

  // Dibujar el texto "Tipo de bien" en negrita
  doc.text(bienText, startBienX, titleU);

  // Cambiar a estilo normal para el valor del tipo de bien
  doc.setFont('helvetica', 'normal');
  doc.text(bienValue, startBienX + bienTextWidth, titleU);

  // Añadir la tabla de datos
  let item = 1; // Inicializar el contador

  // Lista de incidencias por codigo patrimonial
  doc.autoTable({
    startY: 35, // Altura de la tabla respecto a la parte superior
    margin: { left: 10 },
    head: [['Ítem', 'Incidencia', 'Fecha', 'Categoría', 'Asunto', 'Documento', 'Área', 'Prioridad', 'Estado']],
    body: data.map(reporte => [
      item++,
      reporte.INC_numero_formato,
      reporte.fechaIncidenciaFormateada,
      reporte.CAT_nombre,
      reporte.INC_asunto,
      reporte.INC_documento,
      reporte.ARE_nombre,
      reporte.PRI_nombre,
      reporte.ESTADO
    ]),
    styles: {
      fontSize: 8,
      cellPadding: 2,
    },
    headStyles: {
      fillColor: [44, 62, 80],
      textColor: [255, 255, 255],
      fontStyle: 'bold',
    },
    columnStyles: {
      0: { cellWidth: 10 },
      1: { cellWidth: 25 }, // Ancho para la columna Incidencia
      2: { cellWidth: 20 }, // Ancho para la columna fecha
      3: { cellWidth: 45 }, // Ancho para la columna categoria
      4: { cellWidth: 50 }, // Ancho para la columna asunto
      5: { cellWidth: 40 }, // Ancho para la columna Documento
      6: { cellWidth: 50 }, // Ancho para la columna area
      7: { cellWidth: 20 }, // Ancho para la columna prioridad
      8: { cellWidth: 20 } // Ancho para la columna estado
    }
  });

  // Añadir el pie de pagina
  const totalPages = doc.internal.getNumberOfPages();
  for (let i = 1; i <= totalPages; i++) {
    doc.setPage(i);
    addFooter(doc, i, totalPages);
  }

  // Mostrar mensaje de exito de pdf generado
  toastr.success('Archivo PDF generado.');
  // Retrasar la apertura del PDF y limpiar el campo de entrada
  setTimeout(() => {
    window.open(doc.output('bloburl'));
    $('#codigoPatrimonial').val('');
  }, 1500);
}

// Encabezado
function addHeader(doc, logoUrl) {
  doc.setFontSize(9);
  doc.setFont('helvetica', 'normal');
  const fechaImpresion = new Date().toLocaleDateString();
  const headerText2 = 'Subgerencia de Informática y Sistemas';
  const reportTitle = 'REPORTE DE INCIDENCIA POR CÓDIGO PATRIMONIAL';

  const pageWidth = doc.internal.pageSize.width;
  const marginX = 10;
  const marginY = 5;
  const logoWidth = 25;
  const logoHeight = 25;

  doc.addImage(logoUrl, 'PNG', marginX, marginY, logoWidth, logoHeight);

  doc.setFont('helvetica', 'bold');
  doc.setFontSize(15);
  const titleWidth = doc.getTextWidth(reportTitle);
  const titleX = (pageWidth - titleWidth) / 2;
  const titleY = 15;
  doc.text(reportTitle, titleX, titleY);
  doc.setLineWidth(0.5);
  doc.line(titleX, titleY + 1, titleX + titleWidth, titleY + 1);

  doc.setFontSize(8);
  doc.setFont('helvetica', 'normal');
  const fechaText = `Fecha de impresión: ${fechaImpresion}`;
  const headerText2Width = doc.getTextWidth(headerText2);
  const fechaTextWidth = doc.getTextWidth(fechaText);
  const headerText2X = pageWidth - marginX - headerText2Width;
  const fechaTextX = pageWidth - marginX - fechaTextWidth;
  const headerText2Y = marginY + logoHeight / 2;
  const fechaTextY = headerText2Y + 5;

  doc.text(headerText2, headerText2X, headerText2Y);
  doc.text(fechaText, fechaTextX, fechaTextY);
}
// Fin de encabezado

// Pie de pagina
function addFooter(doc, pageNumber, totalPages) {
  doc.setFontSize(8);
  doc.setFont('helvetica', 'italic');
  const footerY = 200;
  doc.setLineWidth(0.05);
  doc.line(20, footerY - 5, doc.internal.pageSize.width - 20, footerY - 5);

  const footerText = 'Sistema de Gestión de Incidencias';
  const pageInfo = `Página ${pageNumber} de ${totalPages}`;
  const pageWidth = doc.internal.pageSize.width;

  doc.text(footerText, 20, footerY);
  doc.text(pageInfo, pageWidth - 20 - doc.getTextWidth(pageInfo), footerY);
}
// Fin de pie de pagina