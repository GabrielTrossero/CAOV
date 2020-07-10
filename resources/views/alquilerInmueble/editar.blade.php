@extends('layouts.master')

@section('content')

<div class="cuadro" style="padding-top:25px; padding-bottom:25px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Consultar Disponibilidad de Inmueble') }}</div>
                <div class="card-body">
                            <form action="#" id="form-check">
                                <input type="text" id="action" value="{{ url('alquilerinmueble/disponibilidad') }}'" hidden>
                                <div class="form-group row">
                                  <label for="chequear-fecha-hora-inicio" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Inicio') }}</label>
                                  <div class="col-md-6">
                                    <input type="date" name="chequear-fecha-inicio" id="chequear-fecha-inicio" class="form-control" value="">
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="chequear-fecha-hora-inicio" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Finalización') }}</label>
                                  <div class="col-md-6">
                                    <input type="date" name="chequear-fecha-fin" id="chequear-fecha-fin" class="form-control" value="">
                                  </div>
                                </div>
                                <input type="text" id="token" value="{{ csrf_token() }}" hidden>
                                <input type="text" id="tipo" value="inmueble" hidden>
                                <div class="form-group row">
                                  <div style="width: 100%; text-align: center;">
                                      <div style="display: inline-block;">
                                          <button type="button" id="chequear" class="btn btn-outline-primary" title="Comprobar disponibilidad">Enviar</button>
                                      </div>
                                      <div style="display: inline-block;">
                                          <button type="button" id="ocultar-scheduler" class="btn btn-outline-warning offset-md-10" style="display: none" title="Ocultar calendario">Ocultar calendario</button>
                                      </div>
                                  </div>
                                </div>
                            </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="scheduler" class="dhx_cal_container" style='width:100%; height:100vh; display: none;'>
	<div class="dhx_cal_navline">
		<div class="dhx_cal_prev_button">&nbsp;</div>
		<div class="dhx_cal_next_button">&nbsp;</div>
		<div class="dhx_cal_today_button"></div>
		<div class="dhx_cal_date"></div>
		<div class="dhx_cal_tab" name="day_tab"></div>
		<div class="dhx_cal_tab" name="week_tab"></div>
		<div class="dhx_cal_tab" name="month_tab"></div>
	</div>
	<div class="dhx_cal_header"></div>
	<div class="dhx_cal_data"></div>
</div>

