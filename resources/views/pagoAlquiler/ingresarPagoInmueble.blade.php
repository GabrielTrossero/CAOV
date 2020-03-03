@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Ingresar N° de Recibo para el Alquiler de Inmueble') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/pagoalquiler/pagoinmueble') }}">
                        {{ csrf_field() }}

                        @if (\Session::has('newID'))
                          <input type="hidden" name="id" value="{!! \Session::get('newID') !!}">
                        @else
                          <input type="hidden" name="id" value="{{ $reservaInmueble->id }}">
                        @endif

                        <div class="form-group row">
                            <label for="DNI" class="col-md-4 col-form-label text-md-right">{{ __('DNI del Solicitante') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="DNI" id="DNI" class="form-control" maxlength="8" value="{{ old('DNI') ?? $reservaInmueble->persona->DNI }}" disabled>
                                <span class="text-danger">{{$errors->first('DNI')}}</span>
                                @if (\Session::has('DNIinexistente'))
                                  <span class="text-danger">{!! \Session::get('DNIinexistente') !!}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inmueble" class="col-md-4 col-form-label text-md-right">{{ __('Inmueble') }}</label>

                            <div class="col-md-6">
                                <select name="inmueble" id="inmueble" class="form-control" disabled>
                                  @foreach ($inmuebles as $inmueble)
                                    @if ($inmueble->id == $reservaInmueble->idInmueble)
                                      <option value="{{ $inmueble->id }}" selected>{{ $inmueble->nombre }}</option>
                                    @else
                                      <option value="{{ $inmueble->id }}">{{ $inmueble->nombre }}</option>
                                    @endif
                                  @endforeach
                                </select>
                                <span class="text-danger">{{$errors->first('inmueble')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaSol" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Solicitud') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaSol" id="fechaSol" class="form-control" value="{{ old('fechaSol') ?? $reservaInmueble->fechaSolicitud }}" disabled>
                                <span class="text-danger">{{$errors->first('fechaSol')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaHoraInicio" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Inicio') }}</label>

                            <div class="col-md-6">
                                <input type="datetime" name="fechaHoraInicio" id="fechaHoraInicio" class="form-control" value="{{ old('fechaHoraInicio') ?? $reservaInmueble->fechaHoraInicio }}" disabled>
                                <span class="text-danger">{{$errors->first('fechaHoraInicio')}}</span>
                                @if (\Session::has('errorFechaHoraInicio'))
                                  <span class="text-danger">{!! \Session::get('errorFechaHoraInicio') !!}</span>
                                @endif
                                @if (\Session::has('solapamientoFechaHoraInicio'))
                                  <span class="text-danger">{!! \Session::get('solapamientoFechaHoraInicio') !!}</span>
                                @endif
                                @if (\Session::has('solapamientoFechaHoraFin'))
                                  <span class="text-danger">{!! \Session::get('solapamientoFechaHoraFin') !!}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaHoraFin" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Finalización') }}</label>

                            <div class="col-md-6">
                                <input type="datetime" name="fechaHoraFin" id="fechaHoraFin" class="form-control" value="{{ old('fechaHoraFin') ?? $reservaInmueble->fechaHoraFin }}" disabled>
                                <span class="text-danger">{{$errors->first('fechaHoraFin')}}</span>
                                @if (\Session::has('errorFechaHoraFin'))
                                  <span class="text-danger">{!! \Session::get('errorFechaHoraFin') !!}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="observacion" class="col-md-4 col-form-label text-md-right">{{ __('Observación') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="observacion" id="observacion" class="form-control" value="{{ old('observacion') ?? $reservaInmueble->observacion }}" disabled>
                                <span class="text-danger">{{$errors->first('observacion')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="costoReserva" class="col-md-4 col-form-label text-md-right">{{ __('Costo de la Reserva') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="costoReserva" id="costoReserva" class="form-control" value="{{ old('costoReserva') ?? $reservaInmueble->costoReserva }}" disabled>
                                <span class="text-danger">{{$errors->first('costoReserva')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="costoTotal" class="col-md-4 col-form-label text-md-right">{{ __('Costo Total') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="costoTotal" id="costoTotal" class="form-control" value="{{ old('costoTotal') ?? $reservaInmueble->costoTotal }}" disabled>
                                <span class="text-danger">{{$errors->first('costoTotal')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="medioPago" class="col-md-4 col-form-label text-md-right">{{ __('Medio de Pago') }}</label>

                            <div class="col-md-6">
                                <select name="medioPago" id="medioPago" class="form-control" disabled>
                                  @foreach ($mediosDePago as $medioDePago)
                                    @if ($medioDePago->id == $reservaInmueble->idMedioDePago)
                                      <option value="{{ $medioDePago->id }}" selected>{{ $medioDePago->nombre }}</option>
                                    @else
                                      <option value="{{ $medioDePago->id }}">{{ $medioDePago->nombre }}</option>
                                    @endif

                                  @endforeach
                                </select>
                                <span class="text-danger">{{$errors->first('medioPago')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoEvento" class="col-md-4 col-form-label text-md-right">{{ __('Tipo de Evento') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="tipoEvento" id="tipoEvento" class="form-control" value="{{ old('tipoEvento') ?? $reservaInmueble->tipoEvento }}" disabled>
                                <span class="text-danger">{{$errors->first('tipoEvento')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantAsistentes" class="col-md-4 col-form-label text-md-right">{{ __('Cantidad de Asistentes') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantAsistentes" id="cantAsistentes" class="form-control" value="{{ old('cantAsistentes') ?? $reservaInmueble->cantAsistentes }}" disabled>
                                <span class="text-danger">{{$errors->first('cantAsistentes')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="servicioLimp" class="col-md-4 col-form-label text-md-right">{{ __('Servicio de Limpieza') }}</label>

                            <div class="col-md-6">
                                <select name="servicioLimp" id="servicioLimp" class="form-control" disabled>
                                  @if ($reservaInmueble->tieneServicioLimpieza)
                                    <option value="0">No</option>
                                    <option value="1" selected>Si</option>
                                  @else
                                    <option value="0" selected>No</option>
                                    <option value="1">Si</option>
                                  @endif
                                </select>
                                <span class="text-danger">{{$errors->first('servicioLimp')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="musica" class="col-md-4 col-form-label text-md-right">{{ __('Música') }}</label>

                            <div class="col-md-6">
                                <select name="musica" id="musica" class="form-control" disabled>
                                  @if ($reservaInmueble->tieneMusica)
                                    <option value="0">No</option>
                                    <option value="1" selected>Si</option>
                                  @else
                                    <option value="0" selected>No</option>
                                    <option value="1">Si</option>
                                  @endif
                                </select>
                                <span class="text-danger">{{$errors->first('musica')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="reglamento" class="col-md-4 col-form-label text-md-right">{{ __('Reglamento') }}</label>

                            <div class="col-md-6">
                                <select name="reglamento" id="reglamento" class="form-control" disabled>
                                  @if ($reservaInmueble->tieneReglamento)
                                    <option value="0">No</option>
                                    <option value="1" selected>Si</option>
                                  @else
                                    <option value="0" selected>No</option>
                                    <option value="1">Si</option>
                                  @endif
                                </select>

                                <span class="text-danger">{{$errors->first('reglamento')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="numRecibo" class="col-md-4 col-form-label text-md-right">{{ __('N° Recibo') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="numRecibo" id="numRecibo" class="form-control" value="{{ $reservaInmueble->numRecibo }}" min="0" required>

                                <span class="text-danger">{{$errors->first('numRecibo')}}</span>

                                @if (\Session::has('validarNumRecibo'))
                                  <span class="text-danger">{!! \Session::get('validarNumRecibo') !!}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Confirmar') }}
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
