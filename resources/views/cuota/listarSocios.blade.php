@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <table>
        <div class="form-group row">
          <form method="POST" action="{{ url('#') }}">
            {{ csrf_field() }}
            <label class="col-md-8 col-form-label">Cobro de Cuota - Listado de Socios</label>
            <div class="col-md-3">
                <input type="number" name="buscar" id="buscar" class="form-control" placeholder="Ingresar DNI">
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
          </form>
        </div>
      </table>
    </div>

    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>N° Socios</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>DNI</b></td>
          <td><b>Apellido</b></td>
          <td><b>Nombres</b></td>
          <td><b>Categoria</b></td>
          <td><b>Último mes pagado</b></td>
        </tr>
        <tr>
          <td>1</td>
          <td>40895147</td>
          <td>sdfsdf</td>
          <td>sdfs dsfdf</td>
          <td>Activo</td>
          <td>Enero</td>
        </tr>
        <tr>
          <td>2</td>
          <td>43435147</td>
          <td>sdfsdf</td>
          <td>sdfsdfddf dsfdf</td>
          <td>Activo</td>
          <td>Enero</td>
        </tr>
        <tr>
          <td>3</td>
          <td>23695147</td>
          <td>sdfsdfddfdf</td>
          <td>sdfsd dsfdf</td>
          <td>Grupo Familiar</td>
          <td>Julio</td>
        </tr>
      </table>
    </div>
  </div>
</div>

@stop
