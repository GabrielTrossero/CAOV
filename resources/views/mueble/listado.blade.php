@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
          <label class="col-md-8 col-form-label"><b>Listado de Muebles</b></label>
    </div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>Nombre</b></td>
          <td><b>Cantidad</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>Ver Mueble</b></td>
        </tr>
        <tr>
          <td>Sillas</td>
          <td>200</td>
          <td><a href="{{ url('/mueble/show/'.'1') }}" style="color: red;">ver</a> </td>
        </tr>
        <tr>
          <td>Mesas</td>
          <td>20</td>
          <td><a href="{{ url('/mueble/show/'.'2') }}" style="color: red;">ver</a> </td>
        </tr>
        <tr>
          <td>Caballetes</td>
          <td>40</td>
          <td><a href="{{ url('/mueble/show/'.'3') }}" style="color: red;">ver</a> </td>
        </tr>
      </table>
    </div>
  </div>
</div>

@stop
