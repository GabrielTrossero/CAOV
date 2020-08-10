@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos del Inmueble</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <th>Nombre</th>
          <th>Descripción</th>
        </tr>
        <tr>
          <td>{{ $inmueble->nombre }}</td>
          <td>{{ $inmueble->descripcion }}</td>
        </tr>
      </table>

      <div class="card-footer row">
        <div >
          <a style="text-decoration:none" href="{{ url('/inmueble/show') }}">
            <button type="button" class="btn btn-secondary">
              Volver
            </button>
          </a>
        </div>

        <div class="col-md-10 text-md-center">
          <a style="text-decoration:none" href="{{ url('/inmueble/edit/'.$inmueble->id) }}">
            <button type="button" class="btn btn-outline-warning" style="display:inline">
              Editar Inmueble
            </button>
          </a>

          &nbsp;&nbsp;
          <form action="{{url('/inmueble/delete')}}" method="post" style="display:inline">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $inmueble->id }}">
            <button type="submit" class="btn btn-outline-danger" style="display:inline">
              Eliminar Inmueble
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>


@stop