<div class="cuadro" style="padding-top:15px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Modificar Alquiler de Inmueble') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/alquilerinmueble/edit') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $reservaInmueble->id }}">

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
                            <label for="idPersona" class="col-md-4 col-form-label text-md-right">{{ __('Persona *') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" name="idPersona" id="idPersona">
                                  <option disabled>Seleccionar Persona</option>
                                  <option value="{{$reservaInmueble->persona->id}}" selected>{{ $reservaInmueble->persona->DNI ." - ". $reservaInmueble->persona->apellido .", ". $reservaInmueble->persona->nombres }}</option>

                                  @foreach ($personas as $persona)
                                    <option value="{{$persona->id}}">{{ $persona->DNI .' - '. $persona->apellido .', '. $persona->nombres }}</option>
                                  @endforeach
                                </select>

                                @if ($errors->first('idPersona'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('idPersona') }}
                                  </div>
                                @endif

                                @if (\Session::has('validarPersonaExiste'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('validarPersonaExiste') !!}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inmueble" class="col-md-4 col-form-label text-md-right">{{ __('Inmueble *') }}</label>

                            <div class="col-md-6">
                                <select name="inmueble" id="inmueble" class="form-control" required>
                                  @foreach ($inmuebles as $inmueble)
                                    @if ($inmueble->id == $reservaInmueble->idInmueble)
                                      <option value="{{ $inmueble->id }}" selected>{{ $inmueble->nombre }}</option>
                                    @else
                                      <option value="{{ $inmueble->id }}">{{ $inmueble->nombre }}</option>
                                    @endif
                                  @endforeach
                                </select>

                                @if ($errors->first('inmueble'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('inmueble') }}
                                  </div>
                                @endif

                                @if (\Session::has('validarInmueble'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('validarInmueble') !!}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaSol" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Solicitud *') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaSol" id="fechaSol" class="form-control" required value="{{ old('fechaSol') ?? $reservaInmueble->fechaSolicitud }}">

                                @if ($errors->first('fechaSol'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('fechaSol') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaHoraInicio" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Inicio *') }}</label>

                            <div class="col-md-6">
                                <input type="datetime" name="fechaHoraInicio" id="fechaHoraInicio" class="form-control" required value="{{ old('fechaHoraInicio') ?? $reservaInmueble->fechaHoraInicio }}">

                                @if ($errors->first('fechaHoraInicio'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('fechaHoraInicio') }}
                                  </div>
                                @endif

                                @if (\Session::has('solapamientoFechas'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('solapamientoFechas') !!}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaHoraFin" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Finalización*')}}</label>

                            <div class="col-md-6">
                                <input type="datetime" name="fechaHoraFin" id="fechaHoraFin" class="form-control" required value="{{ old('fechaHoraFin') ?? $reservaInmueble->fechaHoraFin }}">

                                @if ($errors->first('fechaHoraFin'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('fechaHoraFin') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="observacion" class="col-md-4 col-form-label text-md-right">{{ __('Observación') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="observacion" id="observacion" class="form-control" value="{{ old('observacion') ?? $reservaInmueble->observacion }}">

                                @if ($errors->first('observacion'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('observacion') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="costoReserva" class="col-md-4 col-form-label text-md-right">{{ __('Costo de la Reserva *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="costoReserva" id="costoReserva" class="form-control" required value="{{ old('costoReserva') ?? $reservaInmueble->costoReserva }}">

                                @if ($errors->first('costoReserva'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('costoReserva') }}
                                  </div>
                                @endif

                                @if (\Session::has('validarMonto'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('validarMonto') !!}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="costoTotal" class="col-md-4 col-form-label text-md-right">{{ __('Costo Total *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="costoTotal" id="costoTotal" class="form-control" required value="{{ old('costoTotal') ?? $reservaInmueble->costoTotal }}">

                                @if ($errors->first('costoTotal'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('costoTotal') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="medioPago" class="col-md-4 col-form-label text-md-right">{{ __('Medio de Pago *') }}</label>

                            <div class="col-md-6">
                                <select name="medioPago" id="medioPago" class="form-control" required>
                                  @foreach ($mediosDePago as $medioDePago)
                                    @if ($medioDePago->id == $reservaInmueble->idMedioDePago)
                                      <option value="{{ $medioDePago->id }}" selected>{{ $medioDePago->nombre }}</option>
                                    @else
                                      <option value="{{ $medioDePago->id }}">{{ $medioDePago->nombre }}</option>
                                    @endif

                                  @endforeach
                                </select>

                                @if ($errors->first('medioPago'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('medioPago') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoEvento" class="col-md-4 col-form-label text-md-right">{{ __('Tipo de Evento *') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="tipoEvento" id="tipoEvento" class="form-control" required value="{{ old('tipoEvento') ?? $reservaInmueble->tipoEvento }}">

                                @if ($errors->first('tipoEvento'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('tipoEvento') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantAsistentes" class="col-md-4 col-form-label text-md-right">{{ __('Cantidad de Asistentes *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantAsistentes" id="cantAsistentes" class="form-control" required value="{{ old('cantAsistentes') ?? $reservaInmueble->cantAsistentes }}">

                                @if ($errors->first('cantAsistentes'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('cantAsistentes') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="servicioLimp" class="col-md-4 col-form-label text-md-right">{{ __('Servicio de Limpieza *') }}</label>

                            <div class="col-md-6">
                                <select name="servicioLimp" id="servicioLimp" class="form-control" required>
                                  @if ($reservaInmueble->tieneServicioLimpieza)
                                    <option value="0">No</option>
                                    <option value="1" selected>Si</option>
                                  @else
                                    <option value="0" selected>No</option>
                                    <option value="1">Si</option>
                                  @endif
                                </select>

                                @if ($errors->first('servicioLimp'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('servicioLimp') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="musica" class="col-md-4 col-form-label text-md-right">{{ __('Música *') }}</label>

                            <div class="col-md-6">
                                <select name="musica" id="musica" class="form-control" required>
                                  @if ($reservaInmueble->tieneMusica)
                                    <option value="0">No</option>
                                    <option value="1" selected>Si</option>
                                  @else
                                    <option value="0" selected>No</option>
                                    <option value="1">Si</option>
                                  @endif
                                </select>

                                @if ($errors->first('musica'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('musica') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="reglamento" class="col-md-4 col-form-label text-md-right">{{ __('Reglamento *') }}</label>

                            <div class="col-md-6">

                                <select name="reglamento" id="reglamento" class="form-control" >
                                  @if ($reservaInmueble->tieneReglamento)
                                    <option value="0">No</option>
                                    <option value="1" selected>Si</option>
                                  @else
                                    <option value="0" selected>No</option>
                                    <option value="1">Si</option>
                                  @endif

                                </select>

                                @if ($errors->first('reglamento'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('reglamento') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="numRecibo" class="col-md-4 col-form-label text-md-right">{{ __('N° Recibo') }}</label>

                            <div class="col-md-6">
                                @if($reservaInmueble->numRecibo == null)
                                  <input type="number" name="numRecibo" id="numRecibo" class="form-control" placeholder="El alquiler no ha sido pagado" disabled>
                                @else
                                  <input type="number" name="numRecibo" id="numRecibo" class="form-control" value="{{ old('numRecibo') ?? $reservaInmueble->numRecibo }}" min="0">
                                @endif

                                <span class="text-danger">{{$errors->first('numRecibo')}}</span>

                                @if (\Session::has('validarNumRecibo'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('validarNumRecibo') }}
                                  </div>
                                @endif
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
