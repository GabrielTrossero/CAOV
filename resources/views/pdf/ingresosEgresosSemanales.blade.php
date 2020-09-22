@extends('pdf.master')

@section('title', 'Ingresos y Egresos Semanales')

@section('content')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <br>
    <h4 align="center">Estadísticas de Ingresos y Egresos Semanales</h4>
    <br>
    <div class="tam_letra_x-small">
          <div align="center">
              <div class="container-img">
                <h4>Balance de Ingresos y Egresos (últimas 24 semanas)</h4> 
                <br>
                <img class="balance" id="balance-ocho-semanas" src="https://quickchart.io/chart?c={{ $lineaBalanceIngresosEgresosSemanales }}" alt="Balance de Ingresos y Egresos (últimas 8 semanas)">
              </div>
          </div>
          <div class="page-break"></div>
    </div>

    <br>
    <h4 align="center">Ingresos y Egresos Semanales Generales</h4>
    <div class="tam_letra_x-small">
      <table class="table table-striped">
        <thead>
            <tr>
              <th>Fecha (Año - Semana)</th>
              <th>Ingresos</th>
              <th>Egresos</th>
              <th>Balance</th>
            </tr>
          </thead>
          <tbody>
          @foreach ($montos->ingresos as $semana => $valor)
              <tr>
                <td>{{ $semana }}</td>

                <td class="montos">{{ '$ '.$montos->ingresos[$semana] }}</td>

                <td class="montos">{{ '$ '.$montos->egresos[$semana] }}</td>

                <td class="montos">{{ '$ '. ($montos->ingresos[$semana] - $montos->egresos[$semana]) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
@endsection
