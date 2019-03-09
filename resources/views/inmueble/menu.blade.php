@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('inmueble/create') }}"><i class="icono3 fas fa-plus"></i> &nbsp; Agregar Inmueble</a>
  <br>
  <a href="{{ url('inmueble/show') }}"><i class="icono3 fas fa-list-ol"></i> &nbsp; Listar Inmuebles</a>
</div>


@stop
