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
                            <label for="tipo" class="col-md-4 col-form-label text-md-right">{{ __('Tipo *') }}</label>

                            <div class="col-md-6">
                                <select name="tipo" id="tipo" class="form-control">
                                  <option value="a">Activo</option>
                                  <option value="c">Cadete</option>
                                  <option value="g">Grupo Familiar</option>
                                </select>

                                @if ($errors->first('tipo'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('tipo') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="montoMensual" class="col-md-4 col-form-label text-md-right">{{ __('Monto Mensual *') }}</label>

                            <div class="col-md-6">
                              <input type="number" name="montoMensual" id="montoMensual" class="form-control" min="0" value="{{ old('montoMensual') }}" placeholder="Ingresar monto" required>

                                @if ($errors->first('montoMensual'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('montoMensual') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="montoInteresGrupoFamiliar" class="col-md-4 col-form-label text-md-right">{{ __('Monto Interés Grupo Familiar') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="montoInteresGrupoFamiliar" id="montoInteresGrupoFamiliar" class="form-control" value="{{ old('montoInteresGrupoFamiliar') }}" min="0" placeholder="Ingresar monto de interés por integrante" disabled>

                                @if ($errors->first('montoInteresGrupoFamiliar'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('montoInteresGrupoFamiliar') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantidadIntegrantes" class="col-md-4 col-form-label text-md-right">{{ __('Aplicado después de cuantos integrantes') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantidadIntegrantes" id="cantidadIntegrantes" class="form-control" value="{{ old('cantidadIntegrantes') }}" min="0" placeholder="N° de integrantes" disabled>

                                @if ($errors->first('cantidadIntegrantes'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('cantidadIntegrantes') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="montoInteresMensual" class="col-md-4 col-form-label text-md-right">{{ __('Monto Interés Mensual *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="montoInteresMensual" id="montoInteresMensual" class="form-control" value="{{ old('montoInteresMensual') }}" min="0" placeholder="Ingresar monto de interés por atraso" required>

                                @if ($errors->first('montoInteresMensual'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('montoInteresMensual') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantidadMeses" class="col-md-4 col-form-label text-md-right">{{ __('Aplicado después de cuantos meses *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantidadMeses" id="cantidadMeses" class="form-control" value="{{ old('cantidadMeses') }}" min="0" placeholder="N° de meses" required>

                                @if ($errors->first('cantidadMeses'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('cantidadMeses') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
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

<!-- Script para filtrar habilitar los input montoInteresGrupoFamiliar y cantidadIntegrantes -->
<script src="{{ asset('js/filtro-agregar-monto-cuota.js') }}"></script>

@stop
