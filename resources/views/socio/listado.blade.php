@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <table>
        <div class="form-group row">
          <label class="col-md-8 col-form-label"><b>Listado de Socios</b></label>
            <div class="col-md-3">
              <input type="text" name="buscar" id="filtroDNI" class="form-control" placeholder="Filtrar DNI">
            </div>
        </div>
      </table>
    </div>

    <div class="card-body border">
      <table class="table" id="tablaFiltroDNI">
        <tr>
          <td><b>DNI</b></td>
          <td><b>Numero de Socio</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>Apellido</b></td>
          <td><b>Nombres</b></td>
          <td><b>Categoria</b></td>
          <td><b>Oficio</b></td>
          <td><b>Deportes</b></td>
          <td><b>Ver Socio</b></td>
        </tr>
        <tr>
          <td>39842653</td>
          <td>1</td>
          <td>Dreher</td>
          <td>Francisco</td>
          <td>Activo</td>
          <td>Director Técnico de Patronato</td>
          <td>
            Futbol
            <br>
            Hockey
          </td>
          <td><a href="{{ url('/socio/show/'.'1') }}" style="color: red;">ver</a> </td>
        </tr>
        <tr>
          <td>38956324</td>
          <td>2</td>
          <td>Trillo</td>
          <td>Tula</td>
          <td>Honorario</td>
          <td>Intendente</td>
          <td>
            Volley
          </td>
          <td><a href="{{ url('/socio/show/'.'2') }}" style="color: red;">ver</a> </td>
        </tr>
      </table>
    </div>
  </div>
</div>

@stop
