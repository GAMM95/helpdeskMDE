$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "2000"
  };
});
// $('#imprimir-incidencia').click(function () {
//   const filaSeleccionada = $('#tablaListarIncidencias .selected');
//   if (filaSeleccionada.length === 0) {
//     toastr.warning('Selecciona una fila para imprimir.');
//     return;
//   }

//   // Obtener el número de incidencia de la fila seleccionada
//   const numeroIncidencia = filaSeleccionada.find('td').first().text().trim();
//   console.log("Número de incidencia:", numeroIncidencia);

//   // Realizar una solicitud AJAX para obtener los datos de la incidencia
//   $.ajax({
//     url: 'ajax/getReporteIncidencia.php',
//     method: 'GET',
//     data: { numero: numeroIncidencia },
//     dataType: 'json',
//     success: function (data) {
//       console.log("Datos recibidos:", data);
//       const incidencia = data.find(inc => inc.INC_numero === numeroIncidencia);

//       if (incidencia) {
//         try {
//           const doc = new jsPDF();
//           const logoUrl = './public/assets/escudo.png';

//           function addHeader(doc) {
//             doc.setFontSize(9);
//             doc.setFont('helvetica', 'normal');

//             const fechaImpresion = new Date().toLocaleDateString();
//             const headerText2 = 'Subgerencia de Informática y Sistemas';
//             const reportTitle = 'REPORTE DE INCIDENCIA';

//             const pageWidth = doc.internal.pageSize.width;
//             const marginX = 10;
//             const marginY = 10;
//             const logoWidth = 25;
//             const logoHeight = 25;

//             doc.addImage(logoUrl, 'PNG', marginX, marginY, logoWidth, logoHeight);

//             doc.setFont('helvetica', 'bold');
//             doc.setFontSize(16);
//             const titleWidth = doc.getTextWidth(reportTitle);
//             const titleX = (pageWidth - titleWidth) / 2;
//             const titleY = 25;

//             doc.text(reportTitle, titleX, titleY);
//             doc.setLineWidth(0.5);
//             doc.line(titleX, titleY + 3, titleX + titleWidth, titleY + 3);

//             doc.setFontSize(8);
//             doc.setFont('helvetica', 'normal');
//             const fechaText = `Fecha de impresión: ${fechaImpresion}`;
//             const headerText2Width = doc.getTextWidth(headerText2);
//             const fechaTextWidth = doc.getTextWidth(fechaText);
//             const headerText2X = pageWidth - marginX - headerText2Width;
//             const fechaTextX = pageWidth - marginX - fechaTextWidth;
//             const headerText2Y = marginY + logoHeight / 2;
//             const fechaTextY = headerText2Y + 5;

//             doc.text(headerText2, headerText2X, headerText2Y);
//             doc.text(fechaText, fechaTextX, fechaTextY);
//           }

//           addHeader(doc);

//           const titleY = 60;
//           doc.setFont('helvetica', 'bold');
//           doc.setFontSize(12);
//           doc.text('Detalle de la Incidencia:', 20, titleY);

//           doc.autoTable({
//             startY: 65,
//             margin: { left: 20 },
//             head: [['Campo', 'Descripción']],
//             body: [
//               ['Número de incidencia:', incidencia.INC_numero],
//               ['Fecha de entrada:', incidencia.fechaIncidenciaFormateada],
//               ['Categoría:', incidencia.CAT_nombre],
//               ['Asunto:', incidencia.INC_asunto],
//               ['Documento:', incidencia.INC_documento],
//               ['Código Patrimonial:', incidencia.INC_codigoPatrimonial],
//               ['Área:', incidencia.ARE_nombre],
//               ['Descripción:', incidencia.INC_descripcion],
//               ['Usuario:', incidencia.Usuario]
//             ],
//             styles: {
//               fontSize: 11,
//               cellPadding: 2,
//             },
//             headStyles: {
//               fillColor: [44, 62, 80],
//               textColor: [255, 255, 255],
//               fontStyle: 'bold',
//             },
//           });

