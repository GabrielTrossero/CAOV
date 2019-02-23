@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('socio/create') }}"><i class="icono3 fas fa-user-plus"></i> &nbsp; Agregar Socio</a>
  <a href="{{ url('socio/show') }}"><i class="icono3 fas fa-user-friends"></i> &nbsp; Listar Socios</a>
</div>


@stop
