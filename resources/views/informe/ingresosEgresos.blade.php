@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
          <label class="col-md-8 col-form-label"><b>Listado de Ingresos/Egresos</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>Tipo</th>
            <th>Numero de Recibo</th>
            <th>Descripcion</th>
            <th>Fecha</th>
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
              <td>{{ date("d/m/Y", strtotime($movExtra->fecha)) }}</td>
              <td>{{ '$'.$movExtra->monto }}</td>
            </tr>
          @endforeach
          @foreach ($alquileresInmueblePagos as $alquilerInmueble)
            <tr>
              <td>{{ 'Ingreso' }}</td>
              <td>{{ '-' }}</td>
              <td>{{ 'Alquileres de Inmuebles' }}</td>
              <td>{{ $alquilerInmueble->mes.'/'.$alquilerInmueble->anio }}</td>
              <td>{{ '$'.$alquilerInmueble->total }}</td>
            </tr>
          @endforeach
          @foreach ($alquileresMueblePagos as $alquilerMueble)
            <tr>
              <td>{{ 'Ingreso' }}</td>
              <td>{{ '-' }}</td>
              <td>{{ 'Alquileres de Muebles' }}</td>
              <td>{{ $alquilerMueble->mes.'/'.$alquilerMueble->anio }}</td>
              <td>{{ '$'.$alquilerMueble->total }}</td>
            </tr>
          @endforeach
          @foreach ($cuotasPagadas as $cuota)
            <tr>
              <td>{{ 'Ingreso' }}</td>
              <td>{{ '-' }}</td>
              <td>{{ 'Alquileres de Muebles' }}</td>
              <td>{{ $cuota->mes.'/'.$cuota->anio }}</td>
              <td>{{ '$'.$cuota->total }}</td>
            </tr>
          @endforeach

        </tbody>
      </table>
    </div>

    <div class="card-footer">
      <form action="{{url('/informe/ingresos_egresos')}}" method="post" style="display:inline">
        {{ csrf_field() }}
        <button type="submit" class="btn btn-outline-danger" style="display:inline">
          Generar PDF
        </button>
      </form>
    </div>

  </div>
</div>

@stop
