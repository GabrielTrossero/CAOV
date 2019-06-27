<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    protected $table = "tipousuario";
    public $timestamps = false;

    protected $fillable = [
        'nombre'
    ];
}
