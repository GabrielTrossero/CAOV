$(document).ready(function () {
    let btnGuardar = $("#guardar-grupo");
    let selectPareja = $("#pareja");
    let selectCadetes = $("#miembros");

    selectCadetes.change(function () {
        chequearCantidadDeMiembros();
    });

    selectPareja.change(function () {
        chequearCantidadDeMiembros();
    });

    function chequearCantidadDeMiembros() {
        if ((parseInt(selectPareja.val()) != 0) || (selectCadetes.val().length > 0)) {
            btnGuardar.addClass("btn-primary");
            btnGuardar.removeClass("icono-editar-disabled");
            btnGuardar.removeAttr("disabled");
            btnGuardar.removeAttr("title");
        } else {
            btnGuardar.removeClass("btn-primary");
            btnGuardar.addClass("icono-editar-disabled");
            btnGuardar.attr("disabled", "disabled");
            btnGuardar.attr("title", "El grupo debe tener al menos 2 integrantes");
        }
    }
});

