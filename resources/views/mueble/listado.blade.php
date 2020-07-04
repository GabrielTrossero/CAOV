@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Muebles</b></label>
      @if (\Session::has('muebleTieneAlquileres'))
        <br>
        <span class="text-danger">{!! \Session::get('muebleTieneAlquileres') !!}</span>
      @endif
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Cantidad</th>
            <th>Acciones</th>
          </tr>
        </thead>
          <tbody>
            @foreach ($muebles as $mueble)

              <tr>
                <td>{{ $mueble->nombre }}</td>
                <td>{{ $mueble->cantidad }}</td>
                <td>
                  <a class="icono-editar-anchor" href="{{ url('/mueble/edit/'.$mueble->id) }}">
                    <i class="fas fa-edit icono-editar" title="Editar"></i>
                 </a>
                 @if (sizeof($mueble->reservasDeMueble) == 0)
                  <form action="{{url('/mueble/delete')}}" method="post" style="display:inline">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $mueble->id }}">
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
    </div>
  </div>
</div>

@stop
