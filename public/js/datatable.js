$(document).ready(function() {
    //para ordenar correctamente por fecha dependiendo del formato
    $.fn.dataTable.moment('d/MM/YYYY');
    $.fn.dataTable.moment('MM/YYYY');

    //SE TIENEN VARIOS idDataTable PARA LOS CASOS EN QUE SE PUEDEN SELECCIONAR MÁS DE UNA TABLA
    $('#idDataTable').DataTable({
      //para cambiar el lenguaje a español
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
      }
    });

    $('#idDataTable2').DataTable({
      //para cambiar el lenguaje a español
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
      }
    });
} );
