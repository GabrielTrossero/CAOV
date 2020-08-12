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
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Nombre de Usuario *') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" minlength="8" maxlength="75" required>

                                @if ($errors->first('username'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('username') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email *') }}</label>

                            <div class="col-md-6">
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" maxlength="75" required>

                                @if ($errors->first('email'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('email') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="idPersona" class="col-md-4 col-form-label text-md-right">{{ __('Persona *') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" name="idPersona" id="idPersona">
                                  <option selected disabled>Seleccionar Persona</option>
                                  @foreach ($personas as $persona)
                                    <option value="{{$persona->id}}">{{ $persona->DNI .' - '. $persona->apellido .', '. $persona->nombres }}</option>
                                  @endforeach
                                </select>

                                @if ($errors->first('idPersona'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('idPersona') }}
                                  </div>
                                @endif

                                @if (\Session::has('validarPersonaExiste'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('validarPersonaExiste') !!}
                                  </div>
                                  @elseif (\Session::has('validarSocioNoExiste'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('validarSocioNoExiste') !!}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña *') }}</label>

                            <div class="col-md-6">
                                <input type="password" name="password" id="password" class="form-control" minlength="8" maxlength="80" required>

                                @if ($errors->first('password'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('password') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="passwordRepeat" class="col-md-4 col-form-label text-md-right">{{ __('Repetir Contraseña *') }}</label>

                            <div class="col-md-6">
                                <input type="password" name="passwordRepeat" id="passwordRepeat" class="form-control" minlength="8" maxlength="80" required>

                                @if ($errors->first('passwordRepeat'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('passwordRepeat') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="idTipoUsuario" class="col-md-4 col-form-label text-md-right">{{ __('Tipo de Usuario *') }}</label>

                            <div class="col-md-6">
                                <select name="idTipoUsuario" id="idTipoUsuario" class="form-control" required>

                                  @foreach ($tiposUsuarios as $tipoUsuario)
                                    <option value="{{ $tipoUsuario->id }}">{{ $tipoUsuario->nombre }}</option>
                                  @endforeach

                                </select>

                                @if ($errors->first('idTipoUsuario'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('idTipoUsuario') }}
                                  </div>
                                @endif
                                @if (\Session::has('validarTipoUsuario'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('validarTipoUsuario') !!}
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
