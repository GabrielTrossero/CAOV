@extends('pdf.master')

@section('title', 'Pagos')

@section('content')
    <h1>Pagos</h1>
    <table align="center">
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
              <td>{{ $cuotaPagada->fechaPago }}</td>
              <td>Cuota</td>
              <td>${{ $cuotaPagada->montoTotal }}</td>
            </tr>
          @endforeach
          
          @foreach ($reservasInmueble as $reservaInmueble)
            <tr>
              <td>{{ $reservaInmueble->numRecibo }}</td>
              <td>{{ $reservaInmueble->fechaSolicitud }}</td>
              <td>Alquiler de {{ $reservaInmueble->inmueble->nombre }}</td>
              <td>${{ $reservaInmueble->costoTotal }}</td>
            </tr>
          @endforeach

          @foreach ($reservasMueble as $reservaMueble)
            <tr>
              <td>{{ $reservaMueble->numRecibo }}</td>
              <td>{{ $reservaMueble->fechaSolicitud }}</td>
              <td>Alquiler de {{ $reservaMueble->cantidad . " " . $reservaMueble->mueble->nombre}}</td>
              <td>${{ $reservaMueble->costoTotal }}</td>
            </tr>
          @endforeach

        </tbody>
      </table>
@endsection