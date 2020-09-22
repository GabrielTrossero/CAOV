@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Alquileres de Muebles</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI Solicitante</th>
            <th>N° de Contrato Mueble</th>
            <th>Mueble</th>
            <th>Desde</th>
            <th>Hasta</th>
            <th>Costo Total</th>
            <th>N° Recibo</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($reservas as $reserva)
            <tr>
              <td>{{ $reserva->persona->DNI }}</td>
              <td>{{ $reserva->id }}</td>
              <td>{{ $reserva->mueble->nombre }}</td>
              <td>{{ date("d/m/Y H:i", strtotime($reserva->fechaHoraInicio)) }}</td>
              <td>{{ date("d/m/Y H:i", strtotime($reserva->fechaHoraFin)) }}</td>
              <td class="montos">{{ "$ ". $reserva->costoTotal }}</td>
              @if ($reserva->numRecibo)
                <td>{{ $reserva->numRecibo }}</td>
              @else
                <td>-</td>
              @endif
              <td><a href="{{ url('/alquilermueble/show/'.$reserva->id) }}"> <i class="fas fa-plus"></i></a> </td>
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
