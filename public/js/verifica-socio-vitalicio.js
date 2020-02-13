'use strict'

let vitalicio = $("#vitalicio");
let grupo = $("#idGrupoFamiliar");

console.log(vitalicio.val());

if (vitalicio.val() == 's') {
    grupo.prop('disabled', true);
}

vitalicio.change(function () {
   if (vitalicio.val() == 'n') {
       grupo.prop('disabled', false);
   } 
   else if(vitalicio.val() == 's') {
       grupo.prop('disabled', true);
   } 
});