//para el boton de abrir o cerrar el menu
$('#mostrar-nav').on('click',function(){
  $('nav').toggleClass('mostrar');
  if($('li').hasClass('abierto')){//para que el menú se "reinicie" cuado lo cierro
    $('li').children('.children').slideUp();
    $('li').removeClass('abierto');
  }
});



//funcion que se llama cuando hago click
$('.submenu').click(function(){
  if((!$(this).hasClass('abierto')) && ($('li').hasClass('abierto'))){//para cerrar un submenu abierto cuado quiero abrir otro
    $('li').children('.children').slideUp();
    $('li').removeClass('abierto');
  }

  //para cerrar o abrir un submenu cuando hago click
	$(this).children('.children').slideToggle();
  $(this).addClass('abierto');
});
