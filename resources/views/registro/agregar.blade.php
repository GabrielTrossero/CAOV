@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Agregar Registro') }}</div>

                @if (\Session::has('success'))
                  <div class="alert alert-success">
                      {!! \Session::get('success') !!}
                  </div>
                @endif

                <div class="card-body">
                    <form method="POST" action="{{ url("/registro/create") }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ Auth::user()->id }}">

                        <div class="form-group row">
                            <label for="numRecibo" class="col-md-4 col-form-label text-md-right">{{ __('Numero de Recibo *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="numRecibo" id="numRecibo" class="form-control" value="{{ old('numRecibo') }}" required>

                                @if ($errors->first('numRecibo'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('numRecibo') }}
                                  </div>
                                @endif

                                @if (\Session::has('validarNumRecibo'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('validarNumRecibo') !!}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fecha" class="col-md-4 col-form-label text-md-right">{{ __('Fecha *') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old( 'fecha') }}" required>

                                @if ($errors->first('fecha'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('fecha') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="monto" class="col-md-4 col-form-label text-md-right">{{ __('Monto *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="monto" id="monto" class="form-control" value="{{ old( 'monto') }}" required>

                                @if ($errors->first('monto'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('monto') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="descripcion" class="col-md-4 col-form-label text-md-right">{{ __('Descripción *') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="descripcion" id="descripcion" class="form-control" value="{{ old( 'descripcion') }}" required>

                                @if ($errors->first('descripcion'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('descripcion') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoRegistro" class="col-md-4 col-form-label text-md-right">{{ __('Tipo de Registro *') }}</label>

                            <div class="col-md-6">
                                <select name="tipoRegistro" id="tipoRegistro" class="form-control" required>
                                  <option value="1" selected>Ingreso</option>
                                  <option value="2">Egreso</option>
                                </select>

                                @if ($errors->first('tipoRegistro'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('tipoRegistro') }}
                                  </div>
                                @endif
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
