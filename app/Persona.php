<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = "persona";
    public $timestamps = false;

    protected $fillable = [
        'apellido', 'DNI', 'domicilio', 'email', 'nombres', 'telefono'
    ];
}
