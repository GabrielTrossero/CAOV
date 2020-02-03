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

  //esto lo hago porque el value de los siguientes input no los tengo en otro lado, entonces los conservo en la variable "valor" de cada input
  $('#mesesAtraso').attr('valor', $("#mesesAtraso").val()); //inserto en la variable valor del input mesesAtraso lo que tiene como value el input mesesAtraso
  $('#interesAtraso').attr('valor', $("#interesAtraso").val());

  //PARA MONTO TOTAL
  var valueAtraso = parseInt(jQuery('#valorAtraso').val(),10);
  var valueGrupoFamiliar = parseInt(jQuery('#valorGrupoFamiliar').val(),10);
  var valueMensual = parseInt(jQuery('#valorMensual').val(),10);
  var total = ( "$" + (valueAtraso + valueGrupoFamiliar + valueMensual));

  $("#montoTot").val(total); //inserto el valor seleccionado en el input "id=montoTotal"






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
    }
  });



/*
  $("#tipoSocio").change(function () { //detecta los cambios del input tipoSocio
    var montoCuota = new Object();
    var estado = $("#tipoSocio").find(':selected').val(); //asigno a estado el valor que tiene el atributo "tipoSocio" del input select seleccionado

    //dependiendo del tipo de socio, se tendrán diferentes montos
    if (estado == 'g') {
      montoCuota = $('#montoCuotaGrupoFamiliar').val();
    }
    else if (estado == 'c') {
      montoCuota = $('#montoCuotaCadete').val();
    }
    else if (estado == 'a') {
      montoCuota = $('#montoCuotaActivo').val();
    }

//INPUT MONTO MENSUAL
    montoCuota = JSON.parse(montoCuota); //transformo de json (string) a objeto
    $('#montoMensual').val("$" + montoCuota.montoMensual);

//INPUT INTERESES
    //moment sirve para sacar la diferencia de meses entre las dos fechas
    var mesAnio = moment($("#fechaMesAnio").attr('mesAnio'));
    var fechaPago = moment($("#fechaPago").val()); //obtengo el value que tiene el input
    var mesesAtrazados = fechaPago.diff(mesAnio, 'months'); //calculo la diferencia de meses entre el pago y el mes/anio de la cuota

    if (mesesAtrazados > montoCuota.cantidadMeses){
      var montoPagar = (mesesAtrazados - montoCuota.cantidadMeses) * montoCuota.montoInteresMensual; //calculo el monto a pagar
      $("#interesAtrazo").val("$" + montoPagar + " (" + mesesAtrazados + " meses)"); //inserto en el input id=interesAtrazo
      $("#valorAtrazo").val(montoPagar);
    }
    else if (mesesAtrazados >= 0){
      $("#interesAtrazo").val("$0 (" + mesesAtrazados + " meses)");
      $("#valorAtrazo").val(0);
    }
    else {
      $("#interesAtrazo").val("$0 (0 meses)");
      $("#valorAtrazo").val(0);
    }
  });*/
});
