$(document).ready(function () {
  //tomo el valor del input Estado y hago cambios en el input Fecha de Pago
  $("#pagada").change(function () { //detecta los cambios del input select
    var estado = $("#pagada").find(':selected').val(); //asigno a estado el valor que tiene el atributo "pagada" del input select seleccionado

    if (estado == 'n') {
      $('#fechaPago').prop('required', false);
      $('#fechaPago').prop('disabled', true);
      $('#fechaPago').val(null);
      $('#medioPago').prop('disabled', true);
      $('#montoMensual').val('La cuota no está pagada');
      $('#mesesAtrazo').val('La cuota no está pagada');
      $('#interesAtrazo').val('La cuota no está pagada');
      $('#cantidadIntegrantes').val('La cuota no está pagada');
      $('#interesGrupoFamiliar').val('La cuota no está pagada');
      $('#montoTot').val('La cuota no está pagada');
    }
    else if (estado == 's') {
      $('#fechaPago').prop('disabled', false);
      $('#fechaPago').prop('required', true);
      $('#medioPago').prop('disabled', false);

      //esto lo hago porque los value de los siguientes input no los tengo en otro lado, entonces los conservo en la variable valor de cada input
      var cantInt = $('#cantidadIntegrantes').attr('valor'); //obtengo el value original de la variable valor en el input cantidadIntegrantes
      $('#cantidadIntegrantes').val(cantInt); //y lo pongo como value del input
      var intGruFam = $('#interesGrupoFamiliar').attr('valor'); //y lo mismo con esta
      $('#interesGrupoFamiliar').val(intGruFam);
      var fechPag = $('#fechaPago').attr('valor'); //y esta
      $('#fechaPago').val(fechPag);
    }
  });



  //EJECUTO EL CÓDIGO QUE TIENE MONTOS.JS (para que me complete los input de nuevo)
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
