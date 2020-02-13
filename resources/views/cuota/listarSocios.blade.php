@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Socios - Buscar Cuotas</b></label>
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
            <th>Último mes pagado</th>
            <th>Acción</th>
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
              @elseif ($socio->edad < 18)
                <td>{{ 'Cadete' }}</td>
              @else
                <td>{{ 'Activo' }}</td>
              @endif

              @if ($socio->fechaUltimoPago)
                <td>{{ date("d/m/Y", strtotime($socio->fechaUltimoPago)) }}</td>
              @else
                <td> - </td>
              @endif

              <td><a href="{{ url('/cuota/showSocioCuotas/'.$socio->id) }}"> <i class="fas fa-plus"></i></a> </td>

            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>


@stop
