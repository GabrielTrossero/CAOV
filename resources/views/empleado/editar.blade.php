@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Editar Empleado') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/empleado/edit') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Nombre de Usuario') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="username" id="username" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="persona" class="col-md-4 col-form-label text-md-right">{{ __('Persona') }}</label>

                            <div class="col-md-6">
                                <select name="persona" id="persona" class="form-control">
                                  <option value="0">Seleccionar Persona</option>
                                  <option value="1">Penkita - 39848956</option>
                                  <option value="2">Misio - 38956842</option>
                                  <option value="3">Tula - 38959655</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                            <div class="col-md-6">
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="passwordRepeat" class="col-md-4 col-form-label text-md-right">{{ __('Repetir Contraseña') }}</label>

                            <div class="col-md-6">
                                <input type="password" name="passwordRepeat" id="passwordRepeat" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoUsuario" class="col-md-4 col-form-label text-md-right">{{ __('Tipo de Usuario') }}</label>

                            <div class="col-md-6">
                                <select name="tipoUsuario" id="tipoUsuario" class="form-control">
                                  <option value="1">Empleado</option>
                                  <option value="2">Administrador</option>
                                </select>
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
