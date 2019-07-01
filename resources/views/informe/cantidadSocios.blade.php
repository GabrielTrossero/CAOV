@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header">
      <label class="col-md-8 col-form-label"><b>Cantidad Total de Socios</b></label>
    </div>
    <div class="card-body border">
      <table class="table">
        <tr>
          <th>Cantidad de Socios</th>
        </tr>
        <tr>
          <td>1200</td>
        </tr>
      </table>
    </div>

    <div class="card-footer">
      <form action="{{url('/informe/cantidad_socios')}}" method="post" style="display:inline">
        {{ csrf_field() }}
        <button type="submit" class="btn btn-outline-danger" style="display:inline">
          Generar PDF
        </button>
      </form>
    </div>

  </div>
</div>

@stop
