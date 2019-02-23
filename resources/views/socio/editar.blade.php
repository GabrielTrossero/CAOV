@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Modificar Socio') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/socio/edit') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="1">

                        <div class="form-group row">
                            <label for="numSocio" class="col-md-4 col-form-label text-md-right">{{ __('Numero de Socio') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="numSocio" id="numSocio" class="form-control" value="23">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaNac" class="col-md-4 col-form-label text-md-right">{{ __('Fecha Nacimiento') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaNac" id="FechaNac" class="form-control" value="26/12/1996">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="oficio" class="col-md-4 col-form-label text-md-right">{{ __('Oficio') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="oficio" id="oficio" class="form-control" value="Carpintero">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="sacarDeporte" class="col-md-4 col-form-label text-md-right">{{ __('Sacar Deporte') }}</label>

                            <div class="col-md-6">
                                <select name="sacarDeporte" id="sacarDeporte" class="form-control">
                                  <option value="0">Ninguno</option>
                                  <option value="1">Futbol</option>
                                  <option value="2">Hockey</option>
                                  <option value="3">Volley</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="agregarDeporte" class="col-md-4 col-form-label text-md-right">{{ __('Agregar Deporte') }}</label>

                            <div class="col-md-6">
                                <select name="agregarDeporte" id="agregarDeporte" class="form-control">
                                  <option value="0">Ninguno</option>
                                  <option value="1">Futbol</option>
                                  <option value="2">Hockey</option>
                                  <option value="3">Volley</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="grupoFamiliar" class="col-md-4 col-form-label text-md-right">{{ __('Grupo Familiar') }}</label>

                            <div class="col-md-6">
                                <select name="grupoFamiliar" id="GrupoFamiliar" class="form-control">
                                  <option value="0">Ninguno</option>
                                  <option value="1">3984562 Titular</option>
                                  <option value="2">3758962 Titular</option>
                                  <option value="3">2589632 Titular</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="categoria" class="col-md-4 col-form-label text-md-right">{{ __('Categoria') }}</label>

                            <div class="col-md-6">
                                <select name="categoria" id="categoria" class="form-control">
                                  <option value="1">Cadete</option>
                                  <option value="2">Activo</option>
                                  <option value="3">Honorario</option>
                                  <option value="4">Grupo Familiar</option>
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
