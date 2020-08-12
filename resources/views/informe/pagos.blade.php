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
            <th>Fecha</th>
            <th>Descripcion</th>
            <th>Monto</th>
            <th>Numero de Recibo</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cuotasPagadas as $cuotaPagada)
            <tr>
              <td>{{ date("d/m/Y", strtotime($cuotaPagada->fechaPago)) }}</td>
              <td>Cuota</td>
              <td>${{ $cuotaPagada->montoTotal }}</td>
              <td>-</td>
            </tr>
          @endforeach

          @foreach ($reservasInmueble as $reservaInmueble)
            <tr>
              <td>{{ date("d/m/Y", strtotime($reservaInmueble->fechaSolicitud)) }}</td>
              <td>Alquiler de {{ $reservaInmueble->inmueble->nombre }}</td>
              <td>${{ $reservaInmueble->costoTotal }}</td>
              <td>{{ $reservaInmueble->numRecibo }}</td>
            </tr>
          @endforeach

          @foreach ($reservasMueble as $reservaMueble)
            <tr>
              <td>{{ date("d/m/Y", strtotime($reservaMueble->fechaSolicitud)) }}</td>
              <td>{{ $reservaMueble->mueble->nombre . " - " . $reservaMueble->cantidad }} Unidade/s</td>
              <td>${{ $reservaMueble->costoTotal }}</td>
              <td>{{ $reservaMueble->numRecibo }}</td>
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

        <div class="col-md-10 text-md-center">
          <form action="{{url('/informe/pdf_pagos')}}" method="get" style="display:inline">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-outline-danger" style="display:inline">
              Generar PDF
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@stop
