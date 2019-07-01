@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <table>
        <label class="col-md-8 col-form-label"><b>Listado de Grupos Familiares</b></label>
      </table>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>Titular</th>
            <th>Ver Grupo Familiar</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>39875632 - Joaquin Ricle</td>
            <td><a href="{{ url('/grupofamiliar/show/'.'1') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
          <tr>
            <td>39875245 - Ema Goette</td>
            <td><a href="#"> <i class="fas fa-plus"></i></a> </td>
          </tr>
          <tr>
            <td>38752986 - Misio Nero</td>
            <td><a href="#"> <i class="fas fa-plus"></i></a> </td>
          </tr>
        <tbody>
      </table>
    </div>
  </div>
</div>

@stop
