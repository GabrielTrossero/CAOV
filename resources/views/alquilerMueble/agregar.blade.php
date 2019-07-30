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
                                <input type="number" name="DNI" id="DNI" class="form-control" value="{{ old('DNI') }}" min="0" required>

                                <span class="text-danger">{{$errors->first('DNI')}}</span>

                                @if (\Session::has('DNIinexistente'))
                                  <span class="text-danger">{!! \Session::get('DNIinexistente') !!}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaSolicitud" class="col-md-4 col-form-label text-md-right">{{ __('Fecha Actual') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaSolicitud" id="fechaSolicitud" class="form-control" value="{{ old('fechaSolicitud') }}" required>

                                <span class="text-danger">{{$errors->first('fechaSolicitud')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoMueble" class="col-md-4 col-form-label text-md-right">{{ __('Seleccione el Mueble') }}</label>

                            <div class="col-md-6">
                              <select name="tipoMueble" id="tipoMueble" class="form-control" required>
                                @foreach ($muebles as $mueble)
                                  <option value="{{ $mueble->id }}">{{ $mueble->nombre }}</option>
                                @endforeach
                              </select>

                              <span class="text-danger">{{$errors->first('tipoMueble')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantMueble" class="col-md-4 col-form-label text-md-right">{{ __('Ingrese la Cantidad') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantMueble" id="cantMueble" class="form-control" value="{{ old('cantMueble') }}" required>

                                <span class="text-danger">{{$errors->first('cantMueble')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaHoraInicio" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Inicio') }}</label>

                            <div class="col-md-6">
                                <input type="datetime-local" name="fechaHoraInicio" id="fechaHoraInicio" class="form-control" value="{{ old('fechaHoraInicio') }}" required>

                                <span class="text-danger">{{$errors->first('fechaHoraInicio')}}</span>

                                @if (\Session::has('solapamientoFechas'))
                                  <span class="text-danger">{!! \Session::get('solapamientoFechas') !!}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaHoraFin" class="col-md-4 col-form-label text-md-right">{{ __('Fecha y Hora de Finalización') }}</label>

                            <div class="col-md-6">
                                <input type="datetime-local" name="fechaHoraFin" id="fechaHoraFin" class="form-control" value="{{ old('fechaHoraFin') }}" required>

                                <span class="text-danger">{{$errors->first('fechaHoraFin')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="costo" class="col-md-4 col-form-label text-md-right">{{ __('Costo ($)') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="costo" id="costo" class="form-control" value="{{ old('costo') }}" min="0" required>

                                <span class="text-danger">{{$errors->first('costo')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="medioPago" class="col-md-4 col-form-label text-md-right">{{ __('Medio de Pago') }}</label>

                            <div class="col-md-6">
                                <select name="medioPago" id="medioPago" class="form-control" required>
                                  @foreach ($mediosDePagos as $medioDePago)
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
