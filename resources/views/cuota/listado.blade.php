@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Cuotas</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI Socio</th>
            <th>Mes y Anio</th>
            <th>Fecha Pago</th>
            <th>Monto Mensual</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>39842653</td>
            <td>01/05/2019</td>
            <td>05/05/2019</td>
            <td>150</td>
            <td><a href="{{ url('/cuota/show/'.'1') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
          <tr>
            <td>25963214</td>
            <td>01/11/2019</td>
            <td>12/04/2020</td>
            <td>300</td>
            <td><a href="{{ url('/cuota/show/'.'2') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
