//para el boton de abrir o cerrar el menu
$('#mostrar-nav').on('click',function(){ //cuando se haga click a mostrar-nav que se ejecute la funcion
  $('nav').toggleClass('mostrar'); //si no tine la clase mostrar se la asigna, y si la tiene se la saca

  //para que el menú se "reinicie" cuado lo cierro
  if($('li').hasClass('abierto')){ //si algun submenú está abierto que se ejecute lo siguiente
    $('li').children('.children').slideUp(); //al elmento que tiene la clase children la funcion slideUp le pone style="display: none;" para ocultarla
    $('li').removeClass('abierto'); //sacarle la clase abierto al submenú
  }
});





//funcion que se llama cuando hago click
$('.submenu').click(function(){

  //para cerrar un submenu abierto cuado quiero abrir otro
  if((!$(this).hasClass('abierto')) && ($('li').hasClass('abierto'))){ //si un submenú está abierto y lo apreto para cerrarlo, que no se ejecute esto. Esta linea dice que si el submenú que selecciono está abierto y hay algun otro submenú que está abierto se ejecute lo siguiente
    $('li').children('.children').slideUp(); //al elmento que tiene la clase children la funcion slideUp le pone style="display: none;" para ocultarla
    $('li').removeClass('abierto'); //sacarle la clase abierto al submenú
  }

  //para cerrar o abrir un submenu cuando hago click
	$(this).children('.children').slideToggle(); //al hijo del submenú que se hizo click que ademas tiene la clase children se le ponga style="display: block;" cuando se aprieta y style="display: none;" cuando se apreta y ya está abierta para que la misma se oculte
  $(this).addClass('abierto'); //le agrego la clase abierto al submenú seleccionado (esto siempre se va a ejecutar para abrir un submenú por la condición del if)
});
