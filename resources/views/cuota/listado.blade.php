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
            <th>Mes/Año</th>
            <th>Fecha Pago</th>
            <th>Monto Mensual Pagado</th>
            <th>Tipo de Cobro</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cuotas as $cuota)
            <tr>
              <td>{{ $cuota->socio->persona->DNI ?? 'Socio eliminado' }}</td>
              <td>{{date("m/Y", strtotime($cuota->fechaMesAnio))}}</td> <!-- para mostrar solo mes/año -->
              <td>{{date("d/m/Y", strtotime($cuota->fechaPago))}}</td><!-- para mostrar en formato dia/mes/año -->

              <!--para mostrar con los corresponientes descuentos-->
              @if ($cuota->tipo == "s")
                <td>{{ "$". ($cuota->montoCuota->monto - ($cuota->montoCuota->monto * $cuota->montoCuota->dtoSemestre / 100)) }}</td>
              @elseif ($cuota->tipo == "a")
                <td>{{ "$". ($cuota->montoCuota->monto - ($cuota->montoCuota->monto * $cuota->montoCuota->dtoAnio / 100)) }}</td>
              @elseif ($cuota->tipo == "m")
                <td>{{ "$". ($cuota->montoCuota->monto) }}</td>
              @endif

              @if ($cuota->tipo == "s")
                <td>Semestral</td>
              @elseif ($cuota->tipo == "a")
                <td>Anual</td>
              @elseif ($cuota->tipo == "m")
                <td>Mensual</td>
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
