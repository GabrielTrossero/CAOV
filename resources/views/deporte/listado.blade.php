@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label><b>Listado de Deportes</b></label>
    </div>

    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>Nombre</b></td>     <!-- la <b> es para poner en negrita -->
          <td><b>Ver Deporte</b></td>
        </tr>
        <tr>
          <td>Futbol</td>
          <td><a href="{{ url('/deporte/show/'.'1') }}" style="color: red;">ver</a> </td>
        </tr>
        <tr>
          <td>Basquet</td>
          <td><a href="{{ url('/deporte/show/'.'2') }}" style="color: red;">ver</a> </td>
        </tr>
      </table>
    </div>
  </div>
</div>

@stop
