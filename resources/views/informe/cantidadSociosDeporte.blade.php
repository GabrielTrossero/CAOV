@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Cantidad de Socios por Deporte</b></label>
    </div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <th>Deporte</th>
          <th>Cantidad de Socios</th>
        </tr>
        <tr>
          <td>Futbol</td>
          <td>120</td>
        </tr>
        <tr>
          <td>Basquet</td>
          <td>79</td>
        </tr>
      </table>
    </div>

    <div class="card-footer">
      <form action="{{url('/informe/cantidad_socios_deporte')}}" method="post" style="display:inline">
        {{ csrf_field() }}
        <button type="submit" class="btn btn-outline-danger" style="display:inline">
          Generar PDF
        </button>
      </form>
    </div>

  </div>
</div>

@stop
