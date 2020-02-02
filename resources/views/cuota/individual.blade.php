@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Información del Socio</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <th>DNI</th>
          <th>Numero de Socio</th>
          <th>Apellido</th>
          <th>Nombres</th>
          <th>Categoria</th>
          <th>Oficio</th>
          <th>Deportes</th>
          <th>Fecha de Nacimiento</th>
          <th>Activo</th>
        </tr>

        <tr>
          <td>{{ $cuota->socio->persona->DNI }}</td>
          <td>{{ $cuota->socio->numSocio }}</td>
          <td>{{ $cuota->socio->persona->apellido }}</td>
          <td>{{ $cuota->socio->persona->nombres }}</td>

          @if ($cuota->socio->vitalicio == 's')
            <td>{{ 'Vitalicio' }}</td>
          @elseif ($cuota->socio->idGrupoFamiliar)
            <td>{{ 'Grupo Familiar' }}</td>
          @elseif ($cuota->socio->edad >= 18)
            <td>{{ 'Activo' }}</td>
          @else
            <td>{{ 'Cadete' }}</td>
          @endif

          <td>{{ $cuota->socio->oficio }}</td>

          <td>
            @foreach ($cuota->socio->deportes as $deporte)
              {{ $deporte->nombre }}
              <br>
            @endforeach
          </td>

          <td>{{ date("d/m/Y", strtotime($cuota->socio->fechaNac)) }}</td>

          @if ($cuota->socio->activo)
            <td>Si</td>
          @else
            <td>No</td>
          @endif

        </tr>
      </table>

    </div>
  </div>



