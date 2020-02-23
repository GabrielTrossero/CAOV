@extends('pdf.master')

@section('title', 'Comprobante de Mueble')

@section('content')
    <h1>Comprobante de Pago de Alquiler de Mueble ({{ $data['muebleNombre'] }})</h1>
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
        <li>CANTIDAD ALQUILADA: {{ $data['cantidad'] }}</li>
        <br>
        <li>COSTO DE ALQUILER DEL SALON: ${{ $data['costoTotal'] }}</li>
        <br>
        <li>OBSERVACION: {{ $data['observacion'] }}</li>
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