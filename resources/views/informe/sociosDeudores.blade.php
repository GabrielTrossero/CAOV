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
            <th>Monto que adeuda</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($socios as $socio)
              <tr>
                <td>{{ $socio->persona->DNI }}</td>
                <td>{{ $socio->numSocio }}</td>
                <td>{{ $socio->persona->apellido }}</td>
                <td>{{ $socio->persona->nombres }}</td>
                <td>{{ $socio->cantCuotas }}</td>
                <td>{{ '$'.$socio->montoDeuda }}</td>
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
