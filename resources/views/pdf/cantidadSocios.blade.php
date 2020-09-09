@extends('pdf.master')

@section('title', 'Cantidad de Socios')

@section('content')
<br>
<h4 align="center">Estadísticas de Socios</h4>
<div class="tam_letra_x-small">
  <div align="center">
    <div class="container-img">
      <h2>Socios Nuevos y Dados de Baja en el mes</h2>
      <br>
      <img class="balance" id="socios-nuevos-bajas" src="https://quickchart.io/chart?c={{ $barraSociosNuevosYBajas }}" alt="Comparación Socios Nuevos y Dados de Baja en el mes">
    </div>
    <div class="page-break"></div>
    <div class="container-img">
      <h2>Transferencia de Cadetes a Activos</h2>
      <h2>(considerando 2 años anteriores y posteriores)</h2>
      <br>
      <img class="balance" id="socios-cadetes-a-activos" src="https://quickchart.io/chart?c={{ $barraSociosCadetesPasanActivos }}" alt="Cadetes que pasarán (o pasaron) a Activos">
    </div>
    <div class="page-break"></div>
    <div class="container-img">
      <h2>Movimiento de Socios de los últimos seis meses</h2>
      <br>
      <img class="balance" id="movimiento-socios" src="https://quickchart.io/chart?c={{ $donaSociosNuevosYBajasUltimosSeisMeses }}" alt="Movimiento de Socios de los últimos Seis meses">
    </div>
  </div>
</div>

@endsection
