@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('/grupofamiliar/create') }}"><i class="icono3 fas fa-user-plus"></i> &nbsp; Agregar Grupo Familiar</a>
  <br>
  <a href="{{ url('/grupofamiliar/show') }}"><i class="icono3 fas fa-user-friends"></i> &nbsp; Listar Grupos Familiares</a>
</div>


@stop
