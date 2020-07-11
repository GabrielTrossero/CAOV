@extends('layouts.master')

@section('content')


<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Agregar Grupo Familiar') }}</div>

                <div class="card-body">
                  @if ($integrantesEliminados > 0)
                  <div class="alert alert-warning">
                    {{ 'Atención: se han eliminado '. $integrantesEliminados .' cadete/s de diferentes grupos por cumplir 18 años y pasar a ser activo/s.' }}
                  </div>
                  @endif
                  @if ($gruposEliminados > 0)
                    <div class="alert alert-warning">
                      {{ 'Atención: se han eliminado '. $gruposEliminados .' grupo/s por tener un solo integrante.' }}
                    </div>
                  @endif
                    <form method="POST" action="{{ url('/grupofamiliar/create') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="titular" class="col-md-4 col-form-label text-md-right">{{ __('Titular *') }}</label>

                            <div class="col-md-6">
                                <select name="titular" id="titular" class="form-control" required>
                                    @foreach ($sociosMayores as $socio)
                                      <option value="{{ $socio->id }}">{{ $socio->persona->DNI." - ".$socio->persona->nombres." ".$socio->persona->apellido }}</option>
                                    @endforeach
                                </select>
                                @if (\Session::has('errorIguales'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('errorIguales') !!}
                                  </div>
                                @endif
                                @if (\Session::has('errorMenoresEdad'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('errorMenoresEdad') !!}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pareja" class="col-md-4 col-form-label text-md-right">{{ __('Pareja') }}</label>

                            <div class="col-md-6">
                                <select name="pareja" id="pareja" class="form-control">
                                  <option value="0">No posee Pareja</option>
                                  @foreach ($sociosMayores as $socio)
                                    <option value="{{ $socio->id }}">{{ $socio->persona->DNI." - ".$socio->persona->nombres." ".$socio->persona->apellido }}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="miembros" class="col-md-4 col-form-label text-md-right">{{ __('Miembros cadetes') }}</label>

                            <div class="col-md-6">
                                <select name="miembros[]" id="miembros" class="form-control" multiple>
                                  @foreach ($sociosMenores as $socio)
                                    <option value="{{ $socio->id }}">{{ $socio->persona->DNI." - ".$socio->persona->apellido .", ". $socio->persona->nombres }}</option>
                                  @endforeach
                                </select>
                                @if (\Session::has('errorEdadNuevoMiembro'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('errorEdadNuevoMiembro') !!}
                                  </div>
                                @endif
                            </div>
                        </div>
                        <br>
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

@stop
