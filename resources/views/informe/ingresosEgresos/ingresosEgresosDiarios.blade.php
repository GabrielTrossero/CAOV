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
              <th>Descripcion</th>
              <th>Monto</th>
              <th>Numero de Recibo</th>
              <th>Más Información</th>
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

                <td>{{ $movExtra->descripcion }}</td>
                
                @if ($movExtra->tipo == "1")
                  <td class="montos">{{ '$ '.$movExtra->monto }}</td>
                @elseif($movExtra->tipo == "2")
                  <td class="montos">{{ '$ -'.$movExtra->monto }}</td>
                @endif
                
                <td>{{ $movExtra->numRecibo }}</td>
                <td><a href="{{ url('/registro/show') }}"> <i class="fas fa-plus"></i></a> </td>
              </tr>
            @endforeach

            @foreach ($alquileresInmueblePagos as $alquilerInmueble)
              <tr>
                <td>{{ 'Ingreso' }}</td>
                <td>{{ 'Alquileres de Inmuebles' }}</td>
                <td class="montos">{{ '$ '.$alquilerInmueble->costoTotal }}</td>
                <td>{{ $alquilerInmueble->numRecibo }}</td>
                <td><a href="{{ url('/alquilerinmueble/show/'.$alquilerInmueble->id) }}"> <i class="fas fa-plus"></i></a> </td>
              </tr>
            @endforeach

            @foreach ($alquileresMueblePagos as $alquilerMueble)
              <tr>
                <td>{{ 'Ingreso' }}</td>
                <td>{{ 'Alquileres de Muebles' }}</td>
                <td class="montos">{{ '$ '.$alquilerMueble->costoTotal }}</td>
                <td>{{ $alquilerMueble->numRecibo }}</td>
                <td><a href="{{ url('/alquilermueble/show/'.$alquilerMueble->id) }}"> <i class="fas fa-plus"></i></a> </td>
              </tr>
            @endforeach

            @foreach ($cuotasPagadas as $cuota)
              <tr>
                <td>{{ 'Ingreso' }}</td>
                <td>{{ 'Cuota' }}</td>
                <td class="montos">{{ '$ '.$cuota->montoTotal }}</td>
                <td>{{ '-' }}</td>
                <td><a href="{{ url('/cuota/show/'.$cuota->id) }}"> <i class="fas fa-plus"></i></a> </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="alert alert-danger" align="center">
          {{ 'El balance del día fue de $ '. $balance }}
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