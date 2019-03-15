@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos del Empleado</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>DNI</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>Nombre de Usuario</b></td>
          <td><b>Apellido</b></td>
          <td><b>Nombres</b></td>
          <td><b>Tipo de Usuario</b></td>
        </tr>
        <tr>
          <td>36854715</td>
          <td>Pichon123</td>
          <td>Gonzalez</td>
          <td>Roberto</td>
          <td>Empleado</td>
        </tr>
      </table>

      <div class="card-footer">

        <a style="text-decoration:none" href="{{ url('/empleado/edit/'.'1') }}">
          <button type="button" class="btn btn-outline-warning" style="display:inline">
            Editar Empleado
          </button>
        </a>

        &nbsp;&nbsp;
        <form action="{{url('/empleado/delete')}}" method="post" style="display:inline">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="1">
          <button type="submit" class="btn btn-outline-danger" style="display:inline">
            Eliminar Empleado
          </button>
        </form>

      </div>

    </div>
  </div>
</div>


@stop
