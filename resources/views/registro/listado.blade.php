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
            <th>Tipo</th>
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
              @if ($movimiento->tipo == "1")
                <td>{{ 'Ingreso' }}</td>
              @elseif ($movimiento->tipo == "2")
                <td>{{ 'Egreso' }}</td>
              @endif
              <td class="montos">{{ '$ '. $movimiento->monto }}</td>
              <td>
                <form action="{{url('/registro/delete')}}" method="post" style="display:inline" onsubmit="return confirm('¿Está seguro que desea eliminar el Registro?');">
                  {{ csrf_field() }}
                  <input type="hidden" name="id" value="{{ $movimiento->id }}">
                  <button class="icono-eliminar" type="submit">
                    <i class="fas fa-trash" style="color:red;" title="Eliminar"></i>
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="card-footer row">
        <div >
          <a style="text-decoration:none" onclick="history.back()">
            <button type="button" class="btn btn-secondary">
              Volver
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

@stop
