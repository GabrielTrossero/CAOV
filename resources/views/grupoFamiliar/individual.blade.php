@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos del Grupo Familiar</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>DNI</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>Apellido</b></td>
          <td><b>Nombres</b></td>
          <td><b>Titular</b></td>
          <td><b>Ver Socio</b></td>
        </tr>
        <tr>
          <td>36854715</td>
          <td>Pichon</td>
          <td>Culiao</td>
          <td>Si</td>
          <td><a href="{{ url('/socio/show/'.'1') }}" style="color: red;">ver</a> </td>
        </tr>
        <tr>
          <td>36874715</td>
          <td>Pichona</td>
          <td>Culiada</td>
          <td>No</td>
          <td><a href="{{ url('/socio/show/'.'2') }}" style="color: red;">ver</a> </td>
        </tr>
        <tr>
          <td>36814715</td>
          <td>Pichon</td>
          <td>Culiaito</td>
          <td>No</td>
          <td><a href="{{ url('/socio/show/'.'3') }}" style="color: red;">ver</a> </td>
        </tr>
      </table>

      <div class="card-footer">

        <a style="text-decoration:none" href="{{ url('/grupofamiliar/edit/'.'1') }}">
          <button type="button" class="btn btn-outline-warning" style="display:inline">
            Editar Grupo Familiar
          </button>
        </a>

        &nbsp;&nbsp;
        <form action="{{url('/grupofamiliar/delete')}}" method="post" style="display:inline">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="1">
          <button type="submit" class="btn btn-outline-danger" style="display:inline">
            Eliminar Grupo Familiar
          </button>
        </form>

      </div>

    </div>
  </div>
</div>


@stop
