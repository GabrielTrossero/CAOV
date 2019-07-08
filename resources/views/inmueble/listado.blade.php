@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Inmuebles</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Cancha</td>
            <td>150 X 30 metros</td>
            <td><a href="{{ url('/inmueble/show/'.'1') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
          <tr>
            <td>SUM</td>
            <td>Capacidad para 100 personas</td>
            <td><a href="{{ url('/inmueble/show/'.'2') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
