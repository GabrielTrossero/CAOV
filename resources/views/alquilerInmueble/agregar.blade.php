@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Agregar Alquiler de Inmueble') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/alquilerinmueble/create') }}">
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
                            <label for="costoReserva" class="col-md-4 col-form-label text-md-right">{{ __('Costo de la Reserva') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="costoReserva" id="costoReserva" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="costoTotal" class="col-md-4 col-form-label text-md-right">{{ __('Costo Total') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="costoTotal" id="costoTotal" class="form-control">
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

                        <div class="form-group row">
                            <label for="horario" class="col-md-4 col-form-label text-md-right">{{ __('Horario') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="horario" id="horario" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoEvento" class="col-md-4 col-form-label text-md-right">{{ __('Tipo de Evento') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="tipoEvento" id="tipoEvento" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantAsistentes" class="col-md-4 col-form-label text-md-right">{{ __('Cantidad de Asistentes') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantAsistentes" id="cantAsistentes" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="servicioLimp" class="col-md-4 col-form-label text-md-right">{{ __('Servicio de Limpieza') }}</label>

                            <div class="col-md-6">
                                <select name="servicioLimp" id="servicioLimp" class="form-control">
                                  <option value="1">Si</option>
                                  <option value="2">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="musica" class="col-md-4 col-form-label text-md-right">{{ __('Música') }}</label>

                            <div class="col-md-6">
                                <select name="musica" id="musica" class="form-control">
                                  <option value="1">Si</option>
                                  <option value="2">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="reglamento" class="col-md-4 col-form-label text-md-right">{{ __('Reglamento') }}</label>

                            <div class="col-md-6">
                                <select name="reglamento" id="reglamento" class="form-control">
                                  <option value="1">Si</option>
                                  <option value="2">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="numRecibo" class="col-md-4 col-form-label text-md-right">{{ __('Número de Recibo') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="numRecibo" id="numRecibo" class="form-control">
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
