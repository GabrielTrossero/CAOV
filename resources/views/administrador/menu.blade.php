@extends('layouts.master')

@section('content')

<div class="menu2">
  <br><br>
  <a href="{{ url('administrador/ingresos') }}"><i class="icono3 fas fa-hand-holding-usd"></i> &nbsp; Listar Ingresos</a>
  <br><br><br>

  <form method="POST" action="{{ url('/administrador/backup') }}">
    {{ csrf_field() }}

    <div class="form-group row mb-0">
      <div class="col-md-4 offset-md-4">
        <button type="submit" class="btn btn-danger" style="width: 12em;">
          <i class="icono3 fas fa-save"></i> &nbsp;
          <div style="font-size: 18px;">
            {{ __('Generar BackUp') }}
          </div>
        </button>
      </div>
    </div>
  </form>



</div>


@stop
