$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});

document.addEventListener('DOMContentLoaded', function () {
  const { jsPDF } = window.jspdf;

  $('#imprimir-cierre').click(function () {
    const filaSeleccionada = $('#tablaListarIncidencias .selected');
    if (filaSeleccionada.length === 0) {
      toastr.warning('Selecciona una fila para imprimir.');
      return;
    }

    const datos = filaSeleccionada.find('td').map(function () {
      return $(this).text();
    }).get();

    const doc = new jsPDF();
    const logoUrl = './public/assets/escudo.png';

    const marginX = 20;

    function addHeader(doc) {
      doc.setFontSize(9);
      doc.setFont('helvetica', 'normal');

      const fechaImpresion = new Date().toLocaleDateString();
      const headerText2 = 'Subgerencia de Informática y Sistemas';
      const reportTitle = 'REPORTE DE CIERRE DE INCIDENCIA';

      const pageWidth = doc.internal.pageSize.width;
      const marginX = 10; // Margen desde el borde izquierdo
      const marginY = 10; // Margen desde el borde superior
      const logoWidth = 25;
      const logoHeight = 25;

      // Agregar el logo
      const logoX = marginX;
      const logoY = marginY;
      doc.addImage(logoUrl, 'PNG', logoX, logoY, logoWidth, logoHeight);

      // Ajuste para el texto del centro (título del reporte)
      doc.setFont('helvetica', 'bold'); // Configurar fuente a bold
      doc.setFontSize(16); // Aumentar tamaño del título
      const titleWidth = doc.getTextWidth(reportTitle);
      const titleX = (pageWidth - titleWidth) / 2;
      const titleY = 20; // Fija la posición vertical del título de forma independiente

      // Agregar el título centrado
      doc.text(reportTitle, titleX, titleY);
      doc.setLineWidth(0.5);
      doc.line(titleX, titleY + 3, titleX + titleWidth, titleY + 3);

      // Ajuste para el texto de la derecha (subgerencia y fecha)
      doc.setFontSize(9);
      doc.setFont('times', 'normal');
      const fechaText = `Fecha de impresión: ${fechaImpresion}`;
      const headerText2Width = doc.getTextWidth(headerText2);
      const fechaTextWidth = doc.getTextWidth(fechaText);
      const headerText2X = pageWidth - marginX - headerText2Width;
      const headerText2Y = logoY + logoHeight / 2; // Centrar verticalmente con el logo
      const fechaTextX = pageWidth - marginX - fechaTextWidth;
      const fechaTextY = headerText2Y + 5; // Reducir este valor para disminuir el espacio entre líneas

      // Agregar texto de la subgerencia y fecha de impresión
      doc.text(headerText2, headerText2X, headerText2Y - 10);
      doc.text(fechaText, fechaTextX, fechaTextY - 10);
    }

    // Llamar a la función para agregar el encabezado
    addHeader(doc);


    // Ajusta la posición vertical del subtítulo y la información
    const titleY = 50; // Posición vertical del subtítulo
    doc.setFont('times', 'bold');
    doc.setFontSize(12); // Tamaño del subtítulo
    doc.text('Detalle de la Incidencia:', marginX, titleY); // Subtítulo

    // Información de la incidencia en tabla
    doc.autoTable({
      startY: 55, // Posición vertical donde empezará la tabla
      margin: { left: 20 }, // Margen izquierdo
      head: [[' ', 'Descripción']], // Encabezado de la tabla
      body: [
        ['Número de incidencia:', datos['0']],
        ['Fecha:', datos[1]],
        ['Categoría:', datos[5]],
        ['Asunto:', datos[3]],
        ['Documento:', datos[4]],
        ['Código Patrimonial:', datos[2]],
        ['Área:', datos[6]],
        ['Descripción:', datos[7]],
        ['Estado de incidencia:', datos[8]],
        ['Usuario:', datos[9]]
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
    });

    // Ajusta la posición vertical del subtítulo y la información
    const titleFirma = 200; // Posición vertical del texto "Firma y Sello"
    const titleResponsable = titleFirma + 5; // Posición vertical del texto "Responsable del Área Usuaria"
    doc.setFont('times', 'normal');
    doc.setFontSize(11); // Tamaño del subtítulo

    // Define los textos
    const textFirmaSello = 'Firma y Sello';
    const textResponsable = 'Responsable del Área Usuaria';

    // Calcula el ancho del texto en píxeles
    const textWidthFirmaSello = doc.getTextWidth(textFirmaSello);
    const textWidthResponsable = doc.getTextWidth(textResponsable);

    // Determina el ancho mayor entre los dos textos para centrar ambos y la línea
    const maxTextWidth = Math.max(textWidthFirmaSello, textWidthResponsable);

    // Ajusta el ancho de la línea, añadiendo un extra
    const lineExtraWidth = 20; // Valor adicional para alargar la línea
    const lineWidth = maxTextWidth + lineExtraWidth;
    const pageWidth = doc.internal.pageSize.width;

    // Calcula la posición X para centrar el texto y la línea
    const centerX = (pageWidth - lineWidth) / 2;

    // Dibuja la línea centrada
    doc.setLineWidth(0.5);
    doc.line(centerX, titleFirma - 5, centerX + lineWidth, titleFirma - 5);

    // Dibuja el texto centrado
    doc.text(textFirmaSello, centerX + (lineWidth - textWidthFirmaSello) / 2, titleFirma);
    doc.text(textResponsable, centerX + (lineWidth - textWidthResponsable) / 2, titleResponsable);



    // Footer del PDF
    function addFooter(doc, pageNumber, totalPages) {
      doc.setFontSize(8);
      doc.setFont('times', 'italic');
      const footerY = 285;
      doc.setLineWidth(0.5);
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

    doc.output('dataurlnewwindow');
  });

});

$(document).ready(function () {
  $('#tablaListarIncidencias').on('click', 'tr', function () {
    $(this).toggleClass('selected').siblings().removeClass('selected');
  });
});
