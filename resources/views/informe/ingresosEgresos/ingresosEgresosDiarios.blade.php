@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="card">
      <div class="card-header">
        <label class="col-md-8 col-form-label"><b>Listado de Ingresos/Egresos Diarios del <span style="color: red;">{{ date("d/m/Y", strtotime($fecha)) }}</span></b></label>
      </div>
      <div class="card-body border">
        <table id="idDataTable" class="table table-striped">
          <thead>
            <tr>
              <th>Tipo</th>
              <th>Numero de Recibo</th>
              <th>Descripcion</th>
              <th>Monto</th>
            </tr>
          </thead>
          <tbody>
  
            @foreach ($movExtras as $movExtra)
              <tr>
                @if ($movExtra->tipo == "1")
                  <td>{{ 'Ingreso' }}</td>
                @elseif ($movExtra->tipo == "2")
                  <td>{{ 'Egreso' }}</td>
                @endif
                <td>{{ $movExtra->numRecibo }}</td>
                <td>{{ $movExtra->descripcion }}</td>
                @if ($movExtra->tipo == "1")
                  <td>{{ '$'.$movExtra->monto }}</td>
                @elseif($movExtra->tipo == "2")
                  <td>{{ '- $'.$movExtra->monto }}</td>
                @endif
                
              </tr>
            @endforeach
            @foreach ($alquileresInmueblePagos as $alquilerInmueble)
              <tr>
                <td>{{ 'Ingreso' }}</td>
                <td>{{ $alquilerInmueble->numRecibo }}</td>
                <td>{{ 'Alquileres de Inmuebles' }}</td>
                <td>{{ '$'.$alquilerInmueble->costoTotal }}</td>
              </tr>
            @endforeach
            @foreach ($alquileresMueblePagos as $alquilerMueble)
              <tr>
                <td>{{ 'Ingreso' }}</td>
                <td>{{ $alquilerMueble->numRecibo }}</td>
                <td>{{ 'Alquileres de Muebles' }}</td>
                <td>{{ '$'.$alquilerMueble->costoTotal }}</td>
              </tr>
            @endforeach
            @foreach ($cuotasPagadas as $cuota)
              <tr>
                <td>{{ 'Ingreso' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ 'Cuota' }}</td>
                <td>{{ '$'.$cuota->montoTotal }}</td>
              </tr>
            @endforeach
  
          </tbody>
        </table>

        <div class="card-footer row">
          <div >
            <a style="text-decoration:none" href="{{ url('/informe/ingresos_egresos_diarios_generales') }}">
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