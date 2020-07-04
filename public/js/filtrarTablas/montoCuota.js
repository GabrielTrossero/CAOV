$(document).ready(function () {
  //detecta los cambios del input select "filtroTabla"
  $("#filtroTabla").change(function () {
    var estado = $("#filtroTabla").find(':selected').val(); //asigno a estado el valor que tiene el atributo "pagada" del input select seleccionado

    //si selecciono la tabla activa, oculto el nombre y los datos des demás tablas
    if (estado == 'activa') {
      ocultar(); //llamo a la función
      $("#nomTablaActual").show();
      $("#tablaActual").show();
    }
    //si selecciono la tabla historica, oculto el nombre y los datos des demás tablas
    else if (estado == 'historica') {
      ocultar();
      $("#nomTablaHistorica").show();
      $("#tablaHistorica").show();
    }
  });

});


function ocultar() {
  $(".col-md-9").hide(); //oculto todos los nombres
  $(".card-body").hide(); //oculto todas las tablas
}
