@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('informe/ingresos_egresos_diarios_generales') }}"><i class="icono3 fas fa-hand-holding-usd"></i> &nbsp; Ingresos/Egresos Diarios</a>
  <br>
  <a href="{{ url('informe/ingresos_egresos_semanales') }}"><i class="icono3 fas fa-hand-holding-usd"></i> &nbsp; Ingresos/Egresos Semanales</a>
  <br>
  <a href="{{ url('informe/ingresos_egresos_mensuales') }}"><i class="icono3 fas fa-hand-holding-usd"></i> &nbsp; Ingresos/Egresos Mensuales</a>
</div>

@stop
