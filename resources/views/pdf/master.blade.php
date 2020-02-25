<html>
    <head>
        <title>CAOV - @yield('title')</title>
        <link href="css/pdf.css" rel="stylesheet">
    </head>
    <body>
        <header>
          <!--<a href="{{ url('/') }}"><img src="{!! asset('images/logo2.png') !!}" id="logo"> </a>-->
          <div id="titulo">
            Club Atletico Oro Verde
          </div>
          <div id="subtitulo">
            Sede Social: Los Jacarandaes 54
            <br>
            Campo de Deportes: El Trébol y Av. Los Cisnes
            <br>
            ---------------------------------------------------------------------------
            </div>
            Fútbol - Futsal - Patín Artístivo - Hockey S/Césped - Hockey S/Patín -
            <br>
             Vóley - Atletismo - Danzas Españolas - Bochas - Taekwondo -
            <br>
            Hándbol - Básquet - Softbol
        </header>

        <br><br><br><br><br><br>

        <div class="container">
            @yield('content')
        </div>


        <footer>
          <br><br>
          <hr>
          Los Jacarandaes 54 - Oro Verde - Entre Ríos (3101)
          <br>
          Tel: (0343) 407 41 58 - Cel (0343) 6 219 922 WhatsApp
        </footer>
    </body>
</html>
