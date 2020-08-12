@extends('pdf.master')

@section('title', 'Pagos')

@section('content')

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <br>
    <h4 align="center">Pagos</h4>
    <div class="tam_letra_x-small">
      <table class="table table-striped">
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
    </div>

@endsection
