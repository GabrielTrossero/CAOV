@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Muebles</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Cantidad</th>
            <th>Más Información</th>
          </tr>
        </thead>
          <tbody>
          <tr>
            <td>Sillas</td>
            <td>200</td>
            <td><a href="{{ url('/mueble/show/'.'1') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
          <tr>
            <td>Mesas</td>
            <td>20</td>
            <td><a href="{{ url('/mueble/show/'.'2') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
          <tr>
            <td>Caballetes</td>
            <td>40</td>
            <td><a href="{{ url('/mueble/show/'.'3') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
