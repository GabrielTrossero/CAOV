@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <table>
        <div class="form-group row">
          <label class="col-md-8 col-form-label"><b>Listado de Empleados</b></label>
        </div>
      </table>
    </div>

    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>DNI</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>Numero de Socio</b></td>
          <td><b>Nombre de Usuario</b></td>
          <td><b>Apellido</b></td>
          <td><b>Nombres</b></td>
          <td><b>Tipo de Usuario</b></td>
          <td><b>Ver Empleado</b></td>
        </tr>
        <tr>
          <td>36854715</td>
          <td>1</td>
          <td>Pichonaso123</td>
          <td>Pichon</td>
          <td>Culiao</td>
          <td>Empleado</td>
          <td><a href="{{ url('/empleado/show/'.'1') }}" style="color: red;">ver</a> </td>
        </tr>
        <tr>
          <td>37411445</td>
          <td>2</td>
          <td>Wenka</td>
          <td>Benka</td>
          <td>Penka</td>
          <td>Administrador</td>
          <td><a href="#" style="color: red;">ver</a> </td>
        </tr>
        <tr>
          <td>36411485</td>
          <td>3</td>
          <td>ElMisio</td>
          <td>Misio</td>
          <td>Nero</td>
          <td>Empleado</td>
          <td><a href="#" style="color: red;">ver</a> </td>
        </tr>
      </table>
    </div>
  </div>
</div>

@stop
