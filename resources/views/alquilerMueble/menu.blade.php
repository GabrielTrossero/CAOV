@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('alquilermueble/create') }}"><i class="icono3 fas fa-file-medical"></i> &nbsp; Agregar Alquiler de Mueble</a>
  <br>
  <a href="{{ url('alquilermueble/show') }}"><i class="icono3 fas fa-list-ol"></i> &nbsp; Listar Alquileres de Muebles</a>
</div>


@stop
