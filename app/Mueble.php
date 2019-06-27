<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mueble extends Model
{
    protected $table = "mueble";
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'cantidad'
    ];
}
