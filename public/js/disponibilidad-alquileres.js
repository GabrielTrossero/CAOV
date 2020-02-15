$(document).ready(function () {
    let boton = $("#chequear");
    boton.click(function () {
        let fechaIngresada = $("#chequear-fecha").val();
        let tipoAlquiler = $("#tipo").val();
        let token = $("#token").val();

        if (tipoAlquiler == "inmueble") {
            let inmuebleSeleccionado = $("#inmueble").val();
            
            $.post('/alquilerinmueble/disponibilidad', {fecha: fechaIngresada, _token: token, inmueble: inmuebleSeleccionado})
            .done(function(data){
                let mensaje = "Horarios Reservados:";
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
            
            $.post('/alquilermueble/disponibilidad', {fecha: fechaIngresada, _token: token, mueble: muebleSeleccionado})
            .done(function(data){
                let mensaje = "Horarios Reservados:";
                fechasReservadas = data.fechasReservadas;
                console.log(fechasReservadas);
                for (let i = 0; i < fechasReservadas.length; i++) {
                    let elem = fechasReservadas[i];
                    mensaje += "\n" + elem[1] + " hasta " + elem[2];
                }

                if (fechasReservadas.length == 0) {
                    mensaje += "\nNo hay reservas para el mueble en la fecha seleccionada";       
                }

                alert(mensaje);
            })
            .catch(error => {
                console.log(error)
            });
        }
    });
});