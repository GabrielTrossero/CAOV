@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Socios</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI</th>
            <th>Numero de Socio</th>
            <th>Apellido</th>
            <th>Nombres</th>
            <th>Categoria</th>
            <th>Oficio</th>
            <th>Deportes</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>39842653</td>
            <td>1</td>
            <td>Dreher</td>
            <td>Francisco</td>
            <td>Activo</td>
            <td>Director Técnico de Patronato</td>
            <td>
              Futbol
              <br>
              Hockey
            </td>
            <td><a href="{{ url('/socio/show/'.'1') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
          <tr>
            <td>38956324</td>
            <td>2</td>
            <td>Trillo</td>
            <td>Tula</td>
            <td>Honorario</td>
            <td>Intendente</td>
            <td>
              Volley
            </td>
            <td><a href="{{ url('/socio/show/'.'2') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
