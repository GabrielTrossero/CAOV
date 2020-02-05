@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Cuotas</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI Socio</th>
            <th>N° de Socio</th>
            <th>Mes/Año</th>
            <th>Estado Cuota</th>
            <th>Monto Base</th>
            <th>Monto Total</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cuotas as $cuota)
            <tr>
              <td>{{ $cuota->socio->persona->DNI ?? 'Socio eliminado' }}</td>
              <td>{{ $cuota->socio->numSocio }}</td>
              <td>{{date("m/Y", strtotime($cuota->fechaMesAnio))}}</td> <!-- para mostrar solo mes/año -->

              @if ($cuota->inhabilitada)
                <td>{{ 'Inhabilitada' }}</td>
              @elseif ($cuota->fechaPago)
                <td>{{ 'Pagada' }}</td>
              @else
                <td>{{ 'No Pagada' }}</td>
              @endif

              <td>{{ '$'.$cuota->montoCuota->montoMensual }}</td>

              @if ($cuota->fechaPago)
                <!--suma del monto base + intereses por atraso + intereses cantidad integrantes -->
                <td>{{ '$'. ($cuota->montoCuota->montoMensual + $cuota->montoInteresAtraso + $cuota->montoInteresGrupoFamiliar) }}</td>
              @else
                <td>{{ 'Sin Fecha de Pago' }}</td>
              @endif

              <td><a href="{{ url('/cuota/show/'.$cuota->id) }}"> <i class="fas fa-plus"></i></a> </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@stop
