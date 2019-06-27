<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MontoCuota extends Model
{
    protected $table = "montocuota";
    public $timestamps = false;

    protected $fillable = [
        'dtoAnio', 'dtoSemestre', 'fechaCreacion', 'monto', 'tipo'
    ];
}
