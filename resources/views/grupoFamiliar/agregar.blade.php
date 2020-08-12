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

                                @if ($errors->first('titular'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('titular') }}
                                  </div>
                                @endif
                                @if (\Session::has('error'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('error') !!}
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

                                @if ($errors->first('pareja'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('pareja') }}
                                  </div>
                                @endif
                                @if (\Session::has('errorPareja'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('errorPareja') !!}
                                  </div>
                                @endif
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

                                @if (\Session::has('errorAdherente'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('errorAdherente') !!}
                                  </div>
                                @endif
                            </div>
                        </div>
                        <br>
                        <div class="form-group row mb-0">
                            <div class="col-md-1 offset-md-4">
                              <a style="text-decoration:none" onclick="history.back()">
                                <button type="button" class="btn btn-secondary">
                                  Volver
                                </button>
                              </a>
                            </div>

                            <div class="offset-md-1">
                                <button type="submit" id="guardar-grupo" class="btn icono-editar-disabled" title="El grupo debe tener al menos 2 integrantes" disabled>
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

<script src="{{ asset('js/comprobar-integrantes-crear-grupo.js') }}"></script>

@stop
