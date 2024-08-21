$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

$('#reportes-areas').click(function () {
  // Obtener el número de incidencia desde el campo de entrada
  const codigoArea = $('#area').val();

  if (!codigoArea) {
    toastr.warning('Seleccione una &acute;rea para generar reporte.');
    return;
  }

  // Realizar una solicitud AJAX para obtener los datos de la incidencia
  $.ajax({
    url: 'ajax/getReportePorArea.php',
    method: 'GET',
    data: { area: codigoArea },
    success: function (data) {
      console.log("Datos recibidos:", data);

      if (data.error) {
        toastr.error('Error en la solicitud: ' + data.error);
        return;
      }

      const incidencia = data.find(inc => inc.ARE_codigo == codigoArea);
      try {
        if (incidencia) {

          const { jsPDF } = window.jspdf;
          const doc = new jsPDF('landscape');

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

          // Inicializar el contador
          let item = 1;

          doc.autoTable({
            startY: 48,
            margin: { left: 20 },
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
              fontSize: 11,
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
              6: { cellWidth: 50 }, // Ancho para la columna codigo patrimonial
              7: { cellWidth: 20 }, // Ancho para la columna prioridad
              8: { cellWidth: 20 } // Ancho para la columna estado
            }
          });

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

        }
        // else {
        //   toastr.warning('No se ha encontrado incidencia para el área seleccionada.');
        // }
      } catch (error) {
        toastr.error('Hubo un error al generar reporte.');
        console.error('Error al generar el PDF:', error.message);
      }
    },
    error: function (xhr, status, error) {
      toastr.error('Hubo un error al obtener los datos de la incidencia.');
      console.error('Error al realizar la solicitud AJAX:', error);
    }
  });
});