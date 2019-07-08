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
            <th>Deuda</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>39842653</td>
            <td>1</td>
            <td>Ricle</td>
            <td>Penka</td>
            <td>3800</td>
            <td><a href="{{ url('/informe/socio_deudor/'.'1') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
          <tr>
            <td>38956324</td>
            <td>2</td>
            <td>Tula</td>
            <td>Tula</td>
            <td>1000</td>
            <td><a href="{{ url('/informe/socio_deudor/'.'2') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="card-footer">
      <form action="{{url('/informe/deudores')}}" method="post" style="display:inline">
        {{ csrf_field() }}
        <button type="submit" class="btn btn-outline-danger" style="display:inline">
          Generar PDF
        </button>
      </form>
    </div>

  </div>
</div>

@stop
