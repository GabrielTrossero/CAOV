<script src="http://code.jquery.com/jquery-latest.js"></script>

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
    <li class="submenu"><a href="#"> <i class="icono fas fa-warehouse"></i> &nbsp; Inventario/Salones</a>
      <ul class="children">
        <li><a href="{{ url('/inmueble') }}"> Inmueble </a></li>
        <li><a href="{{ url('/mueble') }}"> Mueble </a></li>
      </ul>
    </li>
    <li class="submenu"><a href="#"> <i class="icono fas fa-warehouse"></i> &nbsp; Alquileres</a>
      <ul class="children">
        <li><a href="{{ url('/alquilerinmueble') }}"> Inmueble </a></li>
        <li><a href="{{ url('/alquilermueble') }}"> Mueble </a></li>
      </ul>
    </li>
    <li class="submenu"><a href="#"> <i class="icono fas fa-file-invoice-dollar"></i> &nbsp; Pagos</a>
      <ul class="children">
        <li><a href="{{ url('/cuota') }}"> Cuota </a></li>
        <li><a href="{{ url('/pagoalquiler') }}"> Alquiler </a></li>
      </ul>
    </li>
    <li><a href="{{ url('/registros') }}"> <i class="icono far fa-clipboard"></i> &nbsp; Registros</a></li>
    <li><a href="{{ url('/informes') }}"> <i class="icono fas fa-chart-bar"></i> &nbsp; Informes</a></li>
    <li><a href="{{ url('/empleado') }}"> <i class="icono fas fa-users"></i> &nbsp; Empleados</a></li>
    <li><a href="{{ url('/deportes') }}"> <i class="icono far fa-clipboard"></i> &nbsp; Deportes</a></li>
    <li><a href="{{ url('/administrador') }}"> <i class="icono far fa-user"></i> &nbsp; Administradores</a></li>
  </ul>
</nav>

<script src="{!! asset('js/menu-desplegable.js') !!}"></script> <!--conexion a js para boton de despliegue-->
