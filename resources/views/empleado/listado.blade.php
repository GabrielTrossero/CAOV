@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Empleados</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI</th>
            <th>Numero de Socio</th>
            <th>Nombre de Usuario</th>
            <th>Apellido</th>
            <th>Nombres</th>
            <th>Tipo de Usuario</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>36854715</td>
            <td>1</td>
            <td>Pichon123</td>
            <td>Gonzalez</td>
            <td>Roberto</td>
            <td>Empleado</td>
            <td><a href="{{ url('/empleado/show/'.'1') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
          <tr>
            <td>37411445</td>
            <td>2</td>
            <td>Perez25</td>
            <td>Perez</td>
            <td>Juan</td>
            <td>Administrador</td>
            <td><a href="#"> <i class="fas fa-plus"></i></a> </td>
          </tr>
          <tr>
            <td>36411485</td>
            <td>3</td>
            <td>Menga</td>
            <td>Mengarelli</td>
            <td>Jose Luis</td>
            <td>Empleado</td>
            <td><a href="#"> <i class="fas fa-plus"></i></a> </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
