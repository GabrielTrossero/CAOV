@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('/empleado/create') }}"><i class="icono3 fas fa-user-plus"></i> &nbsp; Agregar Empleado</a>
  <br>
  <a href="{{ url('/empleado/show') }}"><i class="icono3 fas fa-user-friends"></i> &nbsp; Listar Empleados</a>
</div>


@stop
