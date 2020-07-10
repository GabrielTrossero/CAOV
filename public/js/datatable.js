$(document).ready(function() {
    //para ordenar correctamente por fecha dependiendo del formato
    $.fn.dataTable.moment('DD/MM/YYYY');
    $.fn.dataTable.moment('MM/YYYY');
    $.fn.dataTable.moment('DD/MM/YYYY HH:ss');

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

    $('#idDataTable3').DataTable({
      //para cambiar el lenguaje a español
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
      }
    });

    $('#idDataTable4').DataTable({
      //para cambiar el lenguaje a español
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
      }
    });

    $('#idDataTable5').DataTable({
      //para cambiar el lenguaje a español
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
      }
    });

    $('#idDataTable6').DataTable({
      //para cambiar el lenguaje a español
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
      }
    });

    $('#idDataTable7').DataTable({
      //para cambiar el lenguaje a español
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
      }
    });

    $('#idDataTable8').DataTable({
      //para cambiar el lenguaje a español
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
      }
    });
} );
