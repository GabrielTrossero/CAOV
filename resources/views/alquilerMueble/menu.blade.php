@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('alquilermueble/create') }}"><i class="icono3 fas fa-user-plus"></i> &nbsp; Agregar Alquiler de Mueble</a>
  <a href="{{ url('alquilermueble/show') }}"><i class="icono3 fas fa-user-friends"></i> &nbsp; Listar Alquileres de Muebles</a>
</div>


@stop