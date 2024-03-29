@auth

  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

  <div id="mostrar-nav">
    <i class="icono2 fas fa-bars"></i>
  </div>

  <nav>
    <ul class="menu">
      <li class="submenu"><a href="#"> <i class="icono far fa-address-card"></i> &nbsp; Personas </a>
        <ul class="children">
          <li><a href="{{ url('/persona') }}"> Personas </a></li>
          <li><a href="{{ url('/socio') }}"> Socios </a></li>
          <li><a href="{{ url('/grupofamiliar') }}"> Grupos Familiares </a></li>
        </ul>
      </li>
      @if (Auth::user()->idTipoUsuario == 1)
        <li class="submenu"><a href="#"> <i class="icono fas fa-warehouse"></i> &nbsp; Inventario/Salones</a>
          <ul class="children">
            <li><a href="{{ url('/inmueble') }}"> Inmueble </a></li>
            <li><a href="{{ url('/mueble') }}"> Mueble </a></li>
          </ul>
        </li>
      @endif
      <li><a href="{{ url('/cuota') }}"> <i class="icono fas fa-hands-helping"></i> &nbsp; Cuotas</a></li>
      <li class="submenu"><a href="#"> <i class="icono fas fa-handshake"></i></i> &nbsp; Alquileres</a>
        <ul class="children">
          <li><a href="{{ url('/alquilerinmueble') }}"> Inmueble </a></li>
          <li><a href="{{ url('/alquilermueble') }}"> Mueble </a></li>
        </ul>
      </li>
      <li><a href="{{ url('/registro') }}"> <i class="icono far fa-clipboard"></i> &nbsp; Registros</a></li>
      @if (Auth::user()->idTipoUsuario == 1)
        <li><a href="{{ url('/informe') }}"> <i class="icono fas fa-chart-bar"></i> &nbsp; Informes</a></li>
        <li><a href="{{ url('/empleado') }}"> <i class="icono fas fa-users"></i> &nbsp; Empleados</a></li>
        <li><a href="{{ url('/deporte') }}"> <i class="icono fas fa-futbol"></i> &nbsp; Deportes</a></li>
        <li><a href="{{ url('/administrador') }}"> <i class="icono far fa-user"></i> &nbsp; Administradores</a></li>
      @endif


    </ul>
  </nav>

  <script src="{!! asset('js/menu-desplegable.js') !!}"></script> <!--conexion a js para boton de despliegue-->

@endauth
