@extends('pdf.master')

@section('title', 'Comprobante Cuota')

@section('content')
    <h1>Comprobante de Pago de Cuota</h1>
    <hr>
    <ul>
        <li>Numero de Socio: {{ $data['numSocio'] }}</li>
        <br>
        <li>Apellido y Nombres: {{ $data['apellido_nombres'] }}</li>
        <br>
        <li>Mes/Año: {{ date("m/Y", strtotime($data['fechaMesAnio'])) }}</li>
        <br>
        <li>Fecha de Pago: {{ date("d/m/Y", strtotime($data['fechaPago'])) }}</li>
        <br>
        <li>Monto Base: ${{ $data['montoMensual'] }}</li>
        <br>
        <li>Interés: ${{ $data['interesPorIntegrantes'] + $data['interesMesesAtrasados'] }}</li>
        <br>
        <li>Monto Total: ${{ $data['montoTotal'] }}</li>
    </ul>
@endsection