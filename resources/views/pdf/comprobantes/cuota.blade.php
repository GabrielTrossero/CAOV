@extends('pdf.master')

@section('title', 'Comprobante Cuota')

@section('content')
    <h1>Comprobante de Pago de Cuota</h1>
    <hr>
    <table align="center">
        <thead>
            <tr>
                <th>Numero de Socio</th>
                <th>Apellido y Nombres</th>
                <th>Mes/Año</th>
                <th>Fecha de Pago</th>
                <th>Monto Base</th>
                <th>Interés</th>
                <th>Monto Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $comprobante->socio->numSocio }}</td>
                <td>{{ $comprobante->socio->persona->apellido . ", " . $comprobante->socio->persona->nombres }}</td>
                <td>{{ date("m/Y", strtotime($comprobante->fechaMesAnio)) }}</td>
                <td>{{ date("d/m/Y", strtotime($comprobante->fechaPago)) }}</td>
                <td>${{ $comprobante->montoMensual }}</td>
                <td>${{ $comprobante->interesPorIntegrantes + $comprobante->interesMesesAtrasados }}</td>
                <td>${{ $comprobante->montoTotal }}</td>
            </tr>
        </tbody>
    </table>
@endsection