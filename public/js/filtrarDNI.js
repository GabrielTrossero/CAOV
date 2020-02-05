'use strict'

/**
 * Filtrar datos de una tabla por numero de DNI que se ubica en la primer posicion
 * de la tabla a filtrar
 *
 * el input debe tener id -> filtroDNI
 * la tabla debe tener id -> tablaFiltroDNI
 */

var input = $("#filtroDNI"); //capturo el evento
    input.keyup(() => { //funcion que se ejecuta cada vez que se desencadena el evento
        var td, textValue;
        var filter = input.val(); //le asigno el valor ingresado a la variable
        filter.toUpperCase(); //paso la cadena a mayusculas

        var tr = $("#tablaFiltroDNI tr"); //capturo todos los tr que estan dentro de la tabla

        for (var i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0]; //captura por ej <td>36987425</td>

            if (td) {
                textValue = td.textContent || td.innerText; //capturo solo el valor sacando los "td"

                if (textValue.toUpperCase().indexOf(filter) == 0) { //si coinciden es 0
                    tr[i].style.display = ''; //lo muestro
                } else {
                    tr[i].style.display = 'none'; //lo oculto
                }
            }
        }
    });
