@extends('layouts.master')

@section('content')

<div class="cuadro" style="padding-top:25px;">
    <div class="card">
      
      <div class="card-body border" align="center">
        <div class="contenido-graficas">
          <div class="container-grafico-informe">
            <b>Socios Nuevos y Dados de Baja en los ultimos 12 meses</b>
            <br>
            <img class="grafico-informe" id="socios-nuevos-bajas" src="https://quickchart.io/chart?c={{ $barraSociosNuevosYBajas }}" alt="Comparación Socios Nuevos y Dados de Baja en el mes">
          </div>
          <div class="container-grafico-informe">
            <b>Movimiento de Socios de los últimos Seis meses</b>
            <br>
            <img class="grafico-informe" id="movimiento-socios" src="https://quickchart.io/chart?c={{ $donaSociosNuevosYBajasUltimosSeisMeses }}" alt="Movimiento de Socios de los últimos Seis meses">
          </div>
          <div class="ccontainer-grafico-informe-ingresos-egresos">
            <b>Balance de Ingresos y Egresos (últimos 12 meses)</b>
            <br>
            <img class="grafico-informe" id="balance-doce-meses" src="https://quickchart.io/chart?c={{ $lineaBalanceIngresosEgresosMensual }}" alt="Balance de Ingresos y Egresos (últimos 12 meses)">
          </div>
        </div>
      </div>
    </div>
</div>

@stop
