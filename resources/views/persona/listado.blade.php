@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Personas</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI</th>
            <th>Apellido</th>
            <th>Nombres</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($personas as $persona)

            <tr>
              <td>{{ $persona->DNI }}</td>
              <td>{{ $persona->apellido }}</td>
              <td>{{ $persona->nombres }}</td>
              <td><a href="{{ url('/persona/show/'.$persona->id) }}"> <i class="fas fa-plus"></i></a> </td>
            </tr>

          @endforeach
        </tbody>
      </table>

      <div class="card-footer row">
        <div >
          <a style="text-decoration:none" href="{{ url('/persona') }}">
            <button type="button" class="btn btn-secondary">
              Volver
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

@stop
