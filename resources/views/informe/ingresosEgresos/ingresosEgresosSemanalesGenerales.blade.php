@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="card">
      <div class="card-header">
        <label class="col-md-8 col-form-label"><b>Listado de Ingresos/Egresos Semanales Generales</b></label>
      </div>
      <div class="card-body border">
        <table id="idDataTable" class="table table-striped">
          <thead>
            <tr>
              <th>Fecha (Año - Semana)</th>
              <th>Balance</th>
              <th>Mas Información</th>
            </tr>
          </thead>
          <tbody>
  
            @foreach ($totales as $semana => $valor)
              <tr>
                <td>{{ $semana }}</td>
                <td>{{ '$'.$valor["total"] }}</td>
                <td><a href="{{ url('/informe/ingresos_egresos_semanales/'.$valor["semana"].'/'.$valor["anio"]) }}"> <i class="fas fa-plus"></i></a> </td>
              </tr>
            @endforeach
          </tbody>
        </table>
    </div>
  
      <div class="card-footer">
        <form action="{{url('/informe/pdf_ingresos_egresos_semanales')}}" method="get" style="display:inline">
          {{ csrf_field() }}
          <button type="submit" class="btn btn-outline-danger" style="display:inline">
            Generar PDF
          </button>
        </form>
      </div>
  
  </div>
 </div>

@stop