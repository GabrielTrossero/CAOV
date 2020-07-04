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
            <th>Categoria (actual)</th>
            <th>Oficio</th>
            <th>Deportes</th>
            <th>Fecha de Nacimiento</th>
            <th>Activo</th>
          </tr>

          <tr>
            <td>{{ $socio->persona->DNI }}</td>
            <td>{{ $socio->numSocio }}</td>
            <td>{{ $socio->persona->apellido }}</td>
            <td>{{ $socio->persona->nombres }}</td>

            @if ($socio->vitalicio == 's')
              <td>{{ 'Vitalicio' }}</td>
            @elseif ($socio->idGrupoFamiliar)
              <td>{{ 'Grupo Familiar' }}</td>
            @elseif ($socio->edad < 18)
              <td>{{ 'Cadete' }}</td>
            @else
              <td>{{ 'Activo' }}</td>
            @endif

            <td>{{ $socio->oficio }}</td>

            <td>
              @foreach ($socio->deportes as $deporte)
                {{ $deporte->nombre }}
                <br>
              @endforeach
            </td>

            <td>{{ date("d/m/Y", strtotime($socio->fechaNac)) }}</td>

            @if ($socio->activo)
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
    <div class="card-header">Listado de Cuotas</label></div>
    <div class="card-body border">

        <table class="table">
          <tr>
            <th>DNI Socio Titular</th>
            <th>N° de Socio Titular</th>
            <th>Mes/Año</th>
            <th>Estado Cuota</th>
            <th>Monto Base</th>
            <th>Monto Pagado</th>
            <th>Tipo Socio (Cobrado)</th>
            <th>Más Información</th>
          </tr>
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
                <td>{{ '-' }}</td>
              @endif

              @if ($cuota->montoCuota->tipo == 'c')
                <td>{{ 'Cadete' }}</td>
              @elseif ($cuota->montoCuota->tipo == 'g')
                <td>{{ 'Grupo Familiar' }}</td>
              @elseif ($cuota->montoCuota->tipo == 'a')
                <td>{{ 'Activo' }}</td>
              @endif

              <td><a href="{{ url('/cuota/show/'.$cuota->id) }}"> <i class="fas fa-plus"></i></a> </td>
            </tr>
          @endforeach
      </table>
    </div>
  </div>
</div>

@stop
