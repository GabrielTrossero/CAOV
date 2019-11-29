@extends('layouts.master')

@section('content')

<div class="menu2">
  <a href="{{ url('administrador/ingresos') }}"><i class="icono3 fas fa-hand-holding-usd"></i> &nbsp; Listar Ingresos</a>

  <br><br>
  <form method="POST" action="{{ url('/administrador/backup') }}">
    {{ csrf_field() }}

    &nbsp;&nbsp;&nbsp;&nbsp;
    <button type="submit" class="btn btn-danger">
      <i class="icono3 fas fa-save"></i> &nbsp; {{ __('Generar BackUp') }}
    </button>
    
  </form>
  <span class="text-success" style="display:inline-block; margin-top: 10px;">{!! \Session::get('backupExitoso') !!}</span>
  <span class="text-danger" style="display:inline-block; margin-top: 10px;">{!! \Session::get('backupErroneo') !!}</span>
</div>


@stop
