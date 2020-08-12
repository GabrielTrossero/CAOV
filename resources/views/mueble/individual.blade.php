@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos del Mueble</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <th>Nombre</th>
          <th>Cantidad</th>
        </tr>
        <tr>
          <td>{{ $mueble->nombre }}</td>
          <td>{{ $mueble->cantidad }}</td>
        </tr>
      </table>

      <div class="card-footer row">
        <div >
          <a style="text-decoration:none" onclick="history.back()">
            <button type="button" class="btn btn-secondary">
              Volver
            </button>
          </a>
        </div>

        <div class="col-md-10 text-md-center">
          <a style="text-decoration:none" href="{{ url('/mueble/edit/'.$mueble->id) }}">
            <button type="button" class="btn btn-outline-warning" style="display:inline">
              Editar Mueble
            </button>
          </a>

          &nbsp;&nbsp;
          <form action="{{url('/mueble/delete')}}" method="post" style="display:inline">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $mueble->id }}">
            <button type="submit" class="btn btn-outline-danger" style="display:inline">
              Eliminar Mueble
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>


@stop
