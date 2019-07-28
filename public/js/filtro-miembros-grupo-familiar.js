'use strict'

var selectAccion = $("#accionMiembro");
var selectMiembros = $("#miembro");
var miembrosActuales = $("#miembros-actuales");
var sociosSinGrupo = $("#socios-sin-grupo");

//por defecto no aparece el select de miembros
selectMiembros.css('display', 'none');

selectAccion.change(function(){
  var accion = $("#accionMiembro option:selected").val();
  accion = parseInt(accion);

  if (accion == 0) {
    selectMiembros.css('display', 'none');
  }
  else if (accion == 1) {
    selectMiembros.css('display', 'block');
    miembrosActuales.css('display', 'none');
    sociosSinGrupo.css('display', 'block');
  }
  else if (accion == 2) {
    selectMiembros.css('display', 'block');
    miembrosActuales.css('display', 'block');
    sociosSinGrupo.css('display', 'none');
  }
});
