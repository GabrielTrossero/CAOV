@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Alquileres de Inmuebles</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI Solicitante</th>
            <th>Inmueble</th>
            <th>Fecha/Hora Inicio</th>
            <th>Fecha/Hora Finalización</th>
            <th>Costo Reserva</th>
            <th>Costo Total</th>
            <th>N° Recibo</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($reservasInmuebles as $reservaInmueble)
            <tr>
              <td>{{ $reservaInmueble->persona->DNI }}</td>
              <td>{{ $reservaInmueble->inmueble->nombre }}</td>
              <td>{{ date("d/m/Y H:i", strtotime($reservaInmueble->fechaHoraInicio)) }}</td>
              <td>{{ date("d/m/Y H:i", strtotime($reservaInmueble->fechaHoraFin)) }}</td>
              <td>{{ $reservaInmueble->costoReserva }}</td>
              <td>{{ $reservaInmueble->costoTotal }}</td>
              @if ($reservaInmueble->numRecibo)
                <td>{{ $reservaInmueble->numRecibo }}</td>
              @else
                <td>-</td>
              @endif
              <td><a href="{{ url('/alquilerinmueble/show/'.$reservaInmueble->id) }}"> <i class="fas fa-plus"></i></a> </td>
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
