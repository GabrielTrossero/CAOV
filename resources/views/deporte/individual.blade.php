@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos del Deporte</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>Nombre</b></td>   <!-- la <b> es para poner en negrita -->
        </tr>
        <tr>
          <td>{{ $deporte->nombre }}</td>
        </tr>
      </table>

      <div class="card-footer">

        <a style="text-decoration:none" href="{{ url('/deporte/edit/'.$deporte->id) }}">
          <button type="button" class="btn btn-outline-warning" style="display:inline">
            Editar Deporte
          </button>
        </a>

        &nbsp;&nbsp;
        <form action="{{url('/deporte/delete')}}" method="post" style="display:inline">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="{{ $deporte->id }}">
          <button type="submit" class="btn btn-outline-danger" style="display:inline">
            Eliminar Deporte
          </button>
        </form>
      </div>

    </div>
  </div>
</div>


@stop
