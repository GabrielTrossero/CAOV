@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Cobrar Cuota') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/cuota/pago') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="monto" class="col-md-4 col-form-label text-md-right">{{ __('Monto mensual') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="monto" id="monto" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cantMeses" class="col-md-4 col-form-label text-md-right">{{ __('Cantidad de meses') }}</label>

                            <div class="col-md-6">
                                <input type="number" name="cantMeses" id="cantMeses" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Cobrar') }}
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
