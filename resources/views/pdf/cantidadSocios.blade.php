@extends('pdf.master')

@section('title', 'Cantidad de Socios')

@section('content')
    <h3>Cantidad de Socios:</h3>
    <ul>
      El club cuenta con {{ $cantidadSocios }} socios.
    </ul>

@endsection
