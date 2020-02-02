<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MontoCuota extends Model
{
    protected $table = "montocuota";
    public $timestamps = false;

    protected $fillable = [
         'tipo', 'montoMensual', 'montoInteresGrupoFamiliar', 'cantidadIntegrantes', 'montoInteresMensual', 'cantidadMeses', 'fechaCreacion'
    ];

    //relacion a comprobantes de cuotas
    public function comprobantesDeCuotas(){
      return $this->hasMany('App\ComprobanteCuota', 'idMontoCuota');
    }
}
