@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">

    <div class="card-header">
      <table>
        <div class="form-group row">
          <label class="col-md-9 col-form-label"><b>Detalles del Socio Deudor</b></label>
            <div class="col-md-3">
              <b>DNI: 36987744</b>
              <br>
              <b>N° de Socio: 1</b>
            </div>
        </div>
      </table>
    </div>

    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>Tipo de Deuda</b></td>
          <td><b>Monto</b></td>
          <td><b>Detalle de la Deuda</b></td>
        </tr>
        <tr>
          <td>Cuota</td>
          <td>250</td>
          <td><a href="{{ url('/cuota') }}"> <i class="fas fa-plus"></i></a> </td>
        </tr>
        <tr>
          <td>Alquiler Mueble</td>
          <td>1500</td>
          <td><a href="{{ url('/pagoalquiler/listamueble') }}"> <i class="fas fa-plus"></i></a> </td>
        </tr>
        <tr>
          <td>Alquiler Inmueble</td>
          <td>3000</td>
          <td><a href="{{ url('/pagoalquiler/listainmueble') }}"> <i class="fas fa-plus"></i></a> </td>
        </tr>
        <!-Cuando se aprieta ver se tendría que setear en el input de los listado de cuotas y alquileres el DNI del socio->
      </table>

      <div class="card-footer">
        &nbsp;&nbsp;
        <form action="{{url('/informe/pdf_socio_deudor')}}" method="get" style="display:inline">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="1">
          <button type="submit" class="btn btn-outline-danger" style="display:inline">
            Generar PDF
          </button>
        </form>
      </div>

    </div>
  </div>
</div>


@stop
