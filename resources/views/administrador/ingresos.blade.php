@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
          <label class="col-md-8 col-form-label"><b>Listado de Ingresos</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>Numero de Recibo</th>
            <th>Descripcion</th>
            <th>Fecha</th>
            <th>Monto</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Subsidio Municipalidad</td>
            <td>25/12/2014</td>
            <td>$5000</td>
          </tr>
          <tr>
            <td>2</td>
            <td>Donación Anonima</td>
            <td>12/8/2018</td>
            <td>$2500</td>
          </tr>
          <tr>
            <td>3</td>
            <td>Alquiler sillas</td>
            <td>6/11/2015</td>
            <td>$1700</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
