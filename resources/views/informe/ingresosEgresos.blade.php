@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">

    <div class="card-header">
          <label class="col-md-8 col-form-label"><b>Listado de Ingresos/Egresos</b></label>
    </div>
    
    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>Tipo</b><td>
          <td><b>Numero de Recibo</b></td>
          <td><b>Descripcion</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>Fecha</b></td>
          <td><b>Monto</b></td>
        </tr>
        <tr>
          <td>Ingreso<td>
          <td>1</td>
          <td>Subsidio de la Cristi</td>
          <td>25/12/2014</td>
          <td>$5000</td>
        </tr>
        <tr>
          <td>Ingreso<td>
          <td>2</td>
          <td>Donación de los Pro-Aborto</td>
          <td>12/8/2018</td>
          <td>$2500</td>
        </tr>
        <tr>
          <td>Egreso<td>
          <td>3</td>
          <td>Venta de paragua venenoso</td>
          <td>6/11/2015</td>
          <td>$10000</td>
        </tr>
      </table>
    </div>

    <div class="card-footer">
      <form action="{{url('/informe/ingresos_egresos')}}" method="post" style="display:inline">
        {{ csrf_field() }}
        <button type="submit" class="btn btn-outline-danger" style="display:inline">
          Generar PDF
        </button>
      </form>
    </div>

  </div>
</div>

@stop
