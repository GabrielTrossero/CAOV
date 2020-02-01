<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComprobanteCuota extends Model
{
    protected $table = "comprobantecuota";
    public $timestamps = false;

    protected $fillable = [
        'fechaMesAnio', 'fechaPago', 'cantidadIntegrantes', 'idMedioDePago', 'idMontoCuota', 'idSocio', 'inhabilitada'
    ];

    //relacion a socio
    public function socio(){
      return $this->belongsTo('App\Socio', 'idSocio');
    }

    //relacion a medio de pago
    public function medioDePago(){
      return $this->belongsTo('App\MedioDePago', 'idMedioDePago');
    }

    //relacion a monto de la cuota
    public function montoCuota(){
      return $this->belongsTo('App\MontoCuota', 'idMontoCuota');
    }
}
