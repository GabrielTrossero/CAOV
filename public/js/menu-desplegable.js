//para el boton de abrir el menu
$('#mostrar-nav').on('click',function(){
  $('nav').toggleClass('mostrar');
});

//para abrir o cerrar el submenu
$('.submenu').click(function(){
	$(this).children('.children').slideToggle();
});
