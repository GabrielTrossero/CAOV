@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <table>
        <div class="form-group row">
          <label class="col-md-8 col-form-label">Cobro de Cuota - Listado de Socios</label>
            <div class="col-md-3">
              <input type="text" name="buscar" id="filtroDNI" class="form-control" placeholder="Filtrar DNI">
            </div>
        </div>
      </table>
    </div>


    <div class="card-body border">
      <table class="table"  id="tablaFiltroDNI">
        <tr>
          <td><b>N° Socio</b></td>   <!-- la <b> es para poner en negrita -->
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
          <td>
            <a href="{{ url('/cuota/pago/'.'1') }}">
              <button type="button" class="btn btn-primary">
                Pagar
              </button>
            </a>
          </td>
        </tr>
        <tr>
          <td>2</td>
          <td>43435147</td>
          <td>sdfsdf</td>
          <td>sdfsdfddf dsfdf</td>
          <td>Activo</td>
          <td>Enero</td>
          <td>
            <a href="{{ url('/cuota/pago/'.'1') }}">
              <button type="button" class="btn btn-primary">
                Pagar
              </button>
            </a>
          </td>
        </tr>
        <tr>
          <td>3</td>
          <td>23695147</td>
          <td>sdfsdfddfdf</td>
          <td>sdfsd dsfdf</td>
          <td>Grupo Familiar</td>
          <td>Julio</td>
          <td>
            <a href="{{ url('/cuota/pago/'.'1') }}">
              <button type="button" class="btn btn-primary">
                Pagar
              </button>
            </a>
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>


@stop
