@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Detalles de la Cuota</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <th>DNI Socio</th>
          <th>Tipo Socio (Actual)</th>
          <th>Mes/Anio</th>
          <th>Fecha Pago</th>
          <th>Monto Mensual Cobrado</th>
          <th>Descuento Aplicado</th>
          <th>Tipo de Cobro</th>
          <th>Tipo Socio (Cobrado)</th>
          <th>Medio de Pago</th>
        </tr>

        <tr>
          <td>{{ $cuota->socio->persona->DNI }}</td>

          @if ($cuota->socio->vitalicio == 's')
            <td>{{ 'Vitalicio' }}</td>
          @elseif ($cuota->socio->idGrupoFamiliar)
            <td>{{ 'Grupo Familiar' }}</td>
          @elseif ($cuota->socio->edad >= 18)
            <td>{{ 'Activo' }}</td>
          @else
            <td>{{ 'Cadete' }}</td>
          @endif

          <td>{{date("m/Y", strtotime($cuota->fechaMesAnio))}}</td> <!-- para mostrar solo mes/año -->
          <td>{{date("d/m/Y", strtotime($cuota->fechaPago))}}</td><!-- para mostrar en formato dia/mes/año -->

          <!--para mostrar el monto con los corresponientes descuentos-->
          @if ($cuota->tipo == "s")
            <td>{{ "$". ($cuota->montoCuota->monto - ($cuota->montoCuota->monto * $cuota->montoCuota->dtoSemestre / 100)) }}</td>
          @elseif ($cuota->tipo == "a")
            <td>{{ "$". ($cuota->montoCuota->monto - ($cuota->montoCuota->monto * $cuota->montoCuota->dtoAnio / 100)) }}</td>
          @elseif ($cuota->tipo == "m")
            <td>{{ "$". ($cuota->montoCuota->monto) }}</td>
          @endif


          <!--para mostrar el descuento aplicado-->
          @if ($cuota->tipo == "s")
            <td>{{ "$". $cuota->montoCuota->monto * $cuota->montoCuota->dtoSemestre / 100 ." (". $cuota->montoCuota->dtoSemestre ."%)" }}</td>
          @elseif ($cuota->tipo == "a")
            <td>{{ "$". $cuota->montoCuota->monto * $cuota->montoCuota->dtoAnio / 100 ." (". $cuota->montoCuota->dtoAnio ."%)" }}</td>
          @elseif ($cuota->tipo == "m")
            <td> 0% </td>
          @endif


          @if ($cuota->tipo == "s")
            <td>Semestral</td>
          @elseif ($cuota->tipo == "a")
            <td>Anual</td>
          @elseif ($cuota->tipo == "m")
            <td>Mensual</td>
          @endif

          @if ($cuota->montoCuota->tipo == 's')
            <td>{{ 'Vitalicio' }}</td>
          @elseif ($cuota->montoCuota->tipo == 'g')
            <td>{{ 'Grupo Familiar' }}</td>
          @elseif ($cuota->montoCuota->tipo == 'a')
            <td>{{ 'Activo' }}</td>
          @endif

          <td>{{ $cuota->medioDePago->nombre }}</td>
        </tr>
      </table>

      <div class="card-footer">

        <a style="text-decoration:none" href="{{ url('/cuota/edit/'.$cuota->id) }}">
          <button type="button" class="btn btn-outline-warning" style="display:inline">
            Editar Cuota
          </button>
        </a>

        &nbsp;&nbsp;
        <form action="{{url('/cuota/delete')}}" method="post" style="display:inline">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="{{ $cuota->id }}">
          <button type="submit" class="btn btn-outline-danger" style="display:inline">
            Eliminar Cuota
          </button>
        </form>
      </div>

    </div>
  </div>
</div>


@stop
