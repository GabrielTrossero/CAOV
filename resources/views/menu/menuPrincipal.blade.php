@extends('layouts.master')

@section('content')

<script src="http://code.jquery.com/jquery-latest.js"></script>

<div id="mostrar-nav">
  <i class="icono2 fas fa-bars"></i>
</div>

<nav>
  <ul class="menu">
    <li class="submenu"><a href="#"> <i class="icono far fa-address-card"></i> &nbsp; Socios</a>
      <ul class="children">
        <li><a href="#"> Socios </a></li>
        <li><a href="#"> Grupos Familiares </a></li>
      </ul>
    </li>
    <li class="submenu"><a href="#"> <i class="icono fas fa-warehouse"></i> &nbsp; Alquileres</a>
      <ul class="children">
        <li><a href="#"> Inmueble </a></li>
        <li><a href="#"> Mueble </a></li>
      </ul>
    </li>
    <li class="submenu"><a href="#"> <i class="icono fas fa-file-invoice-dollar"></i> &nbsp; Pagos</a>
      <ul class="children">
        <li><a href="#"> Cuota </a></li>
        <li><a href="#"> Alquiler </a></li>
      </ul>
    </li>
    <li><a href="#"> <i class="icono far fa-clipboard"></i> &nbsp; Registros</a></li>
    <li><a href="#"> <i class="icono fas fa-chart-bar"></i> &nbsp; Informes</a></li>
    <li><a href="#"> <i class="icono fas fa-users"></i> &nbsp; Empleados</a></li>
    <li><a href="#"> <i class="icono far fa-user"></i> &nbsp; Administradores</a></li>
  </ul>
</nav>

<script src="{!! asset('js/menu-desplegable.js') !!}"></script> <!--conexion a js para boton de despliegue-->

@stop
