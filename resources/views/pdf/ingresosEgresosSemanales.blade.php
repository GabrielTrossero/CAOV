@extends('pdf.master')

@section('title', 'Ingresos y Egresos Semanales')

@section('content')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <br>
    <h4 align="center">Ingresos y Egresos Semanales Generales</h4>
    <div class="tam_letra_x-small">
      <table class="table table-striped">
        <thead>
            <tr>
              <th>Fecha (Año - Semana)</th>
              <th>Balance</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($totales as $semana => $valor)
            <tr>
              <td>{{ $semana }}</td>
              <td>{{ '$'.$valor["total"] }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
@endsection