&nbsp;&nbsp;



  <div class="card">
    <div class="card-header">Detalles de la Cuota</div>
    <div class="card-body border tam_letra_small">
      <table class="table">
        <tr>
          <th>Estado Cuota</th>
          <th>Mes/Anio</th>
          <th>Fecha Pago</th>
          <th>Monto Base</th>
          <th>Interés por Atrazo de Pago</th>
          <th>Mes/es de Atrazo</th>
          <th>Interés por Integrante/s de Grupo Familiar</th>
          <th>Cantidad de Integrante/s</th>
          <th>Monto Total</th>
          <th>Tipo Socio (Cobrado)</th>
          <th>Medio de Pago</th>
          <th>Acción</th>
        </tr>

        <tr>
          @if ($cuota->inhabilitada)
            <td>{{ 'Inhabilitada' }}</td>
          @elseif ($cuota->fechaPago)
          <td>{{ 'Pagada' }}</td>
          @else
            <td>{{ 'No Pagada' }}</td>
          @endif

          <td>{{date("m/Y", strtotime($cuota->fechaMesAnio))}}</td> <!-- para mostrar solo mes/año -->
          @if ($cuota->fechaPago)
            <td>{{date("d/m/Y", strtotime($cuota->fechaPago))}}</td><!-- para mostrar en formato dia/mes/año -->
          @else
            <td>{{ '-' }}</td>
          @endif

          <td>{{ '$'. $cuota->montoCuota->montoMensual }}</td>

          @if ($cuota->fechaPago)
            <!--si la cantidad de meses atrazados es mayor que la cantidad mínima de montoCuota (para que no quede negativo)-->
            @if ($cuota->mesesAtrazados > $cuota->montoCuota->cantidadMeses)
              <td>{{ '$'. $cuota->montoInteresAtrazo ." (". ($cuota->mesesAtrazados - $cuota->montoCuota->cantidadMeses) ." mes/es)" }}</td>
            @else
              <td>{{ "$0" }}</td>
            @endif
          @else
            <td>{{ 'Cuota no pagada' }}</td>
          @endif

          @if ($cuota->fechaPago)
            <td>{{ $cuota->mesesAtrazados ." mes/es" }}</td>
          @else
            <td>{{ 'Cuota no pagada' }}</td>
          @endif

          @if ($cuota->montoCuota->tipo == 'g')
            <!--si la cantidad de integrantes registrada es mayor que la cantidad mínima de montoCuota (para que no quede negativo)-->
            @if ($cuota->cantidadIntegrantes > $cuota->montoCuota->cantidadIntegrantes)
              <td>{{ '$'. $cuota->montoInteresGrupoFamiliar ." (". ($cuota->cantidadIntegrantes - $cuota->montoCuota->cantidadIntegrantes) ." integrante/s)" }}</td>
            @else
              <td>{{ "$0" }}</td>
            @endif
          @else
            <td>{{ 'No pertenece a un grupo familiar' }}</td>
          @endif

          @if ($cuota->montoCuota->tipo == 'g')
            <td>{{ $cuota->cantidadIntegrantes ." integrante/s" }}</td>
          @else
            <td>{{ 'No pertenece a un grupo familiar' }}</td>
          @endif

          @if ($cuota->fechaPago)
            <!--suma del monto base + intereses por atrazo + intereses cantidad integrantes -->
            <th>{{ '$'. ($cuota->montoCuota->montoMensual + $cuota->montoInteresAtrazo + $cuota->montoInteresGrupoFamiliar) }}</th>
          @else
            <td>{{ 'Sin Fecha de Pago' }}</td>
          @endif

          @if ($cuota->montoCuota->tipo == 'c')
            <td>{{ 'Cadete' }}</td>
          @elseif ($cuota->montoCuota->tipo == 'g')
            <td>{{ 'Grupo Familiar' }}</td>
          @elseif ($cuota->montoCuota->tipo == 'a')
            <td>{{ 'Activo' }}</td>
          @endif

          @if ($cuota->fechaPago != '')
            <td>{{ $cuota->medioDePago->nombre }}</td>
          @else
            <td>{{ '-' }}</td>
          @endif

          @if (($cuota->fechaPago == '') && (! $cuota->inhabilitada))
            <td>
              <a href="{{ url('/cuota/pago/'.$cuota->id) }}">
                <button type="button" class="btn btn-primary tam_letra_small">
                  Pagar
                </button>
              </a>
            </td>
          @elseif ($cuota->inhabilitada)
            <td>
              <a href="{{ url('/cuota/show/'.$cuota->id) }}">
                <button type="button" class="btn btn-primary tam_letra_small" onClick="alert('No se puede pagar la Cuota, ya que la misma está inhabilitada')">
                  Pagar
                </button>
              </a>
            </td>
          @else
            <td>
              <a href="{{ url('/cuota/pago/'.$cuota->id) }}">
                <button type="button" class="btn btn-primary tam_letra_small" disabled>
                  Pagar
                </button>
              </a>
            </td>
          @endif
        </tr>
      </table>

      <div class="card-footer">

        @if (($cuota->fechaPago != '') && ($cuota->inhabilitada == false))
          <a style="text-decoration:none" href="{{ url('/cuota/edit/'.$cuota->id) }}">
            <button type="button" class="btn btn-outline-warning" style="display:inline">
              Editar Cuota
            </button>
          </a>
        @else
          <a style="text-decoration:none" href="{{ url('/cuota/show/'.$cuota->id) }}">
            <button type="button" class="btn btn-outline-warning" style="display:inline" onClick="alert('No se puede editar la Cuota, ya que la misma debe estar pagada y habilitada')">
              Editar Cuota
            </button>
          </a>
        @endif

        &nbsp;&nbsp;
        @if ($cuota->inhabilitada)
          <form action="{{url('/cuota/enable')}}" method="post" style="display:inline">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $cuota->id }}">
              <button type="submit" class="btn btn-outline-danger" style="display:inline">
                Habilitar Cuota
              </button>
          </form>
        @else
          <form action="{{url('/cuota/disable')}}" method="post" style="display:inline">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $cuota->id }}">
              <button type="submit" class="btn btn-outline-danger" style="display:inline">
                Inhabilitar Cuota
              </button>
          </form>
        @endif
      </div>

    </div>
  </div>
</div>


@stop
