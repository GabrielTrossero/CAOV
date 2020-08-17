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
        'fechaNac', 'idGrupoFamiliar', 'idPersona', 'numSocio', 'oficio', 'vitalicio', 'activo',
        'fechaCreacion', 'fechaBaja'
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

    //relacion a los comprobantes de cuotas (titular)
    public function comprobantesDeCuotas(){
      return $this->hasMany('App\ComprobanteCuota', 'idSocio');
    }

    //relacion a los comprobantes de cuotas (adherentes)
    public function comprobantes(){
      return $this->belongsToMany('App\ComprobanteCuota', 'sociocomprobante', 'idSocio', 'idComprobante');
    }
}
