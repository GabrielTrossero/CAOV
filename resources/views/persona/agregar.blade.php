@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Agregar Persona') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/persona/create') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="DNI" class="col-md-4 col-form-label text-md-right">{{ __('DNI *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="DNI" id="DNI" class="form-control" value="{{ old('DNI') }}" min="0" required>

                                <span class="text-danger">{{$errors->first('DNI')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nombres" class="col-md-4 col-form-label text-md-right">{{ __('Nombres *') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="nombres" id="nombres" class="form-control" value="{{ old('nombres') }}" maxlength="100" required>

                                <span class="text-danger">{{$errors->first('nombres')}}</span>
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="apellido" class="col-md-4 col-form-label text-md-right">{{ __('Apellido *') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="apellido" id="apellido" class="form-control" value="{{ old('apellido') }}" maxlength="100" required>

                                <span class="text-danger">{{$errors->first('apellido')}}</span>
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="domicilio" class="col-md-4 col-form-label text-md-right">{{ __('Domicilio') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="domicilio" id="domicilio" class="form-control" value="{{ old('domicilio') }}" maxlength="100">

                                <span class="text-danger">{{$errors->first('domicilio')}}</span>
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="telefono" class="col-md-4 col-form-label text-md-right">{{ __('Teléfono') }}</label>

                            <div class="col-md-6">
                                <input type="tel" name="telefono" id="telefono" class="form-control" value="{{ old('telefono') }}">

                                <span class="text-danger">{{$errors->first('telefono')}}</span>
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

                            <div class="col-md-6">
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">

                                <span class="text-danger">{{$errors->first('email')}}</span>
                                @if (\Session::has('validarMail'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('validarMail') !!}
                                  </div>
                                @endif
                            </div>

                        </div>

                        @if (isset($redirect))
                            <input type="text" name="redirect" id="redirect" value="{{ $redirect }}" hidden>
                        @endif

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
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
