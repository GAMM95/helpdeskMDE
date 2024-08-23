$(document).ready(function () {
  toastr.options = {
    "positionClass": "toast-bottom-right",
    "progressBar": true,
    "timeOut": "1500"
  };
});

$('#reportes-cierres-fechas').click(function () {
  const fechaInicio = $('#fechaInicio').val();
  const fechaFin = $('#fechaFin').val();

  if (!validarCampos() || !validarFechas()) {
    return;
  }

  // Realizar una solicitud AJAX para obtener los datos del cierre de incidencia
  $.ajax({
    url: 'ajax/getReporteCierresPorFecha.php',
    method: 'GET',
    data: { fechaInicio: fechaInicio, fechaFin: fechaFin },
    dataType: 'json',
    success: function (data) {
      console.log("Datos recibidos:", data);

      if (data.error) {
        toastr.error('Error en la solicitud: ' + data.error);
        return;
      }

      try {
        if (data.length > 0) {
          const { jsPDF } = window.jspdf;
          const doc = new jsPDF('landscape');

          const logoUrl = './public/assets/escudo.png';

          function addHeader(doc) {
            doc.setFontSize(9);
            doc.setFont('helvetica', 'normal');

            const fechaImpresion = new Date().toLocaleDateString();
            const headerText2 = 'Subgerencia de Informática y Sistemas';
            const reportTitle = 'REPORTE DE CIERRES POR FECHA';

            const pageWidth = doc.internal.pageSize.width;
            const marginX = 10;
            const marginY = 5;
            const logoWidth = 25;
            const logoHeight = 25;

            doc.addImage(logoUrl, 'PNG', marginX, marginY, logoWidth, logoHeight);

            // TITULO CENTRAL DEL DOCUMENTO
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(15);
            const titleWidth = doc.getTextWidth(reportTitle);
            const titleX = (pageWidth - titleWidth) / 2;
            const titleY = 15;
            doc.text(reportTitle, titleX, titleY);
            doc.setLineWidth(0.5); // Ancho de subrayado
            doc.line(titleX, titleY + 1, titleX + titleWidth, titleY + 1); // ubicacion del subrayado del titulo

            // Fecha de impresion 
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


          // Subtitulos de fechas
          const fechaInicioText = 'Fecha de Inicio:';
          const fechaFinText = 'Fecha de Fin:';

          // Obtener las fechas en formato original
          const fechaInicioOriginal = $('#fechaInicio').val();
          const fechaFinOriginal = $('#fechaFin').val();

          // Función para formatear la fecha a dd/mm/aaaa
          function formatearFecha(fecha) {
            const partes = fecha.split('-'); // Suponiendo que las fechas están en formato aaaa-mm-dd
            return `${partes[2]}/${partes[1]}/${partes[0]}`; // Retorna dd/mm/aaaa
          }

          // Formatear las fechas
          const fechaInicioValue = ` ${formatearFecha(fechaInicioOriginal)}`;
          const fechaFinValue = ` ${formatearFecha(fechaFinOriginal)}`;

          // Configuracion de fuentes
          doc.setFont('helvetica', 'bold');
          doc.setFontSize(12);

          // Calcular el ancho de los textos
          const fechaInicioAncho = doc.getTextWidth(fechaInicioText);
          const fechaInicioValueAncho = doc.getTextWidth(fechaInicioValue);
          const fechaFinAncho = doc.getTextWidth(fechaFinText);
          const fechaFinValueAncho = doc.getTextWidth(fechaFinValue);

          const spacing = 20; //espacio entre los dos textos

          // Calcular el ancho total de los textos más el espaciado
          const totalWidth = fechaInicioAncho + fechaInicioValueAncho + spacing + fechaFinAncho + fechaFinValueAncho;

          // Ancho de la página
          const pageWidth = doc.internal.pageSize.width;

          // Calcular la posición inicial en X para centrar los textos
          const startX = (pageWidth - totalWidth) / 2;

          const titleY = 25; // La misma posición Y para ambos textos

          // Dibujar el texto "Fecha de Inicio" y su valor
          doc.text(fechaInicioText, startX, titleY);
          doc.setFont('helvetica', 'normal');
          doc.text(fechaInicioValue, startX + fechaInicioAncho, titleY);

          // Dibujar el texto "Fecha de Fin" y su valor
          doc.setFont('helvetica', 'bold');
          doc.text(fechaFinText, startX + fechaInicioAncho + fechaInicioValueAncho + spacing, titleY);
          doc.setFont('helvetica', 'normal');
          doc.text(fechaFinValue, startX + fechaInicioAncho + fechaInicioValueAncho + spacing + fechaFinAncho, titleY);

          // Inicializar el contador
          let item = 1;

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
            $('#fechaInicio').val('');
            $('#fechaFin').val('');
          }, 1500);
        } else {
          toastr.warning('No se ha encontrado cierres de incidencias para las fechas seleccionadas.');
        }
      } catch (error) {
        toastr.error('Hubo un error al generar reporte.');
        console.error('Error al generar el PDF:', error.message);
      }
    },
    error: function (xhr, status, error) {
      toastr.error('Hubo un error al obtener los datos de los cierres de incidencia.');
      console.error('Error al realizar la solicitud AJAX:', error);
    }
  });


  function validarCampos() {
    var valido = false;
    var mensajeError = '';

    var fechaInicioSeleccionada = ($('#fechaInicio').val() !== null && $('#fechaInicio').val().trim() !== '');
    var fechaFinSeleccionada = ($('#fechaFin').val() !== null && $('#fechaFin').val().trim() !== '');

    // Verificar si al menos un campo está lleno
    if (fechaInicioSeleccionada || fechaFinSeleccionada) {
      valido = true;
    } else {
      mensajeError = 'Debe ingresar las fechas para generar reporte.';
    }

    if (!valido) {
      toastr.warning(mensajeError.trim());
    }

    return valido;
  }


  function validarFechas() {
    // Obtener valores de los campos de fecha
    const fechaInicio = new Date($('#fechaInicio').val());
    const fechaFin = new Date($('#fechaFin').val());

    // Obtener la fecha actual
    const fechaHoy = new Date();

    // Validar la fecha de inicio y fin
    let valido = true;
    let mensajeError = '';

    // Bloquear fechas posteriores a la fecha actual
    if (fechaInicio > fechaHoy) {
      mensajeError = 'La fecha de inicio no puede ser posterior a la fecha actual.';
      valido = false;
    }

    if (fechaFin > fechaHoy) {
      mensajeError = 'La fecha de fin no puede ser posterior a la fecha actual.';
      valido = false;
    }

    // Verificar que la fecha de fin sea posterior a la fecha de inicio
    if (fechaInicio && fechaFin && fechaFin < fechaInicio) {
      mensajeError = 'La fecha de fin debe ser posterior a la fecha de inicio.';
      valido = false;
    }

    // Mostrar mensaje de error con Toastr si la validación falla
    if (!valido) {
      toastr.warning(mensajeError.trim());
    }

    return valido;
  }

  // Agregar eventos para validar fechas cuando cambien
  $('#fechaInicio, #fechaFin').on('change', function () {
    validarFechas();
  });
});