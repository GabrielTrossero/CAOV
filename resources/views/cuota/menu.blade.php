@extends('layouts.master')

@section('content')

<div class="menu2">
  @if (Auth::user()->idTipoUsuario == 1)
    <a href="{{ url('cuota/createMontoCuota') }}"><i class="icono3 fas fa-plus"></i> &nbsp; Generar Monto para Cuotas</a>
    <br>
    <a href="{{ url('cuota/showMontoCuota') }}"><i class="icono3 far fa-list-alt"></i> &nbsp; Listar Montos para Cuotas</a>
    <br>
  @endif

  <!--si hay algun tipo de montoCuota faltante, no dejo generar adelantos de cuotas-->
  @if (($activo == false) || ($cadete == false) || ($grupoF == false))
    <a href="javascript:funcion()"><i class="icono3 fas fa-file-medical"></i> &nbsp; Generar Adelanto de Cuota</a>
    <script>
      function funcion(){
        alert('Hay uno o más tipos de socios que no tienen Monto Cuota. Por favor, generar Monto/s Cuota/s correspondientes.');
      }
    </script>
  @else
    <a href="{{ url('cuota/showCreateCuota') }}"><i class="icono3 fas fa-file-medical"></i> &nbsp; Generar Adelanto de Cuota</a>
  @endif

  <br>
  <a href="{{ url('cuota/show') }}"><i class="icono3 fas fa-list-ol"></i> &nbsp; Listar Cuotas</a>
  <br>
  <a href="{{ url('cuota/showSocios') }}"><i class="icono3 fas fa-list-ul"></i> &nbsp; Listar Cuotas de un Socio</a>
  <br>
  <form method="POST" action="{{ url('/cuota/generateCuotasAuto') }}">
    {{ csrf_field() }}

    &nbsp;&nbsp;&nbsp;&nbsp;
    <button type="submit" class="btn btn-danger">
      <i class="icono3 fas fa-save"></i> &nbsp; {{ __('Generar Cuotas de este mes') }}
    </button>

  </form>

</div>


<!--si hay algun tipo de montoCuota faltante, no dejo generar las cuotas-->
@if (($activo == false) || ($cadete == false) || ($grupoF == false))
  <script type="text/javascript">
    $(document).ready(function(){
        $("form").submit(function(e){
            alert('Hay uno o más tipos de socios que no tienen Monto Cuota. Por favor, generar Monto/s Cuota/s correspondientes.');
            return false;
        });
    });
  </script>
@endif


@stop
