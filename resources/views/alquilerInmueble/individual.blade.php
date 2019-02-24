@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos del Alquiler Inmueble</div>
    <div class="card-body border tam_letra">
      <table class="table">
        <tr>
          <td><b>N° de Contrato Inmueble</b></td>    <!-- la <b> es para poner en negrita -->
          <td><b>DNI Solicitante</b></td>
          <td><b>Fecha Solicitud</b></td>
          <td><b>Fecha Realización</b></td>
          <td><b>Observación</b></td>
          <td><b>Costo Reserva</b></td>
          <td><b>Costo Total</b></td>
          <td><b>Medio de Pago</b></td>
          <td><b>Horario</b></td>
          <td><b>Tipo de Evento</b></td>
          <td><b>Cantiad Asistentes</b></td>
          <td><b>Limpieza</b></td>
          <td><b>Música</b></td>
          <td><b>Reglamento</b></td>
          <td><b>N° Recibo</b></td>
        </tr>
        <tr>
          <td>1</td>
          <td>40662158</td>
          <td>09/02/2019</td>
          <td>25/08/2019</td>
          <td></td>
          <td>1000</td>
          <td>3000</td>
          <td>Tarjeta</td>
          <td>18 a 21</td>
          <td>Cumpleaños</td>
          <td>30</td>
          <td>No</td>
          <td>Si</td>
          <td>Si</td>
          <td>18</td>
        </tr>
      </table>

      <div class="card-footer">

        <a style="text-decoration:none" href="{{ url('/alquilerinmueble/edit/'.'1') }}">
          <button type="button" class="btn btn-outline-warning" style="display:inline">
            Editar Alquiler
          </button>
        </a>

        &nbsp;&nbsp;
        <form action="{{url('/alquilerinmueble/delete')}}" method="post" style="display:inline">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="1">
          <button type="submit" class="btn btn-outline-danger" style="display:inline">
            Eliminar Alquiler
          </button>
        </form>
      </div>

    </div>
  </div>
</div>


@stop
