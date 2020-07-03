@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Editar Monto para Cuotas') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/cuota/editMontoCuota') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $montoCuota->id }}">
                        <div class="form-group row">
                            <label for="tipo" class="col-md-4 col-form-label text-md-right">{{ __('Tipo') }}</label>

                            <div class="col-md-6">
                                <select name="tipo" id="tipo" class="form-control">
                                  @if ($montoCuota->tipo == "a")
                                    <option value="a" selected>Activo</option>
                                  @else
                                    <option value="a">Activo</option>
                                  @endif
                                  @if ($montoCuota->tipo == "c")
                                    <option value="c" selected>Cadete</option>
                                  @else
                                    <option value="c">Cadete</option>
                                  @endif
                                  @if ($montoCuota->tipo == "g")
                                    <option value="g" selected>Grupo Familiar</option>
                                  @else
                                    <option value="g">Grupo Familiar</option>
                                  @endif
                                </select>
                                <span class="text-danger">{{$errors->first('tipo')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="montoMensual" class="col-md-4 col-form-label text-md-right">{{ __('Monto Mensual') }}</label>

                            <div class="col-md-6">
                              <input type="number" name="montoMensual" id="montoMensual" class="form-control" min="0" value="{{ $montoCuota->montoMensual }}" placeholder="Ingresar monto" required>

                              <span class="text-danger">{{$errors->first('montoMensual')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="montoInteresGrupoFamiliar" class="col-md-4 col-form-label text-md-right">{{ __('Monto Interés Grupo Familiar') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="montoInteresGrupoFamiliar" id="montoInteresGrupoFamiliar" class="form-control" value="{{ $montoCuota->montoInteresGrupoFamiliar }}" min="0" placeholder="Ingresar monto de interés por integrante" disabled>

                                <span class="text-danger">{{$errors->first('montoInteresGrupoFamiliar')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantidadIntegrantes" class="col-md-4 col-form-label text-md-right">{{ __('Aplicado despúes de cuantos integrantes') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantidadIntegrantes" id="cantidadIntegrantes" class="form-control" value="{{ $montoCuota->cantidadIntegrantes }}" min="0" placeholder="N° de integrantes" disabled>

                                <span class="text-danger">{{$errors->first('cantidadIntegrantes')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="montoInteresMensual" class="col-md-4 col-form-label text-md-right">{{ __('Monto Interes Mensual') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="montoInteresMensual" id="montoInteresMensual" class="form-control" value="{{ $montoCuota->montoInteresMensual }}" min="0" placeholder="Ingresar monto de interes por atraso" required>

                                <span class="text-danger">{{$errors->first('montoInteresMensual')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantidadMeses" class="col-md-4 col-form-label text-md-right">{{ __('Aplicado despúes de cuantos meses') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantidadMeses" id="cantidadMeses" class="form-control" value="{{ $montoCuota->cantidadMeses }}" min="0" placeholder="N° de meses" required>

                                <span class="text-danger">{{$errors->first('cantidadMeses')}}</span>
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