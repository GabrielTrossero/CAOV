@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Modificar Cuota') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/cuota/edit') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $cuota->id }}">

                        <!--para mostrar las distintas alertas-->
                        <div class="form-group row">
                            <label class="col-md-1 col-form-label text-md-right"></label>
                            <div class="col-md-10">

                              @if (\Session::has('errorInhabilitada'))
                                  <div class="alert alert-danger">
                                    {!! \Session::get('errorInhabilitada') !!}
                                  </div>
                              @endif

                              @if (\Session::has('errorNoPagada'))
                                  <div class="alert alert-danger">
                                    {!! \Session::get('errorNoPagada') !!}
                                  </div>
                              @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="DNI" class="col-md-4 col-form-label text-md-right">{{ __('DNI Socio') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="DNI" id="DNI" class="form-control" value="{{ old('DNI') ?? $cuota->socio->persona->DNI }}" min="0" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaMesAnio" class="col-md-4 col-form-label text-md-right">{{ __('Mes y Año correspondiente') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="fechaMesAnio" id="fechaMesAnio" class="form-control" mesAnio="{{ $cuota->fechaMesAnio }}" value="{{ date('m/Y', strtotime($cuota->fechaMesAnio)) }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fechaPago" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de Pago *(si es Pagada)') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="fechaPago" id="fechaPago" class="form-control" valor="{{ $cuota->fechaPago }}" value="{{ $cuota->fechaPago }}"  mesAnio="{{$cuota->fechaMesAnio}}" cantMaxMeses="{{$cuota->montoCuota->cantidadMeses}}" interes="{{$cuota->montoCuota->montoInteresMensual}}" >

                                <span class="text-danger">{{$errors->first('fechaPago')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="medioPago" class="col-md-4 col-form-label text-md-right">{{ __('Medio de Pago *(si es Pagada)') }}</label>

                            <div class="col-md-6">
                                <select name="medioPago" id="medioPago" class="form-control" required>
                                  <option value="1">Efectivo</option>
                                </select>

                                <span class="text-danger">{{$errors->first('medioPago')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pagada" class="col-md-4 col-form-label text-md-right">{{ __('Pagada *') }}</label>

                            <div class="col-md-6">
                                <select name="pagada" id="pagada" class="form-control" required>
                                  <option value="s">Si</option>
                                  <option value="n">No</option>
                                </select>

                                <span class="text-danger">{{$errors->first('pagada')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoSocio" class="col-md-4 col-form-label text-md-right">{{ __('Tipo Socio Cobrado') }}</label>

                            <div class="col-md-6">
                                @if ($cuota->montoCuota->tipo == 'g')
                                  <input type="text" name="tipoSocio" id="tipoSocio" class="form-control" maxlength="75" value="{{ 'Grupo Familiar' }}" disabled>
                                @elseif ($cuota->montoCuota->tipo == 'c')
                                  <input type="text" name="tipoSocio" id="tipoSocio" class="form-control" maxlength="75" value="{{ 'Cadete' }}" disabled>
                                @elseif ($cuota->montoCuota->tipo == 'a')
                                  <input type="text" name="tipoSocio" id="tipoSocio" class="form-control" maxlength="75" value="{{ 'Activo' }}" disabled>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="montoMensual" class="col-md-4 col-form-label text-md-right">{{ __('Monto Base') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="montoMensual" id="montoMensual" class="form-control" maxlength="75" valor="{{ '$'. $cuota->montoCuota->montoMensual }}" value="{{ '$'. $cuota->montoCuota->montoMensual }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="mesesAtraso" class="col-md-4 col-form-label text-md-right">{{ __('Meses de Atraso') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="mesesAtraso" id="mesesAtraso" class="form-control" maxlength="75" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="interesAtraso" class="col-md-4 col-form-label text-md-right">{{ __('Interés por Atraso de Pago') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="interesAtraso" id="interesAtraso" class="form-control" maxlength="75"  disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantidadIntegrantes" class="col-md-4 col-form-label text-md-right">{{ __('Cantidad Integrantes del Grupo Familiar') }}</label>

                            <div class="col-md-6">
                                @if ($cuota->montoCuota->tipo == 'g')
                                  <input type="text" name="cantidadIntegrantes" id="cantidadIntegrantes" class="form-control" maxlength="75" valor="{{ $cuota->cantidadIntegrantes .' integrante/s' }}" value="{{ $cuota->cantidadIntegrantes .' integrante/s' }}" disabled>
                                @else
                                  <input type="text" name="cantidadIntegrantes" id="cantidadIntegrantes" class="form-control" maxlength="75" valor="{{ '-' }}" value="{{ '-' }}" disabled>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="interesGrupoFamiliar" class="col-md-4 col-form-label text-md-right">{{ __('Interés por Integrantes de Grupo Familiar') }}</label>

                            <div class="col-md-6">
                                @if ($cuota->montoCuota->tipo == 'g')
                                  <!--si la cantidad de integrantes del grupo (al momento de generar la cuota) es mayor que la cantidad de integrantes del montoCuota más acutal-->
                                  @if ($cuota->cantidadIntegrantes > $cuota->montoCuota->cantidadIntegrantes)
                                    <input type="text" name="interesGrupoFamiliar" id="interesGrupoFamiliar" class="form-control" maxlength="75" valor="{{ '$'. $cuota->montoInteresGrupoFamiliar ." (". ($cuota->cantidadIntegrantes - $cuota->montoCuota->cantidadIntegrantes) ." integrante/s cobrado/s)" }}" value="{{ '$'. $cuota->montoInteresGrupoFamiliar ." (". ($cuota->cantidadIntegrantes - $cuota->montoCuota->cantidadIntegrantes) ." integrante/s cobrado/s)" }}" disabled>
                                  @else
                                    <input type="text" name="interesGrupoFamiliar" id="interesGrupoFamiliar" class="form-control" maxlength="75" valor="{{ '$0' }}" value="{{ '$0' }}" disabled>
                                  @endif
                                @else
                                  <input type="text" name="interesGrupoFamiliar" id="interesGrupoFamiliar" class="form-control" maxlength="75" valor="{{ 'No pertenece a un grupo familiar' }}" value="{{ 'No pertenece a un grupo familiar' }}" disabled>
                                @endif
                            </div>
                        </div>

                        <!--lo uso para calcular el monto total en montos.js -->
                        <input type="hidden" id="valorAtraso">
                        <input type="hidden" id="valorGrupoFamiliar" value="{{ $cuota->montoInteresGrupoFamiliar }}">
                        <input type="hidden" id="valorMensual" value="{{ $cuota->montoCuota->montoMensual }}">

                        <div class="form-group row">
                            <label for="montoTot" class="col-md-4 col-form-label text-md-right">{{ __('Monto Total') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="montoTot" id="montoTot" class="form-control" disabled>
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

<script src="{!! asset('js/editarCuota.js') !!}"></script> <!--conexion a js que es utilizado en las vistas de Cuota-->

<script src="{!! asset('js/montos.js') !!}"></script> <!--conexion a js que es utilizado en las vistas de Cuota-->

@stop
