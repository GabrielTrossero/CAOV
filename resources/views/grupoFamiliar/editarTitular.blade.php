@extends('layouts.master')

@section('content')


<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Editar Titular') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/grupofamiliar/editTitular') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $grupo->id }}">

                        <div class="form-group row">
                            <label for="titular" class="col-md-4 col-form-label text-md-right">{{ __('Titular *') }}</label>

                            <div class="col-md-6">
                                <select name="titular" id="titular" class="form-control">
                                    @foreach ($grupo->socios as $socio)
                                      @if ($grupo->titular == $socio->id)
                                        <option value="{{ $socio->id }}" selected>{{ $socio->persona->DNI." - ".$socio->persona->nombres." ".$socio->persona->apellido }}</option>
                                      @endif
                                    @endforeach
                                    @foreach ($sociosTitular as $socio)
                                        @if ($socio->activo == 0)
                                            <option value="{{ $socio->id }}" style="color:#FF0000">{{ $socio->persona->DNI." - ".$socio->persona->nombres." ".$socio->persona->apellido }}</option>
                                        @else
                                            <option value="{{ $socio->id }}">{{ $socio->persona->DNI." - ".$socio->persona->nombres." ".$socio->persona->apellido }}</option>
                                        @endif
                                    @endforeach
                                </select>

                                @if ($errors->first('titular'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('titular') }}
                                  </div>
                                @endif
                                @if (\Session::has('titularInactivo'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('titularInactivo') !!}
                                  </div>
                                @endif
                                @if (\Session::has('error'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('error') !!}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <br>

                        <div class="form-group row mb-0">
                            <div class="col-md-1 offset-md-4">
                                <a style="text-decoration:none" onclick="history.back()">
                                  <button type="button" class="btn btn-secondary">
                                    Volver
                                  </button>
                                </a>
                            </div>

                            <div class="offset-md-1">
                                <button type="submit" id="guardar-grupo" class="btn btn-primary">
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
