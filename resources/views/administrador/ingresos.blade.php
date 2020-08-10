@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
          <label class="col-md-8 col-form-label"><b>Listado de Ingresos</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>Numero de Recibo</th>
            <th>Descripcion</th>
            <th>Fecha</th>
            <th>Monto</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($movimientos as $movimiento)
            <tr>
              <td>{{ $movimiento->numRecibo }}</td>
              <td>{{ $movimiento->descripcion }}</td>
              <td>{{ date("d/m/Y", strtotime($movimiento->fecha)) }}</td>
              <td>${{ $movimiento->monto }}</td>
            </tr>
          @endforeach
          
          @foreach ($reservasInmueble as $reservaInmueble)
            <tr>
              <td>{{ $reservaInmueble->numRecibo }}</td>
              <td>Alquiler de {{ $reservaInmueble->inmueble->nombre }}</td>
              <td>{{ date("d/m/Y", strtotime($reservaInmueble->fechaSolicitud)) }}</td>
              <td>${{ $reservaInmueble->costoTotal }}</td>
            </tr>
          @endforeach
          
          @foreach ($reservasMueble as $reservaMueble)
            <tr>
              <td>{{ $reservaMueble->numRecibo }}</td>
              <td>Alquiler de {{ $reservaMueble->cantidad ." ". $reservaMueble->mueble->nombre }}</td>
              <td>{{ date("d/m/Y", strtotime($reservaMueble->fechaSolicitud)) }}</td>
              <td>${{ $reservaMueble->costoTotal }}</td>
            </tr>
          @endforeach

          @foreach ($cuotasPagadas as $cuotaPagada)
            <tr>
              <td>-</td>
              <td>Cuota</td>
              <td>{{ date("d/m/Y", strtotime($cuotaPagada->fechaPago)) }}</td>
              <td>${{ $cuotaPagada->montoTotal }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="card-footer row">
        <div >
          <a style="text-decoration:none" href="{{ url('/administrador') }}">
            <button type="button" class="btn btn-secondary">
              Volver
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

@stop
