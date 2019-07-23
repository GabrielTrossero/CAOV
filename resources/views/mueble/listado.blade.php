@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Muebles</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Cantidad</th>
            <th>Más Información</th>
          </tr>
        </thead>
          <tbody>
            @foreach ($muebles as $mueble)

              <tr>
                <td>{{ $mueble->nombre }}</td>
                <td>{{ $mueble->cantidad }}</td>
                <td><a href="{{ url('/mueble/show/'.$mueble->id) }}"> <i class="fas fa-plus"></i></a> </td>
              </tr>

            @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
