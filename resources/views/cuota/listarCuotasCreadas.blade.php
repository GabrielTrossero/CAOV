@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Cuotas Creadas</b></label>
    </div>
    <div class="card-body border">
      @if ($integrantesEliminados > 0)
        <div class="alert alert-warning">
          {{ 'Atención: se han eliminado '. $integrantesEliminados .' cadete/s de diferentes grupos por cumplir 18 años y pasar a ser activo/s.' }}
        </div>
      @endif
      @if ($gruposEliminados > 0)
        <div class="alert alert-warning">
          {{ 'Atención: se han eliminado '. $gruposEliminados .' grupo/s por tener un solo integrante.' }}
        </div>
      @endif
      @if ($cuotasCreadas->count() == 0)
        <div class="alert alert-danger">
          {{ 'No hay cuotas pendientes para generar.' }}
        </div>
      @endif
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI Socio</th>
            <th>N° de Socio</th>
            <th>Mes/Año</th>
            <th>Estado Cuota</th>
            <th>Monto Base</th>
            <th>Monto Pagado</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cuotasCreadas as $cuota)
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


                <td>{{ '-' }}</td>

              <td><a href="{{ url('/cuota/show/'.$cuota->id) }}"> <i class="fas fa-plus"></i></a> </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="card-footer row">
        <div >
          <a style="text-decoration:none" href="{{ url('/cuota') }}">
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
