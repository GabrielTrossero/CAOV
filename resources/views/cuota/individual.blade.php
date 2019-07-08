@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos de la Cuota</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <th>DNI Socio</th>
          <th>Tipo Socio</th>
          <th>Mes/Anio</th>
          <th>Fecha Pago</th>
          <th>Monto Mensual</th>
          <th>Tipo de Cobro</th>
          <th>Medio de Pago</th>
        </tr>
        <tr>
          <td>40662158</td>
          <td>Grupo Familiar</td>
          <td>01/02/2019</td>
          <td>05/05/2019</td>
          <td>150</td>
          <td>Semestral</td>
          <td>Efectivo</td>
        </tr>
      </table>

      <div class="card-footer">

        <a style="text-decoration:none" href="{{ url('/cuota/edit/'.'1') }}">
          <button type="button" class="btn btn-outline-warning" style="display:inline">
            Editar Cuota
          </button>
        </a>

        &nbsp;&nbsp;
        <form action="{{url('/cuota/delete')}}" method="post" style="display:inline">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="1">
          <button type="submit" class="btn btn-outline-danger" style="display:inline">
            Eliminar Cuota
          </button>
        </form>
      </div>

    </div>
  </div>
</div>


@stop
