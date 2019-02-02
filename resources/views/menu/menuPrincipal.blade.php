@extends('layouts.master')

@section('content')

<div id="mostrar-nav">
  <i class="icono2 fas fa-bars"></i>
</div>

<nav>
  <ul class="menu">
    <li class="activado"><a href="#"> <i class="icono far fa-address-card"></i> &nbsp; Socios</a></li>
    <li><a href="#"> <i class="icono fas fa-warehouse"></i> &nbsp; Alquileres</a></li>
    <li><a href="#"> <i class="icono fas fa-file-invoice-dollar"></i> &nbsp; Pagos</a></li>
    <li><a href="#"> <i class="icono far fa-clipboard"></i> &nbsp; Registros</a></li>
    <li><a href="#"> <i class="icono fas fa-chart-bar"></i> &nbsp; Informes</a></li>
    <li><a href="#"> <i class="icono fas fa-users"></i> &nbsp; Empleados</a></li>
    <li><a href="#"> <i class="icono far fa-user"></i> &nbsp; Administradores</a></li>
  </ul>
</nav>

<script src="{!! asset('js/mostrar-nav.js') !!}"></script> <!--conexion a js para boton de despliegue-->

@stop
