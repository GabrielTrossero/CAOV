@extends('pdf.master')

@section('title', 'Pagos')

@section('content')

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <h4 align="center">Estadísticas de Pagos</h4>
    <div class="tam_letra_x-small">
          <div align="center">
              <div class="container-img">
                <h4>Monto Total ($) por tipo de Ingreso</h4>
                <br>
                <img class="grafico-informe" id="monto-total-pagos" src="https://quickchart.io/chart?c={{ $tortaMontoPorTipoDeIngreso }}" alt="Monto Total por tipo de Ingreso">
              </div>
              <div class="container-img">
                <h4>Monto Total ($) por tipo de Ingreso del Dia</h4>
                <br>
                <img class="grafico-informe" id="monto-total-pagos-dia" src="https://quickchart.io/chart?c={{ $tortaMontoPorTipoDeIngresoHoy }}" alt="Monto Total por tipo de Ingreso del Dia">
              </div>
              <div class="page-break"></div>
              <div class="container-img">
                <h4>Monto Total ($) por tipo de Ingreso de la Semana</h4>
                <br>
                <img class="grafico-informe" id="monto-total-pagos-semana" src="https://quickchart.io/chart?c={{ $tortaMontoPorTipoDeIngresoSemana }}" alt="Monto Total por tipo de Ingreso de la Semana">
              </div>
              <div class="container-img">
                <h4>Monto Total ($) por tipo de Ingreso del Mes</h4>
                <br>
                <img class="grafico-informe" id="monto-total-pagos-mes" src="https://quickchart.io/chart?c={{ $tortaMontoPorTipoDeIngresoMes }}" alt="Monto Total por tipo de Ingreso del Mes">
              </div>
              <div class="page-break"></div>
          </div>
    </div>
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
                <td>{{ 'Cuota' }}</td>
                <td class="montos">{{ '$ '.$cuotaPagada->montoTotal }}</td>
                <td>{{ '-' }}</td>
              </tr>
            @endforeach

            @foreach ($reservasInmueble as $reservaInmueble)
              <tr>
                <td>{{ date("d/m/Y", strtotime($reservaInmueble->fechaSolicitud)) }}</td>
                <td>{{ 'Alquiler de '.$reservaInmueble->inmueble->nombre }}</td>
                <td class="montos">{{ '$ '.$reservaInmueble->costoTotal }}</td>
                <td>{{ $reservaInmueble->numRecibo }}</td>
              </tr>
            @endforeach

            @foreach ($reservasMueble as $reservaMueble)
              <tr>
                <td>{{ date("d/m/Y", strtotime($reservaMueble->fechaSolicitud)) }}</td>
                <td>{{ $reservaMueble->mueble->nombre . " - " . $reservaMueble->cantidad.' Unidade/s' }}</td>
                <td class="montos">{{ '$ '.$reservaMueble->costoTotal }}</td>
                <td>{{ $reservaMueble->numRecibo }}</td>
              </tr>
            @endforeach

          </tbody>
        </table>
    </div>

@endsection
