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
          @foreach ($inmuebles as $inmueble)
            <tr>
              <td>{{ $inmueble->nombre }}</td>
              <td>{{ $inmueble->descripcion }}</td>
              <td><a href="{{ url('/inmueble/show/'.$inmueble->id) }}"> <i class="fas fa-plus"></i></a> </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
