@extends('pdf.master')

@section('title', 'Comprobante de Mueble')

@section('content')
    <h4>Comprobante de Pago de Alquiler de Mueble ({{ $recibo->mueble->nombre }})</h4>
    <hr>
    Fecha de Solicitud: {{ date("d/m/Y", strtotime($recibo->fechaSolicitud)) }}
    <br>
    <ul>
        <li>APELLIDO Y NOMBRE: {{ $recibo->persona->apellido . ", " . $recibo->persona->nombres }}</li>
        <br>
        <li>DIRECCION: {{ $recibo->persona->domicilio }}</li>
        <br>
        <li>DNI: {{ $recibo->persona->DNI }}</li>
        <br>
        @if ($recibo->persona->socio)
            <li>NUMERO DE SOCIO: {{ $recibo->persona->socio->numSocio }}</li>
        @else
            <li>NUMERO DE SOCIO: {{ "-" }}</li>
        @endif
        <br>
        <li>FECHA Y HORA DE INICIO DEL EVENTO: {{ date("d/m/Y H:i:s", strtotime($recibo->fechaHoraInicio)) }}</li>
        <br>
        <li>FECHA Y HORA DE FINALIZACION DEL EVENTO: {{ date("d/m/Y H:i:s", strtotime($recibo->fechaHoraFin)) }}</li>
        <br>
        <li>CANTIDAD ALQUILADA: {{ $recibo->cantidad }}</li>
        <br>
        <li>MONTO DEL ALQUILER: $ {{ $recibo->costoTotal }}</li>
        <br>
        <li>OBSERVACION: {{ $recibo->observacion }}</li>
    </ul>

    <footer>
      <div align="right">
        ........................................
      </div>
      <label>
        @if (isset($recibo->numRecibo))
            NUMERO DE RECIBO: {{ $recibo->numRecibo }}
        @else
            NUMERO DE RECIBO: {{ "-" }}
        @endif
      </label>
      <label id="firma">Firma y aclaración</label>
    </footer>


@endsection
