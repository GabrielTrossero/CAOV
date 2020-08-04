@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label><b>Listado de Deportes</b></label>
    </div>
    <div class="card-body border">
      @if (\Session::has('deporteTieneSocios'))
        <div class="alert alert-danger">
          {!! \Session::get('deporteTieneSocios') !!}
        </div>
      @endif
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($deportes as $deporte)
            <tr>
              <td>{{ $deporte->nombre }}</td>
              <td>
                <a class="icono-editar-anchor" href="{{ url('/deporte/edit/'.$deporte->id) }}">
                    <i class="fas fa-edit icono-editar" title="Editar"></i>
                </a>
                @if (sizeof($deporte->socios) == 0)
                  <form action="{{url('/deporte/delete')}}" method="post" style="display:inline" onsubmit="return confirm('¿Está seguro que desea eliminar el Deporte?');">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $deporte->id }}">
                    <button class="icono-eliminar" type="submit">
                      <i class="fas fa-trash" style="color:red;" title="Eliminar"></i>
                    </button>
                  </form> 
                @else
                  <form style="display:inline">
                    <button class="icono-eliminar-disabled" type="submit" disabled>
                      <i class="fas fa-trash" style="color: darkslategray;" title="Tiene socios anotados"></i>
                    </button>
                  </form>
                @endif
                  
                 
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
