@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('informe/cantidad_socios_deporte') }}"><i class="icono3 fas fa-volleyball-ball"></i> &nbsp; Estadísticas de Deportes</a>
  <br>
  <a href="{{ url('informe/deudores') }}"><i class="icono3 fas fa-thumbs-down"></i> &nbsp; Socios Deudores</a>
  <br>
  <a href="{{ url('informe/cantidad_socios') }}"><i class="icono3 fas fa-user-friends"></i> &nbsp; Cantidad de Socios</a>
  <br>
  <a href="{{ url('informe/ingresos_egresos') }}"><i class="icono3 fas fa-hand-holding-usd"></i> &nbsp; Ingresos/Egresos</a>
  <br>
  <a href="{{ url('informe/pagos') }}"><i class="icono3 fas fa-money-check-alt"></i> &nbsp; Listar Pagos</a>

</div>


@stop
