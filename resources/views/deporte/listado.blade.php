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
          @foreach ($deportes as $deporte)
            <tr>
              <td>{{ $deporte->nombre }}</td>
              <td><a href="{{ url('/deporte/show/'.$deporte->id) }}"> <i class="fas fa-plus"></i></a> </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
