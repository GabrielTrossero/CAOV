@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Inmuebles</b></label>
      @if (\Session::has('inmuebleTieneAlquileres'))
        <br>
        <span class="text-danger">{!! \Session::get('inmuebleTieneAlquileres') !!}</span>
      @endif
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($inmuebles as $inmueble)
            <tr>
              <td>{{ $inmueble->nombre }}</td>
              <td>{{ $inmueble->descripcion }}</td>
              <td>
                <a class="icono-editar-anchor" href="{{ url('/inmueble/edit/'.$inmueble->id) }}">
                   <i class="fas fa-edit icono-editar" title="Editar"></i>
                </a>
                <form action="{{url('/inmueble/delete')}}" method="post" style="display:inline">
                  {{ csrf_field() }}
                  <input type="hidden" name="id" value="{{ $inmueble->id }}">
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
