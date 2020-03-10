@extends('pdf.master')

@section('title', 'Ingresos y Egresos Diarios')

@section('content')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <br>
    <h4 align="center">Ingresos y Egresos Diarios Generales</h4>
    <div class="tam_letra_x-small">
      <table class="table table-striped">
        <thead>
            <tr>
              <th>Fecha</th>
              <th>Balance</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($totales as $fecha => $total)
            <tr>
              <td>{{ date("d/m/Y", strtotime($fecha)) }}</td>
              <td>{{ '$'.$total }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
@endsection
