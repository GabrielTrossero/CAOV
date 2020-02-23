@extends('pdf.master')

@section('title', 'Cantidad de Socios por Deporte')

@section('content')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <br>
    <h4 align="center">Cantidad de socios por deporte</h4>
    <div class="tam_letra_x-small">
      <table class="table table-striped">
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
    </div>
@endsection
