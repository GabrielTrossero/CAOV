@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Modificar Mueble') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/mueble/edit') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $mueble->id }}">

                        <div class="form-group row">
                            <label for="nombre" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') ?? $mueble->nombre }}" maxlength="75" required>

                                <span class="text-danger">{{$errors->first('nombre')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantidadActual" class="col-md-4 col-form-label text-md-right">{{ __('Cantidad Actual') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantidadActual" id="cantidadActual" class="form-control" value="{{ old('cantidadActual') ?? $mueble->cantidad }}" disabled>

                                <span class="text-danger">{{$errors->first('cantidadActual')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="accionCantidad" class="col-md-4 col-form-label text-md-right">{{ __('Sumar o Restar cantidad del mueble') }}</label>

                            <div class="col-md-6">
                                <select name="accionCantidad" id="accionCantidad" class="form-control" required>
                                  <option value="0">Ninguna acción</option>
                                  <option value="1">Restar cantidad</option>
                                  <option value="2">Aumentar cantidad</option>
                                </select>

                                <span class="text-danger">{{$errors->first('accionCantidad')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantidadModificar" class="col-md-4 col-form-label text-md-right">{{ __('Cantidad') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantidadModificar" id="cantidadModificar" class="form-control" value="{{ old('cantidadModificar') }}" min="1">

                                <span class="text-danger">{{$errors->first('cantidadModificar')}}</span>

                                @if (\Session::has('validarCantidad'))
                                  <span class="text-danger">{!! \Session::get('validarCantidad') !!}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Editar') }}
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
