@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Lista de los Alquileres de Muebles</b></label>
    </div>
    <div class="card-body border tam_letra_small">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI Solicitante</th>
            <th>N° de Contrato Mueble</th>
            <th>Fecha Solicitud</th>
            <th>Fecha Realización</th>
            <th>Costo Total</th>
            <th>N° Recibo</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>40662158</td>
            <td>1</td>
            <td>09/02/2019</td>
            <td>05/05/2019</td>
            <td>1200</td>
            <td>32</td>
            <td>
              <a href="{{ url('/pagoalquiler/pagomueble/'.'1') }}">
                <button type="button" class="btn btn-primary tam_letra_x-small" style="width:50px; height:27px">
                  Pagar
                </button>
              </a>
            </td>
          </tr>

          <tr>
            <td>32569322</td>
            <td>2</td>
            <td>17/02/2019</td>
            <td>12/10/2019</td>
            <td>3600</td>
            <td>39</td>
            <td>
              <a href="{{ url('/pagoalquiler/pagomueble/'.'1') }}">
                <button type="button" class="btn btn-primary tam_letra_x-small" style="width:50px; height:27px">
                  Pagar
                </button>
              </a>
            </td>
          </tr>
        <tbody>
      </table>
    </div>
  </div>
</div>


@stop
