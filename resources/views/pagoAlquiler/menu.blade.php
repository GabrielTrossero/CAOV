@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('pagoalquiler/listamueble') }}"><i class="icono3 fas fa-chair"></i> &nbsp; Lista de Alquileres de Muebles</a>
  <a href="{{ url('pagoalquiler/listainmueble') }}"><i class="icono3 fas fa-ethernet"></i>&nbsp; Lista de Alquileres de Inmuebles</a>
</div>


@stop
