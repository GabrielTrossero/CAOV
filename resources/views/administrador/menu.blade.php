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
  
</div>

<div class="col-md-8 backup">
  @if (\Session::has('backupExitoso'))
    <div class="alert alert-danger">
      {!! \Session::get('backupExitoso') !!}
    </div>
  @elseif (\Session::has('backupErroneo'))
    <div class="alert alert-danger">
      {!! \Session::get('backupErroneo') !!}
    </div>
  @endif
</div>



@stop
