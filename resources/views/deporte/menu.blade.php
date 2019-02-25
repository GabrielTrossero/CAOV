@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('deporte/create') }}"><i class="icono3 fas fa-plus"></i> &nbsp; Agregar Deporte</a>
  <a href="{{ url('deporte/show') }}"><i class="icono3 fas fa-list-ol"></i> &nbsp; Listar Deportes</a>
</div>


@stop
