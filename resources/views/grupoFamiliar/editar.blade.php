@extends('layouts.master')

@section('content')


<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Agregar Grupo Familiar') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/grupofamiliar/edit') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $grupo->id }}">

                        <div class="form-group row">
                            <label for="titular" class="col-md-4 col-form-label text-md-right">{{ __('Titular') }}</label>

                            <div class="col-md-6">
                                <select name="titular" id="titular" class="form-control">
                                    <!--<option value="{{ $grupo->socioTitular->id }}">{{ $grupo->socioTitular->persona->DNI." - ".$grupo->socioTitular->persona->nombres." ".$grupo->socioTitular->persona->apellido }}</option>-->
                                    @foreach ($grupo->socios as $socio)
                                      @if ($grupo->titular == $socio->id)
                                        <option value="{{ $socio->id }}" selected>{{ $socio->persona->DNI." - ".$socio->persona->nombres." ".$socio->persona->apellido }}</option>
                                      @else
                                        <option value="{{ $socio->id }}">{{ $socio->persona->DNI." - ".$socio->persona->nombres." ".$socio->persona->apellido }}</option>
                                      @endif

                                    @endforeach
                                </select>
                                <span class="text-danger">{!! \Session::get('errorIguales') !!}</span>
                                <span class="text-danger">{!! \Session::get('errorMenoresEdad') !!}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pareja" class="col-md-4 col-form-label text-md-right">{{ __('Pareja') }}</label>

                            <div class="col-md-6">
                                <select name="pareja" id="pareja" class="form-control">
                                  <option value="0">No posee Pareja</option>
                                  @if ($grupo->socioPareja != NULL)
                                    <option value="{{ $grupo->socioPareja->id }}" selected>{{ $grupo->socioPareja->persona->DNI." - ".$grupo->socioPareja->persona->nombres." ".$grupo->socioPareja->persona->apellido }}</option>
                                  @endif
                                  <option value="{{ $grupo->titular }}">{{ $grupo->socioTitular->persona->DNI . " - " . $grupo->socioTitular->persona->nombres . " " . $grupo->socioTitular->persona->apellido }}</option>
                                  @foreach ($sociosPareja as $socio)
                                    <option value="{{ $socio->id }}">{{ $socio->persona->DNI." - ".$socio->persona->nombres." ".$socio->persona->apellido }}</option>
                                  @endforeach
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
                                <span class="text-danger">{!! \Session::get('errorEliminacionTitular') !!}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="miembro" class="col-md-4 col-form-label text-md-right">{{ __('Miembro') }}</label>

                            <div class="col-md-6">
                                <select name="miembro" id="miembro" class="form-control">

                                  <optgroup id="miembros-actuales" label="Miembros Actuales">
                                    @foreach ($grupo->socios as $socio)
                                      <option value="{{ $socio->id }}">{{ $socio->persona->DNI." - ".$socio->persona->apellido .", ". $socio->persona->nombres }}</option>
                                    @endforeach

                                  </optgroup>

                                  <optgroup id="socios-sin-grupo" label="Socios sin Grupo Familiar">
                                    @foreach ($sociosSinGrupo as $socioSinGrupo)
                                      <option value="{{ $socioSinGrupo->id }}">{{ $socioSinGrupo->persona->DNI." - ".$socioSinGrupo->persona->apellido .", ". $socioSinGrupo->persona->nombres }}</option>
                                    @endforeach
                                  </optgroup>

                                </select>
                                <span class="text-danger">{!! \Session::get('errorEdadNuevoMiembro') !!}</span>
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

<!-- Script para filtrar los select para agregar/quitar miembros del grupo -->
<script src="{{ asset('js/filtro-miembros-grupo-familiar.js') }}"></script>

@stop
