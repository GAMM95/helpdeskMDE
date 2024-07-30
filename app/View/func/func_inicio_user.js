$(function () {
  var options1 = {
    chart: {
      type: 'bar',
      height: 200
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '50%'
      },
    },
    dataLabels: {
      enabled: true
    },
    colors: ["#1abc9c", "#3498db", "#e74c3c"], // Colores para cada barra
    series: [{
      name: 'Incidencias',
      data: incidenciasData
    }],
    xaxis: {
      categories: ['Abiertas', 'Recepcionadas', 'Cerradas'], // Etiquetas de categorías
    },
    tooltip: {
      fixed: {
        enabled: false
      },
      x: {
        show: true
      },
      y: {
        title: {
          formatter: function (seriesName) {
            return 'Cantidad: ';
          }
        }
      },
      marker: {
        show: true
      }
    }
  };

  new ApexCharts(document.querySelector("#support-chart"), options1).render();
});


// Establece el intervalo para recargar la página (30000 ms = 30 segundos)
// setTimeout(function () {
//   window.location.reload();
// }, 30000); // 30000 ms = 30 segundos
