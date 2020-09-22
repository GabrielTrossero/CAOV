@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header row"> <!--row me permite mantener los dos label en la misma linea-->
      <label class="col-md-9 col-form-label" id="nomTablaActual"><b>Listado de Montos de Cuotas (Activas)</b></label>
      <label class="col-md-9 col-form-label" id="nomTablaHistorica" style="display:none"><b>Listado de Montos de Cuotas (Historicas)</b></label>
      <label class="col-md-3 col-form-label">
        <select class="form-control" id="filtroTabla">
          <option value="activa" selected>Activas</option>
          <option value="historica">Historicas</option>
        </select>
      </label>
    </div>

    <!--tabla actual-->
    <div class="card-body border" id="tablaActual">
      @if (\Session::has('montoCuotaTieneCuotas'))
        <div class="alert alert-danger">
          {!! \Session::get('montoCuotaTieneCuotas') !!}
        </div>
      @endif
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>Fecha de Creación</th>
            <th>Tipo</th>
            <th>Monto Mensual</th>
            <th>Interés Grupo Familiar</th>
            <th>Interés por retraso</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($montosActuales as $montoCuota)
            <tr>
              <td>{{ $montoCuota->fechaCreacion }}</td>

              @if ($montoCuota->tipo == 'a')
                <td>Activo</td>
              @elseif ($montoCuota->tipo == 'c')
                <td>Cadete</td>
              @elseif($montoCuota->tipo == 'g')
                  <td>Grupo Familiar</td>
              @endif

              <td class="montos">{{ '$ '.$montoCuota->montoMensual }}</td>

              @if ($montoCuota->tipo == 'g')
            <td class="mismalinea">{{ '$ '.$montoCuota->montoInteresGrupoFamiliar.' por mes,'}} <div> {{ ' a partir del integrante N° '.$montoCuota->cantidadIntegrantes }}</div> </td>
              @else
                <td>{{ '-' }}</td>
              @endif

              <td class="mismalinea">{{ '$ '.$montoCuota->montoInteresMensual.' por mes,'}} <div> {{'a partir del mes N° '.$montoCuota->cantidadMeses.' de atraso' }} </div> </td>
              
              <td>
                @if (sizeof($montoCuota->comprobantesDeCuotas) == 0)
                  <a class="icono-editar-anchor" href="{{ url('/cuota/editMontoCuota/'.$montoCuota->id) }}">
                    <i class="fas fa-edit icono-editar" title="Editar"></i>
                  </a>
                  <form action="{{url('/cuota/deleteMontoCuota')}}" method="post" style="display:inline" onsubmit="return confirm('¿Está seguro que desea eliminar el Monto Cuota?');">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $montoCuota->id }}">
                    <button class="icono-eliminar" type="submit">
                      <i class="fas fa-trash" style="color:red;" title="Eliminar"></i>
                    </button>
                  </form>
                @else
                  <a class="icono-editar-anchor-disabled">
                    <i class="fas fa-edit icono-editar-disabled" title="Acción no disponible"></i>
                  </a>
                  <form style="display:inline">
                    <button class="icono-eliminar-disabled" type="submit" disabled>
                      <i class="fas fa-trash" style="color: darkslategray;" title="Acción no disponible"></i>
                    </button>
                  </form>
                @endif
              </td>
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
      </div>
    </div>

    <!--tabla historica-->
    <div class="card-body border" id="tablaHistorica" style="display:none">
      <table id="idDataTable2" class="table table-striped">
        <thead>
          <tr>
            <th>Fecha de Creación</th>
            <th>Tipo</th>
            <th>Monto Mensual</th>
            <th>Interés Grupo Familiar</th>
            <th>Interés por retraso</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($montosHistoricos as $montoCuota)
            <tr>
              <td>{{ $montoCuota->fechaCreacion }}</td>

              @if ($montoCuota->tipo == 'a')
                <td>Activo</td>
              @elseif ($montoCuota->tipo == 'c')
                <td>Cadete</td>
              @elseif($montoCuota->tipo == 'g')
                  <td>Grupo Familiar</td>
              @endif

              <td>{{ '$'.$montoCuota->montoMensual }}</td>
              @if ($montoCuota->tipo == 'g')
                <td>{{ '$'.$montoCuota->montoInteresGrupoFamiliar.' por mes, a partir del integrante N° '.$montoCuota->cantidadIntegrantes }}</td>
              @else
                <td>{{ '-' }}</td>
              @endif

              <td>{{ '$'.$montoCuota->montoInteresMensual.' por mes, a partir del mes N° '.$montoCuota->cantidadMeses.' de atraso' }}</td>
              <td>
                @if (sizeof($montoCuota->comprobantesDeCuotas) == 0)
                  <a class="icono-editar-anchor" href="{{ url('/cuota/editMontoCuota/'.$montoCuota->id) }}">
                    <i class="fas fa-edit icono-editar" title="Editar"></i>
                  </a>
                  <form action="{{url('/cuota/deleteMontoCuota')}}" method="post" style="display:inline" onsubmit="return confirm('¿Está seguro que desea eliminar el Monto Cuota?');">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $montoCuota->id }}">
                    <button class="icono-eliminar" type="submit">
                      <i class="fas fa-trash" style="color:red;" title="Eliminar"></i>
                    </button>
                  </form>
                @else
                  <a class="icono-editar-anchor-disabled">
                    <i class="fas fa-edit icono-editar-disabled" title="Tiene cuotas asociadas"></i>
                  </a>
                  <form style="display:inline">
                    <button class="icono-eliminar-disabled" type="submit" disabled>
                      <i class="fas fa-trash" style="color: darkslategray;" title="Acción no disponible"></i>
                    </button>
                  </form>
                @endif
              </td>
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
      </div>
    </div>
  </div>
</div>


<script src="{!! asset('js/filtrarTablas/montoCuota.js') !!}"></script> <!--conexion a js que es utilizada para filtar las tablas-->

@stop
