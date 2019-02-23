@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <table>
        <div class="form-group row">
          <label class="col-md-8 col-form-label"><b>Listado de Personas</b></label>
            <div class="col-md-3">
              <input type="text" name="buscar" id="filtroDNI" class="form-control" placeholder="Filtrar DNI">
            </div>
        </div>
      </table>
    </div>

    <div class="card-body border">
      <table class="table" id="tablaFiltroDNI">
        <tr>
          <td><b>DNI</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>Apellido</b></td>
          <td><b>Nombres</b></td>
          <td><b>Ver Persona</b></td>
        </tr>
        <tr>
          <td>36854715</td>
          <td>Pichon</td>
          <td>Culiao</td>
          <td><a href="{{ url('/persona/show/'.'1') }}" style="color: red;">ver</a> </td>
        </tr>
        <tr>
          <td>37411445</td>
          <td>Benka</td>
          <td>Penka</td>
          <td><a href="#" style="color: red;">ver</a> </td>
        </tr>
        <tr>
          <td>36411485</td>
          <td>Misio</td>
          <td>Nero</td>
          <td><a href="#" style="color: red;">ver</a> </td>
        </tr>
      </table>
    </div>
  </div>
</div>

@stop
