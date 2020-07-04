$(document).ready(function () {
    //detecta los cambios del input select "filtroTabla"
    $("#filtroTabla").change(function () {
      var estado = $("#filtroTabla").find(':selected').val(); //asigno a estado el valor que tiene el atributo "pagada" del input select seleccionado
  
      //si selecciono la tabla mes, oculto el nombre y los datos des demás tablas
      if (estado == 'historica') {
        ocultar(); //llamo a la función
        $("#nomTablaHistorica").show();
        $("#tablaHistorica").show();
      }
      //si selecciono la tabla activa, oculto el nombre y los datos de la tabla de la historica
      else if (estado == 'mes') {
        ocultar();
        $("#nomTablaMes").show();
        $("#tablaMes").show();
      }
      else if (estado == 'impaga') {
        ocultar();
        $("#nomTablaImpaga").show();
        $("#tablaImpaga").show();
      }
      else if (estado == 'atrasada') {
        ocultar();
        $("#nomTablaAtrasada").show();
        $("#tablaAtrasada").show();
      }
      else if (estado == 'inhabilitada') {
        ocultar();
        $("#nomTablaInhabilitada").show();
        $("#tablaInhabilitada").show();
      }
      else if (estado == 'pagada') {
        ocultar();
        $("#nomTablaPagada").show();
        $("#tablaPagada").show();
      }
      else if (estado == 'pagadaMes') {
        ocultar();
        $("#nomTablaPagadaMes").show();
        $("#tablaPagadaMes").show();
      }
      else if (estado == 'pagadaFueraDeTermino') {
        ocultar();
        $("#nomTablaPagadaFueraDeTermino").show();
        $("#tablaPagadaFueraDeTermino").show();
      }
    });
  
  });


function ocultar() {
    $(".col-md-9").hide(); //oculto todos los nombres
    $(".card-body").hide(); //oculto todas las tablas
}
