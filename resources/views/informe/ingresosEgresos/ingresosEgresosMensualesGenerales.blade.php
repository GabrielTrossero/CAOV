@extends('layouts.master')

@section('content')

<div class="cuadro" style="padding-top:25px;">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Estadísticas de Ingresos y Egresos Mensuales</b></label>
    </div>
    <div class="card-body border" align="center">
      <div class="contenido-graficas">
        <div class="ccontainer-grafico-informe-ingresos-egresos">
          <b>Balance de Ingresos y Egresos (últimos 12 meses)</b>
          <br>
          <img class="grafico-informe" id="balance-doce-meses" src="https://quickchart.io/chart?c={{ $lineaBalanceIngresosEgresosMensual }}" alt="Balance de Ingresos y Egresos (últimos 12 meses)">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="cuadro" style="margin-top: -7%">
    <div class="card">
      <div class="card-header">
        <label class="col-md-8 col-form-label"><b>Listado de Ingresos/Egresos Mensuales Generales</b></label>
      </div>
      <div class="card-body border">
        <table id="idDataTable" class="table table-striped">
          <thead>
            <tr>
              <th>Fecha (Año - Mes)</th>
              <th>Ingresos</th>
              <th>Egresos</th>
              <th>Balance</th>
              <th>Mas Información</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($montos->ingresos as $mes => $valor)
              <tr>
                <td>{{ $mes }}</td>

                @if ($montos->ingresos[$mes] == 0)
                    <td> - </td>
                @else 
                    <td class="montos">{{ '$ '.$montos->ingresos[$mes] }}</td>
                @endif

                <td class="montos">{{ '$ '.$montos->egresos[$mes] }}</td>

                <td class="montos">{{ '$ '. ($montos->ingresos[$mes] - $montos->egresos[$mes]) }}</td>

                <td><a href="{{ url('/informe/ingresos_egresos_mensuales/'.$mes.'/'.($montos->ingresos[$mes] - $montos->egresos[$mes])) }}"> <i class="fas fa-plus"></i></a> </td>

              </tr>
            @endforeach
          </tbody>
        </table>
    
        <div class="card-footer row">
          <div >
            <a style="text-decoration:none" onclick="history.back()">
              <button type="button" class="btn btn-secondary">
                Volver
              </button>
            </a>
          </div>

          <div class="col-md-10 text-md-center">
            <form action="{{url('/informe/pdf_ingresos_egresos_mensuales')}}" method="get" style="display:inline">
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