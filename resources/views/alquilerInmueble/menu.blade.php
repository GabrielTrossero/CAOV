@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('alquilerinmueble/create') }}"><i class="icono3 fas fa-user-plus"></i> &nbsp; Agregar Alquiler de Inmueble</a>
  <a href="{{ url('alquilerinmueble/show') }}"><i class="icono3 fas fa-user-friends"></i> &nbsp; Listar Alquileres de inmuebles</a>
</div>


@stop
