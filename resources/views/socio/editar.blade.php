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

                        <input type="hidden" name="id" value="{{ $socio->id }}">

                        <div class="form-group row">
                            <label for="numSocio" class="col-md-4 col-form-label text-md-right">{{ __('Número de Socio *') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="numSocio" id="numSocio" class="form-control" value="{{ old('numSocio') ?? $socio->numSocio }}" min="0" required>

                                @if ($errors->first('numSocio'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('numSocio') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaNac" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Nacimiento *') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaNac" id="FechaNac" class="form-control" value="{{ old('fechaNac') ?? $socio->fechaNac }}" required>

                                @if ($errors->first('fechaNac'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('fechaNac') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="oficio" class="col-md-4 col-form-label text-md-right">{{ __('Oficio') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="oficio" id="oficio" class="form-control" value="{{ old('oficio') ?? $socio->oficio }}" maxlength="80">

                                @if ($errors->first('oficio'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('oficio') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="vitalicio" class="col-md-4 col-form-label text-md-right">{{ __('Vitalicio *') }}</label>

                            <div class="col-md-6">
                                <select name="vitalicio" id="vitalicio" class="form-control">
                                  @if ($socio->vitalicio == 'n')
                                    <option value="n" selected>No</option>
                                    <option value="s">Si</option>
                                  @else
                                    <option value="n">No</option>
                                    <option value="s" selected>Si</option>
                                  @endif
                                </select>

                                @if ($errors->first('vitalicio'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('vitalicio') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="idPersona" class="col-md-4 col-form-label text-md-right">{{ __('Persona *') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" name="idPersona" id="idPersona">
                                  <option disabled>Seleccionar Persona</option>
                                  <option value="{{$socio->idPersona}}" selected>{{ $socio->persona->DNI ." - ". $socio->persona->apellido .", ". $socio->persona->nombres }}</option>

                                  @foreach ($personas as $persona)
                                    <option value="{{$persona->id}}">{{ $persona->DNI .' - '. $persona->apellido .', '. $persona->nombres }}</option>
                                  @endforeach
                                </select>

                                @if ($errors->first('idPersona'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('idPersona') }}
                                  </div>
                                @elseif (\Session::has('validarPersonaExiste'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('validarPersonaExiste') !!}
                                  </div>
                                @elseif (\Session::has('validarSocioNoExiste'))
                                  <div class="alert alert-danger errorForm">
                                    {!! \Session::get('validarSocioNoExiste') !!}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="idGrupoFamiliar" class="col-md-4 col-form-label text-md-right">{{ __('Grupo Familiar *(si no es Vitalicio)') }}</label>

                            <div class="col-md-6">
                              <select name="idGrupoFamiliar" id="idGrupoFamiliar" class="form-control">
                                <!-- esto es para seleccionar en el select la opcion que tiene actualmente -->
                                @if ($socio->grupoFamiliar)
                                  <option value="0">No posee grupo familiar</option>
                                  @foreach ($grupos as $grupo)
                                    @if ($socio->grupoFamiliar->id == $grupo->id)
                                      <option value="{{ $grupo->id }}" selected>Titular: {{$grupo->socioTitular->persona->apellido." ".$grupo->SocioTitular->persona->nombres." - ".$grupo->socioTitular->persona->DNI}}</option>
                                    @else
                                      <option value="{{ $grupo->id }}">Titular: {{$grupo->socioTitular->persona->apellido." ".$grupo->SocioTitular->persona->nombres." - ".$grupo->socioTitular->persona->DNI}}</option>
                                    @endif
                                  @endforeach
                                @else
                                  <option value="0" selected>No posee grupo familiar</option>
                                  @foreach ($grupos as $grupo)
                                    <option value="{{ $grupo->id }}">Titular: {{$grupo->socioTitular->persona->apellido." ".$grupo->SocioTitular->persona->nombres." - ".$grupo->socioTitular->persona->DNI}}</option>
                                  @endforeach
                                @endif
                              </select>

                              @if ($errors->first('idGrupoFamiliar'))
                                <div class="alert alert-danger errorForm">
                                  {{ $errors->first('idGrupoFamiliar') }}
                                </div>
                              @endif

                              @if (isset($socio->ifGrupoFamiliar) && ($socio->id == $socio->grupoFamiliar->titular))
                                <div class="alert alert-danger errorForm">
                                  {!! "El Socio a editar es titular de un Grupo Familiar. Para eliminarlo del mismo dirijase a la edición de su Grupo Familiar." !!}
                                </div>
                              @endif

                              @if (\Session::has('validarGrupoFamiliar'))
                                <div class="alert alert-danger errorForm">
                                  {!! \Session::get('validarGrupoFamiliar') !!}
                                </div>
                              @endif

                              @if (\Session::has('esSocioTitular'))
                                <div class="alert alert-danger errorForm">
                                  {!! \Session::get('esSocioTitular') !!}
                                </div>
                              @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="idDeporte" class="col-md-4 col-form-label text-md-right">{{ __('Deporte') }}</label>

                            <div class="col-md-6">
                              <div class="form-check">

                                <!-- recorro cada deporte -->
                                @foreach ($deportes as $deporte)
                                  <?php $bandera = 0 ?>
                                  <!-- recorro cada deporte que realiza -->
                                  @foreach ($socioDeporte as $socDep)
                                    <!-- si el deporte del primer foreach (BD) coincide con el
                                         que realiza lo marco en el checkbox y marco la bandera -->
                                    @if (($socDep->idSocio == $socio->id) && ($socDep->idDeporte == $deporte->id))
                                      <input class="form-check-input" type="checkbox" name="idDeporte[]" id="{{ $deporte->nombre }}" value="{{ $deporte->id }}" checked>
                                      <?php $bandera = 1; ?>
                                    @endif
                                  @endforeach
                                  <!-- en caso que el deporte de la BD no haya sido marcado
                                      (lo se por la bandera), entonces solo pongo el checkbox
                                      y no lo marco -->
                                  @if ($bandera == 0)
                                    <input class="form-check-input" type="checkbox" name="idDeporte[]" id="{{ $deporte->nombre }}" value="{{ $deporte->id }}">
                                  @endif

                                  <label class="form-check-label" for="{{ $deporte->nombre }}">
                                    {{ $deporte->nombre }}
                                  </label>
                                  <br>
                                @endforeach
                              </div>

                              @if (\Session::has('validarDeporte'))
                                <div class="alert alert-danger errorForm">
                                  {!! \Session::get('validarDeporte') !!}
                                </div>
                              @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="activo" class="col-md-4 col-form-label text-md-right">{{ __('Activo *') }}</label>

                            <div class="col-md-6">
                                <select name="activo" id="activo" class="form-control">
                                  @if ($socio->activo == 0)
                                    <option value="0" selected>No</option>
                                    <option value="1">Si</option>
                                  @else
                                    <option value="0">No</option>
                                    <option value="1" selected>Si</option>
                                  @endif
                                </select>

                                @if ($errors->first('activo'))
                                  <div class="alert alert-danger errorForm">
                                    {{ $errors->first('activo') }}
                                  </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-1 offset-md-4">
                              <a style="text-decoration:none" href="{{ url('/socio/show/'.$socio->id) }}">
                                <button type="button" class="btn btn-secondary">
                                  Volver
                                </button>
                              </a>
                            </div>

                            <div class="offset-md-1">
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

<script src="{!! asset('js/verifica-socio-vitalicio.js') !!}"></script>

@stop
