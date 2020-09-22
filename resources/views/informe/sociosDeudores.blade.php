@extends('layouts.master')

@section('content')

<div class="cuadro" style="padding-top:25px; padding-bottom:15px;">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Estadísticas de Socios Deudores</b></label>
    </div>
    <div class="card-body border" align="center">
      <div class="contenido-graficas">
        <div class="container-grafico-informe">
          <b>Socios por cantidad de Cuotas que adeudan</b>
          <br>
          <img class="grafico-informe" id="socios-por-cantidad-cuotas-adeudadas" src="https://quickchart.io/chart?c={{ $tortaCantidadCuotasAdeudadas }}" alt="Socios por cantidad de Cuotas que adeudan">
        </div>
        <div class="container-grafico-informe">
          <b>Monto Total ($) adeudado por Categoría</b>
          <br>
          <img class="grafico-informe" id="monto-adeudado-por-categoria" src="https://quickchart.io/chart?c={{ $tortaMontoTotalAdeudadoPorCategoria }}" alt="Monto Total adeudado por Categoría">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Socios Deudores</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>Número de Socio</th>
            <th>DNI</th>
            <th>Apellido</th>
            <th>Nombres</th>
            <th>Cuotas que adeuda</th>
            <th>Monto que adeuda</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($socios as $socio)
              <tr>
                <td>{{ $socio->numSocio }}</td>
                <td>{{ $socio->persona->DNI }}</td>
                <td>{{ $socio->persona->apellido }}</td>
                <td>{{ $socio->persona->nombres }}</td>
                <td>{{ $socio->cantCuotas }}</td>
                <td class="montos">{{ '$ '.$socio->montoDeuda }}</td>
                <td><a href="{{ url('/informe/socio_deudor/'.$socio->id) }}"> <i class="fas fa-plus"></i></a> </td>
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
          <form action="{{url('/informe/pdf_deudores')}}" method="get" style="display:inline">
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
