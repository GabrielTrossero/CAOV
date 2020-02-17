@extends('pdf.master')

@section('title', 'Ingresos y Egresos Diarios')

@section('content')
    <h1>Ingresos y Egresos Diarios</h1>
    <table align="center">
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
              <td>{{ $alquilerInmueble->numRecibo }}</td>
              <td>{{ 'Alquileres de Inmuebles' }}</td>
              <td>{{ date("d/m/Y", strtotime($alquilerInmueble->fechaSolicitud)) }}</td>
              <td>{{ '$'.$alquilerInmueble->costoTotal }}</td>
            </tr>
          @endforeach
          @foreach ($alquileresMueblePagos as $alquilerMueble)
            <tr>
              <td>{{ 'Ingreso' }}</td>
              <td>{{ $alquilerMueble->numRecibo }}</td>
              <td>{{ 'Alquileres de Muebles' }}</td>
              <td>{{ date("d/m/Y", strtotime($alquilerMueble->fechaSolicitud)) }}</td>
              <td>{{ '$'.$alquilerMueble->total }}</td>
            </tr>
          @endforeach
          @foreach ($cuotasPagadas as $cuota)
            <tr>
              <td>{{ 'Ingreso' }}</td>
              <td>{{ '-' }}</td>
              <td>{{ 'Cuota' }}</td>
              <td>{{ date("d/m/Y", strtotime($cuota->fechaPago)) }}</td>
              <td>{{ '$'.$cuota->montoTotal }}</td>
            </tr>
          @endforeach
          </tbody>
    </table>
@endsection