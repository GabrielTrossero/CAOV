@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label><b>Listado de Deportes</b></label>
    </div>
    <div class="card-body border">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Futbol</td>
            <td><a href="{{ url('/deporte/show/'.'1') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
          <tr>
            <td>Basquet</td>
            <td><a href="{{ url('/deporte/show/'.'2') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
