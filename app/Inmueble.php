<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inmueble extends Model
{
    protected $table = "inmueble";
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'descripcion'
    ];
}
