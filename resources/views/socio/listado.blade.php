@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Socios</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI</th>
            <th>Numero de Socio</th>
            <th>Apellido</th>
            <th>Nombres</th>
            <th>Categoria</th>
            <th>Deportes</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($socios as $socio)
            <tr>
              <td>{{ $socio->persona->DNI }}</td>
              <td>{{ $socio->numSocio }}</td>
              <td>{{ $socio->persona->apellido }}</td>
              <td>{{ $socio->persona->nombres }}</td>

              @if ($socio->vitalicio == 's')
                <td>{{ 'Vitalicio' }}</td>
              @elseif ($socio->idGrupoFamiliar)
                <td>{{ 'Grupo Familiar' }}</td>
              @else
                <td>{{ 'Activo' }}</td>
              @endif

              <td>
                @foreach ($socio->deportes as $deporte)
                  {{ $deporte->nombre }}
                  <br>
                @endforeach
              </td>

              <td><a href="{{ url('/socio/show/'.$socio->id) }}"> <i class="fas fa-plus"></i></a> </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
