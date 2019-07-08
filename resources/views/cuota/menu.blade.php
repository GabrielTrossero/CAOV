@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('cuota/createMontoCuota') }}"><i class="icono3 fas fa-plus"></i> &nbsp; Agregar Monto para Cuotas</a>
  <br>
  <a href="{{ url('cuota/show') }}"><i class="icono3 fas fa-list-ol"></i> &nbsp; Listar Cuotas Pagadas</a>
</div>


@stop
