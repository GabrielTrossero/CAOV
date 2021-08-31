@extends('layouts.master')

@section('content')

<div class="cuadro">

    <div class="card">
      <div class="card-header">Información del Socio</div>
      <div class="card-body border">
        <table class="table">
          <tr>
            <th>Número de Socio</th>
            <th>DNI</th>
            <th>Apellido</th>
            <th>Nombres</th>
            <th>Categoria (actual)</th>
            <th>Oficio</th>
            <th>Deportes</th>
            <th>Fecha de Nacimiento</th>
            <th>Activo</th>
          </tr>

          <tr>
            <td>{{ $socio->numSocio }}</td>
            <td>{{ $socio->persona->DNI }}</td>
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
    @if (!sizeof($cuotas))
      <div class="alert alert-warning">
        {{ 'El socio actual no tiene cuotas generadas.' }}
      </div>

      <table class="table">
        <tr>
          <th>N° de Socio Titular</th>
          <th>DNI Socio Titular</th>
          <th>Mes/Año</th>
          <th>Estado Cuota</th>
          <th>Monto Base</th>
          <th>Monto Pagado</th>
          <th>Tipo Socio (Cobrado)</th>
        </tr>
         <tr>
           <td> - </td>
           <td> - </td>
           <td> - </td>
           <td> - </td>
          <td> - </td>
           <td> - </td>
          <td> - </td>
         </tr>
      </table>

      @else
        <table class="table">
          <tr>
            <th>N° de Socio Titular</th>
            <th>DNI Socio Titular</th>
            <th>Mes/Año</th>
            <th>Estado Cuota</th>
            <th>Monto Base</th>
            <th>Monto Pagado</th>
            <th>Tipo Socio (Cobrado)</th>
            <th>Más Información</th>
          </tr>
          @foreach ($cuotas as $cuota)
            <tr>
              <td>{{ $cuota->socio->numSocio }}</td>
              <td>{{ $cuota->socio->persona->DNI ?? 'Socio eliminado' }}</td>
              <td>{{date("m/Y", strtotime($cuota->fechaMesAnio))}}</td> <!-- para mostrar solo mes/año -->

              @if ($cuota->inhabilitada)
                <td>{{ 'Inhabilitada' }}</td>
              @elseif ($cuota->fechaPago)
                <td>{{ 'Pagada' }}</td>
              @else
                <td>{{ 'No Pagada' }}</td>
              @endif

               <td class="montos">{{ '$ '.$cuota->montoCuota->montoMensual }}</td>

              @if ($cuota->fechaPago)
                <!--suma del monto base + intereses por atraso + intereses cantidad integrantes -->
                <td class="montos">{{ '$ '. ($cuota->montoCuota->montoMensual + $cuota->montoInteresAtraso + $cuota->montoInteresGrupoFamiliar) }}</td>
               @else
                 <td class="montos">{{ '$ 0' }}</td>
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
      @endif

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
