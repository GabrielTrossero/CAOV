@extends('layouts.master')

@section('content')

<div class="cuadro" style="padding-top:25px;">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Estadísticas de Deportes</b></label>
      <div align="center">
        <label class="col-sm-8 col-form-label">Cantidad de socios menores: {{ $sociosMenores }} | Cantidad de socios mayores: {{ $sociosMayores }}</label>
      </div>
    </div>
    <div class="card-body border" align="center">
      <div class="contenido-graficas">
        <div class="container-grafico-informe">
          <b>Socios por Deporte</b>
          <br>
          <img class="grafico-informe" id="socios-por-deporte" src="https://quickchart.io/chart?c={{ $barraSociosPorDeporte }}" alt="Socios por Deporte">
        </div>
        <div class="container-grafico-informe">
          <b>Socios Mayores por Deporte</b>
          <br>
          <img class="grafico-informe" id="activos-por-deporte" src="https://quickchart.io/chart?c={{ $barraActivosPorDeporte }}" alt="Socios Activos por Deporte">
        </div>
        <div  class="container-grafico-informe">
          <b>Socios Cadetes por Deporte</b>
          <br>
          <img class="grafico-informe" id="cadetes-por-deporte" src="https://quickchart.io/chart?c={{ $barraCadetesPorDeporte }}" alt="Socios Cadetes por Deporte">
        </div>
        <div class="container-grafico-informe">
          <b>Socios por cantidad de Deportes practicados</b>
          <br>
          <img class="grafico-informe" id="socios-cantidad-por-deportes-practicados" src="https://quickchart.io/chart?c={{ $barraCantidadDeportesPracticados }}" alt="Socios Con Grupo Familiar por Deporte">
        </div>
      </div>
      <div class="card-footer row">
        <div >
          <a style="text-decoration:none" onclick="history.back()">
            <button type="button" class="btn btn-secondary">
              Volver
            </button>
          </a>
        </div>

        <div class="col-md-10 text-md-center">
          <form action="{{url('/informe/pdf_cantidad_socios_deporte')}}" method="get" style="display:inline">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-outline-danger" style="display:inline">
              Generar PDF
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@stop
