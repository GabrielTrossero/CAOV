//tomo el valor del input Estado y hago cambios en el input Fecha de Pago
$(document).ready(function () {
  $("#estado").change(function () { //detecta los cambios del input select
    var estado = $("#estado").find(':selected').val(); //asigno a estado el valor que tiene el atributo "estado" del input select seleccionado
    if (estado == 'inhabilitada') {
      $('#fechaPago').prop('required', false);
      $('#fechaPago').prop('disabled', true);
      $('#medioPago').prop('disabled', true);
    }
    else if (estado = "pagada") {
      $('#fechaPago').prop('disabled', false);
      $('#fechaPago').prop('required', true);
      $('#medioPago').prop('disabled', false);
    }
  });
});
