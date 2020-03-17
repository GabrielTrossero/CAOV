@extends('pdf.master')

@section('title', 'Ingresos y Egresos Mensuales')

@section('content')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <br>
    <h4 align="center">Ingresos y Egresos Mensuales Generales</h4>
    <div class="tam_letra_x-small">
      <table class="table table-striped">
        <thead>
            <tr>
              <th>Fecha (Año - Mes)</th>
              <th>Balance</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($totales as $mes => $valor)
            <tr>
              <td>{{ $mes }}</td>
              <td>{{ '$'.$valor["total"] }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
@endsection
