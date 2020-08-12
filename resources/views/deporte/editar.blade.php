@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Modificar Deporte') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/deporte/edit') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $deporte->id }}">

                        <div class="form-group row">
                            <label for="nombre" class="col-md-4 col-form-label text-md-right">{{ __('Nombre *') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="nombre" id="nombre" class="form-control" value=" {{ old('nombre') ?? $deporte->nombre }}" maxlength="75" required>

                                @if ($errors->first('nombre'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('nombre') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-1 offset-md-4">
                                <a style="text-decoration:none" onclick="history.back()">
                                  <button type="button" class="btn btn-secondary">
                                    Volver
                                  </button>
                                </a>
                            </div>

                            <div class="offset-md-1">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Guardar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@stop
