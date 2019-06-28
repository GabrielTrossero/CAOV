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

    //relacion a medio de pago
    public function medioDePago(){
      return $this->belongsTo('App\MedioDePago', 'idMedioDePago');
    }

    //relacion a persona
    public function persona(){
      return $this->belongsTo('App\Persona', 'idPersona');
    }

    //relacion a mueble
    public function mueble(){
      return $this->belongsTo('App\Mueble', 'idMueble');
    }
}
