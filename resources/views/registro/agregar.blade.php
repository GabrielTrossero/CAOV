@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
              @if (\Session::has('success'))
                <div class="alert alert-success">
                    {!! \Session::get('success') !!}
                </div>
              @endif
                <div class="card-header">{{ __('Agregar Registro') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url("/registro/") }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="numRecibo" class="col-md-4 col-form-label text-md-right">{{ __('Numero de Recibo') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="numRecibo" id="numRecibo" class="form-control" value="{{ old('numRecibo') }}" required>
                                <span class="text-danger">{{$errors->first('numRecibo')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fecha" class="col-md-4 col-form-label text-md-right">{{ __('Fecha') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old( 'fecha') }}" required>
                                <span class="text-danger">{{$errors->first('fecha')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="monto" class="col-md-4 col-form-label text-md-right">{{ __('Monto') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="monto" id="monto" class="form-control" value="{{ old( 'monto') }}" required>
                                <span class="text-danger">{{$errors->first('monto')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="descripcion" class="col-md-4 col-form-label text-md-right">{{ __('Descripción') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="descripcion" id="descripcion" class="form-control" value="{{ old( 'descripcion') }}" required>
                                <span class="text-danger">{{$errors->first('descripcion')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoRegistro" class="col-md-4 col-form-label text-md-right">{{ __('Tipo de Registro') }}</label>

                            <div class="col-md-6">
                                <select name="tipoRegistro" id="tipoRegistro" class="form-control" required>
                                  <option value="1" selected>Ingreso</option>
                                  <option value="2">Egreso</option>
                                </select>
                                <span class="text-danger">{{$errors->first('tipoRegistro')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="usuario" class="col-md-4 col-form-label text-md-right">{{ __('Usuario') }}</label>

                            <div class="col-md-6">
                                <select name="usuario" id="usuario" class="form-control" required>
                                  @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->persona->DNI . " - " . $usuario->persona->nombres . " " . $usuario->persona->apellido }}</option>
                                  @endforeach
                                </select>

                                <span class="text-danger">{{$errors->first('usuario')}}</span>

                                @if (\Session::has('validarUsuario'))
                                  <span class="text-danger">{!! \Session::get('validarUsuario') !!}</span>
                                @endif
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
