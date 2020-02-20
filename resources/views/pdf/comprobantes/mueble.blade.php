@extends('pdf.master')

@section('title', 'Comprobante de Mueble')

@section('content')
    <h1>Comprobante de Pago de Alquiler de Mueble ({{ $recibo->mueble->nombre }})</h1>
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
        <li>CANTIDAD ALQUILADA: {{ $recibo->cantidad }}</li>
        <br>
        <li>COSTO DE ALQUILER DEL SALON: ${{ $recibo->costoTotal }}</li>
        <br>
        <li>OBSERVACION: {{ $recibo->observacion }}</li>
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