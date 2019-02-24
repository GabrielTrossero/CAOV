@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos del Alquiler Mueble</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>N° de Contrato Mueble</b></td>    <!-- la <b> es para poner en negrita -->
          <td><b>DNI Solicitante</b></td>
          <td><b>Fecha Solicitud</b></td>
          <td><b>Cantidad Tablones</b></td>
          <td><b>Cantidad Sillas</b></td>
          <td><b>Cantidad Caballetes</b></td>
          <td><b>Fecha Realización</b></td>
          <td><b>Observación</b></td>
          <td><b>Costo Total</b></td>
          <td><b>N° Recibo</b></td>
          <td><b>Medio de Pago</b></td>
        </tr>
        <tr>
          <td>1</td>
          <td>40662158</td>
          <td>09/02/2019</td>
          <td>5</td>
          <td>30</td>
          <td>10</td>
          <td>05/05/2019</td>
          <td></td>
          <td>1200</td>
          <td>32</td>
          <td>Efectivo</td>
        </tr>
      </table>

      <div class="card-footer">

        <a style="text-decoration:none" href="{{ url('/alquilermueble/edit/'.'1') }}">
          <button type="button" class="btn btn-outline-warning" style="display:inline">
            Editar Alquiler
          </button>
        </a>

        &nbsp;&nbsp;
        <form action="{{url('/alquilermueble/delete')}}" method="post" style="display:inline">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="1">
          <button type="submit" class="btn btn-outline-danger" style="display:inline">
            Eliminar Alquiler
          </button>
        </form>
      </div>

    </div>
  </div>
</div>


@stop
