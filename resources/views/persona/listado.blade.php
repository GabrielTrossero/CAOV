@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Listado de Personas</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>DNI</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>Apellido</b></td>
          <td><b>Nombres</b></td>
          <td><b>Ver Persona</b></td>
        </tr>
        <tr>
          <td>36854715</td>
          <td>asdf</td>
          <td>sdfsdf</td>
          <td><a href="#" style="color: red;">ver</a> </td>
        </tr>
        <tr>
          <td>544114454</td>
          <td>asdf</td>
          <td>sdfsdf</td>
          <td><a href="#" style="color: red;">ver</a> </td>
        </tr>
        <tr>
          <td>544114454</td>
          <td>asdf</td>
          <td>sdfsdf</td>
          <td><a href="#" style="color: red;">ver</a> </td>
        </tr>
      </table>
    </div>
  </div>
</div>

@stop
