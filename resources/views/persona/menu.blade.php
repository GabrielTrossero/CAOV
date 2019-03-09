@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('persona/create') }}"><i class="icono3 fas fa-user-plus"></i> &nbsp; Agregar Persona</a>
  <br>
  <a href="{{ url('persona/show') }}"><i class="icono3 fas fa-user-friends"></i> &nbsp; Listar Personas</a>
</div>


@stop
