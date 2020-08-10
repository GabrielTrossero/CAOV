@extends('layouts.master')

@section('content')

<div class="cuadro" style="padding-top:25px; padding-bottom:25px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Consultar Disponibilidad de Mueble') }}</div>
                <div class="card-body">
                            <form action="#" id="form-check">
                                <input type="text" id="action" value="{{ url('alquilermueble/disponibilidad') }}'" hidden>
                                <div class="form-group row">
                                    <label for="chequear-fecha-hora-inicio" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Inicio') }}</label>
                                    <div class="col-md-6">
                                        <input type="datetime-local" name="chequear-fecha-hora-inicio" id="chequear-fecha-hora-inicio" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="chequear-fecha-hora-fin" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Finalización') }}</label>
                                    <div class="col-md-6">
                                        <input type="datetime-local" name="chequear-fecha-hora-fin" id="chequear-fecha-hora-fin" class="form-control" value="">
                                    </div>
                                </div>
                                <input type="text" id="token" value="{{ csrf_token() }}" hidden>
                                <input type="text" id="tipo" value="mueble" hidden>
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
                <div class="card-header">{{ __('Agregar Alquiler de Mueble') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/alquilermueble/create') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="idPersona" class="col-md-4 col-form-label text-md-right">{{ __('Persona *') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" name="idPersona" id="idPersona">
                                  <option selected disabled>Seleccionar Persona</option>
                                  @foreach ($personas as $persona)
                                    @if (isset($personaRetornada) && ($persona->id == $personaRetornada->id)))
                                        <option value="{{$persona->id}}" selected>{{ $persona->DNI .' - '. $persona->apellido .', '. $persona->nombres }}</option>
                                    @else
                                        <option value="{{$persona->id}}">{{ $persona->DNI .' - '. $persona->apellido .', '. $persona->nombres }}</option>
                                    @endif
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

                            <div class="col-form-label">
                              <a href="{{ url('/persona/createFromMueble') }}" title="Agregar Persona">
                                <i class="fas fa-plus"></i>
                              </a>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaSolicitud" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Solicitud *') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaSolicitud" id="fechaSolicitud" class="form-control" value="{{ old('fechaSolicitud') }}" required>

                                @if ($errors->first('fechaSolicitud'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('fechaSolicitud') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoMueble" class="col-md-4 col-form-label text-md-right">{{ __('Seleccione el Mueble *') }}</label>

                            <div class="col-md-6">
                              <select name="tipoMueble" id="tipoMueble" class="form-control" required>
                                @foreach ($muebles as $mueble)
                                  <option value="{{ $mueble->id }}">{{ $mueble->nombre }}</option>
                                @endforeach
                              </select>

                              @if ($errors->first('tipoMueble'))
                                <div class="alert alert-danger errorForm">
                                    {{ $errors->first('tipoMueble') }}
                                </div>
                              @endif

                              @if (\Session::has('validarMueble'))
                                <div class="alert alert-danger errorForm">
                                    {!! \Session::get('validarMueble') !!}
                                </div>
                              @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantMueble" class="col-md-4 col-form-label text-md-right">{{ __('Ingrese la Cantidad *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantMueble" id="cantMueble" class="form-control" value="{{ old('cantMueble') }}" min="1" required>
                                
                                @if ($errors->first('cantMueble'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('cantMueble') }}
                                  </div>
                                @endif

                                @if (\Session::has('sinStock'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('sinStock') !!}
                                  </div>
                                @endif

                                @if (\Session::has('validarCantidad'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('validarCantidad') !!}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaHoraInicio" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Inicio *') }}</label>

                            <div class="col-md-6">
                                <input type="datetime-local" name="fechaHoraInicio" id="fechaHoraInicio" class="form-control" value="{{ old('fechaHoraInicio') }}" required>

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
                            <label for="fechaHoraFin" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Finalización*') }}</label>

                            <div class="col-md-6">
                                <input type="datetime-local" name="fechaHoraFin" id="fechaHoraFin" class="form-control" value="{{ old('fechaHoraFin') }}" required>

                                @if ($errors->first('fechaHoraFin'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('fechaHoraFin') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="costo" class="col-md-4 col-form-label text-md-right">{{ __('Costo *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="costo" id="costo" class="form-control" value="{{ old('costo') }}" min="0" required>

                                @if ($errors->first('costo'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('costo') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="medioPago" class="col-md-4 col-form-label text-md-right">{{ __('Medio de Pago *') }}</label>

                            <div class="col-md-6">
                                <select name="medioPago" id="medioPago" class="form-control" required>
                                  @foreach ($mediosDePago as $medioDePago)
                                    <option value="{{ $medioDePago->id }}">{{ $medioDePago->nombre }}</option>
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
                            <label for="observacion" class="col-md-4 col-form-label text-md-right">{{ __('Observación') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="observacion" id="observacion" class="form-control" value="{{ old('observacion') }}" maxlength="100">

                                @if ($errors->first('observacion'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('observacion') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-1 offset-md-4">
                              <a style="text-decoration:none" href="{{ url('/alquilermueble') }}">
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
