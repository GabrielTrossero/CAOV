@extends('layouts.master')

@section('content')

<div class="menu2">
  <br><br>
  <form action="#">
    {{ csrf_field() }}

    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" id="token" value="{{ csrf_token() }}" hidden>

  </form>
  <button class="btn btn-danger" id="backup">
    <i class="icono3 fas fa-save"></i> &nbsp; {{ __('Generar BackUp') }}
  </button>
</div>

<div class="col-md-8 backup" style="text-align: center">
  <div id="mensaje-backup" class="alert alert-primary">
    {!! "El proceso de BackUp se está llevando a cabo. Por favor espere." !!}
  </div>
</div>

<script src="{{ asset('js/run-backup.js') }}"></script>


@stop
