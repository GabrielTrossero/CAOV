<!doctype html>
<html lang="es">
  <header>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!--conexion a los estilos css-->
    <link rel="stylesheet" href="{!! asset('css/style.css') !!}">

    <!--conexion para usar los iconos-->
    <script defer src="https://use.fontawesome.com/releases/v5.7.0/js/all.js" integrity="sha384-qD/MNBVMm3hVYCbRTSOW130+CWeRIKbpot9/gR1BHkd7sIct4QKhT1hOPd+2hO8K" crossorigin="anonymous"></script>



    @include('partials.header')
    <title>CAOV</title>
  </header>
  <body>
    <div class="container">
      @include('partials.menuDesplegable')
      @yield('content')     <!--acá es sustituido por el contenido que se le indique -->
    </div>

    @include('partials.footer')



    <!-- Links de dataTable -->
    <link rel="stylesheet" href="{!! asset('datatable/jquery.dataTables.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('datatable/dataTables.bootstrap4.min.css') !!}">
    <script src="{{ asset('datatable/jquery-3.3.1.js') }}"></script>
    <script src="{{ asset('datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('datatable/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Link de js para la dataTable -->
    <script src="{{ asset('js/datatable.js') }}"></script>


    <script src="{!! asset('js/montos.js') !!}"></script> <!--conexion a js que es utilizado en las vistas de Cuota-->
    <script src="{!! asset('js/editarCuota.js') !!}"></script> <!--conexion a js que es utilizado en las vistas de Cuota-->
    <script src="http://momentjs.com/downloads/moment.min.js"></script>

  </body>
</html>
