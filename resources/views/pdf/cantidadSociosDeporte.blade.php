@extends('pdf.master')

@section('title', 'Cantidad de Socios por Deporte')

@section('content')
    <h1>Cantidad de socios por deporte</h1>
    <table>
        <thead>
            <tr>
                <th>Deporte</th>
                <th>Cantidad de socios</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($deportes as $deporte)
                <tr>
                    <td> {{ $deporte->nombre }} </td>
                    <td> {{ $deporte->cantidadSocios }} </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection