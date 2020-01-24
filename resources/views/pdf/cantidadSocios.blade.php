@extends('pdf.master')

@section('title', 'Cantidad de Socios')

@section('content')
    <h1>Cantidad de Socios</h1>
    <hr>
    <h2>El club cuenta con {{ $cantidadSocios }} socios.</h2>
@endsection