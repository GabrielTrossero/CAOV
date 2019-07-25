@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Modificar Cuota') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/cuota/edit') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $cuota->id }}">

                        <input type="hidden" name="idSocio" value="{{ $cuota->idSocio }}">

                        <div class="form-group row">
                            <label for="DNI" class="col-md-4 col-form-label text-md-right">{{ __('DNI Socio') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="DNI" id="DNI" class="form-control" value="{{ $cuota->socio->persona->DNI }}" min="0" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaPago" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Pago') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaPago" id="fechaPago" class="form-control" value="{{ $cuota->fechaPago }}" required>

                                <span class="text-danger">{{$errors->first('fechaPago')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaMesAnio" class="col-md-4 col-form-label text-md-right">{{ __('Mes y Año correspondinte') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaMesAnio" id="fechaMesAnio" class="form-control" value="{{ $cuota->fechaMesAnio }}" required>

                                <span class="text-danger">{{$errors->first('fechaPago')}}</span>
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
                            <label for="tipo" class="col-md-4 col-form-label text-md-right">{{ __('Tipo') }}</label>

                            <div class="col-md-6">
                                <select name="tipo" id="tipo" class="form-control">
                                  @if ($cuota->tipo == "m")
                                    <option value="m" selected>Mensual</option>
                                    <option value="s">Semestral</option>
                                    <option value="a">Anual</option>
                                  @elseif ($cuota->tipo == "s")
                                    <option value="m">Mensual</option>
                                    <option value="s" selected>Semestral</option>
                                    <option value="a">Anual</option>
                                  @elseif ($cuota->tipo == "a")
                                    <option value="m">Mensual</option>
                                    <option value="s">Semestral</option>
                                    <option value="a" selected>Anual</option>
                                  @endif
                                </select>

                                <span class="text-danger">{{$errors->first('tipo')}}</span>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Editar') }}
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
