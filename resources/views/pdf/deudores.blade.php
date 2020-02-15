@extends('pdf.master')

@section('title', 'Socios Deudores')

@section('content')
    <h1>Socios deudores</h1>
    <table  align="center">
        <thead>
            <tr>
                <th>DNI</th>
                <th>Numero de Socio</th>
                <th>Apellido</th>
                <th>Nombres</th>
                <th>Cuotas que adeuda</th>
            </tr>
        </thead>
        <tbody>
            
           @foreach ($cuotasNoPagadas as $cuotaNoPagada)
              <tr>
                <td>{{ $cuotaNoPagada->DNI }}</td>
                <td>{{ $cuotaNoPagada->numSocio }}</td>
                <td>{{ $cuotaNoPagada->apellido }}</td>
                <td>{{ $cuotaNoPagada->nombres }}</td>
                <td>{{ $cuotaNoPagada->count }}</td>
              </tr>
           @endforeach
            
        </tbody>
    </table>
@endsection