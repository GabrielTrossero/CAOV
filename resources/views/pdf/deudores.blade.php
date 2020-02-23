@extends('pdf.master')

@section('title', 'Socios Deudores')

@section('content')
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <br>
    <h4 align="center">Socios deudores</h4>
    <div class="tam_letra_x-small">
      <table class="table table-striped">
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
    </div>


@endsection
