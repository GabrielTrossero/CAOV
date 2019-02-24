@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
          <label class="col-md-8 col-form-label"><b>Listado de Inmuebles</b></label>
    </div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>Nombre</b></td>
          <td><b>Descripción</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>Ver Inmueble</b></td>
        </tr>
        <tr>
          <td>Cancha</td>
          <td>150 X 30 metros</td>
          <td><a href="{{ url('/inmueble/show/'.'1') }}" style="color: red;">ver</a> </td>
        </tr>
        <tr>
          <td>SUM</td>
          <td>Capacidad para 100 personas</td>
          <td><a href="{{ url('/inmueble/show/'.'2') }}" style="color: red;">ver</a> </td>
        </tr>
      </table>
    </div>
  </div>
</div>

@stop
