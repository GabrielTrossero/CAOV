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

    //relacion a medio de pago
    public function medioDePago(){
      return $this->belongsTo('App\MedioDePago', 'idMedioDePago');
    }

    //relacion a persona
    public function persona(){
      return $this->belongsTo('App\Persona', 'idPersona');
    }

    //relacion a inmueble
    public function inmueble(){
      return $this->belongsTo('App\Inmueble', 'idInmueble');
    }
}
