<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedioDePago extends Model
{
    protected $table = "mediodepago";
    public $timestamps = false;

    protected $fillable = [
        'nombre'
    ];

    //relacion a comprobantes de cuotas
    public function comprobantesDeCuotas(){
      return $this->hasMany('App\ComprobanteCuota', 'idMedioDePago');
    }

    //relacion reservas de muebles
    public function reservasDeMuebles(){
      return $this->hasMany('App\ReservaMueble', 'idMedioDePago');
    }

    //relacion a reservas de inmuebles
    public function reservasDeInmuebles(){
      return $this->hasMany('App\ReservaInmueble', 'idMedioDePago');
    }

}
