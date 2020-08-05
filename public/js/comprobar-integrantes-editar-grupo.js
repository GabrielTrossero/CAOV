$(document).ready(function () {
    let btnGuardar = $("#guardar-grupo");
    let selectPareja = $("#pareja");
    let selectCadetes = $("#miembros");
    let accionMiembros = $("#accionMiembro");
    let cantidadMiembrosActuales = selectCadetes.children("#miembros-actuales").children("option").length;
    let opcionSeleccionada = 0;

    selectCadetes.change(function () {
        if(opcionSeleccionada == 2) {
            chequearCantidadDeMiembros();
        }
    });

    selectPareja.change(function () {
        chequearCantidadDeMiembros();
    });

    accionMiembros.change(function () {
        opcionSeleccionada = parseInt($("#accionMiembro option:selected").val());
        chequearCantidadDeMiembros()
    });

    function chequearCantidadDeMiembros() {
        cantidadMiembrosAEliminar = selectCadetes.children("#miembros-actuales").children("option:selected").length;
        let pareja = parseInt(selectPareja.val());
        if ((pareja != 0) 
            || (cantidadMiembrosAEliminar > 0 && pareja != 0) 
            || (cantidadMiembrosAEliminar < cantidadMiembrosActuales)
            || (opcionSeleccionada != 2)) {
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