//           function addFooter(doc, pageNumber, totalPages) {
//             doc.setFontSize(8);
//             doc.setFont('helvetica', 'italic');
//             const footerY = 285;
//             doc.setLineWidth(0.05);
//             doc.line(20, footerY - 5, doc.internal.pageSize.width - 20, footerY - 5);

//             const footerText = 'Sistema HelpDesk de la MDE';
//             const pageInfo = `Página ${pageNumber} de ${totalPages}`;
//             const pageWidth = doc.internal.pageSize.width;

//             doc.text(footerText, 20, footerY);
//             doc.text(pageInfo, pageWidth - 20 - doc.getTextWidth(pageInfo), footerY);
//           }

//           const totalPages = doc.internal.getNumberOfPages();
//           for (let i = 1; i <= totalPages; i++) {
//             doc.setPage(i);
//             addFooter(doc, i, totalPages);
//           }

//           const nombreArchivo = 'reporte_incidencia.pdf';
//           doc.save(nombreArchivo);

//           toastr.success('Archivo PDF descargado.');
//         } catch (error) {
//           toastr.error('Hubo un error al descargar el archivo PDF.');
//           console.error('Error al generar el PDF:', error.message);
//         }
//       } else {
//         toastr.error('No se encontraron datos para la incidencia seleccionada.');
//       }
//     },
//     error: function (xhr, status, error) {
//       toastr.error('Hubo un error al obtener los datos de la incidencia.');
//       console.error('Error al realizar la solicitud AJAX:', error);
//     }
//   });
// });

// $('#tablaListarIncidencias').on('click', 'tr', function () {
//   $(this).toggleClass('selected').siblings().removeClass('selected');
// });

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
    const logoUrl = './public/assets/escudo.png';

    const marginX = 20;

    function addHeader(doc) {
      doc.setFontSize(9);
      doc.setFont('helvetica', 'normal');

      const fechaImpresion = new Date().toLocaleDateString();
      const headerText2 = 'Subgerencia de Informática y Sistemas';
      const reportTitle = 'REPORTE DE INCIDENCIA';

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
      const titleY = 25; // Fija la posición vertical del título de forma independiente

      // Agregar el título centrado
      doc.text(reportTitle, titleX, titleY);
      doc.setLineWidth(0.5);
      doc.line(titleX, titleY + 3, titleX + titleWidth, titleY + 3);

      // Ajuste para el texto de la derecha (subgerencia y fecha)
      doc.setFontSize(8);
      doc.setFont('helvetica', 'normal');
      const fechaText = `Fecha de impresión: ${fechaImpresion}`;
      const headerText2Width = doc.getTextWidth(headerText2);
      const fechaTextWidth = doc.getTextWidth(fechaText);
      const headerText2X = pageWidth - marginX - headerText2Width;
      const headerText2Y = logoY + logoHeight / 2; // Centrar verticalmente con el logo
      const fechaTextX = pageWidth - marginX - fechaTextWidth;
      const fechaTextY = headerText2Y + 5; // Reducir este valor para disminuir el espacio entre líneas

      // Agregar texto de la subgerencia y fecha de impresión
      doc.text(headerText2, headerText2X, headerText2Y);
      doc.text(fechaText, fechaTextX, fechaTextY);
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
    const titleFirma = 200; // Posición vertical del subtítulo
    doc.setFont('times', 'normal');
    doc.setFontSize(10); // Tamaño del subtítulo
    // Calcula el ancho del texto en píxeles
    const text = '         Firma y Sello \nResponsable del Área Usuaria';
    const textWidth = doc.getTextWidth(text);
    // Dibuja la línea con el mismo ancho que el texto
    doc.line(marginX + 80, titleFirma - 5, marginX + 80 + textWidth, titleFirma - 5);
    // Dibuja el texto
    doc.text(text, marginX + 80, titleFirma); // Subtítulo

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
