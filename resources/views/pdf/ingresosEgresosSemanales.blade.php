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
              <th>Ingresos</th>
              <th>Egresos</th>
              <th>Balance</th>
            </tr>
          </thead>
          <tbody>
          @foreach ($montos->ingresos as $semana => $valor)
              <tr>
                <td>{{ $semana }}</td>

                @if ($montos->ingresos[$semana] == 0)
                    <td> - </td>
                @else 
                    <td>{{ '$'.$montos->ingresos[$semana] }}</td>
                @endif

                @if ($montos->egresos[$semana] == 0)
                    <td> - </td>
                @else 
                    <td>{{ '$'.$montos->egresos[$semana] }}</td>
                @endif

                <td>{{ '$'. ($montos->ingresos[$semana] - $montos->egresos[$semana]) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
@endsection
