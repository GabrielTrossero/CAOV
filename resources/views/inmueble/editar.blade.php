@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Modificar Inmueble') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/inmueble/edit') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $inmueble->id }}">

                        <div class="form-group row">
                            <label for="nombre" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') ?? $inmueble->nombre }}" maxlength="75" required>

                                <span class="text-danger">{{$errors->first('nombre')}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="descripcion" class="col-md-4 col-form-label text-md-right">{{ __('Descripción') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="descripcion" id="descripcion" class="form-control" value="{{ old('descripcion') ?? $inmueble->descripcion }}" maxlength="75">
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


@stop
