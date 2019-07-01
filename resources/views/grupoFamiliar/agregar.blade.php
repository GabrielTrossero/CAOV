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
                      <tr>
                        <td>40567823</td>
                        <td>1</td>
                        <td>Ricle</td>
                        <td>Anibal</td>
                        <td><input type="radio" name="titular" value="1"></td>
                      </tr>
                      <tr>
                        <td>35000123</td>
                        <td>2</td>
                        <td>Martinez</td>
                        <td>Carlos Emilio</td>
                        <td><input type="radio" name="titular" value="2"></td>
                      </tr>
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
