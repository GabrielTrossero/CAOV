@extends('layouts.master')

@section('content')
<!--
<div class="cuadro" style="padding-top:25px; padding-bottom:25px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Consultar Disponibilidad de Mueble') }}</div>
                <div class="card-body">
                     <div class="form-group row">
                        <label for="chequear-fecha" class="col-md-4 col-form-label text-md-right">{{ __('Consultar Disponibilidad') }}</label>

                        <div class="col-md-6">
                            <form action="#" id="form-check">
                                <input type="text" id="action" value="{{ url('alquilermueble/disponibilidad') }}'" hidden>
                                <input type="date" name="chequear-fecha" id="chequear-fecha" class="form-control" value="">
                                <input type="text" id="token" value="{{ csrf_token() }}" hidden>
                                <input type="text" id="tipo" value="mueble" hidden>
                                <button type="button" id="chequear"><i class="fas fa-check" style="color:blue"></i></button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
-->
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
                                <button type="button" id="chequear" class="col-md-1 offset-md-5"><i class="fas fa-check" style="color:blue"></i></button>
                            </form>

                       
                </div>
            </div>
        </div>
    </div>
</div>

<div class="cuadro" style="padding-top:15px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Modificar Alquiler de Mueble') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/alquilermueble/edit') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $reserva->id }}">

                        <div class="form-group row">
                            <label for="idPersona" class="col-md-4 col-form-label text-md-right">{{ __('Persona *') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" name="idPersona" id="idPersona">
                                  <option disabled>Seleccionar Persona</option>
                                  <option value="{{$reserva->persona->id}}" selected>{{ $reserva->persona->DNI ." - ". $reserva->persona->apellido .", ". $reserva->persona->nombres }}</option>

                                  @foreach ($personas as $persona)
                                    <option value="{{$persona->id}}">{{ $persona->DNI .' - '. $persona->apellido .', '. $persona->nombres }}</option>
                                  @endforeach
                                </select>

                                <span class="text-danger">{{$errors->first('idPersona')}}</span>

                                @if (\Session::has('validarPersonaExiste'))
                                  <span class="text-danger">{!! \Session::get('validarPersonaExiste') !!}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaSolicitud" class="col-md-4 col-form-label text-md-right">{{ __('Fecha Actual *') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaSolicitud" id="fechaSolicitud" class="form-control" value="{{ old('fechaSolicitud') ?? $reserva->fechaSolicitud }}" required>

                                <span class="text-danger">{{$errors->first('fechaSolicitud')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoMueble" class="col-md-4 col-form-label text-md-right">{{ __('Seleccione el Mueble *') }}</label>

                            <div class="col-md-6">
                              <select name="tipoMueble" id="tipoMueble" class="form-control" required>
                                @foreach ($muebles as $mueble)
                                  <!--para seleccionar por defecto la que tiene actualmente-->
                                  @if ($reserva->mueble->id == $mueble->id)
                                    <option value="{{ $mueble->id }}" selected>{{ $mueble->nombre }}</option>
                                  @else
                                    <option value="{{ $mueble->id }}">{{ $mueble->nombre }}</option>
                                  @endif
                                @endforeach
                              </select>

                              <span class="text-danger">{{$errors->first('tipoMueble')}}</span>

                              @if (\Session::has('validarMueble'))
                                <span class="text-danger">{!! \Session::get('validarMueble') !!}</span>
                              @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantMueble" class="col-md-4 col-form-label text-md-right">{{ __('Ingrese la Cantidad *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantMueble" id="cantMueble" class="form-control" value="{{ old('cantMueble') ?? $reserva->cantidad }}" min="1" required>

                                <span class="text-danger">{{$errors->first('cantMueble')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaHoraInicio" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Inicio *') }}</label>

                            <div class="col-md-6">
                                <input type="datetime" name="fechaHoraInicio" id="fechaHoraInicio" class="form-control" value="{{ old('fechaHoraInicio') ?? $reserva->fechaHoraInicio }}" required>

                                <span class="text-danger">{{$errors->first('fechaHoraInicio')}}</span>

                                @if (\Session::has('sinStock'))
                                  <span class="text-danger">{!! \Session::get('sinStock') !!}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaHoraFin" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Finalización*') }}</label>

                            <div class="col-md-6">
                                <input type="datetime" name="fechaHoraFin" id="fechaHoraFin" class="form-control" value="{{ old('fechaHoraFin') ?? $reserva->fechaHoraFin }}" required>

                                <span class="text-danger">{{$errors->first('fechaHoraFin')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="costo" class="col-md-4 col-form-label text-md-right">{{ __('Costo ($) *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="costo" id="costo" class="form-control" value="{{ old('costo') ?? $reserva->costoTotal }}" min="0" required>

                                <span class="text-danger">{{$errors->first('costo')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="medioPago" class="col-md-4 col-form-label text-md-right">{{ __('Medio de Pago *') }}</label>

                            <div class="col-md-6">
                                <select name="medioPago" id="medioPago" class="form-control" required>
                                  @foreach ($mediosDePagos as $medioDePago)
                                    <!--para seleccionar por defecto la que tiene actualmente-->
                                    @if ($reserva->medioDePago->id == $medioDePago->id)
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
                            <label for="observacion" class="col-md-4 col-form-label text-md-right">{{ __('Observación') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="observacion" id="observacion" class="form-control" value="{{ old('observacion') ?? $reserva->observacion }}" maxlength="100">

                                <span class="text-danger">{{$errors->first('observacion')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="numRecibo" class="col-md-4 col-form-label text-md-right">{{ __('N° Recibo') }}</label>

                            <div class="col-md-6">
                                @if($reserva->numRecibo == null)
                                  <input type="number" name="numRecibo" id="numRecibo" class="form-control" placeholder="El alquiler no ha sido pagado" disabled>
                                @else
                                  <input type="number" name="numRecibo" id="numRecibo" class="form-control" value="{{ old('numRecibo') ?? $reserva->numRecibo }}" min="0">
                                @endif

                                <span class="text-danger">{{$errors->first('numRecibo')}}</span>

                                @if (\Session::has('validarNumRecibo'))
                                  <span class="text-danger">{!! \Session::get('validarNumRecibo') !!}</span>
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
