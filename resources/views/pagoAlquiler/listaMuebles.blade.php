@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Lista de los Alquileres de Muebles</div>
    <div class="card-body border tam_letra_x-small">
      <table class="table">
        <tr>
          <td><b>N° de Contrato Mueble</b></td>    <!-- la <b> es para poner en negrita -->
          <td><b>DNI Solicitante</b></td>
          <td><b>Fecha Solicitud</b></td>
          <td><b>Cantidad Tablones</b></td>
          <td><b>Cantidad Sillas</b></td>
          <td><b>Cantidad Caballetes</b></td>
          <td><b>Fecha Realización</b></td>
          <td><b>Observación</b></td>
          <td><b>Costo Total</b></td>
          <td><b>N° Recibo</b></td>
          <td><b>Medio de Pago</b></td>
        </tr>
        <tr>
          <td>1</td>
          <td>40662158</td>
          <td>09/02/2019</td>
          <td>5</td>
          <td>30</td>
          <td>10</td>
          <td>05/05/2019</td>
          <td></td>
          <td>1200</td>
          <td>32</td>
          <td>Efectivo</td>
          <td>
            <a href="{{ url('/pagoalquiler/pagomueble/'.'1') }}">
              <button type="button" class="btn btn-primary tam_letra_x-small" style="width:50px; height:27px">
                Pagar
              </button>
            </a>
          </td>
        </tr>

        <tr>
          <td>2</td>
          <td>32569322</td>
          <td>17/02/2019</td>
          <td>20</td>
          <td>120</td>
          <td>40</td>
          <td>12/10/2019</td>
          <td></td>
          <td>3600</td>
          <td>39</td>
          <td>Tarjeta</td>
          <td>
            <a href="{{ url('/pagoalquiler/pagomueble/'.'1') }}">
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
