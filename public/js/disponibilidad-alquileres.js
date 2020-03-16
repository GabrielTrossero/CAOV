$(document).ready(function () {
    let boton = $("#chequear");
    boton.click(function () { 
        

        let tipoAlquiler = $("#tipo").val();
        let token = $("#token").val();

        if (tipoAlquiler == "inmueble") {
            let fechaIngresada = $("#chequear-fecha").val();
            let inmuebleSeleccionado = $("#inmueble").val();
            let nombreInmueble = $("#inmueble option:selected").html();
            
            $.post('/alquilerinmueble/disponibilidad', {fecha: fechaIngresada, _token: token, inmueble: inmuebleSeleccionado})
            .done(function(data){
                let mensaje = "Horarios Reservados para " + nombreInmueble + ":";
                fechasReservadas = data.fechasReservadas;
                
                for (let i = 0; i < fechasReservadas.length; i++) {
                    let elem = fechasReservadas[i];
                    //alert(elem+"\n");
                    mensaje += "\n" + elem[1] + " hasta " + elem[2];
                }

                if (fechasReservadas.length == 0) {
                    mensaje += "\nNo hay reservas para el inmueble en la fecha seleccionada";       
                }

                alert(mensaje);
            })
            .catch(error => {
                console.log(error)
            });
        }
        else if (tipoAlquiler == "mueble") {
            let muebleSeleccionado = $("#tipoMueble").val();
            let nombreMueble = $("#tipoMueble option:selected").html();
            let fechaHoraInicio = $('#chequear-fecha-hora-inicio').val();
            let fechaHoraFin = $('#chequear-fecha-hora-fin').val();

            if (fechaHoraInicio && fechaHoraFin) {
               $.post('/alquilermueble/disponibilidad', {fechaInicio: fechaHoraInicio, fechaFin: fechaHoraFin, _token: token, mueble: muebleSeleccionado})
                .done(function(data){
                    let mensaje = "Horarios Reservados para " + nombreMueble + ":";
                    let fechasReservadas = data.fechasReservadas;
                    let fechasSolapadas = data.fechasSolapadas;

                    //console.log(fechasReservadas);

                    for (let i = 0; i < fechasReservadas.length; i++) {
                        let elem = fechasReservadas[i];
                        mensaje += "\n" + elem[1] + " hasta " + elem[2] + ". " + "Cantidad: " + elem[3];
                    }
                    
                    if (fechasReservadas.length == 0) {
                        mensaje += "\nNo hay reservas para el mueble en la fecha seleccionada";  
                    }

                    if (fechasSolapadas.length) {
                        mensaje += "\n-----------------------------\nHorarios Solapados:";
                        for (let i = 0; i < fechasSolapadas.length; i++) {
                            let elem = fechasSolapadas[i];
                            mensaje += "\n" + elem[1] + " hasta " + elem[2] + ". " + "Cantidad: " + elem[3];
                        }
                    }
                    
                    mensaje += "\nStock restante: " + data.stockRestante;

                    alert(mensaje);
                })
                .catch(error => {
                    console.log(error);
                });
            }
            else {
                alert("ERROR: \nAmbas fechas deben estar completas para la verificación de disponibilidad.");
            }
        }
    });
});