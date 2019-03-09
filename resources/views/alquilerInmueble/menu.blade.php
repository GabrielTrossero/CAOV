@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('alquilerinmueble/create') }}"><i class="icono3 fas fa-file-medical"></i> &nbsp; Agregar Alquiler de Inmueble</a>
  <br>
  <a href="{{ url('alquilerinmueble/show') }}"><i class="icono3 fas fa-list-ol"></i> &nbsp; Listar Alquileres de inmuebles</a>
</div>


@stop
