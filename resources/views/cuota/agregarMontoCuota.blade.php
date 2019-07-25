@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Agregar Monto para Cuotas') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/cuota/createMontoCuota') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="tipo" class="col-md-4 col-form-label text-md-right">{{ __('Tipo') }}</label>

                            <div class="col-md-6">
                                <select name="tipo" id="tipo" class="form-control">
                                  <option value="a">Activo</option>
                                  <option value="c">Cadete</option>
                                  <option value="g">Grupo Familiar</option>
                                </select>

                                <span class="text-danger">{{$errors->first('tipo')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dtoSemestre" class="col-md-4 col-form-label text-md-right">{{ __('Descuento Semestre (%)') }}</label>

                            <div class="col-md-6">
                                <input type="number" step="0.01" name="dtoSemestre" id="dtoSemestre" class="form-control" value="{{ old('dtoSemestre') }}" min="0" max="99" placeholder="0 - 99" required>

                                <span class="text-danger">{{$errors->first('dtoSemestre')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dtoAnio" class="col-md-4 col-form-label text-md-right">{{ __('Descuento Año (%)') }}</label>

                            <div class="col-md-6">
                                <input type="number" step="0.01" name="dtoAnio" id="dtoAnio" class="form-control" value="{{ old('dtoAnio') }}" min="0" max="99" placeholder="0 - 99" required>

                                <span class="text-danger">{{$errors->first('dtoAnio')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="monto" class="col-md-4 col-form-label text-md-right">{{ __('Monto Base Mensual') }}</label>

                            <div class="col-md-6">
                              <input type="number" name="monto" id="monto" class="form-control" min="0" value="{{ old('monto') }}" required>

                              <span class="text-danger">{{$errors->first('monto')}}</span>
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
