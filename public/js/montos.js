//PARA MONTO DE INTERESES
$(document).ready(function () {
  $("#fechaPago").change(function () { //detecta los cambios del input id=fechaPago
    //moment sirve para sacar la diferencia de meses entre las dos fechas
    var mesAnio = moment($("#fechaPago").attr('mesAnio')); //obtengo el valor que tiene el atributo mesAnio del input id=fechaPago
    var fechaPago = moment($("#fechaPago").val()); //obtengo el value que tiene el input
    var mesesAtrazados = fechaPago.diff(mesAnio, 'months'); //calculo la diferencia de meses entre el pago y el mes/anio de la cuota

    var cantMaxMeses = $("#fechaPago").attr("cantMaxMeses"); //obtengo la cantidad máxima de meses posibles para no cobrar atrazo

    if (mesesAtrazados > cantMaxMeses){
      var montoInteres = $("#fechaPago").attr('interes'); //obtengo el valor que tiene el atributo interes del input id=fechaPago
      var montoPagar = (mesesAtrazados - cantMaxMeses) * montoInteres; //calculo el monto a pagar
      $("#interesAtrazo").val("$" + montoPagar + " (" + (mesesAtrazados - cantMaxMeses) + " mes/es cobrado/s)"); //inserto en el input id=interesAtrazo
      $("#mesesAtrazo").val(mesesAtrazados + " mes/es"); //inserto en el input id=mesesAtrazo
      $("#valorAtrazo").val(montoPagar);
    }
    else if (mesesAtrazados >= 0){
      $("#interesAtrazo").val("$0 (0 meses)");
      $("#mesesAtrazo").val(mesesAtrazados + " mes/es");
      $("#valorAtrazo").val(0);
    }
    else {
      $("#interesAtrazo").val("$0 (0 meses)");
      $("#mesesAtrazo").val("0 meses");
      $("#valorAtrazo").val(0);
    }


    //PARA MONTO TOTAL
    var valueAtrazo = parseInt(jQuery('#valorAtrazo').val(),10);
    var valueGrupoFamiliar = parseInt(jQuery('#valorGrupoFamiliar').val(),10);
    var valueMensual = parseInt(jQuery('#valorMensual').val(),10);
    var total = ( "$" + (valueAtrazo + valueGrupoFamiliar + valueMensual));

    $("#montoTot").val(total); //inserto el valor seleccionado en el input "id=montoTotal"
  });
});
