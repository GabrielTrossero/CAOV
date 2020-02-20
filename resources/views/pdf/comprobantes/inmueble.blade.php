@extends('pdf.master')

@section('title', 'Comprobante Inmueble')

@section('content')
    <h1>Comprobante de Pago de Alquiler de Inmueble ({{ $recibo->inmueble->nombre }})</h1>
    <hr>
    <h2>Fecha de Solicitud: {{ date("d/m/Y", strtotime($recibo->fechaSolicitud)) }}</h2>
    <ul>
        <li>APELLIDO Y NOMBRE: {{ $recibo->persona->apellido . ", " . $recibo->persona->nombres }}</li>
        <br>
        <li>DIRECCION: {{ $recibo->persona->domicilio }}</li>
        <br>
        <li>DNI: {{ $recibo->persona->DNI }}</li>
        <br>
        @if ($recibo->persona->socio)
            <li>NUMERO DE SOCIO: {{ $recibo->persona->socio->numSocio }}</li>
        @else
            <li>NUMERO DE SOCIO: {{ "-" }}</li>
        @endif
        <br>
        <li>FECHA Y HORA DE INICIO DEL EVENTO: {{ date("d/m/Y H:i:s", strtotime($recibo->fechaHoraInicio)) }}</li>
        <br>
        <li>FECHA Y HORA DE FINALIZACION DEL EVENTO: {{ date("d/m/Y H:i:s", strtotime($recibo->fechaHoraFin)) }}</li>
        <br>
        <li>TIPO DE EVENTO A REALIZAR: {{ $recibo->tipoEvento }}</li>
        <br>
        <li>CANTIDAD ESTIMADA DE ASISTENTES: {{ $recibo->cantAsistentes . " (MAXIMO 100)"}}</li>
        <br>
        @if ($recibo->tieneServicioLimpieza)
            <li>SERVICIO DE LIMPIEZA: Si</li>
        @else
            <li>SERVICIO DE LIMPIEZA: No</li>
        @endif
        <br>
        @if ($recibo->tieneMusica)
            <li>EL ACONTECIMIENTO SE ACOMPAÑA CON MUSICA (ADJUNTAR SADAIC Y ADICAPIF): Si</li>
        @else
            <li>EL ACONTECIMIENTO SE ACOMPAÑA CON MUSICA (ADJUNTAR SADAIC Y ADICAPIF): No</li>  
        @endif
        <br>
        <li>COSTO DE ALQUILER DEL SALON: ${{ $recibo->costoTotal }}</li>
        <br>
        @if ($recibo->tieneReglamento)
            <li>SE ADJUNTO REGLAMENTO DEL SALON: Si</li>
        @else
            <li>SE ADJUNTO REGLAMENTO DEL SALON: No</li>
        @endif
    </ul>
    <hr>
    <br>
    <br>
    <br>
    <div id="firma" align="right">Firma y aclaración</div>
    <br>
    <br>
    @if (isset($recibo->numRecibo))
        <h2>NUMERO DE RECIBO: {{ $recibo->numRecibo }}</h2>
    @else
        <h2>NUMERO DE RECIBO: {{ "-" }}</h2>
    @endif
@endsection