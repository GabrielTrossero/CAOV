@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Listado de Socios - Generar Cuota</b></label>
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

      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>Número de Socio</th>
            <th>DNI</th>
            <th>Apellido</th>
            <th>Nombres</th>
            <th>Categoria</th>
            <th>Último mes pagado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($socios as $socio)
            <!--para no mostrar los vitalicios y los que no son titulares-->
            @if (($socio->vitalicio == 'n') && ((!$socio->idGrupoFamiliar) || ($socio->id == $socio->grupoFamiliar->titular)))
              <tr>
                <td>{{ $socio->numSocio }}</td>
                <td>{{ $socio->persona->DNI }}</td>
                <td>{{ $socio->persona->apellido }}</td>
                <td>{{ $socio->persona->nombres }}</td>

                @if ($socio->vitalicio == 's')
                  <td>{{ 'Vitalicio' }}</td>
                @elseif ($socio->idGrupoFamiliar)
                  <td>{{ 'Grupo Familiar' }}</td>
                @elseif ($socio->isCadete)
                  <td>{{ 'Cadete' }}</td>
                @else
                  <td>{{ 'Activo' }}</td>
                @endif

                @if ($socio->fechaUltimoPago)
                  <td>{{ date("m/Y", strtotime($socio->fechaUltimoPago)) }}</td>
                @else
                  <td> - </td>
                @endif

                <td><a href="{{ url('/cuota/createCuota/'.$socio->id) }}"> <i class="fas fa-plus"></i></a> </td>

              </tr>
            @endif
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
      </div>
    </div>
  </div>
</div>


@stop
