@extends('pdf.master')

@section('title', 'Cantidad de Socios por Deporte')

@section('content')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <br>
    <h4 align="center">Estadísticas de Deportes</h4>
    <h4 style="font-size: 14px">Cantidad de socios menores: {{ $sociosMenores }}</h4>
    <h4 style="font-size: 14px">Cantidad de socios mayores: {{ $sociosMayores }}</h4>
    <div class="tam_letra_x-small">
          <div align="center">
              <div class="container-img">
                <h4>Socios por Deporte</h4> 
                <br>
                <img class="grafico-informe" id="socios-por-deporte" src="https://quickchart.io/chart?c={{ $barraSociosPorDeporte }}" alt="Socios por Deporte">
              </div>
              <div class="container-img">
                <h4>Socios Mayores por Deporte</h4>
                <br>
                <img class="grafico-informe" id="activos-por-deporte" src="https://quickchart.io/chart?c={{ $barraActivosPorDeporte }}" alt="Socios Activos por Deporte">
              </div>
              <div class="page-break"></div>
              <div class="container-img">
                <h4>Socios Cadetes por Deporte</h4>
                <br>
                <img class="grafico-informe" id="cadetes-por-deporte" src="https://quickchart.io/chart?c={{ $barraCadetesPorDeporte }}" alt="Socios Cadetes por Deporte">
              </div>
              <div class="container-img">
                <h4>Socios por cantidad de Deportes practicados</h4>
                <br>
                <img class="grafico-informe" id="socios-grupo-por-deporte" src="https://quickchart.io/chart?c={{ $barraCantidadDeportesPracticados }}" alt="Socios Con Grupo Familiar por Deporte">
              </div>
        </div>
    </div>
@endsection
