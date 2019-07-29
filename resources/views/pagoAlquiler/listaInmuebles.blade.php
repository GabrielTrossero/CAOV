@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Datos del Alquiler Inmueble</b></label>
    </div>
    <div class="card-body border tam_letra_small">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI Solicitante</th>
            <th>Inmueble</th>
            <th>Fecha/Hora Inicio</th>
            <th>Fecha/Hora Finalización</th>
            <th>Costo Reserva</th>
            <th>Costo Total</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($reservasInmuebles as $reservaInmueble)
            <tr>
              <td>{{ $reservaInmueble->persona->DNI }}</td>
              <td>{{ $reservaInmueble->inmueble->nombre }}</td>
              <td>{{ $reservaInmueble->fechaHoraInicio }}</td>
              <td>{{ $reservaInmueble->fechaHoraFin }}</td>
              <td>{{ $reservaInmueble->costoReserva }}</td>
              <td>{{ $reservaInmueble->costoTotal }}</td>
              <td>
                <a href="{{ url('/pagoalquiler/pagoinmueble/'.$reservaInmueble->id) }}">
                  <button type="button" class="btn btn-primary tam_letra_x-small" style="width:50px; height:27px">
                    Pagar
                  </button>
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>


@stop
