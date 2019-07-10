@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Modificar Mueble') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/mueble/edit') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="1">

                        <div class="form-group row">
                            <label for="nombre" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="nombre" id="nombre" class="form-control" value="Sillas">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="accionCantidad" class="col-md-4 col-form-label text-md-right">{{ __('Sumar o Restar cantidad del mueble') }}</label>

                            <div class="col-md-6">
                                <select name="accionCantidad" id="accionCantidad" class="form-control">
                                  <option value="0">Ninguna</option>
                                  <option value="1">Sumar cantidad</option>
                                  <option value="2">Restar cantidad</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantidad" class="col-md-4 col-form-label text-md-right">{{ __('Cantidad') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantidad" id="cantidad" class="form-control" value="100">
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