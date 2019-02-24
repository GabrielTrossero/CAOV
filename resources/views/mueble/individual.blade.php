@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos del Mueble</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>Nombre</b></td>
          <td><b>Cantidad</b></td>   <!-- la <b> es para poner en negrita -->
        </tr>
        <tr>
          <td>Sillas</td>
          <td>100</td>
        </tr>
      </table>

      <div class="card-footer">

        <a style="text-decoration:none" href="{{ url('/mueble/edit/'.'1') }}">
          <button type="button" class="btn btn-outline-warning" style="display:inline">
            Editar Mueble
          </button>
        </a>

        &nbsp;&nbsp;
        <form action="{{url('/mueble/delete')}}" method="post" style="display:inline">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="1">
          <button type="submit" class="btn btn-outline-danger" style="display:inline">
            Eliminar Mueble
          </button>
        </form>
      </div>

    </div>
  </div>
</div>


@stop
