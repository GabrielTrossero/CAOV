@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('mueble/create') }}"><i class="icono3 fas fa-plus"></i> &nbsp; Agregar Mueble</a>
  <a href="{{ url('mueble/show') }}"><i class="icono3 fas fa-list-ol"></i> &nbsp; Listar Muebles</a>
</div>


@stop
