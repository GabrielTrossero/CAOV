@extends('pdf.master')

@section('title', 'Socios Deudores')

@section('content')
    <h1>Socios deudores</h1>
    <table>
        <thead>
            <tr>
                <th>DNI</th>
                <th>Numero de Socio</th>
                <th>Apellido</th>
                <th>Nombres</th>
                <th>Deuda</th>
                <th>Más Información</th>
            </tr>
        </thead>
        <tbody>
            
           <!-- Iteraciones sobre los socios deudores, con link a info individual de cada uno -->
            
        </tbody>
    </table>
@endsection