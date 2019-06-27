<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocioDeporte extends Model
{
    protected $table = "sociodeporte";
    public $timestamps = false;

    protected $fillable = [
        'idDeporte', 'idSocio'
    ];
}
