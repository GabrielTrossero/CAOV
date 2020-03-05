@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos del Alquiler Mueble</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <th>N° de Contrato Mueble</th>
          <th>DNI Solicitante</th>
          <th>Fecha Solicitud</th>
          <th>Mueble</th>
          <th>Cantidad</th>
          <th>Desde</th>
          <th>Hasta</th>
          <th>Costo</th>
          <th>N° Recibo</th>
          <th>Medio de Pago</th>
          <th>Observación</th>
        </tr>
        <tr>
          <td>{{ $reserva->id }}</td>
          <td>{{ $reserva->persona->DNI }}</td>
          <td>{{ date("d/m/Y", strtotime($reserva->fechaSolicitud)) }}</td>
          <td>{{ $reserva->mueble->nombre }}</td>
          <td>{{ $reserva->cantidad }}</td>
          <td>{{ date("d/m/Y H:i", strtotime($reserva->fechaHoraInicio)) }}</td>
          <td>{{ date("d/m/Y H:i", strtotime($reserva->fechaHoraFin)) }}</td>
          <td>{{ "$". $reserva->costoTotal }}</td>
          @if($reserva->numRecibo)
            <td>{{ $reserva->numRecibo }}</td>
          @else
            <td>-</td>
          @endif
          <td>{{ $reserva->medioDePago->nombre }}</td>
          <td>{{ $reserva->observacion }}</td>
        </tr>
      </table>

      <div class="card-footer">

        @if (!$reserva->numRecibo)
          <a style="text-decoration:none" href="{{ url('/pagoalquiler/pagomueble/'.$reserva->id) }}">
            <button type="button" class="btn btn-outline-primary" style="display:inline">
              Pagar Alquiler
            </button>
          </a>
        @else
          <form action="{{url('/pagoalquiler/pdf_alquiler_mueble/'.$reserva->id)}}" method="get" style="display:inline">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-outline-danger" style="display:inline">
              Generar PDF
            </button>
          </form>
        @endif

        &nbsp;&nbsp;
        <a style="text-decoration:none" href="{{ url('/alquilermueble/edit/'.$reserva->id) }}">
          <button type="button" class="btn btn-outline-warning" style="display:inline">
            Editar Alquiler
          </button>
        </a>

        &nbsp;&nbsp;
        <form action="{{url('/alquilermueble/delete')}}" method="post" style="display:inline">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="{{ $reserva->id }}">
          <button type="submit" class="btn btn-outline-danger" style="display:inline">
            Eliminar Alquiler
          </button>
        </form>
      </div>
    </div>
  </div>


  <br><br>


  <div class="card">
    <div class="card-header">Datos los Alquileres Relacionados</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <th>N° de Contrato Mueble</th>
          <th>DNI Solicitante</th>
          <th>Fecha Solicitud</th>
          <th>Mueble</th>
          <th>Cantidad</th>
          <th>Desde</th>
          <th>Hasta</th>
          <th>Costo</th>
          <th>N° Recibo</th>
          <th>Medio de Pago</th>
          <th>Observación</th>
        </tr>
        @foreach ($reservasRelacionadas as $reservaRelacionada)
        <tr>
          <td>{{ $reservaRelacionada->id }}</td>
          <td>{{ $reservaRelacionada->persona->DNI }}</td>
          <td>{{ date("d/m/Y", strtotime($reservaRelacionada->fechaSolicitud)) }}</td>
          <td>{{ $reservaRelacionada->mueble->nombre }}</td>
          <td>{{ $reservaRelacionada->cantidad }}</td>
          <td>{{ date("d/m/Y H:i", strtotime($reservaRelacionada->fechaHoraInicio)) }}</td>
          <td>{{ date("d/m/Y H:i", strtotime($reservaRelacionada->fechaHoraFin)) }}</td>
          <td>{{ "$". $reservaRelacionada->costoTotal }}</td>
          <td>{{ $reservaRelacionada->numRecibo }}</td>
          <td>{{ $reservaRelacionada->medioDePago->nombre }}</td>
          <td>{{ $reservaRelacionada->observacion }}</td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>


  <br><br>


  <div class="card">
    <div class="card-header">Resumen del Recibo</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <th>N° Recibo</th>
          <th>Mueble/Cantidad</th>
          <th>Costo Total</th>
        </tr>
        <tr>
          <td>{{ $reserva->numRecibo }}</td>
          <td>
            @foreach ($infoRecibo as $alquiler)
              {{ $alquiler->mueble->nombre ." - ". $alquiler->cantidad }}
              <br>
            @endforeach
          </td>
          <td>
            {{ "$". $total }}
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>


@stop
