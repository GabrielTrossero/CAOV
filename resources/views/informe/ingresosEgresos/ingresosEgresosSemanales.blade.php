@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="card">
      <div class="card-header">
        <label class="col-md-8 col-form-label"><b>Listado de Ingresos/Egresos Semanales <span style="color: red;">{{ $semanaAnio }} (Semana - Año)</span></b></label>
      </div>
      <div class="card-body border">
        <table id="idDataTable" class="table table-striped">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Tipo</th>
              <th>Monto</th>
              <th>Descripcion</th>
              <th>Numero de Recibo</th>
              <th>Más Información</th>
            </tr>
          </thead>
          <tbody>
  
            @foreach ($movExtras as $movExtra)
              <tr>
                <td>{{ date("d/m/Y", strtotime($movExtra->fecha)) }}</td>
                @if ($movExtra->tipo == "1")
                  <td>{{ 'Ingreso' }}</td>
                @elseif ($movExtra->tipo == "2")
                  <td>{{ 'Egreso' }}</td>
                @endif
                <td class="montos">{{ '$ '.$movExtra->monto }}</td>
                <td>{{ $movExtra->descripcion }}</td>
                <td>{{ $movExtra->numRecibo }}</td>
                <td><a href="{{ url('/registro/show') }}"> <i class="fas fa-plus"></i></a> </td>
              </tr>
            @endforeach

            @foreach ($alquileresInmueblePagos as $alquilerInmueble)
              <tr>
                <td>{{ date("d/m/Y", strtotime($alquilerInmueble->fechaSolicitud)) }}</td>
                <td>{{ 'Ingreso' }}</td>
                <td class="montos">{{ '$ '.$alquilerInmueble->costoTotal }}</td>
                <td>{{ 'Alquileres de Inmuebles' }}</td>
                <td>{{ $alquilerInmueble->numRecibo }}</td>
                <td><a href="{{ url('/alquilerinmueble/show/'.$alquilerInmueble->id) }}"> <i class="fas fa-plus"></i></a> </td>
              </tr>
            @endforeach

            @foreach ($alquileresMueblePagos as $alquilerMueble)
              <tr>
                <td>{{ date("d/m/Y", strtotime($alquilerMueble->fechaSolicitud)) }}</td>
                <td>{{ 'Ingreso' }}</td>
                <td class="montos">{{ '$ '.$alquilerMueble->costoTotal }}</td>
                <td>{{ 'Alquileres de Muebles' }}</td>
                <td>{{ $alquilerMueble->numRecibo }}</td>
                <td><a href="{{ url('/alquilermueble/show/'.$alquilerMueble->id) }}"> <i class="fas fa-plus"></i></a> </td>
              </tr>
            @endforeach

            @foreach ($cuotasPagadas as $cuota)
              <tr>
                <td>{{ date("d/m/Y", strtotime($cuota->fechaPago)) }}</td>
                <td>{{ 'Ingreso' }}</td>
                <td class="montos">{{ '$ '.$cuota->montoTotal }}</td>
                <td>{{ 'Cuota' }}</td>
                <td>{{ '-' }}</td>
                <td><a href="{{ url('/cuota/show/'.$cuota->id) }}"> <i class="fas fa-plus"></i></a> </td>
              </tr>
            @endforeach
  
          </tbody>
        </table>

        <div class="alert alert-danger" align="center">
          {{ 'El balance de la semana fue de $ '. $balance }}
        </div>

        <div class="card-footer row">
          <div >
            <a style="text-decoration:none" onclick="history.back()">
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