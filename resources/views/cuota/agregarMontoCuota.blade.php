@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Agregar Monto para Cuotas') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/alquilermueble/create') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="medioPago" class="col-md-4 col-form-label text-md-right">{{ __('Tipo') }}</label>

                            <div class="col-md-6">
                                <select name="medioPago" id="medioPago" class="form-control">
                                  <option value="1">Activo</option>
                                  <option value="2">Cadete</option>
                                  <option value="3">Grupo Familiar</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dtoSemestre" class="col-md-4 col-form-label text-md-right">{{ __('Descuento Semestre (%)') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="dtoSemestre" id="dtoSemestre" class="form-control" min="0" max="100">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dtoAnio" class="col-md-4 col-form-label text-md-right">{{ __('Descuento Año (%)') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="dtoAnio" id="dtoAnio" class="form-control" min="0" max="100">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaCreacion" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Creación') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaCreacion" id="fechaCreacion" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="monto" class="col-md-4 col-form-label text-md-right">{{ __('Monto Base') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="monto" id="monto" class="form-control">
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
