@extends('pdf.master')

@section('title', 'Ingresos y Egresos Diarios')

@section('content')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <br>
    <h4 align="center">Estadísticas de Ingresos y Egresos Diarios</h4>
    <br>
    <div class="tam_letra_x-small">
          <div align="center">
              <div class="container-img">
                <h4>Balance de Ingresos y Egresos (últimos 30 días)</h4> 
                <br>
                <img class="balance" id="balance-catorce-dias" src="https://quickchart.io/chart?c={{ $barraBalanceIngresosEgresosDiarios }}" alt="Balance de Ingresos y Egresos (últimos 14 días)">
              </div>
          </div>
          <div class="page-break"></div>
    </div>

    <br>
    <h4 align="center">Ingresos y Egresos Diarios Generales</h4>
    <div class="tam_letra_x-small">
      <table class="table table-striped">
        <thead>
            <tr>
              <th>Fecha</th>
              <th>Ingresos</th>
              <th>Egresos</th>
              <th>Balance</th>
            </tr>
          </thead>
          <tbody>
          @foreach ($montos->ingresos as $fecha => $valor)
              <tr>
                <td>{{ date("d/m/Y", strtotime($fecha)) }}</td>

                <td class="montos">{{ '$ '.$montos->ingresos[$fecha] }}</td>

                <td class="montos">{{ '$ '.$montos->egresos[$fecha] }}</td>

                <td>{{ '$ '. ($montos->ingresos[$fecha] - $montos->egresos[$fecha]) }}</td>
              </tr>
          @endforeach
          </tbody>
        </table>
      </div>
@endsection
