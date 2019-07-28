@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos del Alquiler Inmueble</div>
    <div class="card-body border tam_letra_x-small">
      <table class="table">
        <tr>
          <!-- la <b> es para poner en negrita -->
          <td><b>DNI Solicitante</b></td>
          <td><b>Inmueble</b></td>
          <td><b>Fecha de Solicitud</b></td>
          <td><b>Fecha/Hora Inicio</b></td>
          <td><b>Fecha/Hora Finalización</b></td>
          <td><b>Observación</b></td>
          <td><b>Costo Reserva</b></td>
          <td><b>Costo Total</b></td>
          <td><b>Medio de Pago</b></td>
          <td><b>Tipo de Evento</b></td>
          <td><b>Cantiad Asistentes</b></td>
          <td><b>Limpieza</b></td>
          <td><b>Música</b></td>
          <td><b>Reglamento</b></td>
          <td><b>N° Recibo</b></td>
        </tr>
        <tr>
          <td>{{ $reservaInmueble->persona->DNI }}</td>
          <td>{{ $reservaInmueble->inmueble->nombre }}</td>
          <td>{{ $reservaInmueble->fechaSolicitud }}</td>
          <td>{{ $reservaInmueble->fechaHoraInicio }}</td>
          <td>{{ $reservaInmueble->fechaHoraFin }}</td>
          <td>{{ $reservaInmueble->observacion }}</td>
          <td>{{ $reservaInmueble->costoReserva }}</td>
          <td>{{ $reservaInmueble->costoTotal }}</td>
          <td>{{ $reservaInmueble->medioDePago->nombre }}</td>
          <td>{{ $reservaInmueble->tipoEvento }}</td>
          <td>{{ $reservaInmueble->cantAsistentes }}</td>
          @if ($reservaInmueble->tieneServicioLimpieza)
            <td>Si</td>
          @else
            <td>No</td>
          @endif
          @if ($reservaInmueble->tieneMusica)
            <td>Si</td>
          @else
            <td>No</td>
          @endif
          @if ($reservaInmueble->tieneReglamento)
            <td>Si</td>
          @else
            <td>No</td>
          @endif
          @if ($reservaInmueble->numRecibo)
            <td>{{ $reservaInmueble->numRecibo }}</td>
          @else
            <td>-</td>
          @endif
        </tr>
      </table>

      <div class="card-footer">

        <a style="text-decoration:none" href="{{ url('/alquilerinmueble/edit/'.$reservaInmueble->id) }}">
          <button type="button" class="btn btn-outline-warning" style="display:inline">
            Editar Alquiler
          </button>
        </a>

        &nbsp;&nbsp;
        <form action="{{url('/alquilerinmueble/delete')}}" method="post" style="display:inline">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="{{ $reservaInmueble->id }}">
          <button type="submit" class="btn btn-outline-danger" style="display:inline">
            Eliminar Alquiler
          </button>
        </form>
      </div>

    </div>
  </div>
</div>


@stop
