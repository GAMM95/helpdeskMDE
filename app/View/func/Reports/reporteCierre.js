$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

$('#imprimir-cierre').click(function () {
  // Obtener el número de incidencia desde el campo de entrada
  const numeroCierre = $('#num_cierre').val().trim();

  if (!numeroCierre) {
    toastr.warning('Seleccione una incidencia cerrada para generar PDF.');
    return;
  }

  // Realizar una solicitud AJAX para obtener los datos de la incidencia
  $.ajax({
    url: 'ajax/getReporteCierre.php',
    method: 'GET',
    data: { numero: numeroCierre },
    dataType: 'json',
    success: function (data) {
      console.log("Datos recibidos:", data);
      const cierre = data.find(cie => cie.CIE_numero === numeroCierre);

      if (cierre) {
        try {
          const { jsPDF } = window.jspdf;
          const doc = new jsPDF();

          const logoUrl = './public/assets/escudo.png';

          function addHeader(doc) {
            doc.setFontSize(9);
            doc.setFont('helvetica', 'normal');

            const fechaImpresion = new Date().toLocaleDateString();
            const headerText2 = 'Subgerencia de Informática y Sistemas';
            const reportTitle = 'REPORTE DE CIERRE';

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

          // Detalle del cierre
          const titleY = 45;
          doc.setFont('helvetica', 'bold');
          doc.setFontSize(12);
          doc.text('Detalle del cierre:', 20, titleY);

          doc.autoTable({
            startY: 48,
            margin: { left: 20 },
            head: [['Campo', 'Descripción']],
            body: [
              [{ content: 'Número de incidencia:', styles: { fontStyle: 'bold' } }, cierre.INC_numero_formato],
              [{ content: 'Fecha de cierre:', styles: { fontStyle: 'bold' } }, cierre.fechaCierreFormateada],
              [{ content: 'Estado:', styles: { fontStyle: 'bold' } }, cierre.ESTADO],
              [{ content: 'Prioridad:', styles: { fontStyle: 'bold' } }, cierre.PRI_nombre],
              [{ content: 'Asunto:', styles: { fontStyle: 'bold' } }, cierre.CIE_asunto],
              [{ content: 'Documento:', styles: { fontStyle: 'bold' } }, cierre.CIE_documento],
              [{ content: 'Condición:', styles: { fontStyle: 'bold' } }, cierre.CON_descripcion],
              [{ content: 'Diagnostico:', styles: { fontStyle: 'bold' } }, cierre.CIE_diagnostico],
              [{ content: 'Solución:', styles: { fontStyle: 'bold' } }, cierre.CIE_solucion],
              [{ content: 'Recomendaciones:', styles: { fontStyle: 'bold' } }, cierre.CIE_recomendaciones]
            ],
            styles: {
              fontSize: 10,
              cellPadding: 2,
            },
            headStyles: {
              fillColor: [44, 62, 80],
              textColor: [255, 255, 255],
              fontStyle: 'bold',
            },
            columnStyles: {
              0: { cellWidth: 50 }  // Define un ancho fijo para la columna "Campo"
            }
          });

          // Detalle de la incidencia
          const titleZ = 150;
          doc.setFont('helvetica', 'bold');
          doc.setFontSize(12);
          doc.text('Detalle de la Incidencia:', 20, titleZ);

          doc.autoTable({
            startY: 153,
            margin: { left: 20 },
            head: [['Campo', 'Descripción']],
            body: [
              [{ content: 'Fecha de entrada:', styles: { fontStyle: 'bold' } }, cierre.fechaIncidenciaFormateada],
              [{ content: 'Categoría:', styles: { fontStyle: 'bold' } }, cierre.CAT_nombre],
              [{ content: 'Asunto:', styles: { fontStyle: 'bold' } }, cierre.INC_asunto],
              [{ content: 'Documento:', styles: { fontStyle: 'bold' } }, cierre.INC_documento],
              [{ content: 'Código Patrimonial:', styles: { fontStyle: 'bold' } }, cierre.INC_codigoPatrimonial],
              [{ content: 'Área:', styles: { fontStyle: 'bold' } }, cierre.ARE_nombre],
              [{ content: 'Descripción:', styles: { fontStyle: 'bold' } }, cierre.INC_descripcion],
              [{ content: 'Usuario:', styles: { fontStyle: 'bold' } }, cierre.UsuarioRegistro]
            ],
            styles: {
              fontSize: 10,
              cellPadding: 2,
            },
            headStyles: {
              fillColor: [44, 62, 80],
              textColor: [255, 255, 255],
              fontStyle: 'bold',
            },
            columnStyles: {
              0: { cellWidth: 50 }
            }
          });

          const titleFirma = 260;
          const titleResponsable = titleFirma + 5;
          doc.setFont('times', 'normal');
          doc.setFontSize(11);

          const textFirmaSello = 'Firma y Sello';
          const textResponsable = 'Subgerente de Informática y Sistemas';
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

            const footerText = 'Sistema HelpDesk de la MDE';
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
          doc.autoPrint();
          window.open(doc.output('bloburl')); // Para algunos navegadores es necesario abrir el PDF antes de imprimir

          toastr.success('Archivo enviado a la impresora.');
        } catch (error) {
          toastr.error('Hubo un error al enviar el archivo a la impresora.');
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

$('#tablaIncidenciasCerradas').on('click', 'tr', function () {
  $(this).toggleClass('selected').siblings().removeClass('selected');
});
