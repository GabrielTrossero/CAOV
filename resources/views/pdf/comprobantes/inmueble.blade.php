@extends('pdf.master')

@section('title', 'Comprobante Inmueble')

@section('content')
    <h4>Comprobante de Pago de Alquiler de Inmueble ({{ $recibo->inmueble->nombre }})</h4>
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
        <li>TIPO DE EVENTO A REALIZAR: {{ $recibo->tipoEvento }}</li>
        <br>
        <li>CANTIDAD ESTIMADA DE ASISTENTES: {{ $recibo->cantAsistentes . " (MAXIMO 100)"}}</li>
        <br>
        @if ($recibo->tieneServicioLimpieza)
            <li>SERVICIO DE LIMPIEZA: Si</li>
        @else
            <li>SERVICIO DE LIMPIEZA: No</li>
        @endif
        <br>
        @if ($recibo->tieneMusica)
            <li>EL ACONTECIMIENTO SE ACOMPAÑA CON MUSICA (ADJUNTAR SADAIC Y ADICAPIF): Si</li>
        @else
            <li>EL ACONTECIMIENTO SE ACOMPAÑA CON MUSICA (ADJUNTAR SADAIC Y ADICAPIF): No</li>
        @endif
        <br>
        <li>COSTO DE ALQUILER DEL SALON: ${{ $recibo->costoTotal }}</li>
        <br>
        @if ($recibo->tieneReglamento)
            <li>SE ADJUNTO REGLAMENTO DEL SALON: Si</li>
        @else
            <li>SE ADJUNTO REGLAMENTO DEL SALON: No</li>
        @endif
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
