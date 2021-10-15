@extends('layouts.master')

@section('content')

<div class="cuadro">
  <div class="card">
    <div class="card-header row"> <!--row me permite mantener los dos label en la misma linea-->
      <label class="col-md-9 col-form-label" id="nomTablaHistorica"><b>Listado de Cuotas (Historicas)</b></label>
      <label class="col-md-9 col-form-label" id="nomTablaMes" style="display:none"><b>Listado de Cuotas (Mes en curso)</b></label>
      <label class="col-md-9 col-form-label" id="nomTablaImpaga" style="display:none"><b>Listado de Cuotas (Impagas)</b></label>
      <label class="col-md-9 col-form-label" id="nomTablaAtrasada" style="display:none"><b>Listado de Cuotas (Atrasadas)</b></label>
      <label class="col-md-9 col-form-label" id="nomTablaInhabilitada" style="display:none"><b>Listado de Cuotas (Inhabilitadas)</b></label>
      <label class="col-md-9 col-form-label" id="nomTablaPagada" style="display:none"><b>Listado de Cuotas (Pagadas)</b></label>
      <label class="col-md-9 col-form-label" id="nomTablaPagadaMes" style="display:none"><b>Listado de Cuotas (Pagadas este mes)</b></label>
      <label class="col-md-9 col-form-label" id="nomTablaPagadaFueraDeTermino" style="display:none"><b>Listado de Cuotas (Pagadas este mes)</b></label>
      <label class="col-md-3 col-form-label">
        <select class="form-control" id="filtroTabla">
          <option value="historica" selected title="Listado con todas las cuotas">Historicas</option>
          <option value="mes" title="Listado con las cuotas generadas este mes">Mes en curso</option>
          <option value="impaga" title="Listado con las cuotas no pagadas">Impagas</option>
          <option value="atrasada" title="Listado con las cuotas que no se pagaron y tienen interés de atraso">Atrasadas</option>
          <option value="inhabilitada" title="Listado con las cuotas inhabilitadas">Inhabilitadas</option>
          <option value="pagada" title="Listado con las cuotas pagadas">Pagadas</option>
          <option value="pagadaMes" title="Listado con las cuotas pagadas este mes">Pagadas este mes</option>
          <option value="pagadaFueraDeTermino" title="Listado con las cuotas pagadas fuera de término (con intereses)">Pagadas fuera de término</option>
        </select>
      </label>
    </div>

    <!--tabla Historica-->
    <div class="card-body border" id="tablaHistorica">
      @if ($integrantesEliminados > 0)
        <div class="alert alert-warning">
          {{ 'Atención: se han eliminado '. $integrantesEliminados .' cadete/s de diferentes grupos por cumplir años y pasar a ser activo/s.' }}
        </div>
      @endif
      @if ($gruposEliminados > 0)
        <div class="alert alert-warning">
          {{ 'Atención: se han eliminado '. $gruposEliminados .' grupo/s por tener un solo integrante.' }}
        </div>
      @endif
      <table id="idDataTable" class="table table-striped">
        <thead>
          <tr>
            <th>N° de Socio</th>
            <th>DNI Socio</th>
            <th>Mes/Año</th>
            <th>Estado Cuota</th>
            <th>Monto Base</th>
            <th>Monto Pagado</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cuotasHistoricas as $cuota)
            <tr>
              <td>{{ $cuota->socio->numSocio }}</td>
              <td>{{ $cuota->socio->persona->DNI ?? 'Socio eliminado' }}</td>
              <td>{{date("m/Y", strtotime($cuota->fechaMesAnio))}}</td> <!-- para mostrar solo mes/año -->

              @if ($cuota->inhabilitada)
                <td>{{ 'Inhabilitada' }}</td>
              @elseif ($cuota->fechaPago)
                <td>{{ 'Pagada' }}</td>
              @else
                <td>{{ 'No Pagada' }}</td>
              @endif

              <td class="montos">{{ '$ '.$cuota->montoCuota->montoMensual }}</td>

              @if ($cuota->fechaPago)
                <!--suma del monto base + intereses por atraso + intereses cantidad integrantes -->
                <td class="montos">{{ '$ '. ($cuota->montoCuota->montoMensual + $cuota->montoInteresAtraso + $cuota->montoInteresGrupoFamiliar) }}</td>
              @else
                <td class="montos">{{ '$ 0' }}</td>
              @endif

              <td><a href="{{ url('/cuota/show/'.$cuota->id) }}"> <i class="fas fa-plus"></i></a> </td>
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

    <!--tabla Mes-->
    <div class="card-body border" id="tablaMes" style="display:none">
      <table id="idDataTable2" class="table table-striped">
        <thead>
          <tr>
            <th>N° de Socio</th>
            <th>DNI Socio</th>
            <th>Mes/Año</th>
            <th>Estado Cuota</th>
            <th>Monto Base</th>
            <th>Monto Pagado</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cuotasMes as $cuota)
            <tr>
              <td>{{ $cuota->socio->numSocio }}</td>
              <td>{{ $cuota->socio->persona->DNI ?? 'Socio eliminado' }}</td>
              <td>{{date("m/Y", strtotime($cuota->fechaMesAnio))}}</td> <!-- para mostrar solo mes/año -->

              @if ($cuota->inhabilitada)
                <td>{{ 'Inhabilitada' }}</td>
              @elseif ($cuota->fechaPago)
                <td>{{ 'Pagada' }}</td>
              @else
                <td>{{ 'No Pagada' }}</td>
              @endif

              <td class="montos">{{ '$ '.$cuota->montoCuota->montoMensual }}</td>

              @if ($cuota->fechaPago)
                <!--suma del monto base + intereses por atraso + intereses cantidad integrantes -->
                <td class="montos">{{ '$ '. ($cuota->montoCuota->montoMensual + $cuota->montoInteresAtraso + $cuota->montoInteresGrupoFamiliar) }}</td>
              @else
                <td class="montos">{{ '$ 0' }}</td>
              @endif

              <td><a href="{{ url('/cuota/show/'.$cuota->id) }}"> <i class="fas fa-plus"></i></a> </td>
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

    <!--tabla Impagas-->
    <div class="card-body border" id="tablaImpaga" style="display:none">
      <table id="idDataTable3" class="table table-striped">
        <thead>
          <tr>
            <th>N° de Socio</th>
            <th>DNI Socio</th>
            <th>Mes/Año</th>
            <th>Estado Cuota</th>
            <th>Monto Base</th>
            <th>Monto Pagado</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cuotasImpagas as $cuota)
            <tr>
              <td>{{ $cuota->socio->numSocio }}</td>
              <td>{{ $cuota->socio->persona->DNI ?? 'Socio eliminado' }}</td>
              <td>{{date("m/Y", strtotime($cuota->fechaMesAnio))}}</td> <!-- para mostrar solo mes/año -->

              @if ($cuota->inhabilitada)
                <td>{{ 'Inhabilitada' }}</td>
              @elseif ($cuota->fechaPago)
                <td>{{ 'Pagada' }}</td>
              @else
                <td>{{ 'No Pagada' }}</td>
              @endif

              <td class="montos">{{ '$ '.$cuota->montoCuota->montoMensual }}</td>

              @if ($cuota->fechaPago)
                <!--suma del monto base + intereses por atraso + intereses cantidad integrantes -->
                <td class="montos">{{ '$ '. ($cuota->montoCuota->montoMensual + $cuota->montoInteresAtraso + $cuota->montoInteresGrupoFamiliar) }}</td>
              @else
                <td>{{ '$ 0' }}</td>
              @endif

              <td><a href="{{ url('/cuota/show/'.$cuota->id) }}"> <i class="fas fa-plus"></i></a> </td>
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

    <!--tabla Atrasada-->
    <div class="card-body border" id="tablaAtrasada" style="display:none">
      <table id="idDataTable4" class="table table-striped">
        <thead>
          <tr>
            <th>N° de Socio</th>
            <th>DNI Socio</th>
            <th>Mes/Año</th>
            <th>Estado Cuota</th>
            <th>Monto Base</th>
            <th>Monto Pagado</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cuotasAtrasadas as $cuota)
            <tr>
              <td>{{ $cuota->socio->numSocio }}</td>
              <td>{{ $cuota->socio->persona->DNI ?? 'Socio eliminado' }}</td>
              <td>{{date("m/Y", strtotime($cuota->fechaMesAnio))}}</td> <!-- para mostrar solo mes/año -->

              @if ($cuota->inhabilitada)
                <td>{{ 'Inhabilitada' }}</td>
              @elseif ($cuota->fechaPago)
                <td>{{ 'Pagada' }}</td>
              @else
                <td>{{ 'No Pagada' }}</td>
              @endif

              <td class="montos">{{ '$ '.$cuota->montoCuota->montoMensual }}</td>

              @if ($cuota->fechaPago)
                <!--suma del monto base + intereses por atraso + intereses cantidad integrantes -->
                <td class="montos">{{ '$ '. ($cuota->montoCuota->montoMensual + $cuota->montoInteresAtraso + $cuota->montoInteresGrupoFamiliar) }}</td>
              @else
                <td class="montos">{{ '$ 0' }}</td>
              @endif

              <td><a href="{{ url('/cuota/show/'.$cuota->id) }}"> <i class="fas fa-plus"></i></a> </td>
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

    <!--tabla Inhabilitada-->
    <div class="card-body border" id="tablaInhabilitada" style="display:none">
      <table id="idDataTable5" class="table table-striped">
        <thead>
          <tr>
            <th>N° de Socio</th>
            <th>DNI Socio</th>
            <th>Mes/Año</th>
            <th>Estado Cuota</th>
            <th>Monto Base</th>
            <th>Monto Pagado</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cuotasInhabilitadas as $cuota)
            <tr>
              <td>{{ $cuota->socio->numSocio }}</td>
              <td>{{ $cuota->socio->persona->DNI ?? 'Socio eliminado' }}</td>
              <td>{{date("m/Y", strtotime($cuota->fechaMesAnio))}}</td> <!-- para mostrar solo mes/año -->

              @if ($cuota->inhabilitada)
                <td>{{ 'Inhabilitada' }}</td>
              @elseif ($cuota->fechaPago)
                <td>{{ 'Pagada' }}</td>
              @else
                <td>{{ 'No Pagada' }}</td>
              @endif

              <td class="montos">{{ '$ '.$cuota->montoCuota->montoMensual }}</td>

              @if ($cuota->fechaPago)
                <!--suma del monto base + intereses por atraso + intereses cantidad integrantes -->
                <td class="montos">{{ '$ '. ($cuota->montoCuota->montoMensual + $cuota->montoInteresAtraso + $cuota->montoInteresGrupoFamiliar) }}</td>
              @else
                <td class="montos">{{ '$ 0' }}</td>
              @endif

              <td><a href="{{ url('/cuota/show/'.$cuota->id) }}"> <i class="fas fa-plus"></i></a> </td>
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

    <!--tabla Pagada-->
    <div class="card-body border" id="tablaPagada" style="display:none">
      <table id="idDataTable6" class="table table-striped">
        <thead>
          <tr>
            <th>N° de Socio</th>
            <th>DNI Socio</th>
            <th>Mes/Año</th>
            <th>Estado Cuota</th>
            <th>Monto Base</th>
            <th>Monto Pagado</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cuotasPagadas as $cuota)
            <tr>
              <td>{{ $cuota->socio->numSocio }}</td>
              <td>{{ $cuota->socio->persona->DNI ?? 'Socio eliminado' }}</td>
              <td>{{date("m/Y", strtotime($cuota->fechaMesAnio))}}</td> <!-- para mostrar solo mes/año -->

              @if ($cuota->inhabilitada)
                <td>{{ 'Inhabilitada' }}</td>
              @elseif ($cuota->fechaPago)
                <td>{{ 'Pagada' }}</td>
              @else
                <td>{{ 'No Pagada' }}</td>
              @endif

              <td class="montos">{{ '$ '.$cuota->montoCuota->montoMensual }}</td>

              @if ($cuota->fechaPago)
                <!--suma del monto base + intereses por atraso + intereses cantidad integrantes -->
                <td class="montos">{{ '$ '. ($cuota->montoCuota->montoMensual + $cuota->montoInteresAtraso + $cuota->montoInteresGrupoFamiliar) }}</td>
              @else
                <td class="montos">{{ '$ 0' }}</td>
              @endif

              <td><a href="{{ url('/cuota/show/'.$cuota->id) }}"> <i class="fas fa-plus"></i></a> </td>
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

    <!--tabla PagadaMes-->
    <div class="card-body border" id="tablaPagadaMes" style="display:none">
      <table id="idDataTable7" class="table table-striped">
        <thead>
          <tr>
            <th>N° de Socio</th>
            <th>DNI Socio</th>
            <th>Mes/Año</th>
            <th>Estado Cuota</th>
            <th>Monto Base</th>
            <th>Monto Pagado</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cuotasPagadasMes as $cuota)
            <tr>
              <td>{{ $cuota->socio->numSocio }}</td>
              <td>{{ $cuota->socio->persona->DNI ?? 'Socio eliminado' }}</td>
              <td>{{date("m/Y", strtotime($cuota->fechaMesAnio))}}</td> <!-- para mostrar solo mes/año -->

              @if ($cuota->inhabilitada)
                <td>{{ 'Inhabilitada' }}</td>
              @elseif ($cuota->fechaPago)
                <td>{{ 'Pagada' }}</td>
              @else
                <td>{{ 'No Pagada' }}</td>
              @endif

              <td class="montos">{{ '$ '.$cuota->montoCuota->montoMensual }}</td>

              @if ($cuota->fechaPago)
                <!--suma del monto base + intereses por atraso + intereses cantidad integrantes -->
                <td class="montos">{{ '$ '. ($cuota->montoCuota->montoMensual + $cuota->montoInteresAtraso + $cuota->montoInteresGrupoFamiliar) }}</td>
              @else
                <td class="montos">{{ '$ 0' }}</td>
              @endif

              <td><a href="{{ url('/cuota/show/'.$cuota->id) }}"> <i class="fas fa-plus"></i></a> </td>
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

    <!--tabla PagadaFueraDeTermino-->
    <div class="card-body border" id="tablaPagadaFueraDeTermino" style="display:none">
      <table id="idDataTable8" class="table table-striped">
        <thead>
          <tr>
            <th>N° de Socio</th>
            <th>DNI Socio</th>
            <th>Mes/Año</th>
            <th>Estado Cuota</th>
            <th>Monto Base</th>
            <th>Monto Pagado</th>
            <th>Más Información</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cuotasFueraDeTermino as $cuota)
            <tr>
              <td>{{ $cuota->socio->numSocio }}</td>
              <td>{{ $cuota->socio->persona->DNI ?? 'Socio eliminado' }}</td>
              <td>{{date("m/Y", strtotime($cuota->fechaMesAnio))}}</td> <!-- para mostrar solo mes/año -->

              @if ($cuota->inhabilitada)
                <td>{{ 'Inhabilitada' }}</td>
              @elseif ($cuota->fechaPago)
                <td>{{ 'Pagada' }}</td>
              @else
                <td>{{ 'No Pagada' }}</td>
              @endif

              <td class="montos">{{ '$ '.$cuota->montoCuota->montoMensual }}</td>

              @if ($cuota->fechaPago)
                <!--suma del monto base + intereses por atraso + intereses cantidad integrantes -->
                <td class="montos">{{ '$ '. ($cuota->montoCuota->montoMensual + $cuota->montoInteresAtraso + $cuota->montoInteresGrupoFamiliar) }}</td>
              @else
                <td class="montos">{{ '$ 0' }}</td>
              @endif

              <td><a href="{{ url('/cuota/show/'.$cuota->id) }}"> <i class="fas fa-plus"></i></a> </td>
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

<script src="{!! asset('js/filtrarTablas/listadoCuotas.js') !!}"></script> <!--conexion a js que es utilizada para filtar las tablas-->

@stop
