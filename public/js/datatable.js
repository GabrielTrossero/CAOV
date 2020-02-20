$(document).ready(function() {
    //para ordenar correctamente por fecha dependiendo del formato
    $.fn.dataTable.moment('d/MM/YYYY');
    $.fn.dataTable.moment('MM/YYYY');

    $('#idDataTable').DataTable({
      //para cambiar el lenguaje a español
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
      }
    });
} );
