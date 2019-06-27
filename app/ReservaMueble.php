<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservaMueble extends Model
{
    protected $table = "reservamueble";
    public $timestamps = false;

    protected $fillable = [
        'cantidad', 'costoTotal', 'fechaHoraFin', 'fechaHoraInicio', 'fechaSolicitud',
        'idMedioDePago', 'idMueble', 'idPersona', 'numRecibo', 'observacion'
    ];
}
