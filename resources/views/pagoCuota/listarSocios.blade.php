@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Cobro de Cuota - Listado de Socios</b></label>
    </div>
    <div class="card-body border">
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>DNI</th>
            <th>Numero de Socio</th>
            <th>Apellido</th>
            <th>Nombres</th>
            <th>Categoria</th>
            <th>Último mes pagado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($socios as $socio)
            @if (($socio->vitalicio == 'n') && ((!$socio->idGrupoFamiliar) || ($socio->id == $socio->grupoFamiliar->titular)))
              <tr>
                <td>{{ $socio->persona->DNI }}</td>
                <td>{{ $socio->numSocio }}</td>
                <td>{{ $socio->persona->apellido }}</td>
                <td>{{ $socio->persona->nombres }}</td>

                @if ($socio->vitalicio == 's')
                  <td>{{ 'Vitalicio' }}</td>
                @elseif ($socio->idGrupoFamiliar)
                  <td>{{ 'Grupo Familiar' }}</td>
                @else
                  <td>{{ 'Activo' }}</td>
                @endif

                @if ($socio->ultimoMesPagado)
                  <td>{{date("m/Y", strtotime($socio->ultimoMesPagado)) }}</td>
                @else
                  <td></td>
                @endif

                <td>
                  <a href="{{ url('/pagocuota/pago/'.$socio->id) }}">
                    <button type="button" class="btn btn-primary tam_letra_small">
                      Pagar
                    </button>
                  </a>
                </td>
              </tr>
            @endif
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>


@stop
