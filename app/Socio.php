<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Deporte;
use App\SocioDeporte;

class Socio extends Model
{
    protected $table = "socio";
    public $timestamps = false;

    protected $fillable = [
        'fechaNac', 'idGrupoFamiliar', 'idPersona', 'numSocio', 'oficio', 'vitalicio'
    ];

    //relacion con deportes
    public function deportes(){
      return $this->belongsToMany('App\Deporte', 'sociodeporte', 'idSocio', 'idDeporte');
    }

    //relacion a grupo familiar
    public function grupoFamiliar(){
      return $this->belongsTo('App\GrupoFamiliar', 'idGrupoFamiliar');
    }

    //relacion a persona
    public function persona(){
      return $this->belongsTo('App\Persona', 'idPersona');
    }

    //relacion a los comprobantes de cuotas
    public function comprobantesDeCuotas(){
      return $this->hasMany('App\ComprobanteCuota', 'idSocio');
    }
}
