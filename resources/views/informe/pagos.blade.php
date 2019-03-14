@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
          <label class="col-md-8 col-form-label"><b>Listado de Pagos</b></label>
    </div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>Numero de Recibo</b></td>
          <td><b>Fecha</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>Descripcion</b></td>
          <td><b>Monto $</b></td>
        </tr>
        <tr>
          <td>1</td>
          <td>23/2/2019</td>
          <td>Pago alquiler cancha</td>
          <td>600</td>
        </tr>
        <tr>
          <td>2</td>
          <td>2/3/2019</td>
          <td>Pago cuota societaria</td>
          <td>120</td>
        </tr>
        <tr>
          <td>3</td>
          <td>12/2/2019</td>
          <td>Pago alquiler sillas</td>
          <td>550</td>
        </tr>
      </table>
    </div>
  </div>
</div>

@stop
