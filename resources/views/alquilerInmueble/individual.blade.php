@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos del Alquiler Inmueble</div>
    <div class="card-body border tam_letra_x-small">
      <table class="table">
        <tr>
          <th>DNI Solicitante</th>
          <th>Inmueble</th>
          <th>Fecha de Solicitud</th>
          <th>Fecha/Hora Inicio</th>
          <th>Fecha/Hora Finalización</th>
          <th>Observación</th>
          <th>Costo Reserva</th>
          <th>Costo Total</th>
          <th>Medio de Pago</th>
          <th>Tipo de Evento</th>
          <th>Cantiad Asistentes</th>
          <th>Limpieza</th>
          <th>Música</th>
          <th>Reglamento</th>
          <th>N° Recibo</th>
        </tr>
        <tr>
          <td>{{ $reservaInmueble->persona->DNI }}</td>
          <td>{{ $reservaInmueble->inmueble->nombre }}</td>
          <td>{{ date("d/m/Y", strtotime($reservaInmueble->fechaSolicitud)) }}</td>
          <td>{{ date("d/m/Y H:i", strtotime($reservaInmueble->fechaHoraInicio)) }}</td>
          <td>{{ date("d/m/Y H:i", strtotime($reservaInmueble->fechaHoraFin)) }}</td>
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

        @if (!$reservaInmueble->numRecibo)
          <a style="text-decoration:none" href="{{ url('/pagoalquiler/pagoinmueble/'.$reservaInmueble->id) }}">
            <button type="button" class="btn btn-outline-primary" style="display:inline">
              Pagar Alquiler
            </button>
          </a>
        @else
          <form action="{{url('/pagoalquiler/pdf_alquiler_inmueble/'.$reservaInmueble->id)}}" method="get" style="display:inline">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-outline-danger" style="display:inline">
              Generar PDF
            </button>
          </form>
        @endif
        

        &nbsp;&nbsp;
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
