@extends('layouts.master')

@section('content')


<div class="cuadro" style="padding-top:25px; padding-bottom:25px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Consultar Disponibilidad de Inmueble') }}</div>
                <div class="card-body">
                     <div class="form-group row">
                        <label for="chequear-fecha" class="col-md-4 col-form-label text-md-right">{{ __('Consultar Disponibilidad') }}</label>

                        <div class="col-md-6">
                            <form action="#" id="form-check">
                                <input type="text" id="action" value="{{ url('alquilerinmueble/disponibilidad') }}'" hidden>
                                <input type="date" name="chequear-fecha" id="chequear-fecha" class="form-control" value="">
                                <input type="text" id="token" value="{{ csrf_token() }}" hidden>
                                <input type="text" id="tipo" value="inmueble" hidden>
                                <button type="button" id="chequear"><i class="fas fa-check" style="color:blue"></i></button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="cuadro" style="padding-top:15px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Agregar Alquiler de Inmueble') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/alquilerinmueble/create') }}">
                        {{ csrf_field() }}

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
                                  <option selected disabled>Seleccionar Persona</option>
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
                            <label for="inmueble" class="col-md-4 col-form-label text-md-right">{{ __('Inmueble *') }}</label>

                            <div class="col-md-6">
                                <select name="inmueble" id="inmueble" class="form-control" required>
                                  @foreach ($inmuebles as $inmueble)
                                    <option value="{{ $inmueble->id }}">{{ $inmueble->nombre }}</option>
                                  @endforeach
                                </select>

                                <span class="text-danger">{{$errors->first('inmueble')}}</span>

                                @if (\Session::has('validarInmueble'))
                                  <span class="text-danger">{!! \Session::get('validarInmueble') !!}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaSol" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Solicitud *') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaSol" id="fechaSol" class="form-control" required value="{{ old('fechaSol')  }}">

                                <span class="text-danger">{{$errors->first('fechaSol')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaHoraInicio" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Inicio *') }}</label>

                            <div class="col-md-6">
                                <input type="datetime-local" name="fechaHoraInicio" id="fechaHoraInicio" class="form-control" required value="{{ old('fechaHoraInicio') }}">

                                <span class="text-danger">{{$errors->first('fechaHoraInicio')}}</span>

                                @if (\Session::has('solapamientoFechas'))
                                  <span class="text-danger">{!! \Session::get('solapamientoFechas') !!}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaHoraFin" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Finalización *') }}</label>

                            <div class="col-md-6">
                                <input type="datetime-local" name="fechaHoraFin" id="fechaHoraFin" class="form-control" required value="{{ old('fechaHoraFin') }}">

                                <span class="text-danger">{{$errors->first('fechaHoraFin')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="observacion" class="col-md-4 col-form-label text-md-right">{{ __('Observación') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="observacion" id="observacion" class="form-control" value="{{ old('observacion') }}">

                                <span class="text-danger">{{$errors->first('observacion')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="costoReserva" class="col-md-4 col-form-label text-md-right">{{ __('Costo de la Reserva *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="costoReserva" id="costoReserva" class="form-control" required value="{{ old('costoReserva') }}">

                                <span class="text-danger">{{$errors->first('costoReserva')}}</span>

                                @if (\Session::has('validarMonto'))
                                  <span class="text-danger">{!! \Session::get('validarMonto') !!}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="costoTotal" class="col-md-4 col-form-label text-md-right">{{ __('Costo Total *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="costoTotal" id="costoTotal" class="form-control" required value="{{ old('costoTotal') }}">

                                <span class="text-danger">{{$errors->first('costoTotal')}}</span>
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
                            <label for="tipoEvento" class="col-md-4 col-form-label text-md-right">{{ __('Tipo de Evento *') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="tipoEvento" id="tipoEvento" class="form-control" required value="{{ old('tipoEvento') }}">

                                <span class="text-danger">{{$errors->first('tipoEvento')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantAsistentes" class="col-md-4 col-form-label text-md-right">{{ __('Cantidad de Asistentes *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantAsistentes" id="cantAsistentes" class="form-control" required value="{{ old('cantAsistentes') }}">

                                <span class="text-danger">{{$errors->first('cantAsistentes')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="servicioLimp" class="col-md-4 col-form-label text-md-right">{{ __('Servicio de Limpieza *') }}</label>

                            <div class="col-md-6">
                                <select name="servicioLimp" id="servicioLimp" class="form-control" required>
                                  <option value="0">No</option>
                                  <option value="1">Si</option>
                                </select>

                                <span class="text-danger">{{$errors->first('servicioLimp')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="musica" class="col-md-4 col-form-label text-md-right">{{ __('Música *') }}</label>

                            <div class="col-md-6">
                                <select name="musica" id="musica" class="form-control" required>
                                  <option value="0">No</option>
                                  <option value="1">Si</option>
                                </select>

                                <span class="text-danger">{{$errors->first('musica')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="reglamento" class="col-md-4 col-form-label text-md-right">{{ __('Reglamento *') }}</label>

                            <div class="col-md-6">
                                <select name="reglamento" id="reglamento" class="form-control" required>
                                  <option value="0">No</option>
                                  <option value="1">Si</option>
                                </select>

                                <span class="text-danger">{{$errors->first('reglamento')}}</span>
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
