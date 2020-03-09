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
            <th>Monto Mensual</th>
            <th>Interés Grupo Familiar</th>
            <th>Interés por retraso</th>
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

              <td>{{ '$'.$montoCuota->montoMensual }}</td>
              @if ($montoCuota->tipo == 'g')
                <td>{{ '$'.$montoCuota->montoInteresGrupoFamiliar.' por mes, a partir del integrante N° '.$montoCuota->cantidadIntegrantes }}</td>
              @else
                <td>{{ '-' }}</td>
              @endif

              <td>{{ '$'.$montoCuota->montoInteresMensual.' por mes, a partir del mes N° '.$montoCuota->cantidadMeses.' de atraso' }}</td>
            </tr>
          @endforeach

        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
