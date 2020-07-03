@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header row"> <!--row me permite mantener los dos label en la misma linea-->
      <label class="col-md-9 col-form-label" id="nomTablaActual"><b>Listado de Montos de Cuotas (Activas)</b></label>
      <label class="col-md-9 col-form-label" id="nomTablaHistorica" style="display:none"><b>Listado de Montos de Cuotas (Historicas)</b></label>
      <label class="col-md-3 col-form-label">
        <select class="form-control" id="filtroTabla">
          <option value="activa" selected>Activas</option>
          <option value="historica">Historicas</option>
        </select>
      </label>
    </div>
    <div class="card-body border" id="tablaActual">
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
          @foreach ($montosActuales as $montoCuota)
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

    <div class="card-body border" id="tablaHistorica" style="display:none">
      <table id="idDataTable2" class="table table-striped">
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
          @foreach ($montosHistoricos as $montoCuota)
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


<script src="{!! asset('js/filtrarTablasMontoCuota.js') !!}"></script> <!--conexion a js que es utilizada para filtar las tablas-->

@stop
