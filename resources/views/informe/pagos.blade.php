@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
          <label class="col-md-8 col-form-label"><b>Listado de Pagos</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>Numero de Recibo</th>
            <th>Fecha</th>
            <th>Descripcion</th>
            <th>Monto</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cuotasPagadas as $cuotaPagada)
            <tr>
              <td>-</td>
              <td>{{ date("d/m/Y", strtotime($cuotaPagada->fechaPago)) }}</td>
              <td>Cuota</td>
              <td>${{ $cuotaPagada->montoTotal }}</td>
            </tr>
          @endforeach

          @foreach ($reservasInmueble as $reservaInmueble)
            <tr>
              <td>{{ $reservaInmueble->numRecibo }}</td>
              <td>{{ date("d/m/Y", strtotime($reservaInmueble->fechaSolicitud)) }}</td>
              <td>Alquiler de {{ $reservaInmueble->inmueble->nombre }}</td>
              <td>${{ $reservaInmueble->costoTotal }}</td>
            </tr>
          @endforeach

          @foreach ($reservasMueble as $reservaMueble)
            <tr>
              <td>{{ $reservaMueble->numRecibo }}</td>
              <td>{{ date("d/m/Y", strtotime($reservaMueble->fechaSolicitud)) }}</td>
              <td>{{ $reservaMueble->mueble->nombre . " - " . $reservaMueble->cantidad }} Unidade/s</td>
              <td>${{ $reservaMueble->costoTotal }}</td>
            </tr>
          @endforeach

        </tbody>
      </table>
    </div>

    <div class="card-footer">
      <form action="{{url('/informe/pdf_pagos')}}" method="get" style="display:inline">
        {{ csrf_field() }}
        <button type="submit" class="btn btn-outline-danger" style="display:inline">
          Generar PDF
        </button>
      </form>
    </div>

  </div>
</div>

@stop
