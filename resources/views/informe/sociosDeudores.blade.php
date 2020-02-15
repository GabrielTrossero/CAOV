@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Socios Deudores</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI</th>
            <th>Numero de Socio</th>
            <th>Apellido</th>
            <th>Nombres</th>
            <th>Cuotas que adeuda</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cuotasNoPagadas as $cuotaNoPagada)
            <tr>
              <td>{{ $cuotaNoPagada->DNI }}</td>
              <td>{{ $cuotaNoPagada->numSocio }}</td>
              <td>{{ $cuotaNoPagada->apellido }}</td>
              <td>{{ $cuotaNoPagada->nombres }}</td>
              <td>{{ $cuotaNoPagada->count }}</td>
              <td><a href="{{ url('/informe/socio_deudor/'.$cuotaNoPagada->idSocio) }}"> <i class="fas fa-plus"></i></a> </td>
            </tr> 
          @endforeach
          
        </tbody>
      </table>
    </div>

    <div class="card-footer">
      <form action="{{url('/informe/pdf_deudores')}}" method="get" style="display:inline">
        {{ csrf_field() }}
        <button type="submit" class="btn btn-outline-danger" style="display:inline">
          Generar PDF
        </button>
      </form>
    </div>

  </div>
</div>

@stop
