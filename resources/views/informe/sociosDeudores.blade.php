@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">

    <div class="card-header">
      <table>
        <div class="form-group row">
          <label class="col-md-8 col-form-label"><b>Listado de Socios Deudores</b></label>
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
          <td><b>Deuda</b></td>
          <td><b>Ver Detalles</b></td>
        </tr>
        <tr>
          <td>39842653</td>
          <td>1</td>
          <td>Ricle</td>
          <td>Penka</td>
          <td>3800</td>
          <td><a href="{{ url('/informe/show/'.'1') }}" style="color: red;">ver</a> </td>
        </tr>
        <tr>
          <td>38956324</td>
          <td>2</td>
          <td>Tula</td>
          <td>Tula</td>
          <td>1000</td>
          <td><a href="{{ url('/informe/show/'.'2') }}" style="color: red;">ver</a> </td>
        </tr>
      </table>
    </div>

    <div class="card-footer">
      <form action="{{url('/informe/deudores')}}" method="post" style="display:inline">
        {{ csrf_field() }}
        <button type="submit" class="btn btn-outline-danger" style="display:inline">
          Generar PDF
        </button>
      </form>
    </div>

  </div>
</div>

@stop
