
// TODO: SETEO DEL COMBO CATEGORIA
$(document).ready(function () {
  console.log("FETCHING");
  $.ajax({
    url: 'ajax/getCategoryData.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      var select = $('#cbo_categoria');
      select.empty();
      select.append('<option value="" selected disabled>Seleccione una categoría</option>');
      $.each(data, function (index, value) {
        select.append('<option value="' + value.CAT_codigo + '">' + value.CAT_nombre + '</option>');
      });

      // Verificar y establecer el valor seleccionado
      if (categoriaRegistrada !== '') {
        select.val(categoriaRegistrada);
      } else {
        select.val('');
      }
    },
    error: function (error) {
      console.error('Error en la carga de categorías:', error);
    }
  });
});