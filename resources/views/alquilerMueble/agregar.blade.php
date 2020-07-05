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

                                <span class="text-danger">{{$errors->first('idPersona')}}</span>

                                @if (\Session::has('validarPersonaExiste'))
                                  <span class="text-danger">{!! \Session::get('validarPersonaExiste') !!}</span>
                                @endif
                            </div>
                            <a href="{{ url('/persona/createFromMueble') }}" title="Agregar Persona">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>

                        <div class="form-group row">
                            <label for="fechaSolicitud" class="col-md-4 col-form-label text-md-right">{{ __('Fecha Actual *') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaSolicitud" id="fechaSolicitud" class="form-control" value="{{ old('fechaSolicitud') }}" required>

                                <span class="text-danger">{{$errors->first('fechaSolicitud')}}</span>
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

                              <span class="text-danger">{{$errors->first('tipoMueble')}}</span>

                              @if (\Session::has('validarMueble'))
                                <span class="text-danger">{!! \Session::get('validarMueble') !!}</span>
                              @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantMueble" class="col-md-4 col-form-label text-md-right">{{ __('Ingrese la Cantidad *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantMueble" id="cantMueble" class="form-control" value="{{ old('cantMueble') }}" min="1" required>

                                <span class="text-danger">{{$errors->first('cantMueble')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaHoraInicio" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Inicio *') }}</label>

                            <div class="col-md-6">
                                <input type="datetime-local" name="fechaHoraInicio" id="fechaHoraInicio" class="form-control" value="{{ old('fechaHoraInicio') }}" required>

                                <span class="text-danger">{{$errors->first('fechaHoraInicio')}}</span>

                                @if (\Session::has('sinStock'))
                                  <span class="text-danger">{!! \Session::get('sinStock') !!}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaHoraFin" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Finalización*') }}</label>

                            <div class="col-md-6">
                                <input type="datetime-local" name="fechaHoraFin" id="fechaHoraFin" class="form-control" value="{{ old('fechaHoraFin') }}" required>

                                <span class="text-danger">{{$errors->first('fechaHoraFin')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="costo" class="col-md-4 col-form-label text-md-right">{{ __('Costo ($) *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="costo" id="costo" class="form-control" value="{{ old('costo') }}" min="0" required>

                                <span class="text-danger">{{$errors->first('costo')}}</span>
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

                                <span class="text-danger">{{$errors->first('medioPago')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="observacion" class="col-md-4 col-form-label text-md-right">{{ __('Observación') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="observacion" id="observacion" class="form-control" value="{{ old('observacion') }}" maxlength="100">

                                <span class="text-danger">{{$errors->first('observacion')}}</span>
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
