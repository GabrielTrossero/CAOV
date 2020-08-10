@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos de la Persona</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>DNI</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>Apellido</b></td>
          <td><b>Nombres</b></td>
          <td><b>Domicilio</b></td>
          <td><b>Teléfono</b></td>
          <td><b>Email</b></td>
        </tr>
        <tr>
          <td>{{ $persona->DNI }}</td>
          <td>{{ $persona->apellido }}</td>
          <td>{{ $persona->nombres }}</td>
          <td>{{ $persona->domicilio }}</td>
          <td>{{ $persona->telefono }}</td>
          <td>{{ $persona->email }}</td>
        </tr>
      </table>

      <div class="card-footer row">
        <div>
          <a style="text-decoration:none" href="{{ url('/persona/show') }}">
            <button type="button" class="btn btn-secondary">
              Volver
            </button>
          </a>
        </div>

        <div class="col-md-10 text-md-center">
          <a style="text-decoration:none" href="{{ url('/persona/edit/'.$persona->id) }}">
            <button type="button" class="btn btn-outline-warning" style="display:inline">
              Editar Persona
            </button>
          </a>
          <!-- POR EL MOMENTO NO USAMOS ESTE METODO
          &nbsp;&nbsp;
          <form action="{{ url('/persona/delete') }}" method="post" style="display:inline">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $persona->id }}">
            <button type="submit" class="btn btn-outline-danger" style="display:inline">
              Eliminar Persona
            </button>
          </form>
        -->
        </div>
        
      </div>

    </div>
  </div>
</div>


@stop
