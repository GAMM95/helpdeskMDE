$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "1500"
  };
});

// Generacion del PDF al hacer clic en boton
$('#reporte-pendientes-cierre').click(function () {

  // Realziar la solicitud AJAX para obtener los datos de la incidencia
  $.ajax({
    url: 'ajax/getReportePendientesCierre.php',
    method: 'GET',
    dataType: 'json',
    success: function (data) {
      console.log(data);
      if (data.length === 0) {
        toastr.warning('No se encontraron datos para generar el reporte.');
        return;
      }

      try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape'); // Orientación horizontal

        const logoUrl = './public/assets/escudo.png';

        function addHeader(doc) {
          doc.setFontSize(9);
          doc.setFont('helvetica', 'normal');

          const fechaImpresion = new Date().toLocaleDateString();
          const headerText2 = 'Subgerencia de Informática y Sistemas';
          const reportTitle = 'REPORTE DE INCIDENCIAS PENDIENTES DE CIERRE';

          const pageWidth = doc.internal.pageSize.width;
          const marginX = 10;
          const marginY = 5;
          const logoWidth = 25;
          const logoHeight = 25;

          doc.addImage(logoUrl, 'PNG', marginX, marginY, logoWidth, logoHeight);

          doc.setFont('helvetica', 'bold');
          doc.setFontSize(16);
          const titleWidth = doc.getTextWidth(reportTitle);
          const titleX = (pageWidth - titleWidth) / 2;
          const titleY = 20;
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

        addHeader(doc);

        const titleY = 45;
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(12);
        doc.text('Detalle de la Incidencia:', 20, titleY);


        let item = 1; // Contador para item
        doc.autoTable({
          startY: 35,
          margin: { left: 4, right: 10 },
          head: [['Item', 'Incidencia', 'Fecha', 'Categoría', 'Asunto', 'Documento', 'Código patrimonial', 'Área solicitante', 'Prioridad', 'Estado']],
          body: data.map(reporte => [
            item++,
            reporte.INC_numero_formato,
            reporte.fechaIncidenciaFormateada,
            reporte.CAT_nombre,
            reporte.INC_asunto,
            reporte.INC_documento,
            reporte.INC_codigoPatrimonial,
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
            0: { cellWidth: 10 }, // Ancho para la columna item
            1: { cellWidth: 25 }, // Ancho para la columna Número de incidencia
            2: { cellWidth: 20 }, // Ancho para la columna Fecha
            3: { cellWidth: 40 }, // Ancho para la columna Categoría
            4: { cellWidth: 40 }, // Ancho para la columna Asunto
            5: { cellWidth: 35 }, // Ancho para la columna Documento
            6: { cellWidth: 35 }, // Ancho para la columna codigo patrimonial
            7: { cellWidth: 40 }, // Ancho para la columna Área solicitante
            8: { cellWidth: 20 }, // Ancho para la columna prioridad
            9: { cellWidth: 22 }  // Ancho para la columna Estado
          }
        });

        function addFooter(doc, pageNumber, totalPages) {
          doc.setFontSize(8);
          doc.setFont('helvetica', 'italic');
          const footerY = 200; // Ajuste la posición del pie de página en la orientación horizontal
          doc.setLineWidth(0.05);
          doc.line(20, footerY - 5, doc.internal.pageSize.width - 20, footerY - 5);

          const footerText = 'Sistema de Gestión de Incidencias';
          const pageInfo = `Página ${pageNumber} de ${totalPages}`;
          const pageWidth = doc.internal.pageSize.width;

          doc.text(footerText, 20, footerY);
          doc.text(pageInfo, pageWidth - 20 - doc.getTextWidth(pageInfo), footerY);
        }

        // Pie de pagina
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
        }, 1500);
      } catch (error) {
        toastr.error('Hubo un error al generar PDF.');
        console.error('Error al generar el PDF:', error.message);
      }
    },
    error: function (xhr, status, error) {
      toastr.error('Hubo un error al obtener los datos de la incidencia.');
      console.error('Error en AJAX:', xhr.responseText, 'Status:', status, 'Error:', error);
    }
  });
});