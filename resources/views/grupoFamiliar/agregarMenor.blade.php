@extends('layouts.master')

@section('content')


<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Agregar Menor') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/grupofamiliar/addMenor') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $grupo->id }}">

                        <div class="form-group row">
                            <label for="menores" class="col-md-4 col-form-label text-md-right">{{ __('Menores *') }}</label>

                            <div class="col-md-6">
                                <select name="menores[]" id="menores" class="form-control" multiple >
                                    @foreach ($sociosMenores as $socio)
                                        <option value="{{ $socio->id }}">{{ $socio->persona->DNI." - ".$socio->persona->nombres." ".$socio->persona->apellido }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->first('menores'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('menores') }}
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
                                <a style="text-decoration:none" href="{{ url('/grupofamiliar/show/'.$grupo->id) }}">
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