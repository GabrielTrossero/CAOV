@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Empleados</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI</th>
            <th>Nombre de Usuario</th>
            <th>Apellido</th>
            <th>Nombres</th>
            <th>Tipo de Usuario</th>
            <th>Activo</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($usuarios as $usuario)
            <tr>
              <td>{{ $usuario->persona->DNI }}</td>
              <td>{{ $usuario->username }}</td>
              <td>{{ $usuario->persona->apellido }}</td>
              <td>{{ $usuario->persona->nombres }}</td>
              <td>{{ $usuario->tipoUsuario->nombre}}</td>
              @if ($usuario->activo)
                  <td>Si</td>
              @else
                  <td>No</td>
              @endif
              <td><a href="{{ url('/empleado/show/'.$usuario->id) }}"> <i class="fas fa-plus"></i></a> </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="card-footer row">
        <div >
          <a style="text-decoration:none" onclick="history.back()">
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
