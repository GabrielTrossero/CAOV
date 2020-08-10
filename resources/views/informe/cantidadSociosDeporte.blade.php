@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Cantidad de Socios por Deporte</b></label>
    </div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <th>Deporte</th>
          <th>Cantidad de Socios</th>
        </tr>

        @foreach ($deportes as $deporte)
          <tr>
            <td>{{ $deporte->nombre }}</td>
            <td>{{ $deporte->cantidadSocios }}</td>
          </tr>
        @endforeach


      </table>

      <div class="card-footer row">
        <div >
          <a style="text-decoration:none" href="{{ url('/informe') }}">
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
