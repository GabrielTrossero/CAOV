@extends('layouts.master')

@section('content')

<div class="cuadro" style="padding-top:25px;">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Estadísticas de Socios</b></label>
    </div>
    <div class="card-body border" align="center">
      <div class="contenido-graficas">
        <div class="container-grafico-informe">
          <b>Socios Nuevos y Dados de Baja en el mes</b>
          <br>
          <img class="grafico-informe" id="socios-nuevos-bajas" src="https://quickchart.io/chart?c={{ $lineaSociosNuevosYBajas }}" alt="Comparación Socios Nuevos y Dados de Baja en el mes">
        </div>
        <div class="container-grafico-informe">
          <b>Transferencia de Cadetes a Activos</b>
          <br>
          <img class="grafico-informe" id="socios-cadetes-a-activos" src="https://quickchart.io/chart?c={{ $barraSociosCadetesPasanActivos }}" alt="Cadetes que pasarán (o pasaron) a Activos">
        </div>
        <div class="container-grafico-informe">
          <b>Movimiento de Socios de los últimos Seis meses</b>
          <br>
          <img class="grafico-informe" id="movimiento-socios" src="https://quickchart.io/chart?c={{ $donaSociosNuevosYBajasUltimosSeisMeses }}" alt="Movimiento de Socios de los últimos Seis meses">
        </div>
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
          <form action="{{url('/informe/pdf_cantidad_socios')}}" method="get" style="display:inline">
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
