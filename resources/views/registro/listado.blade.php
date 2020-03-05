@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
          <label class="col-md-8 col-form-label"><b>Listado de Registros</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        @if (\Session::has('validarEliminar'))
          <div class="alert alert-danger">
            {!! \Session::get('validarEliminar') !!}
          </div>
        @endif
        <thead>
          <tr>
            <th>Numero de Recibo</th>
            <th>Descripcion</th>
            <th>Fecha</th>
            <th>Monto</th>
            <th>Eliminar</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($movimientos as $movimiento)
            <tr>
              <td>{{ $movimiento->numRecibo }}</td>
              <td>{{ $movimiento->descripcion }}</td>
              <td>{{ date("d/m/Y", strtotime($movimiento->fecha)) }}</td>
              <td>${{ $movimiento->monto }}</td>
              <td><a href="{{ url('/registro/delete/'.$movimiento->id) }}" style="color:red";> <i class="fas fa-trash"></i></a> </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
