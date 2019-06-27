<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    protected $table = "socio";
    public $timestamps = false;

    protected $fillable = [
        'fechaNac', 'idGrupoFamiliar', 'idPersona', 'numSocio', 'oficio', 'vitalicio'
    ];
}
