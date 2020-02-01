@extends('pdf.master')

@section('title', 'Socio Deudor')

@section('content')
    <h1>Socio Deudor</h1>
    <h2>Nombre: </h2>
    <h2>DNI: </h2>
    <h2>Numero de Socio: </h2>
    <table>
        <thead>
            <tr>
                <th>Tipo de Deuda</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            
           <!-- Iteraciones sobre las deudas del socio -->
            
        </tbody>
    </table>
@endsection