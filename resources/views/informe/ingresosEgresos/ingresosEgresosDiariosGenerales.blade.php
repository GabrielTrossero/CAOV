@extends('layouts.master')

@section('content')

<div class="cuadro" style="padding-top:25px;">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Estadísticas de Ingresos y Egresos Diarios</b></label>
    </div>
    <div class="card-body border" align="center">
      <div class="contenido-graficas">
        <div class="ccontainer-grafico-informe-ingresos-egresos">
          <b>Balance de Ingresos y Egresos (últimos 14 días)</b>
          <br>
          <img class="grafico-informe" id="balance-catorce-dias" src="https://quickchart.io/chart?c={{ $lineaBalanceIngresosEgresosDiarios }}" alt="Balance de Ingresos y Egresos (últimos 14 días)">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="cuadro" style="margin-top: -7%">
    <div class="card">
      <div class="card-header">
        <label class="col-md-8 col-form-label"><b>Listado de Ingresos/Egresos Diarios Generales</b></label>
      </div>
      <div class="card-body border">
        <table id="idDataTable" class="table table-striped">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Ingresos</th>
              <th>Egresos</th>
              <th>Balance</th>
              <th>Mas Información</th>
            </tr>
          </thead>
          <tbody>
  
            @foreach ($montos->ingresos as $fecha => $valor)
              <tr>
                <td>{{ date("d/m/Y", strtotime($fecha)) }}</td>

                @if ($montos->ingresos[$fecha] == 0)
                    <td> - </td>
                @else 
                    <td>{{ '$'.$montos->ingresos[$fecha] }}</td>
                @endif

                @if ($montos->egresos[$fecha] == 0)
                    <td> - </td>
                @else 
                    <td>{{ '$'.$montos->egresos[$fecha] }}</td>
                @endif

                <td>{{ '$'. ($montos->ingresos[$fecha] - $montos->egresos[$fecha]) }}</td>
                
                <td><a href="{{ url('/informe/ingresos_egresos_diarios/'.$fecha.'/'.($montos->ingresos[$fecha] - $montos->egresos[$fecha])) }}"> <i class="fas fa-plus"></i></a> </td>
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
            <form action="{{url('/informe/pdf_ingresos_egresos_diarios')}}" method="get" style="display:inline">
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