@extends('layouts.master')

@section('content')

<div class="menu2">
  @if (Auth::user()->idTipoUsuario == 1)
    <a href="{{ url('cuota/createMontoCuota') }}"><i class="icono3 fas fa-plus"></i> &nbsp; Generar Monto para Cuotas</a>
    <br>
    <a href="{{ url('cuota/showMontoCuota') }}"><i class="icono3 far fa-list-alt"></i> &nbsp; Listar Montos para Cuotas</a>
    <br>
  @endif
  <a href="{{ url('cuota/showCreateCuota') }}"><i class="icono3 fas fa-file-medical"></i> &nbsp; Generar Adelanto de Cuota</a>
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

@if (\Session::has('sinCuotasGeneradas'))
  <script type="text/javascript">
    window.onload = () => { //para que el script se muestre despúes de cargar el html
            alert('{!! \Session::get('sinCuotasGeneradas') !!}')
        }
  </script>
@elseif (\Session::has('conCuotasGeneradas'))
  <script type="text/javascript">
    window.onload = () => {
            alert('{!! \Session::get('conCuotasGeneradas') !!}')
        }
  </script>
@endif


@stop
