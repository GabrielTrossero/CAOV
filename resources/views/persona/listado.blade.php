@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Personas</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI</th>
            <th>Apellido</th>
            <th>Nombres</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>36854715</td>
            <td>Moreyra</td>
            <td>Pedro</td>
            <td><a href="{{ url('/persona/show/'.'1') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
          <tr>
            <td>37411445</td>
            <td>Ricle</td>
            <td>Nicolas</td>
            <td><a href="#" > <i class="fas fa-plus"></i></a> </td>
          </tr>
          <tr>
            <td>36411485</td>
            <td>Misio</td>
            <td>Nero</td>
            <td><a href="#"><i class="fas fa-plus"></i></a> </td>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

@stop
