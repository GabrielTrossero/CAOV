@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos del Empleado</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>DNI</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>Nombre de Usuario</b></td>
          <td><b>Email</b></td>
          <td><b>Apellido</b></td>
          <td><b>Nombres</b></td>
          <td><b>Tipo de Usuario</b></td>
          <td><b>Activo</b></td>
        </tr>
        <tr>
          <td>{{ $usuario->persona->DNI }}</td>
          <td>{{ $usuario->username }}</td>
          <td>{{ $usuario->email }}</td>
          <td>{{ $usuario->persona->apellido }}</td>
          <td>{{ $usuario->persona->nombres }}</td>
          <td>{{ $usuario->tipoUsuario->nombre }}</td>
          @if ($usuario->activo)
              <td>Si</td>
          @else
              <td>No</td>
          @endif
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
          <a style="text-decoration:none" href="{{ url('/empleado/edit/'.$usuario->id) }}">
            <button type="button" class="btn btn-outline-warning" style="display:inline">
              Editar Empleado
            </button>
          </a>

          &nbsp;&nbsp;
          
          @if ($usuario->activo)
            <form action="{{url('/empleado/delete')}}" method="post" style="display:inline">
              {{ csrf_field() }}
              <input type="hidden" name="id" value="{{ $usuario->id }}">
              <button type="submit" class="btn btn-outline-danger" style="display:inline">
                Desactivar Empleado
              </button>
            </form>
          @else
            <form action="{{url('/empleado/enable')}}" method="post" style="display:inline">
              {{ csrf_field() }}
              <input type="hidden" name="id" value="{{ $usuario->id }}">
              <button type="submit" class="btn btn-outline-danger" style="display:inline">
                Activar Empleado
              </button>
            </form> 
          @endif
        </div>
      </div>
    </div>
  </div>
</div>


@stop
