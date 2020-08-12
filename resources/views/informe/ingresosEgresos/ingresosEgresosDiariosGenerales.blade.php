@extends('layouts.master')

@section('content')

<div class="cuadro">
    <div class="card">
      <div class="card-header">
        <label class="col-md-8 col-form-label"><b>Listado de Ingresos/Egresos Diarios Generales</b></label>
      </div>
      <div class="card-body border">
        <table id="idDataTable" class="table table-striped">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Balance</th>
              <th>Mas Información</th>
            </tr>
          </thead>
          <tbody>
  
            @foreach ($totales as $fecha => $total)
              <tr>
                <td>{{ date("d/m/Y", strtotime($fecha)) }}</td>
                <td>{{ '$'.$total }}</td>
                <td><a href="{{ url('/informe/ingresos_egresos_diarios/'.$fecha) }}"> <i class="fas fa-plus"></i></a> </td>
              </tr>
            @endforeach
          </tbody>
        </table>
  
        <div class="card-footer row">
          <div >
            <a style="text-decoration:none" onclick="history.back()">
              <button type="button" class="btn btn-secondary">
                Volver
              </button>
            </a>
          </div>

          <div class="col-md-10 text-md-center">
            <form action="{{url('/informe/pdf_ingresos_egresos_diarios')}}" method="get" style="display:inline">
              {{ csrf_field() }}
              <button type="submit" class="btn btn-outline-danger" style="display:inline">
                Generar PDF
              </button>
            </form>
          </div>
      </div>
    </div>
  </div>
 </div>

@stop