@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Agregar Empleado') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/empleado/create') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Nombre de Usuario') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" required>

                                @foreach ($errors->get('username') as $message)
                                  <span class="text-danger">{{$message}}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

                            <div class="col-md-6">
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>

                                <span class="text-danger">{{$errors->first('email')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="persona" class="col-md-4 col-form-label text-md-right">{{ __('Persona') }}</label>

                            <div class="col-md-6">
                                <select name="persona" id="persona" class="form-control" required>
                                  
                                  @foreach ($personas as $persona)
                                    <option value="{{ $persona->id }}">{{ $persona->DNI." - ".$persona->nombres." ".$persona->apellido }}</option>
                                  @endforeach

                                </select>

                                <span class="text-danger">{{$errors->first('persona')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                            <div class="col-md-6">
                                <input type="password" name="password" id="password" class="form-control" required>

                                <span class="text-danger">{{$errors->first('password')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="passwordRepeat" class="col-md-4 col-form-label text-md-right">{{ __('Repetir Contraseña') }}</label>

                            <div class="col-md-6">
                                <input type="password" name="passwordRepeat" id="passwordRepeat" class="form-control" required>

                                <span class="text-danger">{{$errors->first('passwordRepeat')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoUsuario" class="col-md-4 col-form-label text-md-right">{{ __('Tipo de Usuario') }}</label>

                            <div class="col-md-6">
                                <select name="tipoUsuario" id="tipoUsuario" class="form-control" required>

                                  @foreach ($tiposUsuarios as $tipoUsuario)
                                    <option value="{{ $tipoUsuario->id }}">{{ $tipoUsuario->nombre }}</option>
                                  @endforeach

                                </select>

                                <span class="text-danger">{{$errors->first('tipoUsuario')}}</span>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Agregar') }}
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
