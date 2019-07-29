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
                            <label for="numSocio" class="col-md-4 col-form-label text-md-right">{{ __('Numero de Socio') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="numSocio" id="numSocio" class="form-control" value="{{ $socio->numSocio }}" required>

                                <span class="text-danger">{{$errors->first('numSocio')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaNac" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Nacimiento') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaNac" id="FechaNac" class="form-control" value="{{ $socio->fechaNac }}" required>

                                <span class="text-danger">{{$errors->first('fechaNac')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="oficio" class="col-md-4 col-form-label text-md-right">{{ __('Oficio') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="oficio" id="oficio" class="form-control" value="{{ $socio->oficio }}" maxlength="80">

                                <span class="text-danger">{{$errors->first('oficio')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="vitalicio" class="col-md-4 col-form-label text-md-right">{{ __('Vitalicio') }}</label>

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

                                <span class="text-danger">{{$errors->first('vitalicio')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="DNI" class="col-md-4 col-form-label text-md-right">{{ __('DNI del Persona') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="DNI" id="DNI" class="form-control" value="{{ $socio->persona->DNI }}" min="0" required>

                                <span class="text-danger">{{$errors->first('DNI')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="idGrupoFamiliar" class="col-md-4 col-form-label text-md-right">{{ __('Grupo Familiar') }}</label>

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

                              <span class="text-danger">{{$errors->first('idGrupoFamiliar')}}</span>
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

                                @if ($errors->has('idDeporte'))
                                  <span class="text-danger">Ingrese un Deporte válido</span>
                                @endif
                              </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="activo" class="col-md-4 col-form-label text-md-right">{{ __('Activo') }}</label>

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

                                <span class="text-danger">{{$errors->first('activo')}}</span>
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
