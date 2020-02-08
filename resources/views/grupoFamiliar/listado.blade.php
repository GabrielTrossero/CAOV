@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <table>
        <label class="col-md-8 col-form-label"><b>Listado de Grupos Familiares</b></label>
      </table>
    </div>
    <div class="card-body border">
      @if (isset($gruposActualizados))
          <div class="alert alert-warning">
            {!! $gruposActualizados !!}
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
    </div>
  </div>
</div>

@stop
