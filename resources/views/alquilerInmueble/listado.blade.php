@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <table>
        <div class="form-group row">
          <label class="col-md-8 col-form-label"><b>Listado de Alquileres de Inmuebles</b></label>
            <div class="col-md-3">
              <input type="text" name="buscar" id="filtroDNI" class="form-control" placeholder="Filtrar DNI">
            </div>
        </div>
      </table>
    </div>

    <div class="card-body border">
      <table class="table" id="tablaFiltroDNI">
        <tr>
          <td><b>DNI Solicitante</b></td>    <!-- la <b> es para poner en negrita -->
          <td><b>N° de Contrato Inmueble</b></td>
          <td><b>Fecha Realización</b></td>
          <td><b>Costo Reserva</b></td>
          <td><b>Costo Total</b></td>
          <td><b>N° Recibo</b></td>
          <td><b>Ver Alquiler</b></td>
        </tr>
        <tr>
          <td>39842653</td>
          <td>1</td>
          <td>27/05/2019</td>
          <td>1000</td>
          <td>3000</td>
          <td>21</td>
          <td><a href="{{ url('/alquilerinmueble/show/'.'1') }}" style="color: red;">ver</a> </td>
        </tr>
        <tr>
          <td>25963214</td>
          <td>2</td>
          <td>05/05/2019</td>
          <td>4500</td>
          <td>1500</td>
          <td></td>
          <td><a href="{{ url('/alquilerinmueble/show/'.'2') }}" style="color: red;">ver</a> </td>
        </tr>
      </table>
    </div>
  </div>
</div>

@stop
