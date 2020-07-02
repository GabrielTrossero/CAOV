@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Agregar Socio') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/socio/create') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="numSocio" class="col-md-4 col-form-label text-md-right">{{ __('Numero de Socio') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="numSocio" id="numSocio" class="form-control" value="{{ old('numSocio') }}" required>

                                <span class="text-danger">{{$errors->first('numSocio')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaNac" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Nacimiento') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaNac" id="fechaNac" class="form-control" value="{{ old('fechaNac') }}" required>

                                <span class="text-danger">{{$errors->first('fechaNac')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="oficio" class="col-md-4 col-form-label text-md-right">{{ __('Oficio') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="oficio" id="oficio" class="form-control" value="{{ old('oficio') }}" maxlength="80">

                                <span class="text-danger">{{$errors->first('oficio')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="vitalicio" class="col-md-4 col-form-label text-md-right">{{ __('Vitalicio') }}</label>

                            <div class="col-md-6">
                                <select name="vitalicio" id="vitalicio" class="form-control">
                                  <option value="n">No</option>
                                  <option value="s">Si</option>
                                </select>

                                <span class="text-danger">{{$errors->first('vitalicio')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="idPersona" class="col-md-4 col-form-label text-md-right">{{ __('Persona') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" name="idPersona" id="idPersona">
                                  <option selected disabled>Seleccionar Persona</option>
                                  @foreach ($personas as $persona)
                                    <option value="{{$persona->id}}">{{ $persona->DNI .' - '. $persona->apellido .', '. $persona->nombres }}</option>
                                  @endforeach
                                </select>

                                <span class="text-danger">{{$errors->first('idPersona')}}</span>

                                @if (\Session::has('validarPersonaExiste'))
                                  <span class="text-danger">{!! \Session::get('validarPersonaExiste') !!}</span>
                                @elseif (\Session::has('validarSocioNoExiste'))
                                  <span class="text-danger">{!! \Session::get('validarSocioNoExiste') !!}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="idGrupoFamiliar" class="col-md-4 col-form-label text-md-right">{{ __('Grupo Familiar') }}</label>

                            <div class="col-md-6">
                              <select name="idGrupoFamiliar" id="idGrupoFamiliar" class="form-control">
                                <option value="0">No posee grupo familiar</option>
                                @foreach ($grupos as $grupo)
                                  <option value="{{ $grupo->id }}">Titular: {{$grupo->socioTitular->persona->apellido." ".$grupo->SocioTitular->persona->nombres." - ".$grupo->socioTitular->persona->DNI}}</option>
                                @endforeach
                              </select>

                              <span class="text-danger">{{$errors->first('idGrupoFamiliar')}}</span>

                              @if (\Session::has('validarGrupoFamiliar'))
                                <span class="text-danger">{!! \Session::get('validarGrupoFamiliar') !!}</span>
                              @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="idDeporte" class="col-md-4 col-form-label text-md-right">{{ __('Deporte') }}</label>

                            <div class="col-md-6">
                              <div class="form-check">
                                @foreach ($deportes as $deporte)
                                  <input class="form-check-input" type="checkbox" name="idDeporte[]" id="{{ $deporte->nombre }}" value="{{ $deporte->id }}">
                                  <label class="form-check-label" for="{{ $deporte->nombre }}">
                                    {{ $deporte->nombre }}
                                  </label>
                                  <br>
                                @endforeach
                              </div>

                              @if (\Session::has('validarDeporte'))
                                <span class="text-danger">{!! \Session::get('validarDeporte') !!}</span>
                              @endif
                            </div>
                        </div>

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

<script src="{!! asset('js/verifica-socio-vitalicio.js') !!}"></script>

@stop
