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
            <th>N° de Contrato Inmueble</th>
            <th>Costo Reserva</th>
            <th>Costo Total</th>
            <th>Medio de Pago</th>
            <th>Horario</th>
            <th>Tipo de Evento</th>
            <th>Cantiad Asistentes</th>
            <th>N° Recibo</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>40662158</td>
            <td>1</td>
            <td>1000</td>
            <td>3000</td>
            <td>Tarjeta</td>
            <td>18 a 21</td>
            <td>Cumpleaños</td>
            <td>30</td>
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
            <td>1000</td>
            <td>4000</td>
            <td>Tarjeta</td>
            <td>21 a 23</td>
            <td>Cumpleaños</td>
            <td>50</td>
            <td>10</td>
            <td>
              <a href="{{ url('/pagoalquiler/pagoinmueble/'.'1') }}">
                <button type="button" class="btn btn-primary tam_letra_x-small" style="width:50px; height:27px">
                  Pagar
                </button>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>


@stop
