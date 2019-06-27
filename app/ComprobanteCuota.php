<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComprobanteCuota extends Model
{
    protected $table = "comprobantecuota";
    public $timestamps = false;

    protected $fillable = [
        'fechaMesAnio', 'fechaPago', 'idMedioDePago', 'idMontoCuota', 'idSocio'
    ];
}
