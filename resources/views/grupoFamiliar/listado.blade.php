@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <table>
        <div class="form-group row">
          <label class="col-md-8 col-form-label"><b>Listado de Grupos Familiares</b></label>
          <div class="col-md-3">
            <input type="text" name="buscar" id="filtroDNI" class="form-control" placeholder="Filtrar DNI">
          </div>
        </div>
      </table>
    </div>

    <div class="card-body border">
      <table class="table" id="tablaFiltroDNI">
        <tr>
          <td><b>Titular</b></td><!-- la <b> es para poner en negrita -->
          <td><b>Ver Grupo Familiar</b></td>
        </tr>
        <tr>
          <td>39875632 - Penka Ricle</td>
          <td><a href="{{ url('/grupofamiliar/show/'.'1') }}" style="color: red;">ver</a> </td>
        </tr>
        <tr>
          <td>39875245 - Ema Goette</td>
          <td><a href="#" style="color: red;">ver</a> </td>
        </tr>
        <tr>
          <td>38752986 - Misio Nero</td>
          <td><a href="#" style="color: red;">ver</a> </td>
        </tr>
      </table>
    </div>
  </div>
</div>

@stop
