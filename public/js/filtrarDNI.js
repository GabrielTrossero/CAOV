'use strict'

/**
 * Filtrar datos de una tabla por numero de DNI que se ubica en la primer posicion
 * de la tabla a filtrar
 *
 * el input debe tener id -> filtroDNI
 * la tabla debe tener id -> tablaFiltroDNI
 */
//  CON JavaScript PURO
/*
//capturo el elemento con tal id
var input = document.getElementById("filtroDNI");

//agrego el esuchador de eventos con la funcion onkeyup()
input.addEventListener('keyup', function(event) {
  // Declaro las variables
  var filter, table, tr, td, i, txtValue;

  //paso el contenido del elemento escuchado a MAYUS
  filter = input.value.toUpperCase();

  //capturo el elemento con tal id
  table = document.getElementById("tablaFiltroDNI");

  //capturo el elemento con tal etiqueta
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  //empiezo desde 1 para no filtrar los nombres de las columnas
  for (i = 1; i < tr.length; i++) {
    //capturo la columna 1 (segundo td) de la fila i
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      //tomo el valor del elemento de la posicion [i][0]
      txtValue = td.textContent || td.innerText;

      //si existe el input dentro del texto de el elemento de la posicion [i][0] muestra la fila
      if (txtValue.toUpperCase().indexOf(filter) == 0) {
        tr[i].style.display = "";
      } else {
        //si no existe el input oculta la fila i
        tr[i].style.display = "none";
      }
    }
  }
});
*/

// CON jQuery

var input = $("#filtroDNI");
    input.keyup(() => {
        var td, textValue;
        var filter = input.val();
        filter.toUpperCase();

        var tr = $("#tablaFiltroDNI tr");

        for (var i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];

            if (td) {
                textValue = td.textContent || td.innerText;

                if (textValue.toUpperCase().indexOf(filter) == 0) {
                    tr[i].style.display = '';
                } else {
                    tr[i].style.display = 'none';
                }
            }
        }
    });
