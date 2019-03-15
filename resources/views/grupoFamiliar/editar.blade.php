@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Agregar Grupo Familiar') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/empleado/create') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="titulas" class="col-md-4 col-form-label text-md-right">{{ __('Titular') }}</label>

                            <div class="col-md-6">
                                <select name="titular" id="titular" class="form-control">
                                  <option value="0">Seleccionar Socio</option>
                                  <option value="1">Penkita - 39848956</option>
                                  <option value="2">Misio - 38956842</option>
                                  <option value="3">Tula - 38959655</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="accionMiembro" class="col-md-4 col-form-label text-md-right">{{ __('Agregar o Eliminar Miembro') }}</label>

                            <div class="col-md-6">
                                <select name="accionMiembro" id="accionMiembro" class="form-control">
                                  <option value="0">Ninguna</option>
                                  <option value="1">Agregar Miembro</option>
                                  <option value="2">Eliminar Miembro</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="miembro" class="col-md-4 col-form-label text-md-right">{{ __('Miembro') }}</label>

                            <div class="col-md-6">
                                <select name="miembro" id="miembro" class="form-control">
                                  <option value="0">Seleccionar Socio</option>
                                  <option value="1">Penkita - 39848956</option>
                                  <option value="2">Misio - 38956842</option>
                                  <option value="3">Tula - 38959655</option>
                                </select>
                            </div>
                        </div>

                        <br>

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
