@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Montos de Cuotas</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>Fecha de Creación</th>
            <th>Tipo</th>
            <th>Monto</th>
            <th>Descuento Mensual</th>
            <th>Descuento Anual</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($montosCuotas as $montoCuota)
            <tr>
              <td>{{ $montoCuota->fechaCreacion }}</td>

              @if ($montoCuota->tipo == 'a')
                <td>Activo</td>
              @elseif ($montoCuota->tipo == 'c')
                <td>Cadete</td>
              @elseif($montoCuota->tipo == 'g')
                  <td>Grupo Familiar</td>
              @endif

              <td>${{ $montoCuota->monto }}</td>
              <td>{{ $montoCuota->dtoSemestre }}%</td>
              <td>{{ $montoCuota->dtoAnio }}%</td>
            </tr>
          @endforeach

        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
