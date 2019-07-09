@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Cobro de Cuota - Listado de Socios</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI</th>
            <th>Numero de Socio</th>
            <th>Apellido</th>
            <th>Nombres</th>
            <th>Categoria</th>
            <th>Último mes pagado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>40566858</td>
            <td>1</td>
            <td>Ricle</td>
            <td>Joaquin</td>
            <td>Activo</td>
            <td>Enero</td>
            <td>
              <a href="{{ url('/pagocuota/pago/'.'1') }}">
                <button type="button" class="btn btn-primary tam_letra_small">
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
              <a href="{{ url('/pagocuota/pago/'.'1') }}">
                <button type="button" class="btn btn-primary tam_letra_small">
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
              <a href="{{ url('/pagocuota/pago/'.'1') }}">
                <button type="button" class="btn btn-primary tam_letra_small">
                  Pagar
                </button>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>


@stop
