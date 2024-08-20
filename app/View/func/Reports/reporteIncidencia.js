$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

$('#imprimir-incidencia').click(function () {
  // Obtener el número de incidencia desde el campo de entrada
  const numeroIncidencia = $('#numero_incidencia').val().trim();

  if (!numeroIncidencia) {
    toastr.warning('Seleccione una incidencia para generar PDF.');
    return;
  }

  // Realizar una solicitud AJAX para obtener los datos de la incidencia
  $.ajax({
    url: 'ajax/getReporteIncidencia.php',
    method: 'GET',
    data: { numero: numeroIncidencia },
    dataType: 'json',
    success: function (data) {
      console.log("Datos recibidos:", data);
      const incidencia = data.find(inc => inc.INC_numero === numeroIncidencia);

      if (incidencia) {
        try {
          const { jsPDF } = window.jspdf;
          const doc = new jsPDF();

          const logoUrl = './public/assets/escudo.png';

          function addHeader(doc) {
            doc.setFontSize(9);
            doc.setFont('helvetica', 'normal');

            const fechaImpresion = new Date().toLocaleDateString();
            const headerText2 = 'Subgerencia de Informática y Sistemas';
            const reportTitle = 'REPORTE DE INCIDENCIA';

            const pageWidth = doc.internal.pageSize.width;
            const marginX = 10;
            const marginY = 10;
            const logoWidth = 25;
            const logoHeight = 25;

            doc.addImage(logoUrl, 'PNG', marginX, marginY, logoWidth, logoHeight);

            doc.setFont('helvetica', 'bold');
            doc.setFontSize(16);
            const titleWidth = doc.getTextWidth(reportTitle);
            const titleX = (pageWidth - titleWidth) / 2;
            const titleY = 25;

            doc.text(reportTitle, titleX, titleY);
            doc.setLineWidth(0.5);
            doc.line(titleX, titleY + 3, titleX + titleWidth, titleY + 3);

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

          addHeader(doc);

          const titleY = 45;
          doc.setFont('helvetica', 'bold');
          doc.setFontSize(12);
          doc.text('Detalle de la Incidencia:', 20, titleY);

          doc.autoTable({
            startY: 48,
            margin: { left: 20 },
            head: [['Campo', 'Descripción']],
            body: [
              [{ content: 'Número de incidencia:', styles: { fontStyle: 'bold' } }, incidencia.INC_numero_formato],
              [{ content: 'Fecha:', styles: { fontStyle: 'bold' } }, incidencia.fechaIncidenciaFormateada],
              [{ content: 'Categoría:', styles: { fontStyle: 'bold' } }, incidencia.CAT_nombre],
              [{ content: 'Asunto:', styles: { fontStyle: 'bold' } }, incidencia.INC_asunto],
              [{ content: 'Documento:', styles: { fontStyle: 'bold' } }, incidencia.INC_documento],
              [{ content: 'Código Patrimonial:', styles: { fontStyle: 'bold' } }, incidencia.INC_codigoPatrimonial],
              [{ content: 'Área solicitante:', styles: { fontStyle: 'bold' } }, incidencia.ARE_nombre],
              [{ content: 'Descripción:', styles: { fontStyle: 'bold' } }, incidencia.INC_descripcion],
              [{ content: 'Estado:', styles: { fontStyle: 'bold' } }, incidencia.ESTADO],
              [{ content: 'Usuario:', styles: { fontStyle: 'bold' } }, incidencia.Usuario]
            ],
            styles: {
              fontSize: 11,
              cellPadding: 2,
            },
            headStyles: {
              fillColor: [44, 62, 80],
              textColor: [255, 255, 255],
              fontStyle: 'bold',
            },
            columnStyles: {
              0: { cellWidth: 50 }, // Ancho para la columna Campo
              1: { cellWidth: 120 } // Ancho para la columna Descripcion
            }
          });

          const titleFirma = 200;
          const titleResponsable = titleFirma + 5;
          doc.setFont('times', 'normal');
          doc.setFontSize(11);

          const textFirmaSello = 'Firma y Sello';
          const textResponsable = 'Responsable del Área Usuaria';
          const textWidthFirmaSello = doc.getTextWidth(textFirmaSello);
          const textWidthResponsable = doc.getTextWidth(textResponsable);
          const maxTextWidth = Math.max(textWidthFirmaSello, textWidthResponsable);
          const lineExtraWidth = 20;
          const lineWidth = maxTextWidth + lineExtraWidth;
          const pageWidth = doc.internal.pageSize.width;
          const centerX = (pageWidth - lineWidth) / 2;

          doc.setLineWidth(0.5);
          doc.line(centerX, titleFirma - 5, centerX + lineWidth, titleFirma - 5);
          doc.text(textFirmaSello, centerX + (lineWidth - textWidthFirmaSello) / 2, titleFirma);
          doc.text(textResponsable, centerX + (lineWidth - textWidthResponsable) / 2, titleResponsable);

          function addFooter(doc, pageNumber, totalPages) {
            doc.setFontSize(8);
            doc.setFont('helvetica', 'italic');
            const footerY = 285;
            doc.setLineWidth(0.05);
            doc.line(20, footerY - 5, doc.internal.pageSize.width - 20, footerY - 5);

            const footerText = 'Sistema de Gestión de Incidencias';
            const pageInfo = `Página ${pageNumber} de ${totalPages}`;
            const pageWidth = doc.internal.pageSize.width;

            doc.text(footerText, 20, footerY);
            doc.text(pageInfo, pageWidth - 20 - doc.getTextWidth(pageInfo), footerY);
          }

          const totalPages = doc.internal.getNumberOfPages();
          for (let i = 1; i <= totalPages; i++) {
            doc.setPage(i);
            addFooter(doc, i, totalPages);
          }

          // Enviar el documento directamente a la impresora
          window.open(doc.output('bloburl')); // Para algunos navegadores es necesario abrir el PDF antes de imprimir

          toastr.success('Archivo PDF generado.');
        } catch (error) {
          toastr.error('Hubo un error al generar PDF.');
          console.error('Error al generar el PDF:', error.message);
        }
      } else {
        toastr.warning('No se ha seleccionado una incidencia.');
      }
    },
    error: function (xhr, status, error) {
      toastr.error('Hubo un error al obtener los datos de la incidencia.');
      console.error('Error al realizar la solicitud AJAX:', error);
    }
  });
});

$('#tablaListarIncidencias').on('click', 'tr', function () {
  $(this).toggleClass('selected').siblings().removeClass('selected');
});
