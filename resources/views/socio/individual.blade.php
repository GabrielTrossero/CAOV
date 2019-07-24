@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos del Socio</div>
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
          <th>Titular Grupo Familiar</th>
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
          @else
            @if ($socio->edad >= 18)
              <td>{{ 'Activo' }}</td>
            @else
              <td>{{ 'Cadete' }}</td>
            @endif
          @endif

          <td>{{ $socio->oficio }}</td>
          <td>
            @foreach ($socio->deportes as $deporte)
              {{ $deporte->nombre }}
              <br>
            @endforeach
          </td>

          <td>{{ $socio->fechaNac }}</td>

          @if ($socio->grupoFamiliar)
            <td>{{ $socio->grupoFamiliar->socioTitular->persona->apellido.
                  ' '.$socio->grupoFamiliar->socioTitular->persona->nombres.
                  ', '.$socio->grupoFamiliar->socioTitular->persona->DNI
                }}
            </td>
          @else
            <td></td>
          @endif

        </tr>
      </table>

      <div class="card-footer">

        <a style="text-decoration:none" href="{{ url('/socio/edit/'.$socio->id) }}">
          <button type="button" class="btn btn-outline-warning" style="display:inline">
            Editar Socio
          </button>
        </a>

        &nbsp;&nbsp;
        <form action="{{url('/socio/delete')}}" method="post" style="display:inline">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="{{ $socio->id }}">
          <button type="submit" class="btn btn-outline-danger" style="display:inline">
            Eliminar Socio
          </button>
        </form>
      </div>

    </div>
  </div>
</div>


@stop
