@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Información del Socio</div>
    <div class="card-body border">
      <table class="table">
        <thead>
          <tr>
            <th>DNI</th>
            <th>Numero de Socio</th>
            <th>Apellido</th>
            <th>Nombres</th>
            <th>Categoria (actual)</th>
            <th>Deportes</th>
            <th>Fecha de Nacimiento</th>
            <th>Activo</th>
          </tr>
        </thead>

        <tbody>
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
        </tbody>

      </table>

    </div>
  </div>

  &nbsp;&nbsp;

  <div class="card">
    <div class="card-header">Cuotas No Pagadas</label></div>
    <div class="card-body border">

        <table class="table">
          <thead>
            <tr>
              <th>Mes/Año</th>
              <th>Monto Base</th>
              <th>Tipo Socio (Cobrado)</th>
              <th>Más Información</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($cuotasNoPagadas as $cuotaNoPagada)
            <tr>

              <td>{{date("m/Y", strtotime($cuotaNoPagada->fechaMesAnio))}}</td>
              <td>{{ '$'.$cuotaNoPagada->montoCuota->montoMensual }}</td>

              @if ($cuotaNoPagada->montoCuota->tipo == 'c')
                <td>{{ 'Cadete' }}</td>
              @elseif ($cuotaNoPagada->montoCuota->tipo == 'g')
                <td>{{ 'Grupo Familiar' }}</td>
              @elseif ($cuotaNoPagada->montoCuota->tipo == 'a')
                <td>{{ 'Activo' }}</td>
              @endif

              <td><a href="{{ url('/cuota/show/'.$cuotaNoPagada->id) }}"> <i class="fas fa-plus"></i></a> </td>
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

        <div class="col-md-10 text-md-center">
          <form action="{{url('/informe/pdf_socio_deudor')}}" method="get" style="display:inline">
            {{ csrf_field() }}
            <input type="text" name="id" value="{{ $socio->id }}" hidden>
            <button type="submit" class="btn btn-outline-danger" style="display:inline">
              Generar PDF
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>


@stop
