@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('registro/create') }}"><i class="icono3 fas fa-plus"></i> &nbsp; Agregar Registro</a>
  <br>
  <a href="{{ url('registro/show') }}"><i class="icono3 far fa-list-alt"></i> &nbsp; Listar Registros</a>
</div>


@stop
