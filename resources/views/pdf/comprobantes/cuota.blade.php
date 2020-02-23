@extends('pdf.master')

@section('title', 'Comprobante Cuota')

@section('content')
    <h4>Comprobante de Pago de Cuota</h4>
    <hr>
    <ul>
      <li>Número de Socio: {{ $comprobante->socio->numSocio }}</li>
      <br>
      <li>Apellido y Nombres: {{ $comprobante->socio->persona->apellido . ", " . $comprobante->socio->persona->nombres }}</li>
      <br>
      <li>DNI: {{ $comprobante->socio->persona->DNI }}</li>
      <br>
      <li>Domicilio: {{ $comprobante->socio->persona->domicilio }}</li>
      <br>
      <li>Telefono: {{ $comprobante->socio->persona->telefono }}</li>
      <br>
      <li>Mes/Año: {{ date("m/Y", strtotime($comprobante->fechaMesAnio)) }}</li>
      <br>
      <li>Fecha de Pago: {{ date("d/m/Y", strtotime($comprobante->fechaPago)) }}</li>
      <br>
      <li>Monto Base: ${{ $comprobante->montoMensual }}</li>
      <br>
      <li>Intereses: ${{ $comprobante->interesPorIntegrantes + $comprobante->interesMesesAtrasados }}</li>
      <br>
      <li>Monto Total: ${{ $comprobante->montoTotal }}</li>
    </ul>


@endsection
