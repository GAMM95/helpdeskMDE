$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "1500"
  };
});
// Manejar la generación de PDF al hacer clic en el botón
$('#reportes-areas').click(function () {
  const codigoArea = $('#area').val();

  if (!codigoArea) {
    toastr.warning('Seleccione un área para generar el reporte.');
    return;
  }

  // Realizar una solicitud AJAX para obtener los datos de la incidencia
  $.ajax({
    url: 'ajax/getReportePorArea.php',
    method: 'GET',
    data: { area: codigoArea },
    success: function (data) {
      if (data.error) {
        toastr.error('Error en la solicitud: ' + data.error);
        return;
      }
      // Generar el PDF
      generarPDF(data);
    },
    error: function (xhr, status, error) {
      toastr.error('Hubo un error al obtener los datos del área.');
      console.error('Error al realizar la solicitud AJAX:', error);
    }
  });
});

function generarPDF(data) {
  if (!data || data.length === 0) {
    toastr.warning('No se encontró información para el &aacute;rea seleccionada.');
    return;
  }

  const { jsPDF } = window.jspdf;
  const doc = new jsPDF('landscape');

  // Añadir encabezado
  const logoUrl = './public/assets/escudo.png';
  addHeader(doc, logoUrl);

  const titleY = 25;
  doc.setFont('helvetica', 'bold');
  doc.setFontSize(12);

  const areaText = 'Área:';
  const codigoWidth = doc.getTextWidth(areaText);
  const codigoValue = ` ${$('#nombreArea').val()}`;
  const codigoValueWidth = doc.getTextWidth(codigoValue);

  const pageWidth = doc.internal.pageSize.width;
  const totalWidth = codigoWidth + codigoValueWidth;
  const startX = (pageWidth - totalWidth) / 2;

  // Dibujar el texto "Código Patrimonial" en negrita
  doc.text(areaText, startX, titleY);

  // Cambiar a estilo normal para el valor del código patrimonial
  doc.setFont('helvetica', 'normal');
  doc.text(codigoValue, startX + codigoWidth, titleY);


  // Añadir tabla de datos
  let item = 1;
  doc.autoTable({
    startY: 35,
    margin: { left: 10, right: 10 },
    head: [['Ítem', 'Incidencia', 'Fecha', 'Categoría', 'Asunto', 'Documento', 'Código Patrimonial', 'Prioridad', 'Estado']],
    body: data.map(reporte => [
      item++,
      reporte.INC_numero_formato,
      reporte.fechaIncidenciaFormateada,
      reporte.CAT_nombre,
      reporte.INC_asunto,
      reporte.INC_documento,
      reporte.INC_codigoPatrimonial,
      reporte.PRI_nombre,
      reporte.ESTADO
    ]),
    styles: {
      fontSize: 8,
      cellPadding: 2,
    },
    headStyles: {
      // fillColor: [44, 62, 80],
      fillColor: [0, 0, 0],
      textColor: [255, 255, 255],
      fontStyle: 'bold',
    },
    columnStyles: {
      0: { cellWidth: 10 }, // ancho para la columna item
      1: { cellWidth: 25 }, // ancho para la columna incidencia
      2: { cellWidth: 20 }, // ancho para la columna fecha
      3: { cellWidth: 55 }, // ancho para la columna categoria
      4: { cellWidth: 50 }, // ancho para la columna asunto
      5: { cellWidth: 45 }, // ancho para la columna documento
      6: { cellWidth: 35 }, // ancho para la columna codigo patrimonial
      7: { cellWidth: 20 }, // ancho para la columna prioridad
      8: { cellWidth: 20 } // ancho para la columna estado
    }
  });

  // Añadir pie de página
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
  const reportTitle = 'REPORTE DE INCIDENCIA POR ÁREA';

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