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

                        <!--para mostrar las distintas alertas-->
                        <div class="form-group row">
                            <label class="col-md-1 col-form-label text-md-right"></label>
                            <div class="col-md-10">

                              <div class="alert alert-warning">
                                {{ 'ACLARACIÓN: El costo Total incluye el costo de la Reserva.' }}
                              </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="persona" class="col-md-4 col-form-label text-md-right">{{ __('Solicitante') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="persona" id="persona" class="form-control" maxlength="8" value="{{ $reservaInmueble->persona->DNI .' - '. $reservaInmueble->persona->apellido .', '. $reservaInmueble->persona->nombres }}" disabled>
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
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaSol" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Solicitud') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaSol" id="fechaSol" class="form-control" value="{{ $reservaInmueble->fechaSolicitud }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaHoraInicio" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Inicio') }}</label>

                            <div class="col-md-6">
                                <input type="datetime" name="fechaHoraInicio" id="fechaHoraInicio" class="form-control" value="{{ $reservaInmueble->fechaHoraInicio }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaHoraFin" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Finalización') }}</label>

                            <div class="col-md-6">
                                <input type="datetime" name="fechaHoraFin" id="fechaHoraFin" class="form-control" value="{{ old('fechaHoraFin') ?? $reservaInmueble->fechaHoraFin }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="observacion" class="col-md-4 col-form-label text-md-right">{{ __('Observación') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="observacion" id="observacion" class="form-control" value="{{ old('observacion') ?? $reservaInmueble->observacion }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="costoReserva" class="col-md-4 col-form-label text-md-right">{{ __('Costo de la Reserva') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="costoReserva" id="costoReserva" class="form-control" value="{{ old('costoReserva') ?? $reservaInmueble->costoReserva }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="costoTotal" class="col-md-4 col-form-label text-md-right">{{ __('Costo Total') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="costoTotal" id="costoTotal" class="form-control" value="{{ old('costoTotal') ?? $reservaInmueble->costoTotal }}" disabled>
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
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoEvento" class="col-md-4 col-form-label text-md-right">{{ __('Tipo de Evento') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="tipoEvento" id="tipoEvento" class="form-control" value="{{ old('tipoEvento') ?? $reservaInmueble->tipoEvento }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantAsistentes" class="col-md-4 col-form-label text-md-right">{{ __('Cantidad de Asistentes') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantAsistentes" id="cantAsistentes" class="form-control" value="{{ old('cantAsistentes') ?? $reservaInmueble->cantAsistentes }}" disabled>
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
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="numRecibo" class="col-md-4 col-form-label text-md-right">{{ __('N° Recibo') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="numRecibo" id="numRecibo" class="form-control" value="{{ old('numRecibo') ?? $reservaInmueble->numRecibo }}" min="0" required>

                                <span class="text-danger">{{$errors->first('numRecibo')}}</span>

                                @if (\Session::has('validarNumRecibo'))
                                  <span class="text-danger">{!! \Session::get('validarNumRecibo') !!}</span>
                                @endif
                            </div>
                        </div>

                        <!--para mostrar las distintas alertas-->
                        <div class="form-group row">
                            <label class="col-md-1 col-form-label text-md-right"></label>
                            <div class="col-md-10">

                              <div class="alert alert-danger" align="center">
                                {{ 'MONTO A PAGAR: $'. ($reservaInmueble->costoTotal - $reservaInmueble->costoReserva) }}
                              </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
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
