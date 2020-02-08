<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocioComprobante extends Model
{
    protected $table = "sociocomprobante";
    public $timestamps = false;

    protected $fillable = [
        'idComprobante', 'idSocio'
    ];

    public function comprobante(){
      return $this->belongsTo('App\ComprobanteCuota', 'idComprobante');
    }

    public function socio(){
      return $this->belongsTo('App\Socio', 'idSocio');
    }
}
