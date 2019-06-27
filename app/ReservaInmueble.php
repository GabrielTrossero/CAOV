<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservaInmueble extends Model
{
    protected $table = "reservainmueble";
    public $timestamps = false;

    protected $fillable = [
        'cantAsistentes', 'costoReserva', 'costoTotal', 'fechaHoraFin', 'fechaHoraInicio',
        'fechaSolicitud', 'idInmueble', 'idMedioDePago', 'idPersona', 'numRecibo', 'observacion',
        'tieneMusica', 'tieneReglamento', 'tieneServicioLimpieza', 'tipoEvento'
    ];
}
