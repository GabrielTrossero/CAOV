@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <table>
        <div class="form-group row">
          <label class="col-md-8 col-form-label"><b>Cobro de Cuota - Listado de Socios</b></label>
            <div class="col-md-3">
              <input type="text" name="buscar" id="filtroDNI" class="form-control" placeholder="Filtrar DNI">
            </div>
        </div>
      </table>
    </div>


    <div class="card-body border">
      <table class="table"  id="tablaFiltroDNI">
        <tr>
          <td><b>DNI</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>Numero de Socio</b></td>
          <td><b>Apellido</b></td>
          <td><b>Nombres</b></td>
          <td><b>Categoria</b></td>
          <td><b>Último mes pagado</b></td>
        </tr>
        <tr>
          <td>40566858</td>
          <td>1</td>
          <td>Ricle</td>
          <td>Joaquin</td>
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
          <td>43568695</td>
          <td>2</td>
          <td>Lopez</td>
          <td>Anibal</td>
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
          <td>23568954</td>
          <td>3</td>
          <td>Stallman</td>
          <td>Richard</td>
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
