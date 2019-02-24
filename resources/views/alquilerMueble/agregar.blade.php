@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Agregar Alquiler de Mueble') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/alquilermueble/create') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="DNI" class="col-md-4 col-form-label text-md-right">{{ __('DNI del Solicitante') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="DNI" id="DNI" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaSol" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Solicitud') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaSol" id="fechaSol" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantTablones" class="col-md-4 col-form-label text-md-right">{{ __('Cantidad de Tablones') }}</label>

                            <div class="col-md-6">
                                <input type="number" value="0" name="cantTablones" id="cantTablones" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantSillas" class="col-md-4 col-form-label text-md-right">{{ __('Cantidad de Sillas') }}</label>

                            <div class="col-md-6">
                                <input type="number" value="0" name="cantSillas" id="cantSillas" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantCaballetes" class="col-md-4 col-form-label text-md-right">{{ __('Cantidad de Caballetes') }}</label>

                            <div class="col-md-6">
                                <input type="number" value="0" name="cantCaballetes" id="cantCaballetes" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaRea" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Realización') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaRea" id="fechaRea" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="observacion" class="col-md-4 col-form-label text-md-right">{{ __('Observación') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="observacion" id="observacion" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="costoTotal" class="col-md-4 col-form-label text-md-right">{{ __('Costo Total') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="costoTotal" id="costoTotal" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="numRecibo" class="col-md-4 col-form-label text-md-right">{{ __('Número de Recibo') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="numRecibo" id="numRecibo" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="medioPago" class="col-md-4 col-form-label text-md-right">{{ __('Medio de Pago') }}</label>

                            <div class="col-md-6">
                                <select name="medioPago" id="medioPago" class="form-control">
                                  <option value="1">Efectivo</option>
                                  <option value="2">Tarjeta</option>
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
