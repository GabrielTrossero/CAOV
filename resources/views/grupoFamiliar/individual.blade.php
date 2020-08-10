@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">Datos del Grupo Familiar</div>
    <div class="card-body border">
      @if (\Session::has('errorEliminar'))
        <div class="alert alert-danger">
          {!! \Session::get('errorEliminar') !!}
        </div>
      @endif
      <table class="table">
        <tr>
          <td><b>DNI</b></td>   <!-- la <b> es para poner en negrita -->
          <td><b>Apellido</b></td>
          <td><b>Nombres</b></td>
          <td><b>Tipo Integrante</b></td>
          <th>Acciones</th>
          <td><b>Info. Socio</b></td>
        </tr>


        @foreach ($grupo->socios as $socio)
          <tr>
            <td>{{ $socio->persona->DNI }}</td>
            <td>{{ $socio->persona->apellido }}</td>
            <td>{{ $socio->persona->nombres }}</td>

            @if ($grupo->socioTitular->id == $socio->id)
              <td>Titular</td>
            @elseif ($grupo->pareja == $socio->id)
              <td>Pareja</td>
            @else
              <td>Menor</td>
            @endif

            <td>
              @if ($grupo->socioTitular->id == $socio->id)
                <a class="icono-editar-anchor" href="{{ url('/grupofamiliar/editTitular/'.$grupo->id) }}">
                  <i class="fas fa-edit icono-editar" title="Editar"></i>
                </a>
              @elseif($grupo->pareja == $socio->id)
                <a class="icono-editar-anchor" href="{{ url('/grupofamiliar/editPareja/'.$grupo->id) }}">
                  <i class="fas fa-edit icono-editar" title="Editar"></i>
                </a>
              @else 
                <form action="{{url('/grupofamiliar/deleteMenor')}}" method="post" style="display:inline" onsubmit="return confirm('¿Está seguro que desea eliminar el integrante?');">
                  {{ csrf_field() }}
                  <input type="hidden" name="id" value="{{ $socio->id }}">
                  <button class="icono-eliminar" type="submit" style="margin-left: 0%">
                    <i class="fas fa-trash" style="color:red;" title="Eliminar"></i>
                  </button>
                </form>
              @endif
            </td>

            <td><a href="{{ url('/socio/show/'.$socio->id) }}"> <i class="fas fa-plus"></i></a> </td>
          </tr>
        @endforeach

        <!--en caso de que no tenga pareja-->
        <tr>
          @if($grupo->pareja == 0)
              <td>-</td>
              <td>-</td>
              <td>-</td>
              <td>Pareja</td>
              <td>
                <a class="icono-editar-anchor" href="{{ url('/grupofamiliar/editPareja/'.$grupo->id) }}">
                  <i class="fas fa-edit icono-editar" title="Editar"></i>
                </a>
              </td>
              <td>-</td>
            @endif
        </tr>

      </table>

      <div class="card-footer row">
        <div>
          <a style="text-decoration:none" href="{{ url('/grupofamiliar/show') }}">
            <button type="button" class="btn btn-secondary">
              Volver
            </button>
          </a>
        </div>

        <div class="col-md-10 text-md-center">
          <a style="text-decoration:none" href="{{ url('/grupofamiliar/addMenor/'.$grupo->id) }}">
            <button type="button" class="btn btn-outline-primary" style="display:inline">
              Agregar Menor
            </button>
          </a>

          @if (isset($grupo->pareja))
            &nbsp;&nbsp;
            <form action="{{url('/grupofamiliar/cambiarRoles')}}" method="post" style="display:inline" onsubmit="return confirm('¿Está seguro que desea intercambiar los roles Titular y Pareja?');">
              {{ csrf_field() }}
              <input type="hidden" name="id" value="{{ $grupo->id }}">
              <button type="submit" class="btn btn-outline-secondary" title="Cambiar Titular por Pareja y viceversa" style="display:inline">
                Intercambiar roles 
              </button>
            </form>
          @endif
          

          &nbsp;&nbsp;
          <form action="{{url('/grupofamiliar/delete')}}" method="post" style="display:inline" onsubmit="return confirm('¿Está seguro que desea eliminar el Grupo Familiar?');">
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
</div>


@stop
