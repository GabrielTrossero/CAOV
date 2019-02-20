@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verifique su dirección de correo electrónico') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Se ha enviado un link de verificaión a su e-mail.') }}
                        </div>
                    @endif

                    {{ __('Antes de continuar, chequee su e-mail por el link de verificación.') }}
                    {{ __('Si no recibió el email') }}, <a href="{{ route('verification.resend') }}">{{ __('click aquí para solicitar otro') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
