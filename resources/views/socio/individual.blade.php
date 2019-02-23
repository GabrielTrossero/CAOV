@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos del Socio</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>DNI</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>Numero de Socio</b></td>
          <td><b>Apellido</b></td>
          <td><b>Nombres</b></td>
          <td><b>Categoria</b></td>
          <td><b>Deportes</b></td>
          <td><b>Grupo Familiar</b></td>
        </tr>
        <tr>
          <td>40662158</td>
          <td>1</td>
          <td>Zapata</td>
          <td>Juan Bautista</td>
          <td>Honorario</td>
          <td>
            Hockey
            <br>
            Futbol
          </td>
          <td>Titular: Penka 39856235</td>
        </tr>
      </table>

      <div class="card-footer">

        <a style="text-decoration:none" href="{{ url('/socio/edit/'.'1') }}">
          <button type="button" class="btn btn-outline-warning" style="display:inline">
            Editar Socio
          </button>
        </a>

        &nbsp;&nbsp;
        <form action="{{url('/socio/delete')}}" method="post" style="display:inline">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="1">
          <button type="submit" class="btn btn-outline-danger" style="display:inline">
            Eliminar Socio
          </button>
        </form>
      </div>

    </div>
  </div>
</div>


@stop
