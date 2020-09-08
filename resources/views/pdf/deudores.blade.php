@extends('pdf.master')

@section('title', 'Socios Deudores')

@section('content')
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <br>
    <h4 align="center">Estadísticas de Socios Deudores</h4>
    <div class="tam_letra_x-small">
          <div align="center">
              <div class="container-img">
                <h4>Socios por cantidad de Cuotas que adeudan</h4> 
                <br>
                <img class="grafico-informe" id="socios-por-cantidad-cuotas-adeudadas" src="https://quickchart.io/chart?c={{ $tortaCantidadCuotasAdeudadas }}" alt="Socios por cantidad de Cuotas que adeudan">
              </div>
              <div class="page-break"></div>
              <div class="container-img">
                <h4>Monto Total ($) adeudado por Categoría</h4>
                <br>
                <img class="grafico-informe" id="monto-adeudado-por-categoria" src="https://quickchart.io/chart?c={{ $tortaMontoTotalAdeudadoPorCategoria }}" alt="Monto Total adeudado por Categoría">
              </div>
              <div class="page-break"></div>
          </div>
    </div>
    <h4 align="center">Socios deudores</h4>
    <div class="tam_letra_x-small">
      <table class="table table-striped">
          <tr>
            <th>N° de Socio</th>
            <th>DNI</th>
            <th>Apellido</th>
            <th>Nombres</th>
            <th>Cuotas que adeuda</th>
            <th>Monto que adeuda</th>
          </tr>
        </thead>
        <tbody>

        @foreach ($socios as $socio)
          <tr>
            <td>{{ $socio->numSocio }}</td>
            <td>{{ $socio->persona->DNI }}</td>
            <td>{{ $socio->persona->apellido }}</td>
            <td>{{ $socio->persona->nombres }}</td>
            <td>{{ $socio->cantCuotas }}</td>
            <td>{{ '$'.$socio->montoDeuda }}</td>
          </tr>
        @endforeach

        </tbody>
      </table>
    </div>


@endsection
