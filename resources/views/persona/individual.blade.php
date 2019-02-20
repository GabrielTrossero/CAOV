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
          <td>40662158</td>
          <td>Zapata</td>
          <td>Juan Bautista</td>
          <td>Los Cardenales 448 - Oro Verde</td>
          <td>3435908231</td>
          <td>zapa@tilla.com</td>
        </tr>
      </table>

      <div class="card-footer">
        <form action="{{action('PersonaController@update')}}" method="post" style="display:inline">
          {{ csrf_field() }}
          <button type="submit" class="btn btn-outline-warning" style="display:inline">
            Editar Persona
          </button>
        </form>
        &nbsp;&nbsp;
        <form action="{{action('PersonaController@destroy')}}" method="post" style="display:inline">
          {{ csrf_field() }}
          <button type="submit" class="btn btn-outline-danger" style="display:inline">
            Eliminar Persona
          </button>
        </form>
      </div>

    </div>
  </div>
</div>


@stop
