$(document).ready(function () {
    let boton = $("#chequear");

    scheduler.locale = {
        date:{
            month_full:["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
                "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            month_short:["Ene", "Feb", "Mar", "Abr", "May", "Jun", 
                "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            day_full:["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", 
                "Viernes", "Sabado"],
            day_short:["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"]
        },
        labels:{
            dhx_cal_today_button:"Hoy",
            day_tab:"Dia",
            week_tab:"Semana",
            month_tab:"Mes",
            icon_save:"Guardar ",
            icon_cancel:"Cancelar",
            icon_details:"Detalles",
            icon_edit:"Editar",
            icon_delete:"Borrar",
            section_description:"Descripción",
            section_time:"Tiempo",
        }
    };

    let sched = $("#scheduler");
    let botonOcultarScheduler = $("#ocultar-scheduler");

    botonOcultarScheduler.click(function () {
        sched.css("display", "none");
        botonOcultarScheduler.css("display", "none");
    });

    boton.click(function () { 
        let tipoAlquiler = $("#tipo").val();
        let token = $("#token").val();

        if (tipoAlquiler == "inmueble") {
            let fechaInicioIngresada = $("#chequear-fecha-inicio").val();
            let fechaFinIngresada = $("#chequear-fecha-fin").val();
            let inmuebleSeleccionado = $("#inmueble").val();
            let nombreInmueble = $("#inmueble option:selected").html();
            
            $.post('/alquilerinmueble/disponibilidad', {fechaInicio: fechaInicioIngresada, fechaFin: fechaFinIngresada, _token: token, inmueble: inmuebleSeleccionado})
            .done(function(data){
                let fechasReservadas = data.fechasReservadas;
                let fechasScheduler = [];

                for (let i = 0; i < fechasReservadas.length; i++) {
                    let elem = fechasReservadas[i];
                    fechasScheduler[i] = {start_date: elem[1], end_date: elem[2], text: nombreInmueble.toString()};
                }

                sched.css("display", "");
                scheduler.clearAll();
                scheduler.init('scheduler', Date.now());
                scheduler.parse(fechasScheduler);
                botonOcultarScheduler.css("display", "");
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
                    let fechasReservadas = data.fechasReservadas;
                    let fechasSolapadas = data.fechasSolapadas;
                    let fechasScheduler = [];

                    for (let i = 0; i < fechasReservadas.length; i++) {
                        let elem = fechasReservadas[i];
                        fechasScheduler[i] = {start_date: elem[1], end_date: elem[2], text: nombreMueble+". Cantidad: "+elem[3]};
                    }

                    if (fechasSolapadas.length) {
                        for (let i = 0; i < fechasSolapadas.length; i++) {
                            let elem = fechasSolapadas[i];
                            fechasScheduler[i] = {start_date: elem[1], end_date: elem[2], text: nombreMueble+". Cantidad: "+elem[3]};
                        }
                    }

                    sched.css("display", "");
                    scheduler.clearAll();
                    scheduler.init('scheduler', Date.now());
                    scheduler.parse(fechasScheduler);
                    botonOcultarScheduler.css("display", "");
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