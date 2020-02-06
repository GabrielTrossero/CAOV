@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Generar Cuota') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/cuota/createCuota') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $socio->id }}">

                        <!--para mostrar las distintas alertas-->
                        <div class="form-group row">
                            <label class="col-md-1 col-form-label text-md-right"></label>
                            <div class="col-md-10">

                              @if($socio->ultimaCuota == null)
                                  <div class="alert alert-warning">
                                      {{ 'ACLARACIÓN: El socio actual no tiene cuotas generadas anteriormente, por lo que la cuota actual se generará para el mes corriente.' }}
                                  </div>
                              @endif
                              @if (\Session::has('validarPagada'))
                                  <div class="alert alert-danger">
                                    {!! \Session::get('validarPagada') !!}
                                  </div>
                              @elseif (\Session::has('validarFechaPago'))
                                  <div class="alert alert-danger">
                                    {!! \Session::get('validarFechaPago') !!}
                                  </div>
                              @endif
                            </div>
                        </div>

                        <div class="form-group row">

                            <label for="DNI" class="col-md-4 col-form-label text-md-right">{{ __('DNI') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="DNI" id="DNI" class="form-control" value="{{$socio->persona->DNI}}" disabled>

                                <span class="text-danger">{{$errors->first('DNI')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="numSocio" class="col-md-4 col-form-label text-md-right">{{ __('N° Socio') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="DNI" id="DNI" class="form-control" value="{{$socio->numSocio}}" disabled>

                                <span class="text-danger">{{$errors->first('numSocio')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="mes" class="col-md-4 col-form-label text-md-right">{{ __('Mes Correspondiente') }}</label>

                            <!--en caso de que sea un socio nuevo y no tenga cuotas generadas, la genero en el mes actual-->
                            @if($socio->ultimaCuota == null)
                              <div class="col-md-6">
                                  <input type="text" name="mes" id="mes" class="form-control" value="{{ date("m/Y", strtotime($socio->mesActual)) }}" disabled>

                                  <span class="text-danger">{{$errors->first('mes')}}</span>
                              </div>
                            @else
                              <div class="col-md-6">
                                  <input type="text" name="mes" id="mes" class="form-control" value="{{ date("m/Y", strtotime($socio->ultimaCuota->fechaMesAnio."+ 1 month")) }}" disabled>

                                  <span class="text-danger">{{$errors->first('mes')}}</span>
                              </div>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="categoria" class="col-md-4 col-form-label text-md-right">{{ __('Categoría') }}</label>

                            <div class="col-md-6">
                                @if ($socio->idGrupoFamiliar)
                                    <input type="text" name="categoria" id="categoria" class="form-control" value="Grupo Familiar" disabled>
                                @elseif ($socio->edad < 18)
                                    <input type="text" name="categoria" id="categoria" class="form-control" value="Cadete" disabled>
                                @else
                                    <input type="text" name="categoria" id="categoria" class="form-control" value="Activo" disabled>
                                @endif

                                <span class="text-danger">{{$errors->first('categoria')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="montoMensual" class="col-md-4 col-form-label text-md-right">{{ __('Monto Mensual') }}</label>

                            <div class="col-md-6">
                              @if ($socio->idGrupoFamiliar)
                                  <input type="text" name="montoMensual" id="montoMensual" class="form-control" value="{{$socio->montoGrupoFamiliar}}" disabled>
                              @elseif ($socio->edad < 18)
                                  <input type="text" name="montoMensual" id="montoMensual" class="form-control" value="{{$socio->montoCadete}}" disabled>
                              @else
                                  <input type="text" name="montoMensual" id="montoMensual" class="form-control" value="{{$socio->montoActivo}}" disabled>
                              @endif

                              <span class="text-danger">{{$errors->first('montoMensual')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantidadIntegrantes" class="col-md-4 col-form-label text-md-right">{{ __('Cantidad Integrantes del Grupo Familiar') }}</label>

                            <div class="col-md-6">
                                @if ($socio->idGrupoFamiliar)
                                  <input type="text" name="cantidadIntegrantes" id="cantidadIntegrantes" class="form-control" maxlength="75" value="{{ $socio->cantidadIntegrantes ." integrante/s" }}" disabled>
                                @else
                                  <input type="text" name="cantidadIntegrantes" id="interesGrupoFamiliar" class="form-control" maxlength="75" value="{{ '-' }}" disabled>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="interesGrupoFamiliar" class="col-md-4 col-form-label text-md-right">{{ __('Interés por Integrantes de Grupo Familiar') }}</label>

                            <div class="col-md-6">
                                @if ($socio->idGrupoFamiliar)
                                  <!--si la cantidad de integrantes actual del grupo es mayor que la cantidad de integrantes del montoCuota más acutal-->
                                  @if ($socio->cantidadIntegrantes > $socio->montoCuotaCantidadIntegrantes)
                                    <input type="text" name="interesGrupoFamiliar" id="interesGrupoFamiliar" class="form-control" maxlength="75" value="{{ '$'. ($socio->montoCuotaInteresGrupoFamiliar * ($socio->cantidadIntegrantes - $socio->montoCuotaCantidadIntegrantes)) ." (". ($socio->cantidadIntegrantes - $socio->montoCuotaCantidadIntegrantes) ." integrante/s cobrado/s)" }}" disabled>
                                  @else
                                    <input type="text" name="interesGrupoFamiliar" id="interesGrupoFamiliar" class="form-control" maxlength="75" value="{{ '$0' }}" disabled>
                                  @endif
                                @else
                                  <input type="text" name="interesGrupoFamiliar" id="interesGrupoFamiliar" class="form-control" maxlength="75" value="{{ 'No pertenece a un grupo familiar' }}" disabled>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="montoTotal" class="col-md-4 col-form-label text-md-right">{{ __('Monto Total') }}</label>

                            <div class="col-md-6">
                              @if ($socio->idGrupoFamiliar)
                                  <input type="text" name="montoTotal" id="montoTotal" class="form-control" value="{{ '$'. ($socio->montoGrupoFamiliar + $socio->montoCuotaInteresGrupoFamiliar * ($socio->cantidadIntegrantes - $socio->montoCuotaCantidadIntegrantes)) }}" disabled>
                              @elseif ($socio->edad < 18)
                                  <input type="text" name="montoTotal" id="montoTotal" class="form-control" value="{{ '$'. $socio->montoCadete }}" disabled>
                              @else
                                  <input type="text" name="montoTotal" id="montoTotal" class="form-control" value="{{ '$'. $socio->montoActivo }}" disabled>
                              @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="estado" class="col-md-4 col-form-label text-md-right">{{ __('Estado') }}</label>

                            <div class="col-md-6">
                              <select name="estado" id="estado" class="form-control">
                                <option value="pagada"> Pagada </option>
                                <option value="inhabilitada"> Inhabilitada </option>
                              </select>

                              <span class="text-danger">{{$errors->first('estado')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaPago" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Pago') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaPago" id="fechaPago" class="form-control" value="{{ old('fechaPago') }}" required>

                                <span class="text-danger">{{$errors->first('fechaPago')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="medioPago" class="col-md-4 col-form-label text-md-right">{{ __('Medio de Pago') }}</label>

                            <div class="col-md-6">
                                <select name="medioPago" id="medioPago" class="form-control">
                                  <option value="1">Efectivo</option>
                                </select>

                                <span class="text-danger">{{$errors->first('medioPago')}}</span>
                            </div>
                        </div

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

<script src="{!! asset('js/agregarCuota.js') !!}"></script> <!--conexion a js-->

@stop
