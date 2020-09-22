$(document).ready(function () {
  //EJECUTO EL CÓDIGO QUE TIENE MONTOS.JS (para que me complete los input de nuevo)
  //moment sirve para sacar la diferencia de meses entre las dos fechas
  var mesAnio = moment($("#fechaPago").attr('mesAnio')); //obtengo el valor que tiene el atributo mesAnio del input id=fechaPago
  var fechaPago = moment($("#fechaPago").val()); //obtengo el value que tiene el input
  var mesesAtrasados = fechaPago.diff(mesAnio, 'months'); //calculo la diferencia de meses entre el pago y el mes/anio de la cuota

  var cantMaxMeses = $("#fechaPago").attr("cantMaxMeses"); //obtengo la cantidad máxima de meses posibles para no cobrar atraso

  if (mesesAtrasados > cantMaxMeses){
    var montoInteres = $("#fechaPago").attr('interes'); //obtengo el valor que tiene el atributo interes del input id=fechaPago
    var montoPagar = (mesesAtrasados - cantMaxMeses) * montoInteres; //calculo el monto a pagar
    $("#interesAtraso").val("$ " + montoPagar + " (" + (mesesAtrasados - cantMaxMeses) + " mes/es cobrado/s)"); //inserto en el input id=interesAtraso
    $("#mesesAtraso").val(mesesAtrasados + " mes/es"); //inserto en el input id=mesesAtraso
    $("#valorAtraso").val(montoPagar);
  }
  else if (mesesAtrasados >= 0){
    $("#interesAtraso").val("$ 0 (0 meses)");
    $("#mesesAtraso").val(mesesAtrasados + " mes/es");
    $("#valorAtraso").val(0);
  }
  else {
    $("#interesAtraso").val("$ 0 (0 meses)");
    $("#mesesAtraso").val("0 meses");
    $("#valorAtraso").val(0);
  }

  //PARA MONTO TOTAL
  var valueAtraso = parseInt(jQuery('#valorAtraso').val(),10);
  var valueGrupoFamiliar = parseInt(jQuery('#valorGrupoFamiliar').val(),10);
  var valueMensual = parseInt(jQuery('#valorMensual').val(),10);
  var total = ( "$ " + (valueAtraso + valueGrupoFamiliar + valueMensual));

  $("#montoTot").val(total); //inserto el valor seleccionado en el input "id=montoTotal"

  //esto lo hago porque el value de los siguientes input no los tengo en otro lado, entonces los conservo en la variable "valor" de cada input
  $('#mesesAtraso').attr('valor', $("#mesesAtraso").val()); //inserto en la variable valor del input mesesAtraso lo que tiene como value el input mesesAtraso
  $('#interesAtraso').attr('valor', $("#interesAtraso").val());
  $('#montoTot').attr('valor', $("#montoTot").val());







  //detecta los cambios del input select "pagada"
  $("#pagada").change(function () {
    var estado = $("#pagada").find(':selected').val(); //asigno a estado el valor que tiene el atributo "pagada" del input select seleccionado

    if (estado == 'n') {
      $('#fechaPago').prop('required', false);
      $('#fechaPago').prop('disabled', true);
      $('#fechaPago').val(null);
      $('#medioPago').prop('disabled', true);
      $('#montoMensual').val('La cuota no está pagada');
      $('#mesesAtraso').val('La cuota no está pagada');
      $('#interesAtraso').val('La cuota no está pagada');
      $('#cantidadIntegrantes').val('La cuota no está pagada');
      $('#interesGrupoFamiliar').val('La cuota no está pagada');
      $('#montoTot').val('La cuota no está pagada');
    }
    else if (estado == 's') {
      $('#fechaPago').prop('disabled', false);
      $('#fechaPago').prop('required', true);
      $('#medioPago').prop('disabled', false);

      //esto lo hago porque los value de los siguientes input no los tengo en otro lado, entonces los conservo en la variable valor de cada input
      var montMen = $('#montoMensual').attr('valor'); //obtengo el value original de la variable valor en el input montoMensual
      $('#montoMensual').val(montMen); //y lo pongo como value del input
      var mesesAtr = $('#mesesAtraso').attr('valor');
      $('#mesesAtraso').val(mesesAtr);
      var inteAtr = $('#interesAtraso').attr('valor');
      $('#interesAtraso').val(inteAtr);
      var cantInt = $('#cantidadIntegrantes').attr('valor');
      $('#cantidadIntegrantes').val(cantInt);
      var intGruFam = $('#interesGrupoFamiliar').attr('valor');
      $('#interesGrupoFamiliar').val(intGruFam);
      var fechPag = $('#fechaPago').attr('valor');
      $('#fechaPago').val(fechPag);
      var monTot = $('#montoTot').attr('valor');
      $('#montoTot').val(monTot);
    }
  });

});
