@extends('pdf.master')

@section('title', 'Ingresos y Egresos Mensuales')

@section('content')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
    <br>
    <h4 align="center">Estadísticas de Ingresos y Egresos Mensuales</h4>
    <br>
    <div class="tam_letra_x-small">
          <div align="center">
              <div class="container-img">
                <h4>Balance de Ingresos y Egresos (últimos 12 meses)</h4> 
                <br>
                <img class="balance" id="balance-doce-meses" src="https://quickchart.io/chart?c={{ $lineaBalanceIngresosEgresosMensual }}" alt="Balance de Ingresos y Egresos (últimos 12 meses)">
              </div>
          </div>
          <div class="page-break"></div>
    </div>

    <br>
    <h4 align="center">Ingresos y Egresos Mensuales Generales</h4>
    <div class="tam_letra_x-small">
      <table class="table table-striped">
        <thead>
            <tr>
              <th>Fecha (Año - Mes)</th>
              <th>Ingresos</th>
              <th>Egresos</th>
              <th>Balance</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($montos->ingresos as $mes => $valor)
              <tr>
                <td>{{ $mes }}</td>

                <td class="montos">{{ '$ '.$montos->ingresos[$mes] }}</td>

                <td class="montos">{{ '$ '.$montos->egresos[$mes] }}</td>

                <td class="montos">{{ '$ '. ($montos->ingresos[$mes] - $montos->egresos[$mes]) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
@endsection
