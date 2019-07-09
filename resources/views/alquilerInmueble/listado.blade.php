@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Alquileres de Inmuebles</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI Solicitante</th>
            <th>N° de Contrato Inmueble</th>
            <th>Fecha Realización</th>
            <th>Costo Reserva</th>
            <th>Costo Total</th>
            <th>N° Recibo</th>
            <th>Ver Alquiler</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>39842653</td>
            <td>1</td>
            <td>27/05/2019</td>
            <td>1000</td>
            <td>3000</td>
            <td>21</td>
            <td><a href="{{ url('/alquilerinmueble/show/'.'1') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
          <tr>
            <td>25963214</td>
            <td>2</td>
            <td>05/05/2019</td>
            <td>4500</td>
            <td>1500</td>
            <td></td>
            <td><a href="{{ url('/alquilerinmueble/show/'.'2') }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
