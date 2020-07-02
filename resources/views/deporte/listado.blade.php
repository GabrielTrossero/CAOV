@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label><b>Listado de Deportes</b></label>
      @if (\Session::has('deporteTieneSocios'))
        <br>
        <span class="text-danger">{!! \Session::get('deporteTieneSocios') !!}</span>
      @endif
    </div>
    <div class="card-body border">
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
                 <form action="{{url('/deporte/delete')}}" method="post" style="display:inline">
                   {{ csrf_field() }}
                   <input type="hidden" name="id" value="{{ $deporte->id }}">
                   <button class="icono-eliminar" type="submit">
                     <i class="fas fa-trash" style="color:red;" title="Eliminar"></i>
                   </button>
                 </form> 
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
