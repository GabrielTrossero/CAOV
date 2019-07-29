@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Lista de los Alquileres de Muebles</b></label>
    </div>
    <div class="card-body border tam_letra_small">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI Solicitante</th>
            <th>N° de Contrato Mueble</th>
            <th>Mueble</th>
            <th>Desde</th>
            <th>Hasta</th>
            <th>Costo Total</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($alquileresMuebles as $alquiler)
            <tr>
              <td>{{ $alquiler->persona->DNI }}</td>
              <td>{{ $alquiler->id }}</td>
              <td>{{ $alquiler->mueble->nombre }}</td>
              <td>{{ date("d/m/Y H:i", strtotime($alquiler->fechaHoraInicio)) }}</td>
              <td>{{ date("d/m/Y H:i", strtotime($alquiler->fechaHoraFin)) }}</td>
              <td>{{ "$". $alquiler->costoTotal }}</td>
              <td>
                <a href="{{ url('/pagoalquiler/pagomueble/'.$alquiler->id) }}">
                  <button type="button" class="btn btn-primary tam_letra_x-small" style="width:50px; height:27px">
                    Pagar
                  </button>
                </a>
              </td>
            </tr>
          @endforeach
        <tbody>
      </table>
    </div>
  </div>
</div>


@stop
