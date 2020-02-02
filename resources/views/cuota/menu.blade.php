@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('cuota/createMontoCuota') }}"><i class="icono3 fas fa-plus"></i> &nbsp; Generar Monto para Cuotas</a>
  <br>
  <a href="{{ url('cuota/showMontoCuota') }}"><i class="icono3 far fa-list-alt"></i> &nbsp; Listar Montos para Cuotas</a>
  <br>
  <a href="{{ url('cuota/showCreateCuota') }}"><i class="icono3 fas fa-file-medical"></i> &nbsp; Generar Adelanto de Cuota</a>
  <br>
  <a href="{{ url('cuota/show') }}"><i class="icono3 fas fa-list-ol"></i> &nbsp; Listar Cuotas</a>
</div>


@stop
