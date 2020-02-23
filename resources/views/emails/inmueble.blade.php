@extends('pdf.master')

@section('title', 'Comprobante Inmueble')

@section('content')
    <h1>Comprobante de Pago de Alquiler de Inmueble ({{ $data['inmuebleNombre'] }})</h1>
    <hr>
    <h2>Fecha de Solicitud: {{ date("d/m/Y", strtotime($data['fechaSolicitud'])) }}</h2>
    <ul>
        <li>APELLIDO Y NOMBRE: {{ $data['apellido_nombres'] }}</li>
        <br>
        <li>DIRECCION: {{ $data['domicilio'] }}</li>
        <br>
        <li>DNI: {{ $data['DNI'] }}</li>
        <br>
        @if ($data['numSocio'])
            <li>NUMERO DE SOCIO: {{ $data['numSocio'] }}</li>
        @else
            <li>NUMERO DE SOCIO: {{ "-" }}</li>
        @endif
        <br>
        <li>FECHA Y HORA DE INICIO DEL EVENTO: {{ date("d/m/Y H:i:s", strtotime($data['fechaHoraInicio'])) }}</li>
        <br>
        <li>FECHA Y HORA DE FINALIZACION DEL EVENTO: {{ date("d/m/Y H:i:s", strtotime($data['fechaHoraFin'])) }}</li>
        <br>
        <li>TIPO DE EVENTO A REALIZAR: {{ $data['tipoEvento'] }}</li>
        <br>
        <li>CANTIDAD ESTIMADA DE ASISTENTES: {{ $data['cantAsistentes'] . " (MAXIMO 100)"}}</li>
        <br>
        @if ($data['tieneServicioLimpieza'])
            <li>SERVICIO DE LIMPIEZA: Si</li>
        @else
            <li>SERVICIO DE LIMPIEZA: No</li>
        @endif
        <br>
        @if ($data['tieneMusica'])
            <li>EL ACONTECIMIENTO SE ACOMPAÑA CON MUSICA (ADJUNTAR SADAIC Y ADICAPIF): Si</li>
        @else
            <li>EL ACONTECIMIENTO SE ACOMPAÑA CON MUSICA (ADJUNTAR SADAIC Y ADICAPIF): No</li>  
        @endif
        <br>
        <li>COSTO DE ALQUILER DEL SALON: ${{ $data['costoTotal'] }}</li>
        <br>
        @if ($data['tieneReglamento'])
            <li>SE ADJUNTO REGLAMENTO DEL SALON: Si</li>
        @else
            <li>SE ADJUNTO REGLAMENTO DEL SALON: No</li>
        @endif
    </ul>
    <hr>
    <br>
    <br>
    @if (isset($data['numRecibo']))
        <h2>NUMERO DE RECIBO: {{ $data['numRecibo'] }}</h2>
    @else
        <h2>NUMERO DE RECIBO: {{ "-" }}</h2>
    @endif
@endsection