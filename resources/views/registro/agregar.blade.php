@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Agregar Registro') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/registro/') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="numRecibo" class="col-md-4 col-form-label text-md-right">{{ __('Numero de Recibo') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="numRecibo" id="numRecibo" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fecha" class="col-md-4 col-form-label text-md-right">{{ __('Fecha') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fecha" id="fecha" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="monto" class="col-md-4 col-form-label text-md-right">{{ __('Monto') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="monto" id="monto" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="descripcion" class="col-md-4 col-form-label text-md-right">{{ __('Descripción') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="descripcion" id="descripcion" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoRegistro" class="col-md-4 col-form-label text-md-right">{{ __('Tipo de Registro') }}</label>

                            <div class="col-md-6">
                                <select name="tipoRegistro" id="tipoRegistro" class="form-control">
                                  <option value="1">Ingreso</option>
                                  <option value="2">Egreso</option>
                                </select>
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
