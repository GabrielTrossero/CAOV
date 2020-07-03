$(document).ready(function () {
  //detecta los cambios del input select "filtroTabla"
  $("#filtroTabla").change(function () {
    var estado = $("#filtroTabla").find(':selected').val(); //asigno a estado el valor que tiene el atributo "pagada" del input select seleccionado

    //si selecciono la tabla activa, oculto el nombre y los datos de la tabla de la historica
    if (estado == 'activa') {
      $("#nomTablaActual").show();
      $("#nomTablaHistorica").hide();
      $("#tablaActual").show();
      $("#tablaHistorica").hide();
    }
    //si selecciono la tabla activa, oculto el nombre y los datos de la tabla de la historica
    else if (estado == 'historica') {
      $("#nomTablaActual").hide();
      $("#nomTablaHistorica").show();
      $("#tablaActual").hide();
      $("#tablaHistorica").show();
    }
  });

});
