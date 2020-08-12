@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Modificar Persona') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/persona/edit') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $persona->id }}">

                        <div class="form-group row">
                            <label for="DNI" class="col-md-4 col-form-label text-md-right">{{ __('DNI *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="DNI" id="DNI" class="form-control" value="{{ old('DNI') ?? $persona->DNI }}" min="0" required>

                                @if ($errors->first('DNI'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('DNI') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombres *') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="nombres" id="nombres" class="form-control" value="{{ old('nombres') ?? $persona->nombres }}" maxlength="100" required>

                                @if ($errors->first('nombres'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('nombres') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="apellido" class="col-md-4 col-form-label text-md-right">{{ __('Apellido *') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="apellido" id="apellido" class="form-control" value="{{ old('apellido') ?? $persona->apellido }}" maxlength="100" required>

                                @if ($errors->first('apellido'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('apellido') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="domicilio" class="col-md-4 col-form-label text-md-right">{{ __('Domicilio') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="domicilio" id="domicilio" class="form-control" value="{{ old('domicilio') ?? $persona->domicilio }}" maxlength="100">

                                @if ($errors->first('domicilio'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('domicilio') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="telefono" class="col-md-4 col-form-label text-md-right">{{ __('Teléfono') }}</label>

                            <div class="col-md-6">
                                <input type="tel" name="telefono" id="telefono" class="form-control" value="{{ old('telefono') ?? $persona->telefono }}">

                                @if ($errors->first('telefono'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('telefono') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

                            <div class="col-md-6">
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') ?? $persona->email }}">

                                @if (\Session::has('validarMail'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('validarMail') !!}
                                  </div>
                                @elseif ($errors->first('email'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('email') }}
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
