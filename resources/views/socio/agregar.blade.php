@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Agregar Socio') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/socio/create') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="numSocio" class="col-md-4 col-form-label text-md-right">{{ __('Numero de Socio') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="numSocio" id="numSocio" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaNac" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Nacimiento') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaNac" id="fechaNac" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="oficio" class="col-md-4 col-form-label text-md-right">{{ __('Oficio') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="oficio" id="oficio" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="categoria" class="col-md-4 col-form-label text-md-right">{{ __('Categoria') }}</label>

                            <div class="col-md-6">
                                <select name="categoria" id="categoria" class="form-control">
                                  <option value="1">Cadete</option>
                                  <option value="2">Activo</option>
                                  <option value="3">Grupo Familiar</option>
                                  <option value="4">Honorario</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="grupofamiliar" class="col-md-4 col-form-label text-md-right">{{ __('Grupo Familiar') }}</label>

                            <div class="col-md-6">
                              <select name="categoria" id="categoria" class="form-control">
                                <option value="0">No posee grupo familiar</option>
                                <option value="1">Titular: Penka - 39840568</option>
                                <option value="2">Titular: Tula - 39845263</option>
                                <option value="3">Titular: Sonia Vera - 30568985</option>
                              </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="deporte" class="col-md-4 col-form-label text-md-right">{{ __('Deporte') }}</label>

                            <div class="col-md-6">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="deporte" id="futbol">
                                <label class="form-check-label" for="futbol">
                                  Futbol
                                </label>
                                <br>
                                <input class="form-check-input" type="checkbox" name="deporte" id="volley">
                                <label class="form-check-label" for="volley">
                                  Volley
                                </label>
                                <br>
                                <input class="form-check-input" type="checkbox" name="deporte" id="basquet">
                                <label class="form-check-label" for="basquet">
                                  Basquet
                                </label>
                              </div>
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
