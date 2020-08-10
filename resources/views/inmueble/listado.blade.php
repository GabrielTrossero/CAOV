@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Inmuebles</b></label>
      @if (\Session::has('inmuebleTieneAlquileres'))
        <div class="alert alert-danger">
          {!! \Session::get('inmuebleTieneAlquileres') !!}
        </div>
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
                @if (sizeof($inmueble->reservasDeInmueble) == 0)
                  <form action="{{url('/inmueble/delete')}}" method="post" style="display:inline" onsubmit="return confirm('¿Está seguro que desea eliminar el Inmueble?');">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $inmueble->id }}">
                    <button class="icono-eliminar" type="submit">
                      <i class="fas fa-trash" style="color:red;" title="Eliminar"></i>
                    </button>
                  </form>
                @else
                  <form style="display:inline">
                    <button class="icono-eliminar-disabled" type="submit" disabled>
                      <i class="fas fa-trash" style="color: darkslategray;" title="Tiene alquileres asociados"></i>
                    </button>
                  </form>
                @endif 
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="card-footer row">
        <div >
          <a style="text-decoration:none" href="{{ url('/inmueble') }}">
            <button type="button" class="btn btn-secondary">
              Volver
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

@stop
