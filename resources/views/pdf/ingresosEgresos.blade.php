@extends('pdf.master')

@section('title', 'Ingresos y Egresos')

@section('content')
    <h1>Ingresos y Egresos</h1>
    <table>
        <thead>
            <tr>
              <th>Tipo</th>
              <th>Numero de Recibo</th>
              <th>Descripcion</th>
              <th>Fecha</th>
              <th>Monto</th>
            </tr>
          </thead>
          <tbody>
              <!-- Iteracion sobre los ingresos y egresos del club -->
          </tbody>
    </table>
@endsection