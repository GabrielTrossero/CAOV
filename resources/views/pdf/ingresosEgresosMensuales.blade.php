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
              <th>Ingresos</th>
              <th>Egresos</th>
              <th>Balance</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($montos->ingresos as $mes => $valor)
              <tr>
                <td>{{ $mes }}</td>

                @if ($montos->ingresos[$mes] == 0)
                    <td> - </td>
                @else 
                    <td>{{ '$'.$montos->ingresos[$mes] }}</td>
                @endif

                @if ($montos->egresos[$mes] == 0)
                    <td> - </td>
                @else 
                    <td>{{ '$'.$montos->egresos[$mes] }}</td>
                @endif

                <td>{{ '$'. ($montos->ingresos[$mes] - $montos->egresos[$mes]) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
@endsection
