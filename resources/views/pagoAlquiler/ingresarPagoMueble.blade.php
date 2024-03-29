@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Ingresar N° de Recibo para el Alquiler de Mueble') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/pagoalquiler/pagomueble') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $reserva->id }}">

                        <input type="hidden" name="tipoMueble" value="{{ $reserva->idMueble }}">

                        <div class="form-group row">
                            <label for="persona" class="col-md-4 col-form-label text-md-right">{{ __('Solicitante') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="persona" id="persona" class="form-control" value="{{ $reserva->persona->DNI .' - '. $reserva->persona->apellido .', '. $reserva->persona->nombres }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaSolicitud" class="col-md-4 col-form-label text-md-right">{{ __('Fecha Actual') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaSolicitud" id="fechaSolicitud" class="form-control" value="{{ $reserva->fechaSolicitud }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoMueble" class="col-md-4 col-form-label text-md-right">{{ __('Seleccione el Mueble') }}</label>

                            <div class="col-md-6">
                              <select name="tipoMueble" id="tipoMueble" class="form-control" disabled>
                                @foreach ($muebles as $mueble)
                                  <!--para seleccionar por defecto la que tiene actualmente-->
                                  @if ($reserva->mueble->id == $mueble->id)
                                    <option value="{{ $mueble->id }}" selected>{{ $mueble->nombre }}</option>
                                  @else
                                    <option value="{{ $mueble->id }}">{{ $mueble->nombre }}</option>
                                  @endif
                                @endforeach
                              </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantMueble" class="col-md-4 col-form-label text-md-right">{{ __('Ingrese la Cantidad') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantMueble" id="cantMueble" class="form-control" value="{{ $reserva->cantidad }}" min="1" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaHoraInicio" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Inicio') }}</label>

                            <div class="col-md-6">
                                <input type="datetime" name="fechaHoraInicio" id="fechaHoraInicio" class="form-control" value="{{ $reserva->fechaHoraInicio }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaHoraFin" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Finalización') }}</label>

                            <div class="col-md-6">
                                <input type="datetime" name="fechaHoraFin" id="fechaHoraFin" class="form-control" value="{{ $reserva->fechaHoraFin }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="costo" class="col-md-4 col-form-label text-md-right">{{ __('Costo') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="costo" id="costo" class="form-control" value="{{ '$ '. $reserva->costoTotal }}" min="0" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="medioPago" class="col-md-4 col-form-label text-md-right">{{ __('Medio de Pago') }}</label>

                            <div class="col-md-6">
                                <select name="medioPago" id="medioPago" class="form-control" disabled>
                                  @foreach ($mediosDePagos as $medioDePago)
                                    <!--para seleccionar por defecto la que tiene actualmente-->
                                    @if ($reserva->medioDePago->id == $medioDePago->id)
                                      <option value="{{ $medioDePago->id }}" selected>{{ $medioDePago->nombre }}</option>
                                    @else
                                      <option value="{{ $medioDePago->id }}">{{ $medioDePago->nombre }}</option>
                                    @endif
                                  @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="observacion" class="col-md-4 col-form-label text-md-right">{{ __('Observación') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="observacion" id="observacion" class="form-control" value="{{ $reserva->observacion }}" maxlength="100" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="numRecibo" class="col-md-4 col-form-label text-md-right">{{ __('N° Recibo *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="numRecibo" id="numRecibo" class="form-control" value="{{ old('numRecibo') ?? $reserva->numRecibo }}" min="0" required>

                                <span class="text-danger">{{$errors->first('numRecibo')}}</span>

                                @if (\Session::has('validarNumRecibo'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('validarNumRecibo') !!}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <!--para mostrar las distintas alertas-->
                        <div class="form-group row">
                            <label class="col-md-1 col-form-label text-md-right"></label>
                            <div class="col-md-10">

                              <div class="alert alert-danger" align="center">
                                {{ 'MONTO A PAGAR: $ '. $reserva->costoTotal }}
                              </div>
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
