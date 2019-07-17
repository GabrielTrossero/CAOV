@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos del Grupo Familiar</div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <td><b>DNI</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>Apellido</b></td>
          <td><b>Nombres</b></td>
          <td><b>Titular</b></td>
          <td><b>Info. Socio</b></td>
        </tr>
        <tr>
          <td>{{ $grupo->socioTitular->persona->DNI }}</td>
          <td>{{ $grupo->socioTitular->persona->apellido }}</td>
          <td>{{ $grupo->socioTitular->persona->nombres }}</td>
          <td>Si</td>
          <td><a href="{{ url('/socio/show/'.$grupo->socioTitular->id) }}"> <i class="fas fa-plus"></i></a> </td>
        </tr>

        @foreach ($grupo->socios as $socio)
          <tr>
            <td>{{ $socio->persona->DNI }}</td>
            <td>{{ $socio->persona->apellido }}</td>
            <td>{{ $socio->persona->nombres }}</td>
            <td>No</td>
            <td><a href="{{ url('/socio/show/'.$socio->id) }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
        @endforeach


      </table>

      <div class="card-footer">

        <a style="text-decoration:none" href="{{ url('/grupofamiliar/edit/'.$grupo->id) }}">
          <button type="button" class="btn btn-outline-warning" style="display:inline">
            Editar Grupo Familiar
          </button>
        </a>

        &nbsp;&nbsp;
        <form action="{{url('/grupofamiliar/delete')}}" method="post" style="display:inline">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="{{ $grupo->id }}">
          <button type="submit" class="btn btn-outline-danger" style="display:inline">
            Eliminar Grupo Familiar
          </button>
        </form>

      </div>

    </div>
  </div>
</div>


@stop
