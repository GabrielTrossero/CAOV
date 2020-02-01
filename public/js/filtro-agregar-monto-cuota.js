'use strict'

var tipoSocio = $("#tipo");

tipoSocio.change(function(){
  var accion = $("#tipo option:selected").val();

  if (accion == 'a') {
    $('#montoInteresGrupoFamiliar').prop('disabled', true);
    $('#cantidadIntegrantes').prop('disabled', true);
    $('#montoInteresGrupoFamiliar').prop('required', false);
    $('#cantidadIntegrantes').prop('required', false);
  }
  else if (accion == 'c') {
    $('#montoInteresGrupoFamiliar').prop('disabled', true);
    $('#cantidadIntegrantes').prop('disabled', true);
    $('#montoInteresGrupoFamiliar').prop('required', false);
    $('#cantidadIntegrantes').prop('required', false);
  }
  else if (accion == 'g') {
    $('#montoInteresGrupoFamiliar').prop('disabled', false);
    $('#cantidadIntegrantes').prop('disabled', false);
    $('#montoInteresGrupoFamiliar').prop('required', true);
    $('#cantidadIntegrantes').prop('required', true);
  }
});
