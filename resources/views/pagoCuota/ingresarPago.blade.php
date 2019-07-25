@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Cobrar Cuota') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/pagocuota/pago') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $socio->id }}">

                        <div class="form-group row">
                            <label for="fechaPago" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Pago') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaPago" id="fechaPago" class="form-control" value="{{ old('fechaPago') }}" required>

                                <span class="text-danger">{{$errors->first('fechaPago')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaMesAnio" class="col-md-4 col-form-label text-md-right">{{ __('Mes y Año correspondinte') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaMesAnio" id="fechaMesAnio" class="form-control" value="{{ old('fechaMesAnio') }}" required>

                                <span class="text-danger">{{$errors->first('fechaMesAnio')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="medioPago" class="col-md-4 col-form-label text-md-right">{{ __('Medio de Pago') }}</label>

                            <div class="col-md-6">
                                <select name="medioPago" id="medioPago" class="form-control">
                                  <option value="1">Efectivo</option>
                                </select>

                                <span class="text-danger">{{$errors->first('medioPago')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipo" class="col-md-4 col-form-label text-md-right">{{ __('Tipo de Pago realizado') }}</label>

                            <div class="col-md-6">
                                <select name="tipo" id="tipo" class="form-control">
                                  <option value="m">Mensual</option>
                                  <option value="s">Semestral</option>
                                  <option value="a">Anual</option>
                                </select>

                                <span class="text-danger">{{$errors->first('tipo')}}</span>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Cobrar') }}
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
