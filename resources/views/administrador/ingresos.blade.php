@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
          <label class="col-md-8 col-form-label"><b>Listado de Ingresos</b></label>
    </div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>Numero de Recibo</b></td>
          <td><b>Descripcion</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>Fecha</b></td>
          <td><b>Monto</b></td>
        </tr>
        <tr>
          <td>1</td>
          <td>Subsidio Municipalidad</td>
          <td>25/12/2014</td>
          <td>$5000</td>
        </tr>
        <tr>
          <td>2</td>
          <td>Donación Anonima</td>
          <td>12/8/2018</td>
          <td>$2500</td>
        </tr>
        <tr>
          <td>3</td>
          <td>Alquiler sillas</td>
          <td>6/11/2015</td>
          <td>$1700</td>
        </tr>
      </table>
    </div>
  </div>
</div>

@stop
