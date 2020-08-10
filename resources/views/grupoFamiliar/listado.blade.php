@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header row">
      <label class="col-md-9 col-form-label"><b>Listado de Grupos Familiares</b></label>
      <button class="btn btn-primary col-md-2">
        <a href="{{ url('/grupofamiliar/create') }}" style="text-decoration: none; color: white;">Agregar Grupo</a>
      </button>
    </div>
    <div class="card-body border">
      @if ($integrantesEliminados > 0)
        <div class="alert alert-warning">
          {{ 'Atención: se han eliminado '. $integrantesEliminados .' cadete/s de diferentes grupos por cumplir 18 años y pasar a ser activo/s.' }}
        </div>
      @endif
      @if ($gruposEliminados > 0)
        <div class="alert alert-warning">
          {{ 'Atención: se han eliminado '. $gruposEliminados .' grupo/s por tener un solo integrante.' }}
        </div>
      @endif

      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>Titular</th>
            <th>Ver Grupo Familiar</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($grupos as $grupo)
            <tr>
              <td>{{ $grupo->socioTitular->persona->DNI." - ".$grupo->socioTitular->persona->nombres." ".$grupo->socioTitular->persona->apellido }}</td>
              <td><a href="{{ url('/grupofamiliar/show/'.$grupo->id) }}"> <i class="fas fa-plus"></i></a> </td>
            </tr>
          @endforeach

        <tbody>
      </table>

      <div class="card-footer row">
        <div >
          <a style="text-decoration:none" href="{{ url('/grupofamiliar') }}">
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
