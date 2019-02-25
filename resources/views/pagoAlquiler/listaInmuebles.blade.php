@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">

    <div class="card-header">
      <table>
        <div class="form-group row">
          <label class="col-md-8 col-form-label"><b>Datos del Alquiler Inmueble</b></label>
            <div class="col-md-3">
              <input type="text" name="buscar" id="filtroDNI" class="form-control" placeholder="Filtrar DNI">
            </div>
        </div>
      </table>
    </div>

    <div class="card-body border tam_letra_xx-small">
      <table class="table" id="tablaFiltroDNI">
        <tr>
          <td><b>DNI Solicitante</b></td>    <!-- la <b> es para poner en negrita -->
          <td><b>N° de Contrato Inmueble</b></td>
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
          <td>40662158</td>
          <td>1</td>
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
          <td>
            <a href="{{ url('/pagoalquiler/pagoinmueble/'.'1') }}">
              <button type="button" class="btn btn-primary tam_letra_x-small" style="width:50px; height:27px">
                Pagar
              </button>
            </a>
          </td>
        </tr>
        <tr>
          <td>23654128</td>
          <td>2</td>
          <td>23/11/2018</td>
          <td>25/08/2019</td>
          <td></td>
          <td>1000</td>
          <td>4000</td>
          <td>Tarjeta</td>
          <td>21 a 23</td>
          <td>Cumpleaños</td>
          <td>50</td>
          <td>No</td>
          <td>Si</td>
          <td>Si</td>
          <td>10</td>
          <td>
            <a href="{{ url('/pagoalquiler/pagoinmueble/'.'1') }}">
              <button type="button" class="btn btn-primary tam_letra_x-small" style="width:50px; height:27px">
                Pagar
              </button>
            </a>
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>


@stop
