//PARA MONTO DE INTERESES
$(document).ready(function () {
  $("#fechaPago").change(function () { //detecta los cambios del input id=fechaPago
    //moment sirve para sacar la diferencia de meses entre las dos fechas
    var mesAnio = moment($("#fechaPago").attr('mesAnio')); //obtengo el valor que tiene el atributo mesAnio del input id=fechaPago
    var fechaPago = moment($("#fechaPago").val()); //obtengo el value que tiene el input
    var mesesAtrasados = fechaPago.diff(mesAnio, 'months'); //calculo la diferencia de meses entre el pago y el mes/anio de la cuota

    var cantMaxMeses = $("#fechaPago").attr("cantMaxMeses"); //obtengo la cantidad máxima de meses posibles para no cobrar atraso

    if (mesesAtrasados > cantMaxMeses){
      var montoInteres = $("#fechaPago").attr('interes'); //obtengo el valor que tiene el atributo interes del input id=fechaPago
      var montoPagar = (mesesAtrasados - cantMaxMeses) * montoInteres; //calculo el monto a pagar
      $("#interesAtraso").val("$" + montoPagar + " (" + (mesesAtrasados - cantMaxMeses) + " mes/es cobrado/s)"); //inserto en el input id=interesAtraso
      $("#mesesAtraso").val(mesesAtrasados + " mes/es"); //inserto en el input id=mesesAtraso
      $("#valorAtraso").val(montoPagar);
    }
    else if (mesesAtrasados >= 0){
      $("#interesAtraso").val("$0 (0 meses)");
      $("#mesesAtraso").val(mesesAtrasados + " mes/es");
      $("#valorAtraso").val(0);
    }
    else {
      $("#interesAtraso").val("$0 (0 meses)");
      $("#mesesAtraso").val("0 meses");
      $("#valorAtraso").val(0);
    }


    //PARA MONTO TOTAL
    var valueAtraso = parseInt(jQuery('#valorAtraso').val(),10);
    var valueGrupoFamiliar = parseInt(jQuery('#valorGrupoFamiliar').val(),10);
    var valueMensual = parseInt(jQuery('#valorMensual').val(),10);
    var total = ( "$" + (valueAtraso + valueGrupoFamiliar + valueMensual));

    $("#montoTot").val(total); //inserto el valor seleccionado en el input "id=montoTotal"
  });
});
