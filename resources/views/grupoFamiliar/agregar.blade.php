@extends('layouts.master')

@section('content')

<div class="cuadro">
            <div class="card">
              <div class="card-header">
                <label class="col-md-8 col-form-label"><b>Agregar Grupo Familiar</b></label>
              </div>
              <div class="card-body border">
                <form method="POST" action="{{ url('/grupofamiliar/create') }}">
                      {{ csrf_field() }}
                  <table id="idDataTable" class="table table-striped">
                    @if ($errors->has('persona'))
                      <span class="text-danger">Ingrese una Socio válido</span>
                    @endif
                    <thead>
                      <tr>
                        <th>DNI</th>
                        <th>N° Socio</th>
                        <th>Apellido</th>
                        <th>Nombres</th>
                        <th>Seleccionar Titular</th>
                      </tr>
                    </thead>
                    <tbody>

                      @foreach ($socios as $socio)
                        <tr>
                          <td>{{ $socio->persona->DNI }}</td>
                          <td>{{ $socio->numSocio }}</td>
                          <td>{{ $socio->persona->apellido }}</td>
                          <td>{{ $socio->persona->nombres }}</td>
                          <td><input type="radio" name="titular" value="{{ $socio->id }}"></td>
                        </tr>
                      @endforeach


                    </tbody>
                  </table>
                  <div class="offset-md-5">
                      <button type="submit" class="btn btn-outline-primary">
                          {{ __('Agregar') }}
                      </button>
                  </div>
                </form>
              </div>
            </div>
        </div>
    </div>
</div>


@stop
