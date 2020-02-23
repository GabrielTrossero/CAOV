@extends('pdf.master')

@section('title', 'Socio Deudor')

@section('content')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <br>
    <h4 align="center">Socio Deudor</h4>
    <div class="tam_letra_x-small">
      <table class="table table-striped">
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

    <br>
    <br>

    <h4 align="center">Cuotas No Pagadas</h4>
    <div class="tam_letra_x-small">
      <table class="table table-striped">
        <thead>
            <tr>
                <th>Mes/Año</th>
                <th>Monto Base</th>
                <th>Tipo Socio (Cobrado)</th>
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
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
@endsection
