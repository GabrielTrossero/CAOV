@extends('layouts.master')

@section('content')


<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Editar Pareja') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/grupofamiliar/editPareja') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $grupo->id }}">

                        <div class="form-group row">
                            <label for="pareja" class="col-md-4 col-form-label text-md-right">{{ __('Pareja *') }}</label>

                            <div class="col-md-6">
                                <select name="pareja" id="pareja" class="form-control">
                                    <option value="0">No posee Pareja</option>
                                    @foreach ($grupo->socios as $socio)
                                      @if ($grupo->pareja == $socio->id)
                                        <option value="{{ $socio->id }}" selected>{{ $socio->persona->DNI." - ".$socio->persona->nombres." ".$socio->persona->apellido }}</option>
                                      @endif
                                    @endforeach
                                    @foreach ($sociosPareja as $socio)
                                        <option value="{{ $socio->id }}">{{ $socio->persona->DNI." - ".$socio->persona->nombres." ".$socio->persona->apellido }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->first('pareja'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('pareja') }}
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